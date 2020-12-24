<?php

use Illuminate\Http\Request;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:sanctum')->get('vista','controladores\users@verperfil');
// Route::get('insertarusuario','controladores\users@insertar');
Route::post('registro','controladores\users@registro');
Route::post('login','controladores\users@logIn');
Route::middleware('auth:sanctum')->delete('logout','controladores\users@logOut');

Route::middleware('auth:sanctum')->get('solicitardatos','controladores\registro_sensoress@solicitardatos');
Route::middleware('auth:sanctum')->post('guardarfotodepersona','controladores\users@guardarfotodepersona');
Route::middleware('auth:sanctum')->post('insertardatos','controladores\registro_sensoress@insertardatos');
Route::middleware('auth:sanctum')->post('guardarfotodeperro','controladores\users@guardarfotodeperro');
Route::middleware('auth:sanctum')->put('nuevafotodepersona','controladores\users@guardarfotodepersona');
Route::middleware('auth:sanctum')->put('nuevafotodeperro','controladores\users@guardarfotodeperro');
Route::middleware('auth:sanctum')->put('actualizardatos','controladores\users@actualizar');

//guardarfotodeperro