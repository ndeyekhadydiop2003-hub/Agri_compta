<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Support\Facades\DB;


use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProduitController;
use App\Http\Controllers\VarieteController;
use App\Http\Controllers\RecolteController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\PerteController;

Route::get('/', function () { return redirect()->route('dashboard'); });

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::resource('produits', ProduitController::class);
Route::resource('varietes', VarieteController::class);
Route::resource('recoltes', RecolteController::class);
Route::resource('ventes', VenteController::class);
Route::resource('pertes', PerteController::class);
Route::resource('pertes', PerteController::class);
Route::put('/pertes/{id}/archive', [PerteController::class, 'archive'])->name('pertes.archive');


