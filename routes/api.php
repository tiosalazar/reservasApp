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

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('cors');

Route::group(['prefix' => 'v1','middleware' => 'auth:api'], function () {
    //    Route::resource('task', 'TasksController');

    //Please do not remove this if you want adminlte:route and adminlte:link commands to works correctly.
    #adminlte_api_routes

    Route::resource('user', 'userController');
    Route::get('user/show_by_email/{$email}','userController@showUserByEmail');
    Route::put('user/update/notifications_push/{$id}','userController@updateNotificationsPush');
    Route::put('user/update/notifications_email/{$id}','userController@updateNotificationsEmail');
    //Route::get('Usuario/consultar/email','userController@ConsultarUsuarioByEmail']);

    Route::resource('cancha', 'CanchaController');
    Route::get('cancha/show_by_zone/{$id}','CanchaController@showCanchaByZoneId');
    

});
