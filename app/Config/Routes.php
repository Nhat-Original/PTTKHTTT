<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'LoginController::index');
$routes->get('/login/(:any)/(:any)', 'LoginController::login/$1');
$routes->get('admin/home/(:any)', 'Admin\Home::index/$1');
// $routes->get('/home/courses', 'Admin\Home::index2');
$routes->get('/courses', 'Admin\CoursesController::index');
