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
                
                -- Status Fields
                `status_pesanan`   ENUM('Belum Bayar', 'Menunggu Pengiriman', 'Dikirim', 'Selesai', 'Dibatalkan', 'Retur', 'Lainnya') DEFAULT 'Lainnya',
                `status_penarikan` ENUM('Belum Ditarik','Sudah Ditarik')
                                   NOT NULL DEFAULT 'Belum Ditarik'
                                   COMMENT 'CEO flag — TIDAK di-reset saat re-import',
                `order_substatus`  VARCHAR(150)    DEFAULT NULL,
                `cancelation_return_type` VARCHAR(150) DEFAULT NULL,

                -- Financials
                `total_amount`                 DECIMAL(15,2)   DEFAULT 0.00 COMMENT 'Valid Revenue (0 if cancelled/returned)',
                `order_amount`                 DECIMAL(15,2)   DEFAULT 0.00,
                `order_refund_amount`          DECIMAL(15,2)   DEFAULT 0.00,
                `shipping_fee_after_discount`  DECIMAL(15,2)   DEFAULT 0.00,
                `original_shipping_fee`        DECIMAL(15,2)   DEFAULT 0.00,
                `shipping_fee_seller_discount` DECIMAL(15,2)   DEFAULT 0.00,
                `shipping_fee_platform_discount` DECIMAL(15,2) DEFAULT 0.00,
                `payment_platform_discount`    DECIMAL(15,2)   DEFAULT 0.00,
                `buyer_service_fee`            DECIMAL(15,2)   DEFAULT 0.00,
                `handling_fee`                 DECIMAL(15,2)   DEFAULT 0.00,

                -- Aggregated quantities
                `total_quantity`               INT             DEFAULT 0,
                `total_sku_quantity_of_return` INT             DEFAULT 0,

                -- Timestamps
                `create_time`      DATETIME        DEFAULT NULL,
                `paid_time`        DATETIME        DEFAULT NULL,
                `rts_time`         DATETIME        DEFAULT NULL,
                `shipped_time`     DATETIME        DEFAULT NULL,
                `delivered_time`   DATETIME        DEFAULT NULL,
                `cancelled_time`   DATETIME        DEFAULT NULL,
                
                -- Logistics & Fulfillment
                `fulfillment_type` VARCHAR(100)    DEFAULT NULL,
                `warehouse_name`   VARCHAR(150)    DEFAULT NULL,
                `tracking_id`      VARCHAR(100)    DEFAULT NULL,
                `delivery_option`  VARCHAR(100)    DEFAULT NULL,
                `shipping_provider` VARCHAR(150)   DEFAULT NULL,
                `weight_kg`        DECIMAL(10,3)   DEFAULT 0.000,
                `package_id`       VARCHAR(100)    DEFAULT NULL,

                -- Cancellation Details
                `cancel_by`        VARCHAR(100)    DEFAULT NULL,
                `cancel_reason`    VARCHAR(300)    DEFAULT NULL,

                -- Buyer Details
                `buyer_username`   VARCHAR(150)    DEFAULT NULL,
                `recipient`        VARCHAR(150)    DEFAULT NULL,
                `phone`            VARCHAR(50)     DEFAULT NULL,
                `zipcode`          VARCHAR(20)     DEFAULT NULL,
                `country`          VARCHAR(100)    DEFAULT NULL,
                `province`         VARCHAR(150)    DEFAULT NULL,
                `regency_and_city` VARCHAR(150)    DEFAULT NULL,
                `districts`        VARCHAR(150)    DEFAULT NULL,
                `villages`         VARCHAR(150)    DEFAULT NULL,
                `detail_address`   TEXT            DEFAULT NULL,
                `additional_address` TEXT          DEFAULT NULL,
                `buyer_message`    TEXT            DEFAULT NULL,
                `payment_method`   VARCHAR(100)    DEFAULT NULL,

                -- System Fields
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
