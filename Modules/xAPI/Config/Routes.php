<?php

$routes->group('xapi', ['namespace' => 'Modules\xAPI\Controllers'], function ($routes) {
    $routes->post('statement', 'xAPIController::sendCustomStatement');
    $routes->get('test', 'xAPIController::testStatement');
});