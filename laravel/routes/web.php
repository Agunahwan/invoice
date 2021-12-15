<?php

use App\Http\Controllers\InvoiceController;
use Illuminate\Support\Facades\Route;

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
});