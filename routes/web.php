<?php

use Illuminate\Support\Facades\Route;

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

// Route::get('/', function () {
//     return view('welcome');
// });
Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('auth.login.form');
Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//shop
Route::resource('shop', App\Http\Controllers\ShopController::class);
//product
Route::resource('product', App\Http\Controllers\ProductController::class);
//product export
Route::get('export/product', [App\Http\Controllers\ProductController::class, 'getExcelData'])->name('product.export');
Route::get('supplier.import.view', [App\Http\Controllers\ProductController::class, 'ImportView'])->name('product.import');
Route::post('supplier.import.store', [App\Http\Controllers\ProductController::class, 'ImportStore'])->name('product.import.store');

Route::post('ajax/counter', [App\Http\Controllers\HomeController::class,'postCounter'])->name('ajax.counter');

Route::get('pattern', [App\Http\Controllers\HomeController::class, 'getPattern'])->name('home.pattern');
