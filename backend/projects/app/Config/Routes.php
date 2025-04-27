<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// API routes
$routes->group('api', function($routes) {
    $routes->group('auth', function($routes) {
        $routes->post('register', 'Api\AuthController::register');
        $routes->post('login', 'Api\AuthController::login');
    });

    $routes->group('projects', ['filter' => 'jwt'], function($routes) {
        $routes->get('/', 'Api\ProjectController::index');
        $routes->post('/', 'Api\ProjectController::create');
        $routes->put('(:num)', 'Api\ProjectController::update/$1');
        $routes->delete('(:num)', 'Api\ProjectController::delete/$1');
    });
});
