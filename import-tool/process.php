<?php
// =====================================================
// process.php — Import Engine (AJAX endpoint)
// Menerima POST multipart/form-data dengan file .xlsx
// Mengembalikan JSON hasil import
// =====================================================

header('Content-Type: application/json');

// ---- Guard: harus POST dengan file ----
if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_FILES['excel_file'])) {
    echo json_encode(['success' => false, 'error' => 'Tidak ada file yang diupload.']);
    exit;
}

$uploadedFile = $_FILES['excel_file'];

// ---- Validasi ekstensi ----
$ext = strtolower(pathinfo($uploadedFile['name'], PATHINFO_EXTENSION));
if ($ext !== 'xlsx') {
    echo json_encode(['success' => false, 'error' => 'Hanya file .xlsx yang diterima. File kamu: .' . $ext]);
    exit;
}

// ---- Simpan ke temp ----
$tmpPath = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'sim_import_' . time() . '.xlsx';
if (!move_uploaded_file($uploadedFile['tmp_name'], $tmpPath)) {
    echo json_encode(['success' => false, 'error' => 'Gagal menyimpan file sementara.']);
    exit;
}

// ---- Autoload PhpSpreadsheet ----
$autoload = __DIR__ . '/vendor/autoload.php';
if (!file_exists($autoload)) {
    @unlink($tmpPath);
    echo json_encode([
        'success' => false,
        'error'   => 'Library PhpSpreadsheet belum terinstall.',
        'hint'    => 'Jalankan: composer install — di folder import-tool/'
    ]);
    exit;
}
require $autoload;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as SpreadsheetDate;

// ---- Load database ----
require __DIR__ . '/config.php';

// ============================================================
// COLUMN MAP — TikTok OrderSKUList (0-indexed, row auto-detect)
// ============================================================
const COL_ORDER_ID          = 'Order ID';
const COL_ORDER_STATUS      = 'Order Status';
const COL_PRODUCT_NAME      = 'Product Name';
const COL_VARIATION         = 'Variation';
const COL_QUANTITY          = 'Quantity';
const COL_SKU_ORDER_AMOUNT  = 'SKU Order Amount';
const COL_SKU_SETTLE_AMT    = 'SKU Settlement Amount';
const COL_CREATE_TIME       = 'Create Time';
const COL_PAID_TIME         = 'Paid Time';

// ============================================================
// HELPER: Parse Excel Date (handles serial numbers & strings)
// ============================================================
function parseExcelDate($val): ?string {
    if (empty($val)) return null;
    if (is_numeric($val)) {
        try {
            $d = SpreadsheetDate::excelToDateTimeObject($val);
            return $d->format('Y-m-d H:i:s');
        } catch (Exception $e) {}
    }
    // Try string parse
    $ts = strtotime($val);
    return $ts ? date('Y-m-d H:i:s', $ts) : null;
}

// ============================================================
// HELPER: Generate kombinasi_produk
// ============================================================
function buildKombinasi(string $nama, string $variasi, array $mappingCache): string {
    $key = strtolower(trim($nama)) . '|||' . strtolower(trim($variasi));
    if (isset($mappingCache[$key])) {
        return $mappingCache[$key];
    }
    // Jika variasi bukan "Default" atau kosong → gabungkan
    $cleanVariasi = trim($variasi);
    if ($cleanVariasi === '' || strtolower($cleanVariasi) === 'default') {
        return trim($nama);  // Pakai nama produk saja
    }
    return trim($nama) . ' — ' . $cleanVariasi;
}

// ============================================================
// STEP 1: Load product_mapping dari database ke memory cache
// ============================================================
$mappingCache = [];
try {
    $rows = $pdo->query("SELECT nama_produk_raw, variasi_raw, kombinasi_label FROM product_mapping")->fetchAll();
    foreach ($rows as $r) {
        $key = strtolower(trim($r['nama_produk_raw'])) . '|||' . strtolower(trim($r['variasi_raw']));
        $mappingCache[$key] = $r['kombinasi_label'];
    }
} catch (Exception $e) {
    // Table mungkin belum ada, lanjut saja
}

// ============================================================
// STEP 2: Baca Excel
// ============================================================
$result = [
    'success'       => false,
    'filename'      => $uploadedFile['name'],
    'total_rows'    => 0,
    'total_orders'  => 0,
    'inserted'      => 0,
    'updated'       => 0,
    'skipped'       => 0,
    'errors'        => [],
    'preview'       => []   // 10 baris sample untuk ditampilkan di UI
];

