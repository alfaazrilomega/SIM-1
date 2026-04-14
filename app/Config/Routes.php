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