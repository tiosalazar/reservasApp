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

Route::group(['middleware' => 'auth'], function () {
    //    Route::get('/link1', function ()    {
//        // Uses Auth Middleware
//    });

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_routes


});
//Funciones de Inicio de Sección y registro.
Route::group(['middleware' => 'cors'], function () {

    //Funciones para Usuario.
    Route::post('user/login','userController@login');
    Route::post('user/create','userController@store');
    Route::post('user/logout','userController@logout');
    Route::post('user/rememberpassword','userController@RecoveryPassword');
    // Password Reset Routes...
    Route::get('password/reset', 'ForgotPasswordController@showLinkRequestForm')->name('admin.password.reset');
    Route::post('password/email', 'ForgotPasswordController@sendResetLinkEmail');
    Route::get('password/reset/{token}', 'ResetPasswordController@showResetForm')->name('admin.password.token');
    Route::post('password/reset', 'ResetPasswordController@reset');

});



Route::get('/p', function () {
    return bcrypt('ygoj45wf');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
