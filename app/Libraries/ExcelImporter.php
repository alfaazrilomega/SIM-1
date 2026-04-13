<?php

namespace App\Libraries;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Shared\Date as SpreadsheetDate;
use App\Models\TransaksiPesananModel;
use App\Models\DetailPesananModel;
use App\Models\ProductMappingModel;

/**
 * ExcelImporter — Library untuk import TikTok OrderSKUList .xlsx ke database
 *
 * Business Rules yang diimplementasikan:
 * 1. Menarik 63 kolom (Order, Logistics, Financials, User Profiling).
 * 2. Mapping "Order Status" TikTok menjadi ENUM kaku bahasa Indonesia.
 * 3. Logika Retur: Merekam 'cancelation_return_type' dan jumlah retur per SKU.
 * 4. Multi-bahasa kolom support (English vs Bahasa Indonesia di Excel header).
 */
class ExcelImporter
{
    private TransaksiPesananModel $transaksiModel;
    private DetailPesananModel    $detailModel;
    private ProductMappingModel   $mappingModel;
    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct()
    {
        $this->transaksiModel = new TransaksiPesananModel();
        $this->detailModel    = new DetailPesananModel();
        $this->mappingModel   = new ProductMappingModel();
        $this->db             = \Config\Database::connect();
    }

    public function import(string $filePath, string $filename = ''): array
    {
        $result = [
            'success'      => false,
            'filename'     => $filename ?: basename($filePath),
            'total_rows'   => 0,
            'total_orders' => 0,
            'inserted'     => 0,
            'updated'      => 0,
            'skipped'      => 0,
            'errors'       => [],
            'preview'      => [],
        ];

        try {
            $spreadsheet = IOFactory::load($filePath);
            $sheet       = $spreadsheet->getActiveSheet();
            $rawRows     = $sheet->toArray(null, true, true, false);
        } catch (\Exception $e) {
            $result['errors'][] = 'Gagal membaca file Excel: ' . $e->getMessage();
            return $result;
        }

        $headerIdx = $this->detectHeaderRow($rawRows);
        if ($headerIdx === null) {
            $result['errors'][] = 'Kolom "Order ID" tidak ditemukan. Pastikan file adalah format TikTok Shop.';
            return $result;
        }

        $colMap = $this->buildColMap($rawRows[$headerIdx]);
        $this->mappingModel->loadCache();

        // Parse semua data dari raw rows ke array terstruktur
        $groups = $this->groupRows($rawRows, $headerIdx, $colMap, $result);
        $result['total_orders'] = count($groups);

        $this->db->transBegin();
        try {
            $previewCount = 0;

            foreach ($groups as $orderId => $data) {
                $wasExisting = $this->transaksiModel->exists($orderId);

                // Ekstrak data level order (buang elemen 'items' array)
                $orderData = $data;
                unset($orderData['items']);

                // Upsert ke tabel transaksi (otomatis allowedFields filter)
                $this->transaksiModel->upsert($orderData);

                // Rekam Detail items
                $this->detailModel->deleteByOrderId($orderId);
                $this->detailModel->bulkInsert($orderId, $data['items']);

                $wasExisting ? $result['updated']++ : $result['inserted']++;

                if ($previewCount < 10) {
                    $result['preview'][] = [
                        'order_id'      => $orderId,
                        'status_pesanan'=> $orderData['status_pesanan'],
                        'total_amount'  => $orderData['total_amount'],
                        'items'         => count($data['items']),
                        'action'        => $wasExisting ? 'UPDATED' : 'INSERTED',
                        'produk_sample' => $data['items'][0]['kombinasi_produk'] ?? '-',
                    ];
                    $previewCount++;
                }
            }

            $this->db->transCommit();
            $result['success'] = true;
            $this->logImport($result);

        } catch (\Throwable $e) {
            $this->db->transRollback();
            $result['errors'][] = 'Database error: ' . $e->getMessage();
            $result['success']  = false;
        }

        return $result;
    }

    // ===========================================================
    // PRIVATE HELPERS
    // ===========================================================

    private function detectHeaderRow(array $rows): ?int
    {
        foreach ($rows as $idx => $row) {
            foreach ($row as $cell) {
                if (trim((string)$cell) === 'Order ID') return $idx;
            }
        }
        return null;
    }

