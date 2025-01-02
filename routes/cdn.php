<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

Route::get('/{file}', [Controllers\CdnController::class, 'file'])->name('cdn.file');