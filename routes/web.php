<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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
//
use App\Http\Controllers\NewController;

Route::get('/', [NewController::class, 'excelToArray']);

use App\Http\Controllers\JSONController;

Route::get('/json', [JSONController::class, 'arrayToJSON']);
