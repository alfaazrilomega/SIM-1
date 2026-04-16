<?php

namespace App\Controllers;

use App\Models\TransaksiKasModel;

class Withdrawal extends BaseController
{
    // -------------------------------------------------------
    // GET /withdrawal  — Halaman Dashboard Pencairan (CEO)
    // -------------------------------------------------------
    public function index(): string
    {
        return view('withdrawal/index');
    }

    // -------------------------------------------------------
    // GET /withdrawal/data  — AJAX: ambil data ringkasan + daftar pending
    // -------------------------------------------------------
    public function data(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        $db = \Config\Database::connect();

        // Ringkasan total
        $summary = $db->query("
            SELECT
                COUNT(*) AS total_selesai,
                SUM(total_amount) AS total_pendapatan,
                SUM(CASE WHEN status_penarikan = 'Belum Ditarik' THEN total_amount ELSE 0 END) AS total_belum_ditarik,
                SUM(CASE WHEN status_penarikan = 'Sudah Ditarik' THEN total_amount ELSE 0 END) AS total_sudah_ditarik,
                COUNT(CASE WHEN status_penarikan = 'Belum Ditarik' THEN 1 END) AS jumlah_pending,
                COUNT(CASE WHEN status_penarikan = 'Sudah Ditarik' THEN 1 END) AS jumlah_ditarik
            FROM transaksi_pesanan
            WHERE status_pesanan = 'Selesai'
        ")->getRowArray();

        // Daftar pesanan belum ditarik (pending)
        $pending = $db->query("
            SELECT order_id, platform, total_amount, create_time, paid_time, tanggal_update
            FROM transaksi_pesanan
            WHERE status_pesanan = 'Selesai'
              AND status_penarikan = 'Belum Ditarik'
            ORDER BY paid_time ASC
            LIMIT 200
        ")->getResultArray();

        // Riwayat pencairan terbaru (10 terakhir)
        $history = $db->query("
            SELECT order_id, platform, total_amount, paid_time, tanggal_update
            FROM transaksi_pesanan
            WHERE status_pesanan = 'Selesai'
              AND status_penarikan = 'Sudah Ditarik'
            ORDER BY tanggal_update DESC
            LIMIT 10
        ")->getResultArray();

        return $this->response->setJSON([
            'success' => true,
            'summary' => $summary,
            'pending' => $pending,
            'history' => $history,
        ]);
    }

    // -------------------------------------------------------
    // POST /withdrawal/tarik  — AJAX: Tandai 1 atau banyak order sebagai Sudah Ditarik
    // Body JSON: { "order_ids": ["xxx", "yyy"] }  atau { "tarik_semua": true }
    // -------------------------------------------------------
    public function tarik(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        $body     = $this->request->getJSON(true) ?? [];
        $db       = \Config\Database::connect();
        $kasModel = new TransaksiKasModel();

        // Mulai Transaksi Database agar sinkronisasi aman
        $db->transStart();

        $whereClause = "";
        $params      = [];

        if (!empty($body['tarik_semua'])) {
            $whereClause = "status_pesanan = 'Selesai' AND status_penarikan = 'Belum Ditarik'";
        } else {
            $orderIds = $body['order_ids'] ?? [];
            if (empty($orderIds) || !is_array($orderIds)) {
                return $this->response->setJSON(['success' => false, 'error' => 'Tidak ada order_id yang dikirim.']);
            }

            $orderIds     = array_values(array_filter(array_map(fn($id) => preg_replace('/[^A-Za-z0-9\-_]/', '', $id), $orderIds)));
            $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
            
            $whereClause = "order_id IN ({$placeholders}) AND status_pesanan = 'Selesai' AND status_penarikan = 'Belum Ditarik'";
            $params      = $orderIds;
        }

        // 1. Ambil data agregat (Total Uang & Jumlah Pesanan) per Platform
        $agregat = $db->query("
            SELECT platform, SUM(total_amount) as nominal_total, COUNT(*) as jumlah_pesanan
            FROM transaksi_pesanan
            WHERE {$whereClause}
            GROUP BY platform
        ", $params)->getResultArray();

        if (empty($agregat)) {
            $db->transRollback();
            return $this->response->setJSON(['success' => false, 'error' => 'Tidak ada data pesanan valid yang bisa dicairkan.']);
        }

        // 2. Tandai Order sebagai 'Sudah Ditarik'
        $db->query("
            UPDATE transaksi_pesanan
            SET status_penarikan = 'Sudah Ditarik'
            WHERE {$whereClause}
        ", $params);
        $totalAffected = $db->affectedRows();

        // 3. Catat ke Tabel Kas (Pemasukan) per Platform
        foreach ($agregat as $row) {
            $platform    = $row['platform'] ?: 'Lainnya';
            $nominal     = (float)$row['nominal_total'];
            $jmlPesanan  = $row['jumlah_pesanan'];

            if ($nominal > 0) {
                $kasModel->save([
                    'tanggal'        => date('Y-m-d'),
                    'tipe_transaksi' => 'Pemasukan',
                    'kategori'       => "Hasil Penjualan " . $platform,
                    'keterangan'     => "Pencairan otomatis dari Dashboard CEO (Total " . $jmlPesanan . " pesanan).",
                    'nominal'        => $nominal
                ]);
            }
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return $this->response->setJSON(['success' => false, 'error' => 'Gagal memproses pencairan dan pencatatan kas.']);
        }

        return $this->response->setJSON([
            'success'  => true,
            'message'  => "Berhasil mencairkan {$totalAffected} pesanan. Data kas otomatis diperbarui.",
            'affected' => $totalAffected,
        ]);
    }

    // -------------------------------------------------------
    // POST /withdrawal/reset  — AJAX: Batalkan penarikan (kembalikan ke Belum Ditarik)
    // Body JSON: { "order_ids": ["xxx"] }
    // -------------------------------------------------------
    public function reset(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
        }

        $body     = $this->request->getJSON(true) ?? [];
        $orderIds = $body['order_ids'] ?? [];

        if (empty($orderIds) || !is_array($orderIds)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Tidak ada order_id yang dikirim.']);
        }

        $orderIds     = array_values(array_filter(array_map(fn($id) => preg_replace('/[^A-Za-z0-9\-_]/', '', $id), $orderIds)));
        $placeholders = implode(',', array_fill(0, count($orderIds), '?'));
        $db           = \Config\Database::connect();

        $db->query("
            UPDATE transaksi_pesanan
            SET status_penarikan = 'Belum Ditarik'
            WHERE order_id IN ({$placeholders})
              AND status_penarikan = 'Sudah Ditarik'
        ", $orderIds);

        $affected = $db->affectedRows();

        return $this->response->setJSON([
            'success'  => true,
            'message'  => "Berhasil membatalkan {$affected} pencairan.",
            'affected' => $affected,
        ]);
    }

    // -------------------------------------------------------
    // GET /temp-reset-semua — Helper untuk mereset semuanya (Bantuan Darurat)
    // -------------------------------------------------------
    public function tempResetSemua()
    {
        $db = \Config\Database::connect();
        $db->query("UPDATE transaksi_pesanan SET status_penarikan = 'Belum Ditarik'");
        $affected = $db->affectedRows();
        return "Berhasil di-reset! Sejumlah <b>{$affected}</b> pesanan telah dikembalikan ke status 'Belum Ditarik'. <br><br><a href='" . base_url('/withdrawal') . "'>Kembali ke Dashboard</a>";
    }
}

