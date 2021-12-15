<?php

use App\Http\Controllers\ClientController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\ItemController;

/** @var \Illuminate\Support\Facades\Route $router */

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

$router->get('/', function () {
    return view('invoice');
});

$router->group(['prefix' => 'invoice'], function ($router) {
    $router->get('add', [InvoiceController::class, 'create']);
    $router->get('data', [InvoiceController::class, 'data']);
    $router->post('save', [InvoiceController::class, 'save']);
});

$router->group(['prefix' => 'client'], function ($router) {
    $router->get('all', [ClientController::class, 'all']);
});

$router->group(['prefix' => 'item'], function ($router) {
    $router->get('all', [ItemController::class, 'all']);
});