try {
    $spreadsheet = IOFactory::load($tmpPath);
    $sheet       = $spreadsheet->getActiveSheet();
    $rows        = $sheet->toArray(null, true, true, false);  // 0-indexed array
} catch (Exception $e) {
    @unlink($tmpPath);
    echo json_encode(['success' => false, 'error' => 'Gagal membaca file Excel: ' . $e->getMessage()]);
    exit;
}

// ============================================================
// STEP 3: Auto-detect header row (cari baris yang ada "Order ID")
// ============================================================
$headerRowIdx = null;
$colMap       = [];  // colName → array-index

foreach ($rows as $ri => $row) {
    foreach ($row as $ci => $cell) {
        if (trim((string)$cell) === 'Order ID') {
            $headerRowIdx = $ri;
            break 2;
        }
    }
}

if ($headerRowIdx === null) {
    @unlink($tmpPath);
    echo json_encode(['success' => false, 'error' => 'Kolom "Order ID" tidak ditemukan. Pastikan file adalah TikTok OrderSKUList export.']);
    exit;
}

// Build column name → index map
foreach ($rows[$headerRowIdx] as $ci => $cell) {
    $colMap[trim((string)$cell)] = $ci;
}

// Cek kolom wajib
$required = [COL_ORDER_ID, COL_ORDER_STATUS, COL_PRODUCT_NAME, COL_VARIATION, COL_QUANTITY];
foreach ($required as $col) {
    if (!isset($colMap[$col])) {
        @unlink($tmpPath);
        echo json_encode(['success' => false, 'error' => "Kolom wajib tidak ditemukan: \"$col\". File mungkin bukan format TikTok OrderSKUList."]);
        exit;
    }
}

// ============================================================
// STEP 4: Baca semua baris data, grouping by Order ID
// ============================================================
$groups = [];  // order_id → [ header_data, [items] ]

for ($ri = $headerRowIdx + 1; $ri < count($rows); $ri++) {
    $row = $rows[$ri];

    $orderId = trim((string)($row[$colMap[COL_ORDER_ID]] ?? ''));
    if ($orderId === '') continue;  // Baris kosong, skip

    $statusPesanan = trim((string)($row[$colMap[COL_ORDER_STATUS]] ?? ''));
    $namaProduk    = trim((string)($row[$colMap[COL_PRODUCT_NAME]] ?? ''));
    $variasi       = trim((string)($row[$colMap[COL_VARIATION]] ?? ''));
    $qty           = (int)($row[$colMap[COL_QUANTITY]] ?? 0);
    $skuOrderAmt   = (float)($row[$colMap[COL_SKU_ORDER_AMOUNT] ?? -1] ?? 0);
    $skuSettleAmt  = (float)($row[$colMap[COL_SKU_SETTLE_AMT] ?? -1] ?? 0);

    $createTime = parseExcelDate($row[$colMap[COL_CREATE_TIME] ?? -1] ?? null);
    $paidTime   = parseExcelDate($row[$colMap[COL_PAID_TIME] ?? -1] ?? null);

    $result['total_rows']++;

    if (!isset($groups[$orderId])) {
        $groups[$orderId] = [
            'status_pesanan' => $statusPesanan,
            'total_amount'   => 0.0,
            'create_time'    => $createTime,
            'paid_time'      => $paidTime,
            'items'          => []
        ];
    }

    // Akumulasi total_amount dari SKU Settlement Amount
    $groups[$orderId]['total_amount'] += $skuSettleAmt;

    // Baris pertama menentukan status (konsisten per order_id)
    if ($groups[$orderId]['status_pesanan'] === '') {
        $groups[$orderId]['status_pesanan'] = $statusPesanan;
    }

    // Build kombinasi_produk
    $kombinasi = buildKombinasi($namaProduk, $variasi, $mappingCache);

    $groups[$orderId]['items'][] = [
        'nama_produk_raw'  => $namaProduk,
        'variasi_raw'      => $variasi,
        'kombinasi_produk' => $kombinasi,
        'quantity'         => $qty,
        'sku_order_amount' => $skuOrderAmt,
        'sku_settlement_amt' => $skuSettleAmt,
    ];
}

$result['total_orders'] = count($groups);

