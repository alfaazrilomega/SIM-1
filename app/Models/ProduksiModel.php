<?php

namespace App\Models;

use CodeIgniter\Model;

class ProduksiModel extends Model
{
    protected $table         = 'produksi';
    protected $primaryKey    = 'id';
    protected $allowedFields = [
        'nama_produksi', 'tanggal', 'id_produk',
        'jumlah_hasil', 'catatan', 'status', 'dibuat_oleh',
    ];
    protected $useTimestamps = true;

    // ────────────────────────────────────────────────────────────
    // Semua produksi + nama & kode produk bumbu
    // ────────────────────────────────────────────────────────────
    public function getAllWithDetail(): array
    {
        return $this->db->query("
            SELECT pr.*,
                   pb.nama AS nama_produk,
                   pb.kode AS kode_produk
            FROM produksi pr
            LEFT JOIN produk_bumbu pb ON pb.id = pr.id_produk
            ORDER BY pr.tanggal DESC, pr.id DESC
        ")->getResultArray();
    }

    // ────────────────────────────────────────────────────────────
    // Detail bahan yang dipakai per satu produksi
    // ────────────────────────────────────────────────────────────
    public function getBahanByProduksi(int $idProduksi): array
    {
        return $this->db->query("
            SELECT pb.*, bb.nama AS nama_bahan, bb.satuan
            FROM produksi_bahan pb
            JOIN bahan_baku bb ON bb.id = pb.id_bahan
            WHERE pb.id_produksi = ?
        ", [$idProduksi])->getResultArray();
    }

    // ────────────────────────────────────────────────────────────
    // Satu produksi lengkap + daftar bahan (untuk endpoint detail)
    // ────────────────────────────────────────────────────────────
    public function getDetailById(int $id): ?array
    {
        $produksi = $this->db->query("
            SELECT pr.*, pb.nama AS nama_produk, pb.kode AS kode_produk
            FROM produksi pr
            LEFT JOIN produk_bumbu pb ON pb.id = pr.id_produk
            WHERE pr.id = ?
        ", [$id])->getRowArray();

        if (!$produksi) return null;

        $produksi['bahan'] = $this->getBahanByProduksi($id);
        return $produksi;
    }

    // ────────────────────────────────────────────────────────────
    // Ringkasan untuk stat cards di halaman Produksi
    // ────────────────────────────────────────────────────────────
    public function getSummary(): array
    {
        $bulanIni = date('Y-m');

        $row = $this->db->query("
            SELECT
                COUNT(*)                                                       AS total,
                SUM(status = 'selesai')                                        AS selesai,
                SUM(status = 'dibatalkan')                                     AS dibatalkan,
                SUM(status = 'selesai' AND DATE_FORMAT(tanggal,'%Y-%m') = ?)   AS bulan_ini
            FROM produksi
        ", [$bulanIni])->getRowArray();

        return [
            'total'      => (int)($row['total']      ?? 0),
            'selesai'    => (int)($row['selesai']     ?? 0),
            'dibatalkan' => (int)($row['dibatalkan']  ?? 0),
            'bulan_ini'  => (int)($row['bulan_ini']   ?? 0),
        ];
    }

    // ────────────────────────────────────────────────────────────
    // JALANKAN PRODUKSI
    //
    // Alur:
    //   1. Validasi stok semua bahan sebelum transaksi dimulai
    //   2. INSERT header ke tabel produksi (status = 'selesai')
    //   3. Untuk setiap bahan:
    //      a. Kurangi stok bahan baku  (BahanBakuModel::kurangiStok, catatLog=TRUE)
    //         → otomatis INSERT ke penggunaan_bahan (muncul di Riwayat Penggunaan)
    //      b. INSERT detail ke produksi_bahan
    //   4. Tambah stok produk bumbu    (ProdukBumbuModel::tambahStok)
    //      → otomatis INSERT ke stok_bumbu_log + log_stok_produk
    //
    // Return: ['success'=>true, 'id'=>$id]
    //      atau ['success'=>false, 'error'=>'...']
    // ────────────────────────────────────────────────────────────
    public function jalankanProduksi(array $header, array $bahanList): array
    {
        $bahanModel  = new BahanBakuModel();
        $produkModel = new ProdukBumbuModel();
        $db          = $this->db;

        // ── Pra-validasi stok sebelum mulai transaksi ──
        foreach ($bahanList as $i => $b) {
            $idBahan = (int)($b['id_bahan'] ?? 0);
            $jumlah  = (float)($b['jumlah'] ?? 0);

            if (!$idBahan || $jumlah <= 0) {
                return ['success' => false, 'error' => "Data bahan ke-" . ($i + 1) . " tidak valid."];
            }

            $bahan = $bahanModel->find($idBahan);
            if (!$bahan) {
                return ['success' => false, 'error' => "Bahan ID {$idBahan} tidak ditemukan."];
            }
            if ((float)$bahan['stok'] < $jumlah) {
                return [
                    'success' => false,
                    'error'   => "Stok \"{$bahan['nama']}\" tidak cukup. "
                               . "Tersedia: {$bahan['stok']} {$bahan['satuan']}.",
                ];
            }
        }

        $db->transStart();

        try {
            // ── 1. Simpan header produksi ──
            $idProduksi = $this->insert([
                'nama_produksi' => $header['nama_produksi'],
                'tanggal'       => $header['tanggal']      ?? date('Y-m-d'),
                'id_produk'     => (int)$header['id_produk'],
                'jumlah_hasil'  => (int)$header['jumlah_hasil'],
                'catatan'       => $header['catatan']      ?? '',
                'status'        => 'selesai',
                'dibuat_oleh'   => $header['dibuat_oleh']  ?? null,
            ], true); // true = return insert ID

            // ── 2. Proses setiap bahan ──
            foreach ($bahanList as $b) {
                $idBahan = (int)$b['id_bahan'];
                $jumlah  = (float)$b['jumlah'];
                $nama    = $header['nama_produksi'];

                // Kurangi stok bahan + catat ke penggunaan_bahan (catatLog = TRUE)
                $bahanModel->kurangiStok(
                    $idBahan,
                    $jumlah,
                    "Produksi: {$nama}",
                    true   // ← INSERT ke penggunaan_bahan → muncul di Riwayat Penggunaan
                );

                // Simpan detail bahan ke produksi_bahan
                $db->table('produksi_bahan')->insert([
                    'id_produksi' => $idProduksi,
                    'id_bahan'    => $idBahan,
                    'jumlah'      => $jumlah,
                ]);
            }

            // ── 3. Tambah stok produk bumbu ──
            // Wajib pakai tambahStok() — bukan raw UPDATE — agar otomatis
            // tercatat di stok_bumbu_log DAN log_stok_produk.
            $produkModel->tambahStok(
                (int)$header['id_produk'],
                (int)$header['jumlah_hasil'],
                "Hasil produksi: {$header['nama_produksi']}"
            );

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed.');
            }

            return ['success' => true, 'id' => $idProduksi];

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', '[ProduksiModel::jalankanProduksi] ' . $e->getMessage());
            return ['success' => false, 'error' => 'Gagal menyimpan produksi: ' . $e->getMessage()];
        }
    }

    // ────────────────────────────────────────────────────────────
    // BATALKAN PRODUKSI
    //
    // Alur (kebalikan jalankanProduksi):
    //   1. Kembalikan stok semua bahan (BahanBakuModel::tambahStok, catatLog=FALSE)
    //      Batal-produksi BUKAN pembelian → tidak masuk Riwayat Pembelian.
    //   2. Kurangi stok produk bumbu   (ProdukBumbuModel::kurangiStok)
    //      → otomatis INSERT ke stok_bumbu_log + log_stok_produk
    //   3. Update status produksi → 'dibatalkan'
    //
    // Return: ['success'=>true]
    //      atau ['success'=>false, 'error'=>'...']
    // ────────────────────────────────────────────────────────────
    public function batalkanProduksi(int $id): array
    {
        $produksi = $this->find($id);
        if (!$produksi) {
            return ['success' => false, 'error' => 'Produksi tidak ditemukan.'];
        }
        if ($produksi['status'] !== 'selesai') {
            return ['success' => false, 'error' => 'Produksi ini sudah dibatalkan sebelumnya.'];
        }

        $bahanModel  = new BahanBakuModel();
        $produkModel = new ProdukBumbuModel();
        $db          = $this->db;

        $db->transStart();

        try {
            $nama = $produksi['nama_produksi'];

            // ── 1. Kembalikan stok semua bahan ──
            $detail = $db->table('produksi_bahan')
                ->where('id_produksi', $id)
                ->get()->getResultArray();

            foreach ($detail as $item) {
                // catatLog = FALSE → bukan pembelian, jangan masuk Riwayat Pembelian
                $bahanModel->tambahStok(
                    (int)$item['id_bahan'],
                    (float)$item['jumlah'],
                    "Batal produksi: {$nama}",
                    0, '', '', false
                );
            }

            // ── 2. Kurangi stok produk bumbu ──
            // Pakai kurangiStok() agar log 'keluar' tercatat di kedua tabel log
            $ok = $produkModel->kurangiStok(
                (int)$produksi['id_produk'],
                (int)$produksi['jumlah_hasil'],
                "Batal produksi: {$nama}"
            );

            if (!$ok) {
                $db->transRollback();
                return ['success' => false, 'error' => 'Stok produk tidak mencukupi untuk dibatalkan.'];
            }

            // ── 3. Update status ──
            $this->update($id, ['status' => 'dibatalkan']);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \RuntimeException('Database transaction failed.');
            }

            return ['success' => true];

        } catch (\Throwable $e) {
            $db->transRollback();
            log_message('error', '[ProduksiModel::batalkanProduksi] ' . $e->getMessage());
            return ['success' => false, 'error' => 'Gagal membatalkan produksi: ' . $e->getMessage()];
        }
    }
}