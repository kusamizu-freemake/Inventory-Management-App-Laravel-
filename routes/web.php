<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;

Route::get('/', [ListController::class, 'ShowInventory'])->name('ShowInventory'); // ホームページ
Route::post('/add', [ListController::class, 'AddItem'])->name('AddItem'); // アイテム追加
Route::post('/clear', [ListController::class, 'ClearAllItems'])->name('ClearAllItems'); // アイテム全削除
Route::post('/total', [ListController::class, 'CalculateTotalQuantity'])->name('CalculateTotalQuantity'); // 合計数量取得
Route::post('/delete/{id}', [ListController::class, 'DeleteItem'])->name('DeleteItem'); // アイテム削除
Route::post('/toggle-check/{id}', [ListController::class, 'ToggleCheck'])->name('ToggleCheck'); // チェック状態切替

Route::post('/update', [ListController::class, 'UpdateItem'])->name('UpdateItem'); // 在庫リスト更新