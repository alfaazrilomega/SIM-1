<?php

namespace App\Controllers;

use App\Models\ProdukBumbuModel;

class ProdukBumbu extends BaseController
{
    private ProdukBumbuModel $model;

    public function __construct()
    {
        $this->model = new ProdukBumbuModel();
    }

    // GET /produk-bumbu
    public function index(): string
    {
        return view('produk_bumbu/index');
    }

    // GET /produk-bumbu/data
    public function data(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();

        $list = $this->model->getAllWithLog();
        $log  = $this->model->getLog(0, 50);

        $totalStok  = array_sum(array_column($list, 'stok'));
        $totalNilai = array_sum(array_map(fn($r) => $r['stok'] * $r['harga_jual'], $list));
        $stokNipis  = count(array_filter($list, fn($r) => $r['stok'] <= 5));

        return $this->response->setJSON([
            'success' => true,
            'list'    => $list,
            'log'     => $log,
            'summary' => [
                'total_produk' => count($list),
                'total_stok'   => $totalStok,
                'total_nilai'  => $totalNilai,
                'stok_nipis'   => $stokNipis,
            ],
        ]);
    }

    // POST /produk-bumbu/simpan
    public function simpan(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $body = $this->request->getJSON(true) ?? [];

        $id   = (int)($body['id'] ?? 0);
        $data = [
            'kode'       => trim($body['kode']       ?? ''),
            'nama'       => trim($body['nama']       ?? ''),
            'berat_gram' => (int)($body['berat_gram']  ?? 250),
            'harga_jual' => (float)($body['harga_jual'] ?? 0),
            'keterangan' => trim($body['keterangan'] ?? ''),
        ];

        if (!$data['kode'] || !$data['nama']) {
            return $this->response->setJSON(['success' => false, 'error' => 'Kode dan nama wajib diisi.']);
        }

        if ($id > 0) {
            $this->model->update($id, $data);
        } else {
            $data['stok'] = 0;
            $this->model->insert($data);
            $id = $this->model->getInsertID();
        }

        return $this->response->setJSON(['success' => true, 'id' => $id, 'message' => 'Produk bumbu berhasil disimpan.']);
    }

    // POST /produk-bumbu/tambah-stok
    public function tambahStok(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $body = $this->request->getJSON(true) ?? [];

        $idProduk   = (int)($body['id_produk']   ?? 0);
        $jumlah     = (int)($body['jumlah']       ?? 0);
        $keterangan = trim($body['keterangan']   ?? 'Produksi');

        if (!$idProduk || $jumlah <= 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Data tidak valid.']);
        }

        $this->model->tambahStok($idProduk, $jumlah, $keterangan);
        return $this->response->setJSON(['success' => true, 'message' => "Stok bertambah {$jumlah} kemasan."]);
    }

    // POST /produk-bumbu/kurangi-stok
    public function kurangiStok(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $body = $this->request->getJSON(true) ?? [];

        $idProduk   = (int)($body['id_produk']   ?? 0);
        $jumlah     = (int)($body['jumlah']       ?? 0);
        $keterangan = trim($body['keterangan']   ?? 'Terjual');

        if (!$idProduk || $jumlah <= 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Data tidak valid.']);
        }

        $ok = $this->model->kurangiStok($idProduk, $jumlah, $keterangan);
        if (!$ok) {
            return $this->response->setJSON(['success' => false, 'error' => 'Stok tidak mencukupi.']);
        }

        return $this->response->setJSON(['success' => true, 'message' => "Stok berkurang {$jumlah} kemasan."]);
    }

    // DELETE /produk-bumbu/hapus
    public function hapus(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $id = (int)($this->request->getJSON(true)['id'] ?? 0);
        if (!$id) return $this->response->setJSON(['success' => false, 'error' => 'ID tidak valid.']);

        try {
            $this->model->delete($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Produk bumbu dihapus.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => 'Tidak bisa dihapus, ada log stok terkait.']);
        }
    }

    private function forbidden(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
    }
}