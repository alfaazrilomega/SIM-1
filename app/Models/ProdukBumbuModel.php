<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukBumbuModel extends Model
{
    protected $table         = 'produk_bumbu';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['kode', 'nama', 'berat_gram', 'harga_jual', 'stok', 'keterangan'];
    protected $useTimestamps = true;

    // ────────────────────────────────────────────────────────────
    // Semua produk bumbu + ringkasan mutasi stok
    // ────────────────────────────────────────────────────────────
    public function getAllWithLog(): array
    {
        return $this->db->query("
            SELECT p.*,
                COALESCE(SUM(CASE WHEN sl.tipe='masuk'  THEN sl.jumlah ELSE 0 END), 0) AS total_masuk,
                COALESCE(SUM(CASE WHEN sl.tipe='keluar' THEN sl.jumlah ELSE 0 END), 0) AS total_keluar
            FROM produk_bumbu p
            LEFT JOIN stok_bumbu_log sl ON sl.id_produk = p.id
            GROUP BY p.id
            ORDER BY p.nama ASC
        ")->getResultArray();
    }

    // ────────────────────────────────────────────────────────────
    // TAMBAH STOK PRODUK BUMBU
    //
    // Menulis ke:
    //   1. produk_bumbu.stok        (stok aktual)
    //   2. stok_bumbu_log           (log utama — dibaca halaman Produk Bumbu)
    //   3. log_stok_produk          (tabel lama — tetap diisi agar sinkron)
    // ────────────────────────────────────────────────────────────
    public function tambahStok(int $idProduk, int $jumlah, string $keterangan = ''): void
    {
        $tanggal = date('Y-m-d');
        $now     = date('Y-m-d H:i:s');

        // 1. Update stok produk
        $this->db->query(
            "UPDATE produk_bumbu SET stok = stok + ?, updated_at = ? WHERE id = ?",
            [$jumlah, $now, $idProduk]
        );

        // 2. Insert ke stok_bumbu_log (primary log)
        $this->db->query(
            "INSERT INTO stok_bumbu_log (id_produk, tanggal, tipe, jumlah, keterangan, created_at)
             VALUES (?, ?, 'masuk', ?, ?, ?)",
            [$idProduk, $tanggal, $jumlah, $keterangan, $now]
        );

        // 3. Insert ke log_stok_produk (tabel lama — defensive)
        $this->_insertLogStokProduk($idProduk, 'masuk', $jumlah, $keterangan, $tanggal);
    }

    // ────────────────────────────────────────────────────────────
    // KURANGI STOK PRODUK BUMBU
    //
    // Menulis ke tabel yang sama seperti tambahStok.
    // Return false jika stok tidak mencukupi.
    // ────────────────────────────────────────────────────────────
    public function kurangiStok(int $idProduk, int $jumlah, string $keterangan = ''): bool
    {
        $produk = $this->find($idProduk);
        if (!$produk || (int)$produk['stok'] < $jumlah) {
            return false;
        }

        $tanggal = date('Y-m-d');
        $now     = date('Y-m-d H:i:s');

        // 1. Update stok produk
        $this->db->query(
            "UPDATE produk_bumbu SET stok = stok - ?, updated_at = ? WHERE id = ?",
            [$jumlah, $now, $idProduk]
        );

        // 2. Insert ke stok_bumbu_log
        $this->db->query(
            "INSERT INTO stok_bumbu_log (id_produk, tanggal, tipe, jumlah, keterangan, created_at)
             VALUES (?, ?, 'keluar', ?, ?, ?)",
            [$idProduk, $tanggal, $jumlah, $keterangan, $now]
        );

        // 3. Insert ke log_stok_produk
        $this->_insertLogStokProduk($idProduk, 'keluar', $jumlah, $keterangan, $tanggal);

        return true;
    }

    // ────────────────────────────────────────────────────────────
    // Helper: tulis ke log_stok_produk (tabel lama)
    // Try-catch agar tidak menggagalkan transaksi utama jika
    // tabel belum ada atau struktur kolom berbeda.
    // ────────────────────────────────────────────────────────────
    private function _insertLogStokProduk(
        int    $idProduk,
        string $tipe,
        int    $jumlah,
        string $keterangan,
        string $tanggal
    ): void {
        try {
            $this->db->query(
                "INSERT INTO log_stok_produk (id_produk, tipe, jumlah, keterangan, tanggal, created_at)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$idProduk, $tipe, $jumlah, $keterangan, $tanggal, date('Y-m-d H:i:s')]
            );
        } catch (\Throwable $e) {
            log_message('warning', '[ProdukBumbuModel] log_stok_produk insert gagal: ' . $e->getMessage());
        }
    }

    // ────────────────────────────────────────────────────────────
    // Ambil log mutasi dari stok_bumbu_log
    //   $idProduk = 0 → semua produk (tab Log Mutasi global)
    //   $idProduk > 0 → filter per produk (modal Riwayat)
    // ────────────────────────────────────────────────────────────
    public function getLog(int $idProduk = 0, int $limit = 100): array
    {
        $sql    = "
            SELECT sl.*, p.nama AS nama_produk
            FROM stok_bumbu_log sl
            JOIN produk_bumbu p ON p.id = sl.id_produk
        ";
        $params = [];

        if ($idProduk > 0) {
            $sql     .= " WHERE sl.id_produk = ?";
            $params[] = $idProduk;
        }

        $sql .= " ORDER BY sl.created_at DESC, sl.id DESC LIMIT " . (int)$limit;

        return $this->db->query($sql, $params)->getResultArray();
    }
}