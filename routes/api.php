<?php


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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });


Route::group(['middleware' => ['api'], 'namespace' => 'Api'], function () {
    Route::get('get-main-services', 'BackendController@Categories');

});
Route::post('create', 'UserController@register');
Route::post('login', 'ApiAuthController@login');
