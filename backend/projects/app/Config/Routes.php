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

    $routes->group('projects', ['filter' => 'jwtauth'], function($routes) {
        $routes->post('/', 'Api\ProjectController::index');
        $routes->post('/create', 'Api\ProjectController::create');
        $routes->put('(:num)', 'Api\ProjectController::update/$1');
        $routes->delete('(:num)', 'Api\ProjectController::delete/$1');

        $routes->get('(:num)/members', 'Api\ProjectMemberController::index/$1');
        $routes->post('(:num)/members', 'Api\ProjectMemberController::add/$1');
        $routes->delete('(:num)/members/(:num)', 'Api\ProjectMemberController::remove/$1/$2');
    });
});
