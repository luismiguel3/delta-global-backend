<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('users', 'User::showAll');
$routes->post('user', 'User::create');
$routes->get('user/(:num)', 'User::show/$1');
$routes->put('user/(:num)', 'User::edit/$1');
$routes->delete('user/(:num)', 'User::delete/$1');

$routes->post('login', 'Auth::login');
$routes->post('logout', 'Auth::logout');

$routes->post('image', 'Image::create');

