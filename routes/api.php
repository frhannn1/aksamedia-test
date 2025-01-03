<?php

use App\Http\Controllers\Authcontroller;
use App\Http\Controllers\DivisionController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;



Route::get('/user', [UserController::class,'getDataUser'])->middleware('auth:sanctum');
Route::put('/user/{id}', [UserController::class,'updateName'])->middleware('auth:sanctum');



Route::post('/login',[Authcontroller::class,'login']);
Route::post('/logout',[Authcontroller::class,'logout'])->middleware(['auth:sanctum']);


Route::get('/divisions',[DivisionController::class,'getAllDataDivisi'])->middleware(['auth:sanctum']);

Route::get('/employees',[EmployeeController::class,'getAllDataEmployee'])->middleware(['auth:sanctum']);
Route::post('/employees',[EmployeeController::class,'store'])->middleware(['auth:sanctum']);
Route::put('/employees/{id}',[EmployeeController::class,'update'])->middleware(['auth:sanctum']);
Route::delete('/employees/{id}',[EmployeeController::class,'delete'])->middleware(['auth:sanctum']);
