<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::get('/', [Controllers\MauerController::class, 'index'])->name('mauer');

Route::get('/scribble', [Controllers\MauerController::class, 'scribble'])->middleware('auth')->name('mauer.scribble');
Route::post('/scribble', [Controllers\MauerController::class, 'scribble'])->middleware('auth');

Route::get('/{id}/edit', [Controllers\MauerController::class, 'edit'])->middleware('auth')->name('mauer.edit');
Route::post('/{id}/edit', [Controllers\MauerController::class, 'edit'])->middleware('auth'); // I agree.