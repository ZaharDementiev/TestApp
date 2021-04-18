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

Route::get('/', function () {
    $prods = \App\Models\Prod::all();
    return view('welcome', compact('prods'));
});
Route::get('/test', function () {
    $prods = \App\Models\Prod::all();
    return view('welcome', compact('prods'));
});

Route::post('data/upload', 'App\Http\Controllers\ProdController@getData')->name('get.data');
Route::get('prods/sort/{direction}', 'App\Http\Controllers\ProdController@sortByPrice')->name('sort.price');
Route::post('prods/search', 'App\Http\Controllers\ProdController@searchingProd')->name('prods.search');
Route::post('prods/filter', 'App\Http\Controllers\ProdController@filter')->name('prods.filter');
