<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanViews extends Migration
{
    public function up(): void
    {
        // -------------------------------------------------------
        // View 1: Penjualan Bersih per Produk (Total)
        // qty_bersih = total_qty_bruto - total_retur
        // -------------------------------------------------------
        $this->db->query('DROP VIEW IF EXISTS v_penjualan_bersih_produk');

        $this->db->query("
            CREATE VIEW v_penjualan_bersih_produk AS
            SELECT
                d.kombinasi_produk,
                SUM(d.quantity)                 AS total_qty_bruto,
                SUM(d.sku_quantity_of_return)   AS total_retur,
                SUM(d.quantity - d.sku_quantity_of_return) AS total_qty_bersih,
                SUM(d.sku_subtotal_after_discount)         AS total_pendapatan
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
              AND d.kombinasi_produk IS NOT NULL
              AND d.kombinasi_produk != ''
              AND d.kombinasi_produk NOT LIKE 'Platform%'
            GROUP BY d.kombinasi_produk
            ORDER BY total_qty_bersih DESC
        ");

        // -------------------------------------------------------
        // View 2: Penjualan Bersih per Produk per Bulan
        // -------------------------------------------------------
        $this->db->query('DROP VIEW IF EXISTS v_penjualan_per_bulan');

        $this->db->query("
            CREATE VIEW v_penjualan_per_bulan AS
            SELECT
                DATE_FORMAT(t.paid_time, '%Y-%m')           AS periode_bulan,
                d.kombinasi_produk,
                SUM(d.quantity)                              AS total_qty_bruto,
                SUM(d.sku_quantity_of_return)                AS total_retur,
                SUM(d.quantity - d.sku_quantity_of_return)   AS total_qty_bersih,
                SUM(d.sku_subtotal_after_discount)           AS total_pendapatan
            FROM detail_pesanan d
            INNER JOIN transaksi_pesanan t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
              AND t.paid_time IS NOT NULL
              AND d.kombinasi_produk IS NOT NULL
              AND d.kombinasi_produk != ''
              AND d.kombinasi_produk NOT LIKE 'Platform%'
            GROUP BY periode_bulan, d.kombinasi_produk
            ORDER BY periode_bulan DESC, total_qty_bersih DESC
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP VIEW IF EXISTS v_penjualan_per_bulan');
        $this->db->query('DROP VIEW IF EXISTS v_penjualan_bersih_produk');
    }
}
