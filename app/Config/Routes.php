<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\Login;
use App\Controllers\Logout;

use App\Controllers\Pages;
/** @var RouteCollection $routes */

//home page routes
$routes->get('/', [Home::class,'index']);

//login routes
$routes->get('login',[Login::class,'index']);
$routes->post('login',[Login::class,'login']);

//logout routes
$routes->get('logout', [Logout::class,'logout']);

//"catch-all" route to handle non existent pages
$routes->get('(:segment)', [Pages::class, 'view']);

