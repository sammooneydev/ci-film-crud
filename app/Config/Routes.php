<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;
use App\Controllers\Login;

/** @var RouteCollection $routes */

$routes->get('/', 'home');

//login routes
$routes->get('login','login');
$routes->post('login',[Login::class,'login']);


$routes->get('pages', [Pages::class, 'index']);
//"catch-all" route to handle non existent pages
$routes->get('(:segment)', [Pages::class, 'view']);