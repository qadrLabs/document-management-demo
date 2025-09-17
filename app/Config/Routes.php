<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'DocumentController::index');

// Document routes
$routes->group('documents', function ($routes) {
    $routes->get('/', 'DocumentController::index');
    $routes->get('upload', 'DocumentController::upload');
    $routes->post('store', 'DocumentController::store');
    $routes->get('view/(:num)', 'DocumentController::view/$1');
    $routes->get('download/(:num)', 'DocumentController::download/$1');
    $routes->get('delete/(:num)', 'DocumentController::delete/$1');
});
