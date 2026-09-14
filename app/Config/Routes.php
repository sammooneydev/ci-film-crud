<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Pages;

/** @var RouteCollection $routes */

$routes->get('/', 'home');






$routes->get('pages', [Pages::class, 'index']);
//"catch-all" route to handle non existent pages
$routes->get('(:segment)', [Pages::class, 'view']);