<?php

namespace App\Models;

use CodeIgniter\Model;

class ProdukBumbuModel extends Model
{
    protected $table      = 'produk_bumbu';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode','nama','berat_gram','harga_jual','stok','keterangan'];
    protected $useTimestamps = true;

    public function getAllWithLog(): array
    {
        return $this->db->query("
            SELECT p.*,
                COALESCE(SUM(CASE WHEN sl.tipe='masuk' THEN sl.jumlah ELSE 0 END), 0) AS total_masuk,
                COALESCE(SUM(CASE WHEN sl.tipe='keluar' THEN sl.jumlah ELSE 0 END), 0) AS total_keluar
            FROM produk_bumbu p
            LEFT JOIN stok_bumbu_log sl ON sl.id_produk = p.id
            GROUP BY p.id
            ORDER BY p.nama ASC
        ")->getResultArray();
    }

    public function tambahStok(int $idProduk, int $jumlah, string $keterangan = ''): void
    {
        $this->db->query("UPDATE produk_bumbu SET stok = stok + ? WHERE id = ?", [$jumlah, $idProduk]);
        $this->db->query(
            "INSERT INTO stok_bumbu_log (id_produk, tanggal, tipe, jumlah, keterangan) VALUES (?, CURDATE(), 'masuk', ?, ?)",
            [$idProduk, $jumlah, $keterangan]
        );
    }

    public function kurangiStok(int $idProduk, int $jumlah, string $keterangan = ''): bool
    {
        $produk = $this->find($idProduk);
        if (!$produk || $produk['stok'] < $jumlah) return false;
        $this->db->query("UPDATE produk_bumbu SET stok = stok - ? WHERE id = ?", [$jumlah, $idProduk]);
        $this->db->query(
            "INSERT INTO stok_bumbu_log (id_produk, tanggal, tipe, jumlah, keterangan) VALUES (?, CURDATE(), 'keluar', ?, ?)",
            [$idProduk, $jumlah, $keterangan]
        );
        return true;
    }

    public function getLog(int $idProduk = 0, int $limit = 50): array
    {
        $sql = "
            SELECT sl.*, p.nama AS nama_produk
            FROM stok_bumbu_log sl
            JOIN produk_bumbu p ON p.id = sl.id_produk
        ";
        $params = [];
        if ($idProduk > 0) {
            $sql .= " WHERE sl.id_produk = ?";
            $params[] = $idProduk;
        }
        $sql .= " ORDER BY sl.created_at DESC LIMIT $limit";
        return $this->db->query($sql, $params)->getResultArray();
    }
}