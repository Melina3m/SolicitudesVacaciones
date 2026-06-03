<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\VacationController; // Importamos nuestro controlador
use Illuminate\Support\Facades\Route;

// Ruta de bienvenida (la que ya trae Laravel)
Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    
    
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    // Listar vacaciones
    Route::get('/vacaciones', [VacationController::class, 'index'])->name('vacations.index');
    // Formulario de creación
    Route::get('/vacaciones/crear', [VacationController::class, 'create'])->name('vacations.create');
    // Guardar la solicitud
    Route::post('/vacaciones', [VacationController::class, 'store'])->name('vacations.store');
    // Cambiar estado (Aprobar, Rechazar, Cancelar)
    Route::patch('/vacaciones/{vacationRequest}/estado', [VacationController::class, 'updateStatus'])->name('vacations.updateStatus');

});

require __DIR__.'/auth.php';