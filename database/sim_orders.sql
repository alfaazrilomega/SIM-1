-- =====================================================
-- SIM (Sales Information Management) - Database Schema
-- Platform: TikTok OrderSKUList Export (Full 63 Columns)
-- Updated: V2 with Standardized Financial Workflow
-- =====================================================

CREATE DATABASE IF NOT EXISTS `sim_orders`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `sim_orders`;

-- =====================================================
-- TABLE 1: transaksi_pesanan
-- Data tingkat Order dan detail pengiriman/pembayaran
-- =====================================================
CREATE TABLE IF NOT EXISTS `transaksi_pesanan` (
  `order_id`                  VARCHAR(30)     NOT NULL COMMENT 'TikTok Order ID (18-digit unique)',
  `platform`                  VARCHAR(20)     NOT NULL DEFAULT 'TikTok',
  
  -- Enum Workflow Logistik & Retur
  `status_pesanan`            ENUM('Belum Bayar', 'Menunggu Pengiriman', 'Dikirim', 'Selesai', 'Dibatalkan', 'Retur', 'Lainnya') NOT NULL DEFAULT 'Lainnya',
  `order_substatus`           VARCHAR(100)    DEFAULT NULL,
  `cancelation_return_type`   VARCHAR(100)    DEFAULT NULL,
  
  -- Penarikan CEO
  `status_penarikan`          ENUM('Belum Ditarik','Sudah Ditarik') NOT NULL DEFAULT 'Belum Ditarik' COMMENT 'CEO withdrawal flag',
  
  -- Akumulasi Keuangan & Kuantitas Level Order
  `total_amount`              DECIMAL(15,2)   DEFAULT 0.00 COMMENT 'Valid Revenue (Total Diterima)',
  `order_amount`              DECIMAL(15,2)   DEFAULT 0.00 COMMENT 'Nilai Pesanan Bruto',
  `order_refund_amount`       DECIMAL(15,2)   DEFAULT 0.00,
  `total_quantity`            INT             DEFAULT 0,
  `total_sku_quantity_of_return` INT          DEFAULT 0,
  
  -- Rincian Biaya
  `shipping_fee_after_discount`          DECIMAL(15,2) DEFAULT 0.00,
  `original_shipping_fee`                DECIMAL(15,2) DEFAULT 0.00,
  `shipping_fee_seller_discount`         DECIMAL(15,2) DEFAULT 0.00,
  `shipping_fee_platform_discount`       DECIMAL(15,2) DEFAULT 0.00,
  `payment_platform_discount`            DECIMAL(15,2) DEFAULT 0.00,
  `buyer_service_fee`                    DECIMAL(15,2) DEFAULT 0.00,
  `handling_fee`                         DECIMAL(15,2) DEFAULT 0.00,
  
  -- Timestamps
  `create_time`               DATETIME        DEFAULT NULL,
  `paid_time`                 DATETIME        DEFAULT NULL,
  `rts_time`                  DATETIME        DEFAULT NULL,
  `shipped_time`              DATETIME        DEFAULT NULL,
  `delivered_time`            DATETIME        DEFAULT NULL,
  `cancelled_time`            DATETIME        DEFAULT NULL,
  
  -- Atribut Batal & Logistik
  `cancel_by`                 VARCHAR(100)    DEFAULT NULL,
  `cancel_reason`             VARCHAR(255)    DEFAULT NULL,
  `fulfillment_type`          VARCHAR(100)    DEFAULT NULL,
  `warehouse_name`            VARCHAR(150)    DEFAULT NULL,
  `tracking_id`               VARCHAR(100)    DEFAULT NULL,
  `delivery_option`           VARCHAR(50)     DEFAULT NULL,
  `shipping_provider`         VARCHAR(100)    DEFAULT NULL,
  
  -- Profil Pembeli & Alamat
  `buyer_username`            VARCHAR(150)    DEFAULT NULL,
  `recipient`                 VARCHAR(150)    DEFAULT NULL,
  `phone`                     VARCHAR(50)     DEFAULT NULL,
  `zipcode`                   VARCHAR(20)     DEFAULT NULL,
  `country`                   VARCHAR(50)     DEFAULT NULL,
  `province`                  VARCHAR(100)    DEFAULT NULL,
  `regency_and_city`          VARCHAR(150)    DEFAULT NULL,
  `districts`                 VARCHAR(150)    DEFAULT NULL,
  `villages`                  VARCHAR(150)    DEFAULT NULL,
  `detail_address`            TEXT            DEFAULT NULL,
  `additional_address`        TEXT            DEFAULT NULL,
  
  -- Miscellaneous
  `payment_method`            VARCHAR(50)     DEFAULT NULL,
  `weight_kg`                 DECIMAL(10,3)   DEFAULT 0.000,
  `package_id`                VARCHAR(100)    DEFAULT NULL,
  `buyer_message`             TEXT            DEFAULT NULL,
  
  `tanggal_update`            TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  PRIMARY KEY (`order_id`),
  KEY `idx_status_pesanan` (`status_pesanan`),
  KEY `idx_status_penarikan` (`status_penarikan`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- TABLE 2: detail_pesanan
-- Data spesifik SKU / produk
-- =====================================================
CREATE TABLE IF NOT EXISTS `detail_pesanan` (
  `id_detail`                    INT             NOT NULL AUTO_INCREMENT,
  `order_id`                     VARCHAR(30)     NOT NULL,
  
  -- Identitas Produk
  `sku_id`                       VARCHAR(100)    DEFAULT NULL,
  `seller_sku`                   VARCHAR(100)    DEFAULT NULL,
  `nama_produk_raw`              VARCHAR(500)    DEFAULT NULL,
  `variasi_raw`                  VARCHAR(500)    DEFAULT NULL,
  `kombinasi_produk`             VARCHAR(700)    DEFAULT NULL,
  
  -- Kuantitas & Retur
  `quantity`                     INT             DEFAULT 0,
  `sku_quantity_of_return`       INT             DEFAULT 0,
  
  -- Keuangan Item
  `sku_unit_original_price`      DECIMAL(15,2)   DEFAULT 0.00,
  `sku_subtotal_before_discount` DECIMAL(15,2)   DEFAULT 0.00,
  `sku_platform_discount`        DECIMAL(15,2)   DEFAULT 0.00,
  `sku_seller_discount`          DECIMAL(15,2)   DEFAULT 0.00,
  `sku_subtotal_after_discount`  DECIMAL(15,2)   DEFAULT 0.00,
  `sku_settlement_amt`           DECIMAL(15,2)   DEFAULT 0.00 COMMENT 'Mapped dari Settlement Amount',
  
  PRIMARY KEY (`id_detail`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_kombinasi_produk` (`kombinasi_produk`(255)),
  CONSTRAINT `fk_detail_order`
    FOREIGN KEY (`order_id`) REFERENCES `transaksi_pesanan` (`order_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- TABLE 3: product_mapping
-- =====================================================
CREATE TABLE IF NOT EXISTS `product_mapping` (
  `id`                INT             NOT NULL AUTO_INCREMENT,
  `nama_produk_raw`   VARCHAR(500)    NOT NULL,
  `variasi_raw`       VARCHAR(500)    NOT NULL DEFAULT 'Default',
  `kombinasi_label`   VARCHAR(700)    NOT NULL,
  `keterangan`        VARCHAR(300)    DEFAULT NULL,
  `created_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mapping` (`nama_produk_raw`(200), `variasi_raw`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- TABLE 4: import_log
-- =====================================================
CREATE TABLE IF NOT EXISTS `import_log` (
  `id`              INT             NOT NULL AUTO_INCREMENT,
  `filename`        VARCHAR(255)    NOT NULL,
  `platform`        VARCHAR(20)     DEFAULT 'TikTok',
  `total_rows`      INT             DEFAULT 0,
  `total_orders`    INT             DEFAULT 0,
  `inserted`        INT             DEFAULT 0,
  `updated`         INT             DEFAULT 0,
  `skipped`         INT             DEFAULT 0,
  `imported_at`     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================
-- SEED: product_mapping
-- =====================================================
INSERT IGNORE INTO `product_mapping`
  (`nama_produk_raw`, `variasi_raw`, `kombinasi_label`, `keterangan`)
VALUES
  ('Bumbu Rendang Sitti Nurbaya',  'Default', 'Bumbu Rendang', 'Bumbu sachet rendang'),
  ('Bumbu Soto Sitti Nurbaya',     'Default', 'Bumbu Soto',    'Bumbu sachet soto'),
  ('Bumbu Rawon Sitti Nurbaya',    'Default', 'Bumbu Rawon',   'Bumbu sachet rawon'),
  ('Bumbu Gulai Sitti Nurbaya',    'Default', 'Bumbu Gulai',   'Bumbu sachet gulai'),
  ('Bumbu Ceker Mercon Sitti Nurbaya', 'Default', 'Ceker Mercon', 'Bumbu sachet ceker mercon');

-- =====================================================
-- VIEWS
-- =====================================================
CREATE OR REPLACE VIEW `v_pesanan_selesai` AS
  SELECT
    t.order_id,
    t.platform,
    t.status_pesanan,
    t.status_penarikan,
    t.total_amount,
    t.create_time,
    t.paid_time,
    t.tanggal_update
  FROM `transaksi_pesanan` t
  WHERE t.status_pesanan = 'Selesai';

CREATE OR REPLACE VIEW `v_rekap_produk` AS
  SELECT
    d.kombinasi_produk,
    SUM(d.quantity)         AS total_qty,
    SUM(d.sku_settlement_amt) AS total_revenue,
    COUNT(DISTINCT d.order_id) AS total_orders
  FROM `detail_pesanan` d
  INNER JOIN `transaksi_pesanan` t ON t.order_id = d.order_id
  WHERE t.status_pesanan = 'Selesai'
  GROUP BY d.kombinasi_produk
  ORDER BY total_qty DESC;

CREATE OR REPLACE VIEW `v_pending_withdrawal` AS
  SELECT
    t.order_id,
    t.total_amount,
    t.create_time,
    t.paid_time
  FROM `transaksi_pesanan` t
  WHERE t.status_pesanan = 'Selesai'
    AND t.status_penarikan = 'Belum Ditarik'
  ORDER BY t.paid_time ASC;
