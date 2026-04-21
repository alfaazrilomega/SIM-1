<?php

namespace App\Models;

use CodeIgniter\Model;

class BahanBakuModel extends Model
{
    protected $table         = 'bahan_baku';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['kode', 'nama', 'satuan', 'stok', 'harga_beli', 'keterangan'];
    protected $useTimestamps = true;

    // ────────────────────────────────────────────────────────────
    // Semua bahan + statistik total beli & total pakai
    // ────────────────────────────────────────────────────────────
    public function getAllWithStats(): array
    {
        return $this->db->query("
            SELECT b.*,
                COALESCE((SELECT SUM(pb.jumlah) FROM pembelian_bahan  pb WHERE pb.id_bahan = b.id), 0) AS total_beli,
                COALESCE((SELECT SUM(ub.jumlah) FROM penggunaan_bahan ub WHERE ub.id_bahan = b.id), 0) AS total_pakai
            FROM bahan_baku b
            ORDER BY b.nama ASC
        ")->getResultArray();
    }

    // ────────────────────────────────────────────────────────────
    // TAMBAH STOK
    //
    // Parameter $catatLog:
    //   FALSE (default) → hanya UPDATE stok, TIDAK insert ke
    //                     pembelian_bahan. Dipakai oleh controller
    //                     BahanBaku::beli() yang sudah INSERT sendiri.
    //   TRUE            → UPDATE stok + INSERT ke pembelian_bahan.
    //                     Dipakai jika tidak ada controller yang
    //                     handle log-nya (mis. kembalian stok dari
    //                     BatalProduksi tidak perlu dicatat).
    //
    // Catatan: batal-produksi TIDAK catat ke pembelian_bahan karena
    // itu bukan transaksi beli — cukup kembalikan stok.
    // ────────────────────────────────────────────────────────────
    public function tambahStok(
        int    $idBahan,
        float  $jumlah,
        string $keterangan = '',
        float  $harga      = 0,
        string $supplier   = '',
        string $tanggal    = '',
        bool   $catatLog   = false
    ): void {
        $tanggal = $tanggal ?: date('Y-m-d');
        $now     = date('Y-m-d H:i:s');

        // 1. Update stok (+ update harga_beli jika diberikan)
        if ($harga > 0) {
            $this->db->query(
                "UPDATE bahan_baku SET stok = stok + ?, harga_beli = ?, updated_at = ? WHERE id = ?",
                [$jumlah, $harga, $now, $idBahan]
            );
        } else {
            $this->db->query(
                "UPDATE bahan_baku SET stok = stok + ?, updated_at = ? WHERE id = ?",
                [$jumlah, $now, $idBahan]
            );
        }

        // 2. Insert ke pembelian_bahan hanya jika diminta
        if ($catatLog) {
            try {
                $bahan       = $this->find($idBahan);
                $hargaSatuan = $harga > 0 ? $harga : (float)($bahan['harga_beli'] ?? 0);

                $this->db->query(
                    "INSERT INTO pembelian_bahan
                        (id_bahan, tanggal, jumlah, harga_satuan, total_harga, supplier, catatan, created_at)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?)",
                    [
                        $idBahan,
                        $tanggal,
                        $jumlah,
                        $hargaSatuan,
                        round($hargaSatuan * $jumlah, 2),
                        $supplier,
                        $keterangan,
                        $now,
                    ]
                );
            } catch (\Throwable $e) {
                log_message('warning', '[BahanBakuModel] pembelian_bahan insert gagal: ' . $e->getMessage());
            }
        }
    }

    // ────────────────────────────────────────────────────────────
    // Alias untuk BahanBakuController::beli()
    // Controller sudah INSERT ke pembelian_bahan sendiri, jadi
    // model cukup update stok saja ($catatLog = false).
    // ────────────────────────────────────────────────────────────
    public function addStokWithHarga(int $idBahan, float $jumlah, float $harga): void
    {
        $this->tambahStok($idBahan, $jumlah, '', $harga, '', '', false);
    }

    // ────────────────────────────────────────────────────────────
    // KURANGI STOK
    //
    // Parameter $catatLog:
    //   FALSE (default) → hanya UPDATE stok, TIDAK insert ke
    //                     penggunaan_bahan. Dipakai oleh controller
    //                     BahanBaku::pakai() yang sudah INSERT sendiri.
    //   TRUE            → UPDATE stok + INSERT ke penggunaan_bahan.
    //                     Dipakai oleh ProduksiModel::jalankanProduksi()
    //                     agar otomatis muncul di Riwayat Penggunaan.
    //
    // Return false jika stok tidak mencukupi.
    // ────────────────────────────────────────────────────────────
    public function kurangiStok(
        int    $idBahan,
        float  $jumlah,
        string $keterangan = '',
        bool   $catatLog   = false
    ): bool {
        $bahan = $this->find($idBahan);
        if (!$bahan || (float)$bahan['stok'] < $jumlah) {
            return false;
        }

        $tanggal = date('Y-m-d');
        $now     = date('Y-m-d H:i:s');

        // 1. Update stok
        $this->db->query(
            "UPDATE bahan_baku SET stok = stok - ?, updated_at = ? WHERE id = ?",
            [$jumlah, $now, $idBahan]
        );

        // 2. Insert ke penggunaan_bahan hanya jika diminta
        if ($catatLog) {
            try {
                // Keperluan = teks sebelum ':' (mis. "Produksi: Bumbu A" → "Produksi")
                $keperluan = $keterangan;
                if ($keterangan && strpos($keterangan, ':') !== false) {
                    $keperluan = trim(explode(':', $keterangan)[0]);
                }

                $this->db->query(
                    "INSERT INTO penggunaan_bahan
                        (id_bahan, tanggal, jumlah, keperluan, catatan, created_at)
                     VALUES (?, ?, ?, ?, ?, ?)",
                    [$idBahan, $tanggal, $jumlah, $keperluan, $keterangan, $now]
                );
            } catch (\Throwable $e) {
                log_message('warning', '[BahanBakuModel] penggunaan_bahan insert gagal: ' . $e->getMessage());
            }
        }

        return true;
    }
}