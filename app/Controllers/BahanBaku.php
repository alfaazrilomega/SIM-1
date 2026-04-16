<?php

namespace App\Controllers;

use App\Models\BahanBakuModel;

class BahanBaku extends BaseController
{
    private BahanBakuModel $model;

    public function __construct()
    {
        $this->model = new BahanBakuModel();
    }

    // GET /bahan-baku
    public function index(): string
    {
        return view('bahan_baku/index');
    }

    // GET /bahan-baku/data  — AJAX ringkasan + list
    public function data(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();

        $db   = \Config\Database::connect();
        $list = $this->model->getAllWithStats();

        $totalNilai  = array_sum(array_map(fn($r) => $r['stok'] * $r['harga_beli'], $list));
        $stokNipis   = count(array_filter($list, fn($r) => $r['stok'] <= 1));
        $totalBeli   = $db->query("SELECT COALESCE(SUM(total_harga),0) AS v FROM pembelian_bahan")->getRowArray()['v'];

        $pembelian = $db->query("
            SELECT pb.*, b.nama AS nama_bahan, b.satuan
            FROM pembelian_bahan pb
            JOIN bahan_baku b ON b.id = pb.id_bahan
            ORDER BY pb.tanggal DESC, pb.id DESC
            LIMIT 100
        ")->getResultArray();

        $penggunaan = $db->query("
            SELECT ub.*, b.nama AS nama_bahan, b.satuan
            FROM penggunaan_bahan ub
            JOIN bahan_baku b ON b.id = ub.id_bahan
            ORDER BY ub.tanggal DESC, ub.id DESC
            LIMIT 100
        ")->getResultArray();

        return $this->response->setJSON([
            'success'    => true,
            'list'       => $list,
            'pembelian'  => $pembelian,
            'penggunaan' => $penggunaan,
            'summary'    => [
                'total_jenis'  => count($list),
                'total_nilai'  => $totalNilai,
                'stok_nipis'   => $stokNipis,
                'total_beli'   => $totalBeli,
            ],
        ]);
    }

    // POST /bahan-baku/simpan  — tambah/edit master bahan
    public function simpan(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $body = $this->request->getJSON(true) ?? [];

        $id    = (int)($body['id'] ?? 0);
        $data  = [
            'kode'       => trim($body['kode']       ?? ''),
            'nama'       => trim($body['nama']       ?? ''),
            'satuan'     => trim($body['satuan']     ?? 'kg'),
            'harga_beli' => (float)($body['harga_beli'] ?? 0),
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

        return $this->response->setJSON(['success' => true, 'id' => $id, 'message' => 'Bahan baku berhasil disimpan.']);
    }

    // POST /bahan-baku/beli  — catat pembelian & tambah stok
    public function beli(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $body = $this->request->getJSON(true) ?? [];

        $idBahan   = (int)($body['id_bahan']     ?? 0);
        $jumlah    = (float)($body['jumlah']     ?? 0);
        $harga     = (float)($body['harga_satuan'] ?? 0);
        $tanggal   = $body['tanggal']   ?? date('Y-m-d');
        $supplier  = trim($body['supplier'] ?? '');
        $catatan   = trim($body['catatan']  ?? '');

        if (!$idBahan || $jumlah <= 0 || $harga <= 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Data tidak valid.']);
        }

        $db = \Config\Database::connect();
        $db->query("
            INSERT INTO pembelian_bahan (id_bahan, tanggal, jumlah, harga_satuan, total_harga, supplier, catatan)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ", [$idBahan, $tanggal, $jumlah, $harga, $jumlah * $harga, $supplier, $catatan]);

        $this->model->addStokWithHarga($idBahan, $jumlah, $harga);

        return $this->response->setJSON(['success' => true, 'message' => "Pembelian berhasil dicatat. Stok bertambah {$jumlah}."]);
    }

    // POST /bahan-baku/pakai  — catat penggunaan & kurangi stok
    public function pakai(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $body = $this->request->getJSON(true) ?? [];

        $idBahan  = (int)($body['id_bahan']  ?? 0);
        $jumlah   = (float)($body['jumlah']  ?? 0);
        $tanggal  = $body['tanggal']  ?? date('Y-m-d');
        $keperluan = trim($body['keperluan'] ?? '');
        $catatan  = trim($body['catatan']   ?? '');

        if (!$idBahan || $jumlah <= 0) {
            return $this->response->setJSON(['success' => false, 'error' => 'Data tidak valid.']);
        }

        $ok = $this->model->kurangiStok($idBahan, $jumlah);
        if (!$ok) {
            return $this->response->setJSON(['success' => false, 'error' => 'Stok tidak mencukupi.']);
        }

        $db = \Config\Database::connect();
        $db->query("
            INSERT INTO penggunaan_bahan (id_bahan, tanggal, jumlah, keperluan, catatan)
            VALUES (?, ?, ?, ?, ?)
        ", [$idBahan, $tanggal, $jumlah, $keperluan, $catatan]);

        return $this->response->setJSON(['success' => true, 'message' => "Penggunaan berhasil dicatat. Stok berkurang {$jumlah}."]);
    }

    // DELETE /bahan-baku/hapus  — hapus master bahan
    public function hapus(): \CodeIgniter\HTTP\ResponseInterface
    {
        if (!$this->request->isAJAX()) return $this->forbidden();
        $id = (int)($this->request->getJSON(true)['id'] ?? 0);
        if (!$id) return $this->response->setJSON(['success' => false, 'error' => 'ID tidak valid.']);

        try {
            $this->model->delete($id);
            return $this->response->setJSON(['success' => true, 'message' => 'Bahan baku dihapus.']);
        } catch (\Exception $e) {
            return $this->response->setJSON(['success' => false, 'error' => 'Tidak bisa dihapus, ada transaksi terkait.']);
        }
    }

    private function forbidden(): \CodeIgniter\HTTP\ResponseInterface
    {
        return $this->response->setStatusCode(403)->setJSON(['success' => false, 'error' => 'Forbidden']);
    }
}