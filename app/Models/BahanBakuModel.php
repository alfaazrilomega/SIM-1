<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table      = 'bahan_baku';
    protected $primaryKey = 'id';
    protected $allowedFields = ['kode','nama','satuan','stok','harga_beli','keterangan'];
    protected $useTimestamps = true;

    public function getAllWithStats(): array
    {
        return $this->db->query("
            SELECT b.*,
                COALESCE(SUM(CASE WHEN pb.id IS NOT NULL THEN pb.jumlah END), 0) AS total_beli,
                COALESCE(SUM(CASE WHEN ub.id IS NOT NULL THEN ub.jumlah END), 0) AS total_pakai
            FROM bahan_baku b
            LEFT JOIN pembelian_bahan pb ON pb.id_bahan = b.id
            LEFT JOIN penggunaan_bahan ub ON ub.id_bahan = b.id
            GROUP BY b.id
            ORDER BY b.nama ASC
        ")->getResultArray();
    }

    public function addStok(int $idBahan, float $jumlah): void
    {
        $this->db->query(
            "UPDATE bahan_baku SET stok = stok + ?, harga_beli = harga_beli WHERE id = ?",
            [$jumlah, $idBahan]
        );
    }

    public function addStokWithHarga(int $idBahan, float $jumlah, float $harga): void
    {
        $this->db->query(
            "UPDATE bahan_baku SET stok = stok + ?, harga_beli = ? WHERE id = ?",
            [$jumlah, $harga, $idBahan]
        );
    }

    public function kurangiStok(int $idBahan, float $jumlah): bool
    {
        $bahan = $this->find($idBahan);
        if (!$bahan || $bahan['stok'] < $jumlah) return false;
        $this->db->query(
            "UPDATE bahan_baku SET stok = stok - ? WHERE id = ?",
            [$jumlah, $idBahan]
        );
        return true;
    }
}