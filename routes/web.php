<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SiswaController;

Route::get('/', function () {
    return view('welcome');
});

// Route CRUD Siswa
Route::get('/siswa', [SiswaController::class, 'index'])->name('siswa.index');
Route::get('/siswa/create', [SiswaController::class, 'create'])->name('siswa.create');
Route::post('/siswa', [SiswaController::class, 'store'])->name('siswa.store');

// Route Edit dan Update
Route::get('/siswa/{id}/edit', [SiswaController::class, 'edit'])->name('siswa.edit'); 
Route::put('/siswa/{id}', [SiswaController::class, 'update'])->name('siswa.update'); 

// Route Hapus
Route::delete('/siswa/{id}', [SiswaController::class, 'destroy'])->name('siswa.destroy');

Route::get('/siswa/export-excel', [SiswaController::class, 'exportExcel'])->name('siswa.export.excel');
Route::get('/siswa/export-pdf', [SiswaController::class, 'exportPdf'])->name('siswa.export.pdf');