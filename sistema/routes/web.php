<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLogin'])
        ->name('login');

    Route::post('/login', [LoginController::class, 'login'])
        ->name('login.authenticate');
});

Route::middleware('auth')->group(function () {

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Usuarios
// Usuarios
Route::get('/usuarios', [UserController::class, 'index'])
    ->middleware('permiso:usuarios.ver')
    ->name('usuarios.index');

Route::get('/usuarios/crear', [UserController::class, 'create'])
    ->middleware([
        'permiso:usuarios.crear',
        'permiso:usuarios.asignar_roles',
    ])
    ->name('usuarios.create');

Route::post('/usuarios', [UserController::class, 'store'])
    ->middleware([
        'permiso:usuarios.crear',
        'permiso:usuarios.asignar_roles',
    ])
    ->name('usuarios.store');

Route::get('/usuarios/{user}/editar', [UserController::class, 'edit'])
    ->middleware('permiso:usuarios.editar')
    ->name('usuarios.edit');

Route::put('/usuarios/{user}', [UserController::class, 'update'])
    ->middleware('permiso:usuarios.editar')
    ->name('usuarios.update');

Route::patch('/usuarios/{user}/estado', [UserController::class, 'toggleStatus'])
    ->middleware('permiso:usuarios.cambiar_estado')
    ->name('usuarios.toggle-status');

Route::put('/usuarios/{user}/password', [UserController::class, 'resetPassword'])
    ->middleware('permiso:usuarios.restablecer_password')
    ->name('usuarios.reset-password');

    // Roles
// Roles
Route::get('/roles', [RoleController::class, 'index'])
    ->middleware('permiso:roles.ver')
    ->name('roles.index');

Route::get('/roles/crear', [RoleController::class, 'create'])
    ->middleware(['permiso:roles.crear', 'permiso:roles.asignar_permisos'])
    ->name('roles.create');

Route::post('/roles', [RoleController::class, 'store'])
    ->middleware(['permiso:roles.crear', 'permiso:roles.asignar_permisos'])
    ->name('roles.store');

Route::get('/roles/{role}/editar', [RoleController::class, 'edit'])
    ->middleware(['permiso:roles.editar', 'permiso:roles.asignar_permisos'])
    ->name('roles.edit');

Route::put('/roles/{role}', [RoleController::class, 'update'])
    ->middleware(['permiso:roles.editar', 'permiso:roles.asignar_permisos'])
    ->name('roles.update');

Route::patch('/roles/{role}/estado', [RoleController::class, 'toggleStatus'])
    ->middleware('permiso:roles.cambiar_estado')
    ->name('roles.toggle-status');

    // Permisos
    Route::get('/permisos', [PermissionController::class, 'index'])
    ->middleware('permiso:permisos.ver')
    ->name('permisos.index');

    // Cerrar sesión
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');
});