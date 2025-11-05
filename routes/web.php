<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ListController;

Route::get('/', [ListController::class, 'index'])->name('home'); // ホームページ
Route::post('/add', [ListController::class, 'add'])->name('add'); // アイテム追加
Route::post('/clear', [ListController::class, 'clear'])->name('clear'); // アイテム全削除
Route::post('/total', [ListController::class, 'total'])->name('total'); // 合計数量取得
Route::post('/delete/{id}', [ListController::class, 'delete'])->name('delete'); // アイテム削除
Route::post('/toggle-check/{id}', [ListController::class, 'toggleCheck'])->name('toggleCheck'); // チェック状態切替

Route::post('/update', [ListController::class, 'update'])->name('update'); // 在庫リスト更新