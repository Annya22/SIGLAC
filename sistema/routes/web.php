<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\InstitucionEducativaController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
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

Route::middleware(['auth', 'usuario.activo'])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Dashboard
    |--------------------------------------------------------------------------
    */
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Usuarios
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */
    Route::get('/roles', [RoleController::class, 'index'])
        ->middleware('permiso:roles.ver')
        ->name('roles.index');

    Route::get('/roles/crear', [RoleController::class, 'create'])
        ->middleware([
            'permiso:roles.crear',
            'permiso:roles.asignar_permisos',
        ])
        ->name('roles.create');

    Route::post('/roles', [RoleController::class, 'store'])
        ->middleware([
            'permiso:roles.crear',
            'permiso:roles.asignar_permisos',
        ])
        ->name('roles.store');

    Route::get('/roles/{role}/editar', [RoleController::class, 'edit'])
        ->middleware([
            'permiso:roles.editar',
            'permiso:roles.asignar_permisos',
        ])
        ->name('roles.edit');

    Route::put('/roles/{role}', [RoleController::class, 'update'])
        ->middleware([
            'permiso:roles.editar',
            'permiso:roles.asignar_permisos',
        ])
        ->name('roles.update');

    Route::patch('/roles/{role}/estado', [RoleController::class, 'toggleStatus'])
        ->middleware('permiso:roles.cambiar_estado')
        ->name('roles.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Permisos
    |--------------------------------------------------------------------------
    */
    Route::get('/permisos', [PermissionController::class, 'index'])
        ->middleware('permiso:permisos.ver')
        ->name('permisos.index');

    /*
    |--------------------------------------------------------------------------
    | Instituciones educativas
    |--------------------------------------------------------------------------
    */
    Route::get('/instituciones', [InstitucionEducativaController::class, 'index'])
        ->middleware('permiso:instituciones.ver')
        ->name('instituciones.index');

    Route::get('/instituciones/crear', [InstitucionEducativaController::class, 'create'])
        ->middleware('permiso:instituciones.crear')
        ->name('instituciones.create');

    Route::post('/instituciones', [InstitucionEducativaController::class, 'store'])
        ->middleware('permiso:instituciones.crear')
        ->name('instituciones.store');

    Route::get('/instituciones/{institucion}/editar', [InstitucionEducativaController::class, 'edit'])
        ->middleware('permiso:instituciones.editar')
        ->name('instituciones.edit');

    Route::put('/instituciones/{institucion}', [InstitucionEducativaController::class, 'update'])
        ->middleware('permiso:instituciones.editar')
        ->name('instituciones.update');

    Route::patch('/instituciones/{institucion}/estado', [InstitucionEducativaController::class, 'toggleStatus'])
        ->middleware('permiso:instituciones.cambiar_estado')
        ->name('instituciones.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Niveles educativos
    |--------------------------------------------------------------------------
    */
    Route::get('/niveles', [NivelController::class, 'index'])
        ->middleware('permiso:niveles.ver')
        ->name('niveles.index');

    Route::get('/niveles/crear', [NivelController::class, 'create'])
        ->middleware('permiso:niveles.crear')
        ->name('niveles.create');

    Route::post('/niveles', [NivelController::class, 'store'])
        ->middleware('permiso:niveles.crear')
        ->name('niveles.store');

    Route::get('/niveles/{nivel}/editar', [NivelController::class, 'edit'])
        ->middleware('permiso:niveles.editar')
        ->name('niveles.edit');

    Route::put('/niveles/{nivel}', [NivelController::class, 'update'])
        ->middleware('permiso:niveles.editar')
        ->name('niveles.update');

    Route::patch('/niveles/{nivel}/estado', [NivelController::class, 'toggleStatus'])
        ->middleware('permiso:niveles.cambiar_estado')
        ->name('niveles.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Grados
    |--------------------------------------------------------------------------
    */
    Route::get('/grados', [GradoController::class, 'index'])
        ->middleware('permiso:grados.ver')
        ->name('grados.index');

    Route::get('/grados/crear', [GradoController::class, 'create'])
        ->middleware('permiso:grados.crear')
        ->name('grados.create');

    Route::post('/grados', [GradoController::class, 'store'])
        ->middleware('permiso:grados.crear')
        ->name('grados.store');

    Route::get('/grados/{grado}/editar', [GradoController::class, 'edit'])
        ->middleware('permiso:grados.editar')
        ->name('grados.edit');

    Route::put('/grados/{grado}', [GradoController::class, 'update'])
        ->middleware('permiso:grados.editar')
        ->name('grados.update');

    Route::patch('/grados/{grado}/estado', [GradoController::class, 'toggleStatus'])
        ->middleware('permiso:grados.cambiar_estado')
        ->name('grados.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Estudiantes
    |--------------------------------------------------------------------------
    */
    Route::get('/estudiantes', [EstudianteController::class, 'index'])
        ->middleware('permiso:estudiantes.ver')
        ->name('estudiantes.index');

    Route::get('/estudiantes/crear', [EstudianteController::class, 'create'])
        ->middleware('permiso:estudiantes.crear')
        ->name('estudiantes.create');

    Route::post('/estudiantes', [EstudianteController::class, 'store'])
        ->middleware('permiso:estudiantes.crear')
        ->name('estudiantes.store');

    Route::get('/estudiantes/{estudiante}/editar', [EstudianteController::class, 'edit'])
        ->middleware('permiso:estudiantes.editar')
        ->name('estudiantes.edit');

    Route::put('/estudiantes/{estudiante}', [EstudianteController::class, 'update'])
        ->middleware('permiso:estudiantes.editar')
        ->name('estudiantes.update');

    Route::patch('/estudiantes/{estudiante}/estado', [EstudianteController::class, 'toggleStatus'])
        ->middleware('permiso:estudiantes.cambiar_estado')
        ->name('estudiantes.toggle-status');

    /*
    |--------------------------------------------------------------------------
    | Cerrar sesión
    |--------------------------------------------------------------------------
    */
    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

});