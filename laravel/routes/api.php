<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

/** @var \Illuminate\Support\Facades\Route $router */

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

$router->group(['prefix' => 'v1'], function ($router) {
    $router->get('/', function () {
        return 'Welcome to API v1';
    });
    $router->group(['prefix' => 'invoice'], function ($router) {
        $router->get('all', [InvoiceController::class, 'all']);
        $router->get('{id}', [InvoiceController::class, 'get']);
    });
});