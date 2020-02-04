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
 //Route::get('/home', 'HomeController@index')->name('home');


Route::view('/', 'welcome');

Auth::routes();

Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm');
Route::get('/login/writer', 'Auth\LoginController@showWriterLoginForm');

Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm');
Route::get('/register/writer', 'Auth\RegisterController@showWriterRegisterForm');

Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::post('/login/writer', 'Auth\LoginController@writerLogin');

Route::post('/register/admin', 'Auth\RegisterController@createAdmin');
Route::post('/register/writer', 'Auth\RegisterController@createWriter');


//Use this fo Writer after login
Route::group(['prefix' => 'writer', 'middleware' => ['auth:writer,admin']], function()
{
	//writer Controll
    Route::get('/', 'WriterController@dashboard')->name('dashboard');

});



Route::group(['prefix' => 'lara-admin',  'middleware' => 'auth:admin'], function()
{ 
	//Admin Controll
    Route::get('/', 'AdminController@dashboard')->name('dashboard'); 

    Route::resource('admin','AdminController'); 

	Route::resource('roles','RoleController');

    Route::resource('users','UserController');

    Route::resource('products','ProductController');

});
