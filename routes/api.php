<?php

use Illuminate\Http\Request;

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

Route::post('login', 'UserController@login');
Route::post('register', 'UserController@register');

Route::group(['middleware' => 'auth:api'], function(){
    Route::get('/products', 'ProductsController@index');
    Route::post('/products', 'ProductsController@store');
    Route::get('/products/{products}', 'ProductsController@show');
    Route::post('/products/update/{products}', 'ProductsController@update');
    Route::delete('/products/{products}', 'ProductsController@destroy');

    Route::post('/importFile', 'ExcelController@importFile');
	Route::get('/exportFile/{type}', 'ExcelController@exportFile');
});
