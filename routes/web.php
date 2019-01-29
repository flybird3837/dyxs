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
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('password/username', 'Auth\ForgotPasswordController@showUsernameForm')->name('password.username');
Route::post('password/username', 'Auth\ForgotPasswordController@verifyUsername')->name('password.username.verify');


Route::get('password/code', 'Auth\ForgotPasswordController@showVerifyCodeForm')->name('password.code');
Route::post('password/code', 'Auth\ForgotPasswordController@verifyCode')->name('password.code.verify');
Route::post('password/code/send', 'Auth\ForgotPasswordController@sendVerifyCode')->name('password.code.send');

Route::get('password/reset', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');


Route::namespace('Manage')->middleware(['auth', 'dynamic.config'])->group(function () {
    Route::get('/', 'DashboardController@index')->name('dashboard.index');
    // Route::get('projects/{project}/user', '@user');
    //党组织
    Route::resource('projects', 'ProjectController');
    Route::post('projects/add', 'ProjectController@addByName');
    Route::post('projects/edit', 'ProjectController@editByName');

    Route::resource('projects/{project}/user', 'ProjectUserController')
        ->names('projects.user')->only(['create', 'store', 'edit', 'update']);
    Route::get('projects/{project}/{role}', 'ProjectUserController@create_user')->name('projects.user.create_user');

    //党员
    Route::resource('dangyuans', 'DangyuanController');
    Route::post('/dangyuan/edit', 'DangyuanController@edit');
    Route::post('dangyuan/upload', 'DangyuanController@upload')->name('dangyuan.upload');
    Route::get('dangyuan/download', 'DangyuanController@download')->name('dangyuan.download');

    //宣传片
    Route::resource('xuanchuans', 'XuanchuanController');


});
