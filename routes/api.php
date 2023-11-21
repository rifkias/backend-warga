<?php

use Facade\FlareClient\Api;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
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

Route::namespace("Api")->group(function () {
    Route::post('auth/register', Auth\RegisterController::class);
    Route::post('auth/login', Auth\LoginController::class);

    Route::middleware("auth:sanctum")->group(function () {
        Route::post('auth/userInfo', Auth\UserInfoController::class);
        Route::prefix('admin')->group(function () {
            Route::apiResource('user', Admin\UserController::class);
        });
        Route::group(['prefix' => 'master-address', 'namespace' => "MasterAddress"], function () {
            Route::apiResource('province', ProvinceController::class);
            Route::apiResource('city', CityController::class);
            Route::apiResource('district', DistrictController::class);
            Route::apiResource('village', VillageController::class);
        });
        Route::group(['prefix' => 'master-data', 'namespace' => "MasterData"], function () {
            Route::apiResource("wilayah", WilayahController::class);
            Route::apiResource("house", HouseController::class);
            Route::apiResource("warga", WargaController::class);
        });
        Route::group(['prefix' => 'config', 'namespace' => "Config"], function () {
            Route::apiResource('module', ModuleController::class);
            Route::apiResource('role', RuleController::class);
            Route::apiResource('user-group', UserGroupController::class);
            // Route::get('role-permission/get-by-role/{id}', [RolePermissionController::class, 'getByRole']);
            Route::get('role-permission/get-by-role/{id}', 'RolePermissionController@getByRole');
            Route::apiResource('role-permission', RolePermissionController::class);
            Route::apiResource('user-wilayah', UserWilayahController::class);
        });
    });
});


Route::get("/coba", "ExampleController@coba");
// Show Route Not Found
Route::fallback(function () {
    return response()->json([
        'message' => 'Page Not Found'
    ], 404);
});
