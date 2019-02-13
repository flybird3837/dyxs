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
//
//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

use App\Http\Controllers\Api\ProjectController;


Route::namespace('Api')->group(function () {
    Route::get('org', "ProjectController@list");
    Route::get('org/{project_id}/dangyuan', "ProjectController@dangyuans");
    Route::get('org/{project_id}/xuanchuan/{category}', "ProjectController@xuanchuans");
    Route::get('org/{project_id}/dangyuan/{name}', "ProjectController@dangyuanSearch");
    Route::get('org/{project_id}/{id}', "ProjectController@dangyuanGet");
    Route::get('org/{project_id}/xuanshi/list', "ProjectController@dangyuanXuanshi");
    Route::get('qiniu/token', "ProjectController@qiniuToken");
    Route::post('qiniu/callback', "ProjectController@qiniuCallback");
});