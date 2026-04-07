-- =====================================================
-- SIM (Sales Information Management) - Database Schema
-- Platform: TikTok OrderSKUList Export
-- Created: 2026-04-06
-- Run this in phpMyAdmin or MySQL CLI
-- =====================================================

CREATE DATABASE IF NOT EXISTS `sim_orders`
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE `sim_orders`;

-- =====================================================
-- TABLE 1: transaksi_pesanan
-- One row per Order ID (main order header)
-- =====================================================
CREATE TABLE IF NOT EXISTS `transaksi_pesanan` (
  `order_id`          VARCHAR(30)     NOT NULL COMMENT 'TikTok Order ID (18-digit unique)',
  `platform`          VARCHAR(20)     NOT NULL DEFAULT 'TikTok',
  `status_pesanan`    VARCHAR(60)     DEFAULT NULL COMMENT 'Selesai / Dikirim / Dibatalkan / dll',
  `status_penarikan`  ENUM('Belum Ditarik','Sudah Ditarik') NOT NULL DEFAULT 'Belum Ditarik' COMMENT 'CEO withdrawal flag — jangan di-reset saat re-import',
  `total_amount`      DECIMAL(15,2)   DEFAULT 0.00 COMMENT 'Sum of SKU Settlement Amount for this order',
  `create_time`       DATETIME        DEFAULT NULL COMMENT 'Waktu pesanan dibuat (dari Excel)',
  `paid_time`         DATETIME        DEFAULT NULL COMMENT 'Waktu pembayaran dikonfirmasi',
  `tanggal_update`    TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT 'Waktu terakhir file Excel mengupdate baris ini',
  PRIMARY KEY (`order_id`),
  KEY `idx_status_pesanan` (`status_pesanan`),
  KEY `idx_status_penarikan` (`status_penarikan`),
  KEY `idx_create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tabel header pesanan. 1 row = 1 Order ID. status_penarikan TIDAK di-reset saat re-import.';


-- =====================================================
-- TABLE 2: detail_pesanan
-- One row per SKU/line-item within an order
-- DELETE & RE-INSERT on every upsert of parent order_id
-- =====================================================
CREATE TABLE IF NOT EXISTS `detail_pesanan` (
  `id_detail`           INT             NOT NULL AUTO_INCREMENT,
  `order_id`            VARCHAR(30)     NOT NULL,
  `nama_produk_raw`     VARCHAR(500)    DEFAULT NULL COMMENT 'Kolom "Product Name" dari Excel, verbatim',
  `variasi_raw`         VARCHAR(500)    DEFAULT NULL COMMENT 'Kolom "Variation" dari Excel, verbatim (incl "Default")',
  `kombinasi_produk`    VARCHAR(700)    DEFAULT NULL COMMENT 'Auto-generated: nama_produk + variasi (atau label dari mapping)',
  `quantity`            INT             DEFAULT 0,
  `sku_order_amount`    DECIMAL(15,2)   DEFAULT 0.00 COMMENT 'SKU Order Amount dari Excel',
  `sku_settlement_amt`  DECIMAL(15,2)   DEFAULT 0.00 COMMENT 'SKU Settlement Amount dari Excel',
  PRIMARY KEY (`id_detail`),
  KEY `idx_order_id` (`order_id`),
  KEY `idx_kombinasi_produk` (`kombinasi_produk`(255)),
  CONSTRAINT `fk_detail_order`
    FOREIGN KEY (`order_id`) REFERENCES `transaksi_pesanan` (`order_id`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tabel detail SKU per pesanan. Di-rebuild ulang setiap kali parent order di-re-import.';


-- =====================================================
-- TABLE 3: product_mapping
-- Maps "Default" variation → readable label
-- e.g. "Bumbu Rendang Sitti Nurbaya" + "Default" → "Bumbu Rendang 250gr"
-- =====================================================
CREATE TABLE IF NOT EXISTS `product_mapping` (
  `id`                INT             NOT NULL AUTO_INCREMENT,
  `nama_produk_raw`   VARCHAR(500)    NOT NULL COMMENT 'Harus match persis dengan kolom Product Name di Excel',
  `variasi_raw`       VARCHAR(500)    NOT NULL DEFAULT 'Default',
  `kombinasi_label`   VARCHAR(700)    NOT NULL COMMENT 'Label yang akan ditampilkan, contoh: "Bumbu Rendang 250gr"',
  `keterangan`        VARCHAR(300)    DEFAULT NULL,
  `created_at`        TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_mapping` (`nama_produk_raw`(200), `variasi_raw`(100))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Tabel konfigurasi mapping variasi "Default" ke label baca yang jelas.';


-- =====================================================
-- TABLE 4: import_log
-- Records every import session for audit trail
-- =====================================================
CREATE TABLE IF NOT EXISTS `import_log` (
  `id`              INT             NOT NULL AUTO_INCREMENT,
  `filename`        VARCHAR(255)    NOT NULL,
  `platform`        VARCHAR(20)     DEFAULT 'TikTok',
  `total_rows`      INT             DEFAULT 0 COMMENT 'Total baris data di Excel (exclude header)',
  `total_orders`    INT             DEFAULT 0 COMMENT 'Total unique Order ID diproses',
  `inserted`        INT             DEFAULT 0 COMMENT 'Order ID baru yang di-INSERT',
  `updated`         INT             DEFAULT 0 COMMENT 'Order ID lama yang di-UPDATE',
  `skipped`         INT             DEFAULT 0 COMMENT 'Baris yang di-skip (data invalid)',
  `imported_at`     TIMESTAMP       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
  COMMENT='Log setiap sesi import file Excel.';


-- =====================================================
-- SEED: product_mapping (pre-populated from known products)
-- Produk bumbu dengan variasi "Default" → nama aslinya
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
-- VIEWS untuk laporan
-- =====================================================

-- View: Hanya pesanan Selesai (sesuai business rule)
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

-- View: Rekap quantity per kombinasi produk (hanya Selesai)
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

-- View: Pending withdrawal (CEO view)
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
