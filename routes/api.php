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

//Authentication Routes
Route::post('/auth/login', 'AuthController@login');
Route::post('/auth/register', 'AuthController@register');

//Post Routes
Route::get('/posts', 'PostController@index');
Route::get('/posts/{slug}', 'PostController@show');

//Routes that need token
Route::group(['middleware' => ['jwt.auth']], function () {

	//Authentication Routes
	Route::post('/auth/logout', 'AuthController@logout');

	//Post Routes
	Route::post('/posts', 'PostController@store');
	Route::put('/posts/{slug}', 'PostController@update');
	Route::patch('/posts/{slug}', 'PostController@update');
	Route::delete('/posts/{slug}', 'PostController@destroy');

});