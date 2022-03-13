<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FormController;
use App\Http\Controllers\TelnetController;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/form',[FormController::class,'index'])->name('form');
Route::post('/add/device',[FormController::class,'add_device']);
Route::post('/add/param',[FormController::class,'add_param']);
Route::get('/delete_device/{device_id}',[FormController::class,'delete_device']);
Route::get('/telnet',[TelnetController::class,'index']);