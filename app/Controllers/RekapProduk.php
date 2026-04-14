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
        $allowedSort  = ['qty' => 'total_qty', 'revenue' => 'total_revenue', 'orders' => 'total_orders', 'return' => 'total_retur'];
        $sortCol      = $allowedSort[$sort] ?? 'total_qty';
        $order        = in_array($order, ['ASC', 'DESC']) ? $order : 'DESC';

        $db = \Config\Database::connect();

        // WHERE clause berdasarkan range
        $whereDate = '';
        if ($range !== 'all') {
            $days      = (int) $range;
            $whereDate = "AND t.paid_time >= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        }

        // -------------------------------------------------------
        // 1. Rekap per Kombinasi Produk
        // -------------------------------------------------------
        $produk = $db->query("
            SELECT
                d.kombinasi_produk,
                d.nama_produk_raw,
                d.variasi_raw,
                SUM(d.quantity)                     AS total_qty,
                SUM(d.sku_quantity_of_return)        AS total_retur,
                SUM(d.sku_subtotal_after_discount)   AS total_subtotal,
                SUM(d.sku_settlement_amt)            AS total_settlement,
                SUM(d.sku_platform_discount)         AS total_diskon_platform,
                SUM(d.sku_seller_discount)           AS total_diskon_seller,
                AVG(d.sku_unit_original_price)       AS avg_harga_satuan,
                COUNT(DISTINCT d.order_id)           AS total_orders,
                SUM(d.sku_subtotal_after_discount) / NULLIF(SUM(d.quantity), 0) AS avg_revenue_per_unit
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
                SUM(d.quantity)                   AS total_qty,
                SUM(d.sku_quantity_of_return)     AS total_retur,
                SUM(d.sku_subtotal_after_discount) AS total_revenue,
                SUM(d.sku_settlement_amt)         AS total_settlement,
                SUM(d.sku_platform_discount)      AS total_diskon_platform,
                SUM(d.sku_seller_discount)        AS total_diskon_seller,
                COUNT(DISTINCT d.order_id)        AS total_orders,
                COUNT(DISTINCT d.kombinasi_produk) AS jumlah_varian
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
              {$whereDate}
        ")->getRowArray();

        // -------------------------------------------------------
        // 3. Tren Qty per Produk per Minggu (untuk chart stacked)
        //    Ambil top 5 produk saja, biar chart tidak penuh
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
                    YEARWEEK(t.paid_time, 1)   AS minggu_key,
                    MIN(DATE(t.paid_time))      AS minggu_mulai,
                    SUM(d.quantity)             AS qty
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
                SUM(d.sku_quantity_of_return) AS total_retur,
                SUM(d.quantity) AS total_qty,
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
}