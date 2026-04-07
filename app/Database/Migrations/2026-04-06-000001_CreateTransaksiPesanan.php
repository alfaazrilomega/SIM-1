<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransaksiPesanan extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `transaksi_pesanan` (
                `order_id`         VARCHAR(30)     NOT NULL COMMENT 'TikTok Order ID (18-digit)',
                `platform`         VARCHAR(20)     NOT NULL DEFAULT 'TikTok',
                `status_pesanan`   VARCHAR(60)     DEFAULT NULL,
                `status_penarikan` ENUM('Belum Ditarik','Sudah Ditarik')
                                   NOT NULL DEFAULT 'Belum Ditarik'
                                   COMMENT 'CEO flag — TIDAK di-reset saat re-import',
                `total_amount`     DECIMAL(15,2)   DEFAULT 0.00,
                `create_time`      DATETIME        DEFAULT NULL,
                `paid_time`        DATETIME        DEFAULT NULL,
                `tanggal_update`   TIMESTAMP       NOT NULL
                                   DEFAULT CURRENT_TIMESTAMP
                                   ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`order_id`),
                KEY `idx_status_pesanan`   (`status_pesanan`),
                KEY `idx_status_penarikan` (`status_penarikan`),
                KEY `idx_create_time`      (`create_time`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP TABLE IF EXISTS `transaksi_pesanan`');
    }
}
