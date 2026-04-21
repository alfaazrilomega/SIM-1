<?php

namespace App\Controllers;

class RekapProduk extends BaseController
{
    // -------------------------------------------------------
    // GET /rekap-produk  — Halaman Rekap Produk
    // -------------------------------------------------------
    public function index(): string
    {
        return view('rekap_produk/index');
    }

    // -------------------------------------------------------
    // GET /rekap-produk/data  — AJAX: Data rekap produk
    // Query param: ?sort=qty|revenue|orders|return  &order=desc|asc  &range=30|90|365|all
    // -------------------------------------------------------
    public function data(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        $sort  = $this->request->getGet('sort')  ?? 'qty';
        $order = strtoupper($this->request->getGet('order') ?? 'DESC');
        $range = $this->request->getGet('range') ?? 'all';

        // Sanitasi sort & order
        $allowedSort  = ['qty' => 'total_qty_bersih', 'revenue' => 'total_revenue', 'orders' => 'total_orders', 'return' => 'total_retur'];
        $sortCol      = $allowedSort[$sort] ?? 'total_qty_bersih';
        $order        = in_array($order, ['ASC', 'DESC']) ? $order : 'DESC';

        $db = \Config\Database::connect();

        // WHERE clause berdasarkan range
        $whereDate = '';
        if ($range !== 'all') {
            $days      = (int) $range;
            $whereDate = "AND t.paid_time >= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        }

        // -------------------------------------------------------
        // 1. Rekap per Kombinasi Produk — NET SALES (Bruto - Retur)
        // -------------------------------------------------------
        $produk = $db->query("
            SELECT
                d.kombinasi_produk,
                d.nama_produk_raw,
                d.variasi_raw,
                SUM(d.quantity)                                                     AS total_qty_bruto,
                SUM(d.sku_quantity_of_return)                                       AS total_retur,
                SUM(d.quantity) - SUM(d.sku_quantity_of_return)                     AS total_qty_bersih,
                SUM(d.sku_subtotal_after_discount)                                  AS total_revenue,
                SUM(d.sku_settlement_amt)                                           AS total_settlement,
                SUM(d.sku_platform_discount)                                        AS total_diskon_platform,
                SUM(d.sku_seller_discount)                                          AS total_diskon_seller,
                AVG(d.sku_unit_original_price)                                      AS avg_harga_satuan,
                COUNT(DISTINCT d.order_id)                                          AS total_orders,
                ROUND(
                    SUM(d.sku_subtotal_after_discount)
                    / NULLIF(SUM(d.quantity) - SUM(d.sku_quantity_of_return), 0)
                , 0)                                                                AS avg_revenue_per_unit_bersih,
                ROUND(
                    SUM(d.sku_quantity_of_return) / NULLIF(SUM(d.quantity), 0) * 100
                , 1)                                                                AS pct_retur
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
              {$whereDate}
            GROUP BY d.kombinasi_produk, d.nama_produk_raw, d.variasi_raw
            ORDER BY {$sortCol} {$order}
        ")->getResultArray();

        // -------------------------------------------------------
        // 2. Summary Keseluruhan
        // -------------------------------------------------------
        $summary = $db->query("
            SELECT
                SUM(d.quantity)                                         AS total_qty_bruto,
                SUM(d.sku_quantity_of_return)                           AS total_retur,
                SUM(d.quantity) - SUM(d.sku_quantity_of_return)         AS total_qty_bersih,
                SUM(d.sku_subtotal_after_discount)                      AS total_revenue,
                SUM(d.sku_settlement_amt)                               AS total_settlement,
                SUM(d.sku_platform_discount)                            AS total_diskon_platform,
                SUM(d.sku_seller_discount)                              AS total_diskon_seller,
                COUNT(DISTINCT d.order_id)                              AS total_orders,
                COUNT(DISTINCT d.kombinasi_produk)                      AS jumlah_varian
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
              {$whereDate}
        ")->getRowArray();

        // -------------------------------------------------------
        // 3. Tren Qty Bersih per Produk per Minggu (top 5)
        // -------------------------------------------------------
        $top5Produk = array_slice(
            array_column($produk, 'kombinasi_produk'),
            0, 5
        );

        $trenData = [];
        if (!empty($top5Produk)) {
            $placeholders = implode(',', array_fill(0, count($top5Produk), '?'));
            $trenData = $db->query("
                SELECT
                    d.kombinasi_produk,
                    YEARWEEK(t.paid_time, 1)                                        AS minggu_key,
                    MIN(DATE(t.paid_time))                                          AS minggu_mulai,
                    SUM(d.quantity) - SUM(d.sku_quantity_of_return)                 AS qty_bersih
                FROM detail_pesanan d
                INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
                WHERE t.status_pesanan = 'Selesai'
                  AND d.kombinasi_produk IN ({$placeholders})
                  AND t.paid_time >= DATE_SUB(NOW(), INTERVAL 90 DAY)
                GROUP BY d.kombinasi_produk, YEARWEEK(t.paid_time, 1)
                ORDER BY minggu_key ASC
            ", $top5Produk)->getResultArray();
        }

        // -------------------------------------------------------
        // 4. Retur per produk (top 5 paling banyak retur)
        // -------------------------------------------------------
        $topRetur = $db->query("
            SELECT
                d.kombinasi_produk,
                SUM(d.sku_quantity_of_return)                           AS total_retur,
                SUM(d.quantity)                                         AS total_qty_bruto,
                SUM(d.quantity) - SUM(d.sku_quantity_of_return)         AS total_qty_bersih,
                ROUND(SUM(d.sku_quantity_of_return) / NULLIF(SUM(d.quantity), 0) * 100, 1) AS pct_retur
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
              AND d.sku_quantity_of_return > 0
              {$whereDate}
            GROUP BY d.kombinasi_produk
            ORDER BY total_retur DESC
            LIMIT 5
        ")->getResultArray();

        return $this->response->setJSON([
            'success'    => true,
            'range'      => $range,
            'sort'       => $sort,
            'order'      => strtolower($order),
            'produk'     => $produk,
            'summary'    => $summary,
            'tren_data'  => $trenData,
            'top_retur'  => $topRetur,
        ]);
    }

    // -------------------------------------------------------
    // GET /rekap-produk/unit-terjual — AJAX: Unit Terjual Bersih per Produk
    //
    // Filter opsi:
    //   ?from=2026-01&to=2026-03   → rentang bulan (YYYY-MM)
    //   ?range=all|30|90|365       → N hari terakhir
    //
    // Output per row:
    //   kombinasi_produk, total_qty_bruto, total_retur,
    //   total_qty_bersih (= bruto - retur), pct_retur, avg_harga_satuan
    //
    // Plus: summary agregat & breakdown per_bulan per produk
    // -------------------------------------------------------
    public function unitTerjual(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        $db    = \Config\Database::connect();
        $from  = $this->request->getGet('from');  // e.g. "2026-01"
        $to    = $this->request->getGet('to');    // e.g. "2026-03"
        $range = $this->request->getGet('range') ?? 'all';

        $where  = "WHERE t.status_pesanan = 'Selesai'";
        $params = [];

        if ($from && $to) {
            $where  .= " AND DATE_FORMAT(t.paid_time, '%Y-%m') BETWEEN ? AND ?";
            $params  = [$from, $to];
        } elseif ($range !== 'all') {
            $days    = (int) $range;
            $where  .= " AND t.paid_time >= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        }

        // -------------------------------------------------------
        // QUERY UTAMA: Unit Terjual Bersih per Produk
        // Logika: qty_bersih = SUM(quantity) - SUM(sku_quantity_of_return)
        // -------------------------------------------------------
        $rows = $db->query("
            SELECT
                d.kombinasi_produk,
                d.nama_produk_raw,
                d.variasi_raw,
                SUM(d.quantity)                                         AS total_qty_bruto,
                SUM(d.sku_quantity_of_return)                           AS total_retur,
                SUM(d.quantity) - SUM(d.sku_quantity_of_return)         AS total_qty_bersih,
                AVG(d.sku_unit_original_price)                          AS avg_harga_satuan,
                SUM(d.sku_subtotal_after_discount)                      AS total_subtotal,
                ROUND(
                    SUM(d.sku_quantity_of_return) / NULLIF(SUM(d.quantity), 0) * 100
                , 1)                                                    AS pct_retur
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            {$where}
            GROUP BY d.kombinasi_produk, d.nama_produk_raw, d.variasi_raw
            ORDER BY total_qty_bersih DESC
        ", $params)->getResultArray();

        // -------------------------------------------------------
        // SUMMARY TOTAL
        // -------------------------------------------------------
        $summary = $db->query("
            SELECT
                SUM(d.quantity)                                         AS total_qty_bruto,
                SUM(d.sku_quantity_of_return)                           AS total_retur,
                SUM(d.quantity) - SUM(d.sku_quantity_of_return)         AS total_qty_bersih,
                COUNT(DISTINCT d.kombinasi_produk)                      AS jumlah_produk,
                COUNT(DISTINCT d.order_id)                              AS jumlah_order
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            {$where}
        ", $params)->getRowArray();

        // -------------------------------------------------------
        // BREAKDOWN PER BULAN per Produk
        // -------------------------------------------------------
        $perBulan = $db->query("
            SELECT
                d.kombinasi_produk,
                DATE_FORMAT(t.paid_time, '%Y-%m')                       AS periode_bulan,
                SUM(d.quantity)                                         AS total_qty_bruto,
                SUM(d.sku_quantity_of_return)                           AS total_retur,
                SUM(d.quantity) - SUM(d.sku_quantity_of_return)         AS total_qty_bersih
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            {$where}
            GROUP BY d.kombinasi_produk, DATE_FORMAT(t.paid_time, '%Y-%m')
            ORDER BY periode_bulan ASC, total_qty_bersih DESC
        ", $params)->getResultArray();

        return $this->response->setJSON([
            'success'   => true,
            'filter'    => ['from' => $from, 'to' => $to, 'range' => $range],
            'rows'      => $rows,
            'summary'   => $summary,
            'per_bulan' => $perBulan,
        ]);
    }
}