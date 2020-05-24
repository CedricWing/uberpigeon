<?php
/*****************************************************************
Name: Cedric Wings

File: api.php

Description: This file contains routes for adding pigeons
and to create a delivery order.
******************************************************************/
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
Route::post('/pigeon/add', ['uses' => 'PigeonController@add']);
Route::post('/order/new', ['uses' => 'OrderController@new']);