// ============================================================
// STEP 5: UPSERT ke database — dalam transaction
// ============================================================

// Prepared statements
$sqlUpsert = "
    INSERT INTO transaksi_pesanan
        (order_id, platform, status_pesanan, total_amount, create_time, paid_time)
    VALUES
        (:order_id, 'TikTok', :status_pesanan, :total_amount, :create_time, :paid_time)
    ON DUPLICATE KEY UPDATE
        status_pesanan = VALUES(status_pesanan),
        total_amount   = VALUES(total_amount),
        create_time    = VALUES(create_time),
        paid_time      = VALUES(paid_time)
        -- status_penarikan TIDAK diubah (intentional!)
";

$sqlCheckExists  = "SELECT 1 FROM transaksi_pesanan WHERE order_id = :order_id";
$sqlDeleteDetail = "DELETE FROM detail_pesanan WHERE order_id = :order_id";
$sqlInsertDetail = "
    INSERT INTO detail_pesanan
        (order_id, nama_produk_raw, variasi_raw, kombinasi_produk, quantity, sku_order_amount, sku_settlement_amt)
    VALUES
        (:order_id, :nama_produk_raw, :variasi_raw, :kombinasi_produk, :quantity, :sku_order_amount, :sku_settlement_amt)
";

$stmtUpsert       = $pdo->prepare($sqlUpsert);
$stmtCheckExists  = $pdo->prepare($sqlCheckExists);
$stmtDeleteDetail = $pdo->prepare($sqlDeleteDetail);
$stmtInsertDetail = $pdo->prepare($sqlInsertDetail);

$previewCount = 0;

$pdo->beginTransaction();
try {
    foreach ($groups as $orderId => $data) {
        // Cek apakah sudah ada (untuk counting inserted vs updated)
        $stmtCheckExists->execute([':order_id' => $orderId]);
        $exists = (bool)$stmtCheckExists->fetchColumn();

        // Upsert header
        $stmtUpsert->execute([
            ':order_id'      => $orderId,
            ':status_pesanan' => $data['status_pesanan'],
            ':total_amount'  => round($data['total_amount'], 2),
            ':create_time'   => $data['create_time'],
            ':paid_time'     => $data['paid_time'],
        ]);

        $exists ? $result['updated']++ : $result['inserted']++;

        // Rebuild detail: delete lama, insert baru
        $stmtDeleteDetail->execute([':order_id' => $orderId]);

        foreach ($data['items'] as $item) {
            $stmtInsertDetail->execute([
                ':order_id'          => $orderId,
                ':nama_produk_raw'   => $item['nama_produk_raw'],
                ':variasi_raw'       => $item['variasi_raw'],
                ':kombinasi_produk'  => $item['kombinasi_produk'],
                ':quantity'          => $item['quantity'],
                ':sku_order_amount'  => $item['sku_order_amount'],
                ':sku_settlement_amt' => $item['sku_settlement_amt'],
            ]);
        }

        // Kumpulkan preview
        if ($previewCount < 10) {
            $result['preview'][] = [
                'order_id'        => $orderId,
                'status_pesanan'  => $data['status_pesanan'],
                'total_amount'    => round($data['total_amount'], 2),
                'items'           => count($data['items']),
                'action'          => $exists ? 'UPDATED' : 'INSERTED',
                'produk_sample'   => $data['items'][0]['kombinasi_produk'] ?? '-',
            ];
            $previewCount++;
        }
    }

    $pdo->commit();

    // Log import session
    try {
        $stmtLog = $pdo->prepare("
            INSERT INTO import_log
                (filename, platform, total_rows, total_orders, inserted, updated, skipped)
            VALUES
                (:filename, 'TikTok', :total_rows, :total_orders, :inserted, :updated, :skipped)
        ");
        $stmtLog->execute([
            ':filename'     => $uploadedFile['name'],
            ':total_rows'   => $result['total_rows'],
            ':total_orders' => $result['total_orders'],
            ':inserted'     => $result['inserted'],
            ':updated'      => $result['updated'],
            ':skipped'      => $result['skipped'],
        ]);
    } catch (Exception $e) { /* log gagal, tidak apa-apa */ }

    $result['success'] = true;

} catch (Exception $e) {
    $pdo->rollBack();
    $result['errors'][] = 'Database error: ' . $e->getMessage();
    $result['success']  = false;
}

@unlink($tmpPath);
echo json_encode($result, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
