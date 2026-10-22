<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes
$routes->get('/', 'Home::index');
$routes->get('kos/(:segment)', 'Home::detail/$1');
$routes->get('tentang', 'Home::about');
$routes->get('kontak', 'Home::contact');
$routes->get('favorit', 'Home::favorites');

// REST API Routes
$routes->group('api', static function ($routes) {
    $routes->get('kos', 'Api::getKos');
    $routes->get('kos/(:segment)', 'Api::getKosDetail/$1');
    $routes->get('wilayah', 'Api::getWilayah');
    $routes->get('wilayah/(:num)/geojson', 'Api::getWilayahGeojson/$1');
    $routes->get('fasilitas', 'Api::getFasilitas');
    $routes->post('kos/(:num)/track', 'Api::trackKos/$1');
});

// Admin Auth
$routes->match(['GET', 'POST'], 'admin/login', 'Admin\Auth::login');
$routes->get('admin/logout', 'Admin\Auth::logout');

// Admin Panel (Protected by Auth Filter)
$routes->group('admin', ['filter' => 'auth'], static function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('dashboard', 'Admin\Dashboard::index');

    // Kos CRUD
    $routes->get('kos', 'Admin\Kos::index');
    $routes->get('kos/create', 'Admin\Kos::create');
    $routes->post('kos/create', 'Admin\Kos::store');
    $routes->get('kos/edit/(:num)', 'Admin\Kos::edit/$1');
    $routes->post('kos/edit/(:num)', 'Admin\Kos::update/$1');
    $routes->post('kos/delete/(:num)', 'Admin\Kos::delete/$1');
    $routes->post('kos/toggle-status/(:num)', 'Admin\Kos::toggleStatus/$1');

    // Kos Photos
    $routes->get('kos/(:num)/photos', 'Admin\Kos::photos/$1');
    $routes->post('kos/(:num)/photos/upload', 'Admin\Kos::uploadPhotos/$1');
    $routes->post('kos/(:num)/photos/(:num)/set-main', 'Admin\Kos::setMainPhoto/$1/$2');
    $routes->post('kos/(:num)/photos/(:num)/delete', 'Admin\Kos::deletePhoto/$1/$2');

    // Wilayah CRUD
    $routes->get('wilayah', 'Admin\Wilayah::index');
    $routes->get('wilayah/create', 'Admin\Wilayah::create');
    $routes->post('wilayah/create', 'Admin\Wilayah::store');
    $routes->get('wilayah/edit/(:num)', 'Admin\Wilayah::edit/$1');
    $routes->post('wilayah/edit/(:num)', 'Admin\Wilayah::update/$1');
    $routes->post('wilayah/delete/(:num)', 'Admin\Wilayah::delete/$1');

    // Fasilitas CRUD
    $routes->get('fasilitas', 'Admin\Fasilitas::index');
    $routes->post('fasilitas/create', 'Admin\Fasilitas::store');
    $routes->post('fasilitas/edit/(:num)', 'Admin\Fasilitas::update/$1');
    $routes->post('fasilitas/delete/(:num)', 'Admin\Fasilitas::delete/$1');

    // Statistik
    $routes->get('statistik', 'Admin\Statistik::index');
});
