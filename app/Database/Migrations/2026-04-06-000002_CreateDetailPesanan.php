<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPesanan extends Migration
{
    public function up(): void
    {
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `detail_pesanan` (
                `id_detail`          INT             NOT NULL AUTO_INCREMENT,
                `order_id`           VARCHAR(30)     NOT NULL,
                `nama_produk_raw`    VARCHAR(500)    DEFAULT NULL,
                `variasi_raw`        VARCHAR(500)    DEFAULT NULL,
                `kombinasi_produk`   VARCHAR(700)    DEFAULT NULL,
                `quantity`           INT             DEFAULT 0,
                `sku_order_amount`   DECIMAL(15,2)   DEFAULT 0.00,
                `sku_settlement_amt` DECIMAL(15,2)   DEFAULT 0.00,
                PRIMARY KEY (`id_detail`),
                KEY `idx_order_id`         (`order_id`),
                KEY `idx_kombinasi_produk` (`kombinasi_produk`(255)),
                CONSTRAINT `fk_detail_order`
                    FOREIGN KEY (`order_id`)
                    REFERENCES `transaksi_pesanan` (`order_id`)
                    ON DELETE CASCADE ON UPDATE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");
    }

    public function down(): void
    {
        $this->db->query('DROP TABLE IF EXISTS `detail_pesanan`');
    }
}
