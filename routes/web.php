<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;

Route::get('/', [ListController::class, 'index'])->name('home');
Route::post('/add', [ListController::class, 'add'])->name('add');
Route::post('/clear', [ListController::class, 'clear'])->name('clear');
Route::post('/total', [ListController::class, 'total'])->name('total');
Route::post('/update-quantity/{id}', [ListController::class, 'updateQuantity'])->name('updateQuantity');
Route::post('/toggle-check/{id}', [ListController::class, 'toggleCheck'])->name('toggleCheck');