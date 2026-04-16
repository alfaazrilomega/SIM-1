<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// =============================================
// Import Excel — TikTok OrderSKUList
// =============================================
$routes->get('/import', 'Import::index');
$routes->post('/import/process', 'Import::process');

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