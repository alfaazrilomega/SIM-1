<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSupportTables extends Migration
{
    public function up(): void
    {
        // product_mapping: maps "Default" variation -> readable label
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `product_mapping` (
                `id`               INT          NOT NULL AUTO_INCREMENT,
                `nama_produk_raw`  VARCHAR(500) NOT NULL,
                `variasi_raw`      VARCHAR(500) NOT NULL DEFAULT 'Default',
                `kombinasi_label`  VARCHAR(700) NOT NULL,
                `keterangan`       VARCHAR(300) DEFAULT NULL,
                `created_at`       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uq_mapping` (`nama_produk_raw`(200), `variasi_raw`(100))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // import_log: audit trail
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `import_log` (
                `id`           INT          NOT NULL AUTO_INCREMENT,
                `filename`     VARCHAR(255) NOT NULL,
                `platform`     VARCHAR(20)  DEFAULT 'TikTok',
                `total_rows`   INT          DEFAULT 0,
                `total_orders` INT          DEFAULT 0,
                `inserted`     INT          DEFAULT 0,
                `updated`      INT          DEFAULT 0,
                `skipped`      INT          DEFAULT 0,
                `imported_at`  TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // Seed product_mapping dengan produk yang diketahui
        $this->db->query("
            INSERT IGNORE INTO `product_mapping`
                (`nama_produk_raw`, `variasi_raw`, `kombinasi_label`, `keterangan`)
            VALUES
                ('Bumbu Rendang Sitti Nurbaya',      'Default', 'Bumbu Rendang',  'Bumbu sachet rendang'),
                ('Bumbu Soto Sitti Nurbaya',         'Default', 'Bumbu Soto',     'Bumbu sachet soto'),
                ('Bumbu Rawon Sitti Nurbaya',        'Default', 'Bumbu Rawon',    'Bumbu sachet rawon'),
                ('Bumbu Gulai Sitti Nurbaya',        'Default', 'Bumbu Gulai',    'Bumbu sachet gulai'),
                ('Bumbu Ceker Mercon Sitti Nurbaya', 'Default', 'Ceker Mercon',   'Bumbu sachet ceker mercon')
        ");

        // Views untuk laporan
        $this->db->query("
            CREATE OR REPLACE VIEW `v_pesanan_selesai` AS
            SELECT t.* FROM `transaksi_pesanan` t
            WHERE t.status_pesanan = 'Selesai'
        ");

        $this->db->query("
            CREATE OR REPLACE VIEW `v_rekap_produk` AS
            SELECT
                d.kombinasi_produk,
                SUM(d.quantity)           AS total_qty,
                SUM(d.sku_settlement_amt) AS total_revenue,
                COUNT(DISTINCT d.order_id) AS total_orders
            FROM `detail_pesanan` d
            INNER JOIN `transaksi_pesanan` t ON t.order_id = d.order_id
            WHERE t.status_pesanan = 'Selesai'
            GROUP BY d.kombinasi_produk
            ORDER BY total_qty DESC
        ");

        $this->db->query("
            CREATE OR REPLACE VIEW `v_pending_withdrawal` AS
            SELECT t.order_id, t.total_amount, t.create_time, t.paid_time
            FROM `transaksi_pesanan` t
            WHERE t.status_pesanan    = 'Selesai'
              AND t.status_penarikan  = 'Belum Ditarik'
            ORDER BY t.paid_time ASC
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP VIEW IF EXISTS `v_pending_withdrawal`');
        $this->db->query('DROP VIEW IF EXISTS `v_rekap_produk`');
        $this->db->query('DROP VIEW IF EXISTS `v_pesanan_selesai`');
        $this->db->query('DROP TABLE IF EXISTS `import_log`');
        $this->db->query('DROP TABLE IF EXISTS `product_mapping`');
    }
}
