<?php

use CodeIgniter\Router\RouteCollection;
use App\Controllers\Home;
use App\Controllers\Login;
use App\Controllers\Logout;
use App\Controllers\CreateAccount;
use App\Controllers\Admin;

use App\Controllers\Pages;
/** @var RouteCollection $routes */

//home page routes
$routes->get('/', [Home::class,'index']);

//login routes
$routes->get('login',[Login::class,'index']);
$routes->post('login',[Login::class,'login']);

//create account route
$routes->post('create-account',[CreateAccount::class,'createAccount']);

//logout routes
$routes->get('logout', [Logout::class,'logout']);

//admin routes
$routes->get('admin-panel', [Admin::class, 'index']);
$routes->post('admin/create-film', [Admin::class, 'createFilm']);

//"catch-all" route to handle non existent pages
$routes->get('(:segment)', [Pages::class, 'view']);

