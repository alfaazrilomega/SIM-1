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