    private function buildColMap(array $headerRow): array
    {
        $map = [];
        foreach ($headerRow as $idx => $cell) {
            $map[strtolower(trim((string)$cell))] = $idx;
        }
        return $map;
    }

    // Fungsi canggih untuk menarik nilai berdasarkan berbagai alias kolom
    private function getVal(array $row, $colCandidates, array $colMap, $default = '') 
    {
        $candidates = (array)$colCandidates;
        foreach ($candidates as $col) {
            $k = strtolower(trim($col));
            if (isset($colMap[$k])) {
                $val = $row[$colMap[$k]];
                if ($val !== null && $val !== '') return $val;
            }
            // Partial match fallback
            foreach ($colMap as $actualCol => $idx) {
                if (strpos($actualCol, $k) !== false || strpos($k, $actualCol) !== false) {
                    $val = $row[$idx];
                    if ($val !== null && $val !== '') return $val;
                }
            }
        }
        return $default;
    }

    private function parseCurrency($val): float
    {
        if (is_numeric($val)) return (float)$val;
        $val = str_ireplace(['rp', 'idr', ' ', 'Rp.', 'Rp', "\xC2\xA0"], '', (string)$val);
        if (strpos($val, ',') !== false && strpos($val, '.') !== false) {
            if (strrpos($val, ',') > strrpos($val, '.')) {
                $val = str_replace('.', '', $val); $val = str_replace(',', '.', $val);
            } else {
                $val = str_replace(',', '', $val);
            }
        } else if (strpos($val, ',') !== false) {
            if (preg_match('/,\d{1,2}$/', $val)) $val = str_replace(',', '.', $val);
            else $val = str_replace(',', '', $val);
        } else if (strpos($val, '.') !== false) {
            if (!preg_match('/\.\d{1,2}$/', $val)) $val = str_replace('.', '', $val);
        }
        return (float)$val;
    }

    private function parseStatus($rawStatus): string
    {
        $s = strtolower(trim($rawStatus));
        if (in_array($s, ['unpaid', 'belum dibayar', 'belum bayar'])) return 'Belum Bayar';
        if (in_array($s, ['awaiting shipment', 'to ship', 'menunggu pengiriman'])) return 'Menunggu Pengiriman';
        if (in_array($s, ['in transit', 'shipped', 'dikirim', 'sedang dikirim', 'delivered'])) return 'Dikirim';
        if (in_array($s, ['completed', 'selesai'])) return 'Selesai';
        if (in_array($s, ['cancelled', 'canceled', 'dibatalkan', 'batal'])) return 'Dibatalkan';
        if (in_array($s, ['returned', 'refunded', 'retur', 'dikembalikan'])) return 'Retur';
        return 'Lainnya';
    }

