<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMitraAndAlterPesanan extends Migration
{
    public function up(): void
    {
        // 1. Create tabel mitra_bisnis
        $this->db->query("
            CREATE TABLE IF NOT EXISTS `mitra_bisnis` (
                `id_mitra`   INT          NOT NULL AUTO_INCREMENT,
                `nama_mitra` VARCHAR(200) NOT NULL,
                `tipe_mitra` ENUM('Reseller', 'Maklon') NOT NULL,
                `no_hp`      VARCHAR(50)  DEFAULT NULL,
                `alamat`     TEXT         DEFAULT NULL,
                `created_at` DATETIME     DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id_mitra`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
        ");

        // 2. Alter tabel transaksi_pesanan
        // Menambahkan id_mitra sebagai nullable foreign key ke transaksi
        $this->db->query("
            ALTER TABLE `transaksi_pesanan`
            ADD COLUMN `id_mitra` INT NULL DEFAULT NULL AFTER `platform`,
            ADD KEY `idx_id_mitra` (`id_mitra`)
        ");
        
        // Sengaja tidak menambahkan explicit FOREIGN KEY constraint yang ketat
        // agar fitur hapus/reset database import TikTok tetap bisa berjalan 
        // tanpa crash contraint restrict. Relasi diatur secara logika di Aplikasi.
    }

    public function down(): void
    {
        // Rollback Alter tabel transaksi_pesanan
        try {
            $this->db->query("ALTER TABLE `transaksi_pesanan` DROP KEY `idx_id_mitra`");
            $this->db->query("ALTER TABLE `transaksi_pesanan` DROP COLUMN `id_mitra`");
        } catch (\Exception $e) {
            // Ignore if key/column not found
        }

        // Drop tabel mitra_bisnis
        $this->db->query('DROP TABLE IF EXISTS `mitra_bisnis`');
    }
}
