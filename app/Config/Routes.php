<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// =============================================
// Import Excel — TikTok OrderSKUList (Marketplace)
// =============================================
$routes->get('/import', 'Import::index');
$routes->post('/import/process', 'Import::process');

// =============================================
// Omnichannel Pemasukan (POS, Reseller, Maklon)
// =============================================
$routes->group('pemasukan', function ($routes) {
    $routes->get('/', 'Pemasukan::index');
    $routes->post('store-manual', 'Pemasukan::storeManual');
    $routes->post('store-mitra', 'Pemasukan::storeMitra');
});

// =============================================
// Withdrawal Dashboard (CEO)
// =============================================
$routes->get('/withdrawal', 'Withdrawal::index');
$routes->get('/withdrawal/data', 'Withdrawal::data');
$routes->post('/withdrawal/tarik', 'Withdrawal::tarik');
$routes->post('/withdrawal/reset', 'Withdrawal::reset');
$routes->get('/temp-reset-semua', 'Withdrawal::tempResetSemua');

// =============================================
// Dashboard Analitik
// =============================================
$routes->get('/analytics', 'Analytics::index');
$routes->get('/analytics/data', 'Analytics::data');

// =============================================
// Rekap Produk
// =============================================
$routes->get('/rekap-produk', 'RekapProduk::index');
$routes->get('/rekap-produk/data', 'RekapProduk::data');


// Manajemen Bahan Baku
$routes->get('/bahan-baku',              'BahanBaku::index');
$routes->get('/bahan-baku/data',         'BahanBaku::data');
$routes->post('/bahan-baku/simpan',      'BahanBaku::simpan');
$routes->post('/bahan-baku/beli',        'BahanBaku::beli');
$routes->post('/bahan-baku/pakai',       'BahanBaku::pakai');
$routes->post('/bahan-baku/hapus',       'BahanBaku::hapus');

// Manajemen Produk Bumbu
$routes->get('/produk-bumbu',               'ProdukBumbu::index');
$routes->get('/produk-bumbu/data',          'ProdukBumbu::data');
$routes->post('/produk-bumbu/simpan',       'ProdukBumbu::simpan');
$routes->post('/produk-bumbu/tambah-stok',  'ProdukBumbu::tambahStok');
$routes->post('/produk-bumbu/kurangi-stok', 'ProdukBumbu::kurangiStok');
$routes->post('/produk-bumbu/hapus',        'ProdukBumbu::hapus');

$routes->get('/produksi',              'Produksi::index');
$routes->get('/produksi/data',         'Produksi::data');
$routes->post('/produksi/simpan',      'Produksi::simpan');
$routes->post('/produksi/batalkan',    'Produksi::batalkan');
$routes->get('/produksi/detail/(:num)','Produksi::detail/$1');

// Finance & HRD Modules (Kas, Absensi, Gaji)
// =============================================
$routes->group('finance', function ($routes) {
    $routes->get('pengeluaran', 'Finance::index');
    $routes->post('store', 'Finance::store');
});

$routes->group('hrd', function ($routes) {
    // Karyawan
    $routes->get('karyawan', 'HRD::karyawan');
    $routes->post('store-karyawan', 'HRD::storeKaryawan');
    $routes->post('delete-karyawan/(:num)', 'HRD::deleteKaryawan/$1');
    
    // Absensi
    $routes->get('absensi', 'HRD::absensi');
    $routes->post('store-absensi', 'HRD::storeAbsensi');
    $routes->post('delete-absensi/(:num)', 'HRD::deleteAbsensi/$1');
    
    // Penggajian
    $routes->get('penggajian', 'HRD::penggajian');
    $routes->post('generate-gaji', 'HRD::generateGaji');
    $routes->post('bayar-gaji/(:num)', 'HRD::bayarGaji/$1');
});