    private function groupRows(array $rawRows, int $headerIdx, array $colMap, array &$result): array
    {
        $groups = [];

        for ($i = $headerIdx + 1; $i < count($rawRows); $i++) {
            $row     = $rawRows[$i];
            $orderId = trim((string)$this->getVal($row, ['Order ID', 'Order_ID', 'ID Pesanan'], $colMap));

            if ($orderId === '') continue;
            $result['total_rows']++;

            $rawOrderStatus  = $this->getVal($row, ['Order Status', 'Status Pesanan'], $colMap);
            $enumStatus      = $this->parseStatus($rawOrderStatus);
            $orderSubstatus  = $this->getVal($row, ['Order Substatus', 'Substatus'], $colMap);
            $cancelType      = $this->getVal($row, ['Cancelation/Return Type', 'Tipe Pembatalan/Pengembalian'], $colMap);

            $orderAmount     = $this->parseCurrency($this->getVal($row, ['Order Amount', 'Total Pesanan'], $colMap, 0));
            $orderRefund     = $this->parseCurrency($this->getVal($row, ['Order Refund Amount', 'Jumlah Pengembalian'], $colMap, 0));

            // Jika status Selesai atau Dikirim, set total_amount (valid revenue) ke Order Amount.
            // Jika retur atau batal, total_amount jadi 0 atau dikurangi refund logika (kita set 0 saja jika batal utuh).
            $validRevenue = $orderAmount;
            if ($enumStatus === 'Dibatalkan' || $enumStatus === 'Retur') {
                $validRevenue = 0.00;
            }

            if (!isset($groups[$orderId])) {
                $groups[$orderId] = [
                    'order_id'                  => $orderId,
                    'platform'                  => 'TikTok',
                    'status_pesanan'            => $enumStatus,
                    'order_substatus'           => $orderSubstatus,
                    'cancelation_return_type'   => $cancelType,
                    
                    'order_amount'              => $orderAmount,
                    'order_refund_amount'       => $orderRefund,
                    'total_amount'              => $validRevenue,
                    'total_quantity'            => 0,
                    'total_sku_quantity_of_return' => 0,

                    'shipping_fee_after_discount' => $this->parseCurrency($this->getVal($row, ['Shipping Fee After Discount', 'Ongkos Kirim'], $colMap, 0)),
                    'original_shipping_fee'       => $this->parseCurrency($this->getVal($row, ['Original Shipping Fee'], $colMap, 0)),
                    'shipping_fee_seller_discount'=> $this->parseCurrency($this->getVal($row, ['Shipping Fee Seller Discount'], $colMap, 0)),
                    'shipping_fee_platform_discount'=>$this->parseCurrency($this->getVal($row, ['Shipping Fee Platform Discount'], $colMap, 0)),
                    
                    'payment_platform_discount'   => $this->parseCurrency($this->getVal($row, ['Payment platform discount'], $colMap, 0)),
                    'buyer_service_fee'           => $this->parseCurrency($this->getVal($row, ['Buyer Service Fee'], $colMap, 0)),
                    'handling_fee'                => $this->parseCurrency($this->getVal($row, ['Handling Fee'], $colMap, 0)),

                    'create_time'                 => $this->parseDate($this->getVal($row, ['Created Time', 'Create Time'], $colMap)),
                    'paid_time'                   => $this->parseDate($this->getVal($row, ['Paid Time', 'Payment Time'], $colMap)),
                    'rts_time'                    => $this->parseDate($this->getVal($row, ['RTS Time'], $colMap)),
                    'shipped_time'                => $this->parseDate($this->getVal($row, ['Shipped Time'], $colMap)),
                    'delivered_time'              => $this->parseDate($this->getVal($row, ['Delivered Time'], $colMap)),
                    'cancelled_time'              => $this->parseDate($this->getVal($row, ['Cancelled Time'], $colMap)),

                    'cancel_by'                   => $this->getVal($row, ['Cancel By', 'Dibatalkan Oleh'], $colMap),
                    'cancel_reason'               => $this->getVal($row, ['Cancel Reason', 'Alasan Batal'], $colMap),
                    'fulfillment_type'            => $this->getVal($row, ['Fulfillment Type'], $colMap),
                    'warehouse_name'              => $this->getVal($row, ['Warehouse Name'], $colMap),
                    'tracking_id'                 => $this->getVal($row, ['Tracking ID', 'Nomor Resi'], $colMap),
                    'delivery_option'             => $this->getVal($row, ['Delivery Option'], $colMap),
                    'shipping_provider'           => $this->getVal($row, ['Shipping Provider', 'Kurir'], $colMap),

                    'buyer_username'              => $this->getVal($row, ['Buyer Username', 'Username Pembeli'], $colMap),
                    'recipient'                   => $this->getVal($row, ['Recipient', 'Penerima'], $colMap),
                    'phone'                       => $this->getVal($row, ['Phone', 'No. HP'], $colMap),
                    'zipcode'                     => $this->getVal($row, ['Zipcode', 'Kode Pos'], $colMap),
                    'country'                     => $this->getVal($row, ['Country', 'Negara'], $colMap),
                    'province'                    => $this->getVal($row, ['Province', 'Provinsi'], $colMap),
                    'regency_and_city'            => $this->getVal($row, ['Regency and City', 'Kota/Kabupaten'], $colMap),
                    'districts'                   => $this->getVal($row, ['Districts', 'Kecamatan'], $colMap),
                    'villages'                    => $this->getVal($row, ['Villages', 'Kelurahan'], $colMap),
                    'detail_address'              => $this->getVal($row, ['Detail Address'], $colMap),
                    'additional_address'          => $this->getVal($row, ['Additional address'], $colMap),
                    
                    'payment_method'              => $this->getVal($row, ['Payment Method', 'Metode Pembayaran'], $colMap),
                    'weight_kg'                   => $this->parseCurrency($this->getVal($row, ['Weight(kg)', 'Berat'], $colMap, 0)),
                    'package_id'                  => $this->getVal($row, ['Package ID'], $colMap),
                    'buyer_message'               => $this->getVal($row, ['Buyer Message', 'Pesan Pembeli'], $colMap),

                    'items'                       => [],
                ];
            }

            // --- EKSTRAK DATA ITEM (SKU) ---
            $namaProduk    = trim((string)$this->getVal($row, ['Product Name', 'Nama Produk'], $colMap));
            $variasi       = trim((string)$this->getVal($row, ['Variation', 'Variasi'], $colMap));
            
            $qtyRaw        = $this->getVal($row, ['Quantity', 'Kuantitas', 'Qty'], $colMap, 0);
            $qty           = (int)preg_replace('/[^\d]/', '', (string)$qtyRaw);
            
            $rtnRaw        = $this->getVal($row, ['Sku Quantity of return', 'Kuantitas Retur'], $colMap, 0);
            $qtyRtn        = (int)preg_replace('/[^\d]/', '', (string)$rtnRaw);

            $groups[$orderId]['total_quantity']               += $qty;
            $groups[$orderId]['total_sku_quantity_of_return'] += $qtyRtn;

            $kombinasi = $this->buildKombinasi($namaProduk, $variasi);

            $groups[$orderId]['items'][] = [
                'sku_id'                       => $this->getVal($row, ['SKU ID'], $colMap),
                'seller_sku'                   => $this->getVal($row, ['Seller SKU'], $colMap),
                'nama_produk_raw'              => $namaProduk,
                'variasi_raw'                  => $variasi,
                'kombinasi_produk'             => $kombinasi,
                'quantity'                     => $qty,
                'sku_quantity_of_return'       => $qtyRtn,
                'sku_unit_original_price'      => $this->parseCurrency($this->getVal($row, ['SKU Unit Original Price'], $colMap, 0)),
                'sku_subtotal_before_discount' => $this->parseCurrency($this->getVal($row, ['SKU Subtotal Before Discount'], $colMap, 0)),
                'sku_platform_discount'        => $this->parseCurrency($this->getVal($row, ['SKU Platform Discount'], $colMap, 0)),
                'sku_seller_discount'          => $this->parseCurrency($this->getVal($row, ['SKU Seller Discount'], $colMap, 0)),
                'sku_subtotal_after_discount'  => $this->parseCurrency($this->getVal($row, ['SKU Subtotal After Discount'], $colMap, 0)),
                // Settlement as fallback (jika ada)
                'sku_settlement_amt'           => $this->parseCurrency($this->getVal($row, ['SKU Settlement Amount'], $colMap, 0)),
            ];
        }

        return $groups;
    }

