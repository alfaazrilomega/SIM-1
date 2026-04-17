<?php

namespace App\Controllers;

use App\Models\MitraModel;
use App\Models\ProductMappingModel;
use App\Models\TransaksiPesananModel;
use App\Models\DetailPesananModel;

class Pemasukan extends BaseController
{
    protected $mitraModel;
    protected $productModel;
    protected $transaksiModel;
    protected $detailModel;

    public function __construct()
    {
        $this->mitraModel = new MitraModel();
        $this->productModel = new ProductMappingModel();
        $this->transaksiModel = new TransaksiPesananModel();
        $this->detailModel = new DetailPesananModel();
    }

    public function index()
    {
        $data = [
            // Ambil semua mapping produk untuk list dropdown di mesin POS
            'products' => $this->productModel->findAll(),
            
            // Ambil data Reseller & Maklon
            'reseller' => $this->mitraModel->where('tipe_mitra', 'Reseller')->findAll(),
            'maklon'   => $this->mitraModel->where('tipe_mitra', 'Maklon')->findAll()
        ];

        return view('pemasukan/index', $data);
    }

    public function storeMitra()
    {
        $nama  = $this->request->getPost('nama_mitra');
        $tipe  = $this->request->getPost('tipe_mitra');
        $no_hp = $this->request->getPost('no_hp');
        $alamat= $this->request->getPost('alamat');

        if (!$nama || !in_array($tipe, ['Reseller', 'Maklon'])) {
            return redirect()->back()->with('error', 'Format mitra tidak valid.');
        }

        $this->mitraModel->insert([
            'nama_mitra' => $nama,
            'tipe_mitra' => $tipe,
            'no_hp'      => $no_hp,
            'alamat'     => $alamat
        ]);

        return redirect()->back()->with('success', "Data $tipe berhasil ditambahkan!");
    }

    public function storeManual()
    {
        $platform = $this->request->getPost('platform'); // 'Kasir', 'Reseller', 'Maklon'
        $idMitra  = $this->request->getPost('id_mitra');

        $produk   = $this->request->getPost('produk'); // array of mapping IDs
        $qty      = $this->request->getPost('qty'); // array of qtys
        $price    = $this->request->getPost('price'); // array of per-item prices input by user

        // Validasi input produk
        if (empty($produk) || empty($qty) || empty($price)) {
            return redirect()->back()->with('error', 'Tidak ada data produk yang diinput pada keranjang POS.');
        }

        // 1. Generate Order ID
        $prefix = 'KSR';
        if ($platform === 'Reseller') $prefix = 'RES';
        if ($platform === 'Maklon') $prefix = 'MAK';
        $timeStr = date('ymd-His');
        $orderId = $prefix . '-' . $timeStr . '-' . rand(10,99);

        $totalAmt = 0;
        $totalQty = 0;
        $items = [];

        // 2. Loop detail purchases
        for ($i = 0; $i < count($produk); $i++) {
            $mapId = $produk[$i];
            $q = (int) $qty[$i];
            $p = (float) $price[$i]; // price per unit as inputted by user

            if ($q <= 0) continue;

            $prodMap = $this->productModel->find($mapId);
            if (!$prodMap) continue;

            $subtotal = $q * $p;
            $totalAmt += $subtotal;
            $totalQty += $q;

            // Prepare struct for detail_pesanan
            $items[] = [
                'order_id'            => $orderId,
                'nama_produk_raw'     => $prodMap['nama_produk_raw'],
                'variasi_raw'         => $prodMap['variasi_raw'],
                'kombinasi_produk'    => $prodMap['kombinasi_label'], // Super important for analytics
                'quantity'            => $q,
                'sku_unit_original_price' => $p,
                'sku_subtotal_before_discount' => $subtotal,
                'sku_settlement_amt'  => $subtotal
            ];
        }

        if ($totalQty == 0) {
            return redirect()->back()->with('error', 'Semua Kuantiti bernilai 0. Gagal menyimpan transaksi.');
        }

        // 3. Save to transaksi_pesanan
        $transData = [
            'order_id'         => $orderId,
            'platform'         => $platform,
            'status_pesanan'   => 'Selesai',
            'status_penarikan' => 'Belum Ditarik',
            'total_amount'     => $totalAmt,
            'total_quantity'   => $totalQty,
            'create_time'      => date('Y-m-d H:i:s'),
            'paid_time'        => date('Y-m-d H:i:s')
        ];

        // Jika Reseller/Maklon, tambahkan identifier Mitra
        if (in_array($platform, ['Reseller', 'Maklon'])) {
            if (empty($idMitra)) {
                return redirect()->back()->with('error', "Pemilihan identitas $platform wajib diisi.");
            }
            $transData['id_mitra'] = $idMitra;
            
            // Set buyer details (nice to have)
            $mitra = $this->mitraModel->find($idMitra);
            if ($mitra) {
                $transData['buyer_username'] = $mitra['nama_mitra'];
                $transData['recipient']      = $mitra['nama_mitra'];
            }
        } else {
            $transData['buyer_username'] = 'Guest Cashier';
        }

        // Use standard DB logic -> bypass Validation model temporarly due to mass insert.
        $db = \Config\Database::connect();
        $builder = $db->table('transaksi_pesanan');
        $builder->insert($transData);

        // 4. Save to detail_pesanan
        $this->detailModel->bulkInsert($orderId, $items);

        return redirect()->back()->with('success', "Transaksi $platform ($orderId) berhasil dibukukan dengan total Rp " . number_format($totalAmt, 0, ',', '.'));
    }
}
