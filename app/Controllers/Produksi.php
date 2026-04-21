<?php

namespace App\Controllers;

use App\Models\ProduksiModel;
use App\Models\BahanBakuModel;
use App\Models\ProdukBumbuModel;

class Produksi extends BaseController
{
    private ProduksiModel    $model;
    private BahanBakuModel   $bahanModel;
    private ProdukBumbuModel $produkModel;

    public function __construct()
    {
        $this->model       = new ProduksiModel();
        $this->bahanModel  = new BahanBakuModel();
        $this->produkModel = new ProdukBumbuModel();
    }

    // ────────────────────────────────────────────────────────────
    // GET /produksi
    // ────────────────────────────────────────────────────────────
    public function index(): string
    {
        return view('produksi/index');
    }

    // ────────────────────────────────────────────────────────────
    // GET /produksi/data  — AJAX
    // Mengembalikan: list produksi + detail bahan, summary,
    //                dropdown bahan baku & produk bumbu.
    // ────────────────────────────────────────────────────────────
    public function data(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();

        $list    = $this->model->getAllWithDetail();
        $summary = $this->model->getSummary();

        // Sertakan detail bahan untuk setiap produksi
        foreach ($list as &$p) {
            $p['bahan'] = $this->model->getBahanByProduksi((int)$p['id']);
        }
        unset($p);

        // Data dropdown bahan baku & produk bumbu
        $semuaBahan  = $this->bahanModel->orderBy('nama', 'ASC')->findAll();
        $semuaProduk = $this->produkModel->orderBy('nama', 'ASC')->findAll();

        return $this->response->setJSON([
            'success' => true,
            'list'    => $list,
            'summary' => $summary,
            'bahan'   => $semuaBahan,
            'produk'  => $semuaProduk,
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // POST /produksi/simpan  — catat & jalankan produksi baru
    //
    // Yang terjadi di dalam ProduksiModel::jalankanProduksi():
    //   - Stok bahan berkurang + tercatat di penggunaan_bahan
    //     (muncul di Riwayat Penggunaan halaman Bahan Baku)
    //   - Stok produk bumbu bertambah + tercatat di stok_bumbu_log
    //     & log_stok_produk
    // ────────────────────────────────────────────────────────────
    public function simpan(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $body = $this->request->getJSON(true) ?? [];

        $namaProduksi = trim($body['nama_produksi']  ?? '');
        $tanggal      = trim($body['tanggal']         ?? date('Y-m-d'));
        $idProduk     = (int)($body['id_produk']      ?? 0);
        $jumlahHasil  = (int)($body['jumlah_hasil']   ?? 0);
        $catatan      = trim($body['catatan']          ?? '');
        $bahanList    = $body['bahan']                 ?? [];

        // Validasi input dasar
        if (!$namaProduksi) {
            return $this->response->setJSON(['success' => false, 'error' => 'Nama produksi wajib diisi.']);
        }
        if (!$idProduk) {
            return $this->response->setJSON(['success' => false, 'error' => 'Produk hasil wajib dipilih.']);
        }
        if ($jumlahHasil < 1) {
            return $this->response->setJSON(['success' => false, 'error' => 'Jumlah hasil harus lebih dari 0.']);
        }
        if (empty($bahanList)) {
            return $this->response->setJSON(['success' => false, 'error' => 'Tambahkan minimal 1 bahan baku.']);
        }

        // Validasi setiap bahan
        foreach ($bahanList as $i => $b) {
            if (empty($b['id_bahan'])) {
                return $this->response->setJSON([
                    'success' => false,
                    'error'   => "Bahan ke-" . ($i + 1) . " belum dipilih.",
                ]);
            }
            if (empty($b['jumlah']) || (float)$b['jumlah'] <= 0) {
                return $this->response->setJSON([
                    'success' => false,
                    'error'   => "Jumlah bahan ke-" . ($i + 1) . " harus lebih dari 0.",
                ]);
            }
        }

        // Serahkan semua logika ke model
        $result = $this->model->jalankanProduksi([
            'nama_produksi' => $namaProduksi,
            'tanggal'       => $tanggal,
            'id_produk'     => $idProduk,
            'jumlah_hasil'  => $jumlahHasil,
            'catatan'       => $catatan,
        ], $bahanList);

        if (!$result['success']) {
            return $this->response->setJSON(['success' => false, 'error' => $result['error']]);
        }

        return $this->response->setJSON([
            'success' => true,
            'id'      => $result['id'],
            'message' => "Produksi \"{$namaProduksi}\" berhasil dicatat. Stok produk +{$jumlahHasil} kemasan.",
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // POST /produksi/batalkan  — batalkan produksi yang sudah selesai
    //
    // Yang terjadi di dalam ProduksiModel::batalkanProduksi():
    //   - Stok bahan dikembalikan (tidak dicatat sebagai pembelian)
    //   - Stok produk bumbu dikurangi + tercatat di stok_bumbu_log
    //   - Status produksi → 'dibatalkan'
    // ────────────────────────────────────────────────────────────
    public function batalkan(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $id = (int)($this->request->getJSON(true)['id'] ?? 0);

        if (!$id) {
            return $this->response->setJSON(['success' => false, 'error' => 'ID tidak valid.']);
        }

        $result = $this->model->batalkanProduksi($id);

        if (!$result['success']) {
            return $this->response->setJSON(['success' => false, 'error' => $result['error']]);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Produksi dibatalkan dan stok dikembalikan.',
        ]);
    }

    // ────────────────────────────────────────────────────────────
    // GET /produksi/detail/{id}  — detail satu produksi (opsional)
    // ────────────────────────────────────────────────────────────
    public function detail(int $id): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();

        $detail = $this->model->getDetailById($id);
        if (!$detail) {
            return $this->response->setStatusCode(404)->setJSON([
                'success' => false,
                'error'   => 'Produksi tidak ditemukan.',
            ]);
        }

        return $this->response->setJSON(['success' => true, 'data' => $detail]);
    }

    // ────────────────────────────────────────────────────────────
    // Helper: 403 Forbidden untuk non-AJAX
    // ────────────────────────────────────────────────────────────
    private function forbidden(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response->setStatusCode(403)->setJSON([
            'success' => false,
            'error'   => 'Forbidden',
        ]);
    }
}