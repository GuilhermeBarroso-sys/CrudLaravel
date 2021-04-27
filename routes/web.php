<?php

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

Route::resource('/products', 'ProductController');
Route::post('/products/{product}/pdfGenerate', 'ProductController@pdfGenerate')->name('products.pdfGenerate');
Route::post('/products/{product}/pdfGenerateShow', 'ProductController@pdfGenerateShow')->name('products.pdfGenerateShow');
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