    private function buildKombinasi(string $nama, string $variasi): string
    {
        $cleanVariasi = trim($variasi);
        $mapped = $this->mappingModel->getKombinasi($nama, $cleanVariasi);
        if ($mapped !== null) return $mapped;
        if ($cleanVariasi === '' || strtolower($cleanVariasi) === 'default') return trim($nama);
        return trim($nama) . ' — ' . $cleanVariasi;
    }

    private function parseDate($val): ?string
    {
        if (empty($val) || $val === '-') return null;
        if (is_numeric($val)) {
            try {
                $dt = SpreadsheetDate::excelToDateTimeObject((float)$val);
                return $dt->format('Y-m-d H:i:s');
            } catch (\Exception $e) {}
        }
        // TikTok string format parsing "12-05-2026 15:30:11"
        $val = str_replace('/', '-', $val);
        $ts = strtotime((string)$val);
        return $ts ? date('Y-m-d H:i:s', $ts) : null;
    }

    private function logImport(array $result): void
    {
        try {
            $this->db->query(
                "INSERT INTO import_log (filename, platform, total_rows, total_orders, inserted, updated, skipped)
                 VALUES (?, 'TikTok', ?, ?, ?, ?, ?)",
                [
                    $result['filename'], $result['total_rows'], $result['total_orders'],
                    $result['inserted'], $result['updated'], $result['skipped'],
                ]
            );
        } catch (\Throwable $e) {}
    }
}
