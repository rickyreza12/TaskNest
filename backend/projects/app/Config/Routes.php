<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// CORS Policy Fix
$routes->options('(:any)', function() {
    $response = service('response');
    return $response
        ->setHeader('Access-Control-Allow-Origin', '*')
        ->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization')
        ->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS, PUT, DELETE')
        ->setStatusCode(200);
});

// API routes
$routes->group('api', function($routes) {
    $routes->group('auth', function($routes) {
        $routes->post('register', 'Api\AuthController::register');
        $routes->post('login', 'Api\AuthController::login');
    });

    // Project routes
    $routes->group('projects', ['filter' => 'jwtauth'], function($routes) {
        $routes->post('/', 'Api\ProjectController::index');
        $routes->post('create', 'Api\ProjectController::create');
        $routes->put('(:num)', 'Api\ProjectController::update/$1');
        $routes->delete('(:num)', 'Api\ProjectController::delete/$1');

        // Project Members nested
        $routes->post('(:num)/members', 'Api\ProjectMemberController::index/$1');
        $routes->post('(:num)/members/add', 'Api\ProjectMemberController::add/$1');
        $routes->delete('(:num)/members/(:num)', 'Api\ProjectMemberController::remove/$1/$2');
    });

     // Task routes
     $routes->group('tasks', ['filter' => 'jwtauth'], function($routes) {
        $routes->get('/', 'Api\TaskController::index');
        $routes->post('/', 'Api\TaskController::create');
        $routes->put('(:num)', 'Api\TaskController::update/$1');
        $routes->delete('(:num)', 'Api\TaskController::delete/$1');
    });

    // Focus routes
    $routes->group('focus', ['filter' => 'jwtauth'], function($routes) {
        $routes->get('/', 'Api\FocusController::index');
        $routes->post('start/(:num)', 'Api\FocusController::start/$1');
        $routes->post('end/(:num)', 'Api\FocusController::end/$1');
    });

    // Notification routes (no double 'api')
    $routes->post('notifications/trigger', 'Api\NotificationController::trigger', ['filter' => 'jwtauth']);

    
});
