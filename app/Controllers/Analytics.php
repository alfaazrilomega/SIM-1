<?php

namespace App\Controllers;

class Analytics extends BaseController
{
    // -------------------------------------------------------
    // GET /analytics  — Halaman Dashboard Analitik
    // -------------------------------------------------------
    public function index(): string
    {
        return view('analytics/index');
    }

    // -------------------------------------------------------
    // GET /analytics/data  — AJAX: Semua data chart & KPI
    // Query param: ?range=7|30|90|365|all
    // -------------------------------------------------------
    public function data(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        $range = $this->request->getGet('range') ?? '30';
        $db    = \Config\Database::connect();

        // --- WHERE clause berdasarkan range ---
        $whereDate = '';
        if ($range !== 'all') {
            $days      = (int) $range;
            $whereDate = "AND COALESCE(paid_time, create_time) >= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        }

        // -------------------------------------------------------
        // 1. KPI Cards
        // -------------------------------------------------------
        $kpi = $db->query("
            SELECT
                COUNT(*) AS total_order,
                SUM(total_amount) AS total_revenue,
                AVG(total_amount) AS avg_order_value,
                SUM(total_quantity) AS total_qty_terjual,
                SUM(total_sku_quantity_of_return) AS total_retur,
                COUNT(CASE WHEN status_penarikan = 'Belum Ditarik' THEN 1 END) AS pending_withdrawal,
                SUM(CASE WHEN status_penarikan = 'Belum Ditarik' THEN total_amount ELSE 0 END) AS dana_pending
            FROM transaksi_pesanan
            WHERE status_pesanan IN ('Selesai', 'Dikirim')
            {$whereDate}
        ")->getRowArray();

        // Total pesanan dibatalkan (periode sama)
        $whereCancel = '';
        if ($range !== 'all') {
            $days        = (int) $range;
            $whereCancel = "AND cancelled_time >= DATE_SUB(NOW(), INTERVAL {$days} DAY)";
        }
        $kpiCancel = $db->query("
            SELECT COUNT(*) AS total_cancel
            FROM transaksi_pesanan
            WHERE status_pesanan = 'Dibatalkan'
            {$whereCancel}
        ")->getRowArray();

        // -------------------------------------------------------
        // 2. Grafik Revenue per Hari (max 60 hari terakhir di range tsb)
        // -------------------------------------------------------
        $limitDays = min((int)($range === 'all' ? 365 : $range), 90);
        $revenueChart = $db->query("
            SELECT
                DATE(COALESCE(paid_time, create_time)) AS tgl,
                SUM(total_amount) AS revenue,
                COUNT(*) AS jml_order
            FROM transaksi_pesanan
            WHERE status_pesanan IN ('Selesai', 'Dikirim')
              AND COALESCE(paid_time, create_time) >= DATE_SUB(NOW(), INTERVAL {$limitDays} DAY)
            GROUP BY DATE(COALESCE(paid_time, create_time))
            ORDER BY tgl ASC
        ")->getResultArray();

        // -------------------------------------------------------
        // 3. Grafik Revenue per Minggu (untuk range >= 30)
        // -------------------------------------------------------
        $weeklyChart = $db->query("
            SELECT
                YEARWEEK(COALESCE(paid_time, create_time), 1) AS minggu_key,
                MIN(DATE(COALESCE(paid_time, create_time)))   AS minggu_mulai,
                SUM(total_amount)      AS revenue,
                COUNT(*)               AS jml_order
            FROM transaksi_pesanan
            WHERE status_pesanan IN ('Selesai', 'Dikirim')
              {$whereDate}
            GROUP BY YEARWEEK(COALESCE(paid_time, create_time), 1)
            ORDER BY minggu_key ASC
        ")->getResultArray();

        // -------------------------------------------------------
        // 4. Top 10 Provinsi (berdasarkan revenue)
        // -------------------------------------------------------
        $topProvinsi = $db->query("
            SELECT
                province,
                COUNT(*) AS jml_order,
                SUM(total_amount) AS revenue
            FROM transaksi_pesanan
            WHERE status_pesanan IN ('Selesai', 'Dikirim')
              AND province IS NOT NULL AND province != ''
              {$whereDate}
            GROUP BY province
            ORDER BY revenue DESC
            LIMIT 10
        ")->getResultArray();

        // -------------------------------------------------------
        // 5. Metode Pembayaran
        // -------------------------------------------------------
        $paymentChart = $db->query("
            SELECT
                payment_method,
                COUNT(*) AS jml,
                SUM(total_amount) AS revenue
            FROM transaksi_pesanan
            WHERE status_pesanan IN ('Selesai', 'Dikirim')
              AND payment_method IS NOT NULL AND payment_method != ''
              {$whereDate}
            GROUP BY payment_method
            ORDER BY jml DESC
        ")->getResultArray();

        // -------------------------------------------------------
        // 6. Breakdown Diskon (platform vs seller)
        // -------------------------------------------------------
        $diskonSummary = $db->query("
            SELECT
                SUM(payment_platform_discount) AS total_diskon_platform,
                SUM(d.sku_seller_discount)      AS total_diskon_seller,
                SUM(d.sku_platform_discount)    AS total_diskon_sku_platform,
                SUM(t.total_amount)             AS total_revenue
            FROM transaksi_pesanan t
            LEFT JOIN detail_pesanan d ON d.order_id = t.order_id
            WHERE t.status_pesanan IN ('Selesai', 'Dikirim')
              {$whereDate}
        ")->getRowArray();

        // -------------------------------------------------------
        // 7. Tren Harian (7 hari terakhir) — quick stat
        // -------------------------------------------------------
        $daily7 = $db->query("
            SELECT
                DATE(COALESCE(paid_time, create_time)) AS tgl,
                SUM(total_amount) AS revenue,
                COUNT(*) AS jml_order
            FROM transaksi_pesanan
            WHERE status_pesanan IN ('Selesai', 'Dikirim')
              AND COALESCE(paid_time, create_time) >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(COALESCE(paid_time, create_time))
            ORDER BY tgl ASC
        ")->getResultArray();

        // -------------------------------------------------------
        // 8. Rasio Status Pesanan
        // -------------------------------------------------------
        $statusChart = $db->query("
            SELECT status_pesanan, COUNT(*) AS jml
            FROM transaksi_pesanan
            WHERE 1=1 {$whereDate}
            GROUP BY status_pesanan
        ")->getResultArray();

        // -------------------------------------------------------
        // 9. Alasan Pembatalan
        // -------------------------------------------------------
        $cancelReasons = $db->query("
            SELECT
                cancel_reason,
                cancel_by,
                COUNT(*) AS jml
            FROM transaksi_pesanan
            WHERE status_pesanan = 'Dibatalkan'
              AND cancel_reason IS NOT NULL AND cancel_reason != ''
              {$whereCancel}
            GROUP BY cancel_reason, cancel_by
            ORDER BY jml DESC
            LIMIT 10
        ")->getResultArray();

        // -------------------------------------------------------
        // 10. Revenue bulan ini vs bulan lalu (perbandingan)
        // -------------------------------------------------------
        $revBulanIni = $db->query("
            SELECT SUM(total_amount) AS rev
            FROM transaksi_pesanan
            WHERE status_pesanan = 'Selesai'
              AND YEAR(paid_time) = YEAR(NOW())
              AND MONTH(paid_time) = MONTH(NOW())
        ")->getRowArray()['rev'] ?? 0;

        $revBulanLalu = $db->query("
            SELECT SUM(total_amount) AS rev
            FROM transaksi_pesanan
            WHERE status_pesanan = 'Selesai'
              AND YEAR(paid_time) = YEAR(NOW() - INTERVAL 1 MONTH)
              AND MONTH(paid_time) = MONTH(NOW() - INTERVAL 1 MONTH)
        ")->getRowArray()['rev'] ?? 0;

        // -------------------------------------------------------
        // 11. Top Produk Terlaris
        // -------------------------------------------------------
        $topProducts = $db->query("
            SELECT 
                d.kombinasi_produk AS nama_produk_raw, 
                SUM(d.quantity) AS total_qty, 
                SUM(d.sku_subtotal_after_discount) AS total_revenue,
                SUM(t.total_sku_quantity_of_return) AS total_return
            FROM detail_pesanan d
            JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan IN ('Selesai', 'Dikirim')
              {$whereDate}
            GROUP BY d.kombinasi_produk
            ORDER BY total_qty DESC
            LIMIT 5
        ")->getResultArray();

        return $this->response->setJSON([
            'success'       => true,
            'range'         => $range,
            'kpi'           => $kpi,
            'kpi_cancel'    => $kpiCancel,
            'revenue_chart' => $revenueChart,
            'weekly_chart'  => $weeklyChart,
            'top_provinsi'  => $topProvinsi,
            'payment_chart' => $paymentChart,
            'diskon'        => $diskonSummary,
            'daily7'        => $daily7,
            'status_chart'  => $statusChart,
            'cancel_reasons'=> $cancelReasons,
            'top_products'  => $topProducts,
            'rev_bulan_ini' => $revBulanIni,
            'rev_bulan_lalu'=> $revBulanLalu,
        ]);
    }
}