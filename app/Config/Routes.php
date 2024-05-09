<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */


$routes->get('students', 'Student::showAll');
$routes->post('student', 'Student::create');
$routes->get('student/(:num)', 'Student::show/$1');
$routes->put('student/(:num)', 'Student::edit/$1');
$routes->delete('student/(:num)', 'Student::delete/$1');

$routes->post('user', 'User::create');
$routes->get('users', 'User::showAll');

$routes->post('login', 'Auth::login');
$routes->post('logout', 'Auth::logout');

$routes->post('image/(:num)', 'UserImage::create/$1');
$routes->get('image/(:num)', 'UserImage::show/$1');




