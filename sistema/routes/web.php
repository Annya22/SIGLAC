<?php

use App\Http\Controllers\ActaController;
use App\Http\Controllers\ActaEstudianteController;
use App\Http\Controllers\AsignaturaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\BusquedaController;
use App\Http\Controllers\CursoDesaprobadoController;
use App\Http\Controllers\DigitalizacionController;
use App\Http\Controllers\EstudianteController;
use App\Http\Controllers\GradoController;
use App\Http\Controllers\InstitucionEducativaController;
use App\Http\Controllers\NivelController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\SubsanacionController;
use App\Http\Controllers\TipoActaController;
use App\Http\Controllers\UbicacionController;
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
    | Tipos de acta
    |--------------------------------------------------------------------------
    */

    Route::get('/tipos-acta', [TipoActaController::class, 'index'])
        ->middleware('permiso:tipos_acta.ver')
        ->name('tipos-acta.index');

    Route::get('/tipos-acta/crear', [TipoActaController::class, 'create'])
        ->middleware('permiso:tipos_acta.crear')
        ->name('tipos-acta.create');

    Route::post('/tipos-acta', [TipoActaController::class, 'store'])
        ->middleware('permiso:tipos_acta.crear')
        ->name('tipos-acta.store');

    Route::get('/tipos-acta/{tipoActa}/editar', [TipoActaController::class, 'edit'])
        ->middleware('permiso:tipos_acta.editar')
        ->name('tipos-acta.edit');

    Route::put('/tipos-acta/{tipoActa}', [TipoActaController::class, 'update'])
        ->middleware('permiso:tipos_acta.editar')
        ->name('tipos-acta.update');

    Route::patch('/tipos-acta/{tipoActa}/estado', [TipoActaController::class, 'cambiarEstado'])
        ->middleware('permiso:tipos_acta.cambiar_estado')
        ->name('tipos-acta.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | Ubicaciones
    |--------------------------------------------------------------------------
    */

    Route::get('/ubicaciones', [UbicacionController::class, 'index'])
        ->middleware('permiso:ubicaciones.ver')
        ->name('ubicaciones.index');

    Route::get('/ubicaciones/crear', [UbicacionController::class, 'create'])
        ->middleware('permiso:ubicaciones.crear')
        ->name('ubicaciones.create');

    Route::post('/ubicaciones', [UbicacionController::class, 'store'])
        ->middleware('permiso:ubicaciones.crear')
        ->name('ubicaciones.store');

    Route::get('/ubicaciones/{ubicacion}/editar', [UbicacionController::class, 'edit'])
        ->middleware('permiso:ubicaciones.editar')
        ->name('ubicaciones.edit');

    Route::put('/ubicaciones/{ubicacion}', [UbicacionController::class, 'update'])
        ->middleware('permiso:ubicaciones.editar')
        ->name('ubicaciones.update');

    Route::patch('/ubicaciones/{ubicacion}/estado', [UbicacionController::class, 'cambiarEstado'])
        ->middleware('permiso:ubicaciones.cambiar_estado')
        ->name('ubicaciones.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | Actas históricas
    |--------------------------------------------------------------------------
    */

    Route::get('/actas', [ActaController::class, 'index'])
        ->middleware('permiso:actas.ver')
        ->name('actas.index');

    Route::get('/actas/crear', [ActaController::class, 'create'])
        ->middleware('permiso:actas.crear')
        ->name('actas.create');

    Route::post('/actas', [ActaController::class, 'store'])
        ->middleware('permiso:actas.crear')
        ->name('actas.store');

    Route::get('/actas/{acta}/editar', [ActaController::class, 'edit'])
        ->middleware('permiso:actas.editar')
        ->name('actas.edit');

    Route::put('/actas/{acta}', [ActaController::class, 'update'])
        ->middleware('permiso:actas.editar')
        ->name('actas.update');

    Route::patch('/actas/{acta}/estado', [ActaController::class, 'cambiarEstado'])
        ->middleware('permiso:actas.cambiar_estado')
        ->name('actas.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | Estudiantes asociados a actas
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/actas/{acta}/estudiantes',
        [ActaEstudianteController::class, 'index']
    )
        ->middleware('permiso:actas.asignar_estudiantes')
        ->name('actas.estudiantes.index');

    Route::post(
        '/actas/{acta}/estudiantes',
        [ActaEstudianteController::class, 'store']
    )
        ->middleware('permiso:actas.asignar_estudiantes')
        ->name('actas.estudiantes.store');

    Route::patch(
        '/actas/{acta}/estudiantes/{actaEstudiante}/estado',
        [ActaEstudianteController::class, 'cambiarEstado']
    )
        ->middleware('permiso:actas.asignar_estudiantes')
        ->name('actas.estudiantes.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | Asignaturas
    |--------------------------------------------------------------------------
    */

    Route::get('/asignaturas', [AsignaturaController::class, 'index'])
        ->middleware('permiso:asignaturas.ver')
        ->name('asignaturas.index');

    Route::get('/asignaturas/crear', [AsignaturaController::class, 'create'])
        ->middleware('permiso:asignaturas.crear')
        ->name('asignaturas.create');

    Route::post('/asignaturas', [AsignaturaController::class, 'store'])
        ->middleware('permiso:asignaturas.crear')
        ->name('asignaturas.store');

    Route::get('/asignaturas/{asignatura}/editar', [AsignaturaController::class, 'edit'])
        ->middleware('permiso:asignaturas.editar')
        ->name('asignaturas.edit');

    Route::put('/asignaturas/{asignatura}', [AsignaturaController::class, 'update'])
        ->middleware('permiso:asignaturas.editar')
        ->name('asignaturas.update');

    Route::patch('/asignaturas/{asignatura}/estado', [AsignaturaController::class, 'cambiarEstado'])
        ->middleware('permiso:asignaturas.cambiar_estado')
        ->name('asignaturas.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | Cursos desaprobados
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/cursos-desaprobados',
        [CursoDesaprobadoController::class, 'index']
    )
        ->middleware('permiso:cursos_desaprobados.ver')
        ->name('cursos-desaprobados.index');

    Route::get(
        '/cursos-desaprobados/crear',
        [CursoDesaprobadoController::class, 'create']
    )
        ->middleware('permiso:cursos_desaprobados.crear')
        ->name('cursos-desaprobados.create');

    Route::post(
        '/cursos-desaprobados',
        [CursoDesaprobadoController::class, 'store']
    )
        ->middleware('permiso:cursos_desaprobados.crear')
        ->name('cursos-desaprobados.store');

    Route::get(
        '/cursos-desaprobados/{cursoDesaprobado}/editar',
        [CursoDesaprobadoController::class, 'edit']
    )
        ->middleware('permiso:cursos_desaprobados.editar')
        ->name('cursos-desaprobados.edit');

    Route::put(
        '/cursos-desaprobados/{cursoDesaprobado}',
        [CursoDesaprobadoController::class, 'update']
    )
        ->middleware('permiso:cursos_desaprobados.editar')
        ->name('cursos-desaprobados.update');

    Route::patch(
        '/cursos-desaprobados/{cursoDesaprobado}/estado',
        [CursoDesaprobadoController::class, 'cambiarEstado']
    )
        ->middleware('permiso:cursos_desaprobados.cambiar_estado')
        ->name('cursos-desaprobados.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | Subsanaciones
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/subsanaciones',
        [SubsanacionController::class, 'index']
    )
        ->middleware('permiso:subsanaciones.ver')
        ->name('subsanaciones.index');

    Route::get(
        '/subsanaciones/crear',
        [SubsanacionController::class, 'create']
    )
        ->middleware('permiso:subsanaciones.crear')
        ->name('subsanaciones.create');

    Route::post(
        '/subsanaciones',
        [SubsanacionController::class, 'store']
    )
        ->middleware('permiso:subsanaciones.crear')
        ->name('subsanaciones.store');

    Route::get(
        '/subsanaciones/{subsanacion}/editar',
        [SubsanacionController::class, 'edit']
    )
        ->middleware('permiso:subsanaciones.editar')
        ->name('subsanaciones.edit');

    Route::put(
        '/subsanaciones/{subsanacion}',
        [SubsanacionController::class, 'update']
    )
        ->middleware('permiso:subsanaciones.editar')
        ->name('subsanaciones.update');

    Route::patch(
        '/subsanaciones/{subsanacion}/estado',
        [SubsanacionController::class, 'cambiarEstado']
    )
        ->middleware('permiso:subsanaciones.cambiar_estado')
        ->name('subsanaciones.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | Digitalización documental
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/digitalizaciones',
        [DigitalizacionController::class, 'index']
    )
        ->middleware('permiso:digitalizaciones.ver')
        ->name('digitalizaciones.index');

    Route::get(
        '/digitalizaciones/crear',
        [DigitalizacionController::class, 'create']
    )
        ->middleware('permiso:digitalizaciones.crear')
        ->name('digitalizaciones.create');

    Route::post(
        '/digitalizaciones',
        [DigitalizacionController::class, 'store']
    )
        ->middleware('permiso:digitalizaciones.crear')
        ->name('digitalizaciones.store');

    Route::get(
        '/digitalizaciones/{digitalizacion}/descargar',
        [DigitalizacionController::class, 'descargar']
    )
        ->middleware('permiso:digitalizaciones.descargar')
        ->name('digitalizaciones.descargar');

    Route::patch(
        '/digitalizaciones/{digitalizacion}/estado',
        [DigitalizacionController::class, 'cambiarEstado']
    )
        ->middleware('permiso:digitalizaciones.cambiar_estado')
        ->name('digitalizaciones.cambiar-estado');

    /*
    |--------------------------------------------------------------------------
    | MÓDULO 6 - BÚSQUEDA Y LOCALIZACIÓN
    |--------------------------------------------------------------------------
    */

    Route::get(
        '/busqueda',
        [BusquedaController::class, 'index']
    )
        ->middleware('permiso:busquedas.ver')
        ->name('busqueda.index');

    /*
    |--------------------------------------------------------------------------
    | Cerrar sesión
    |--------------------------------------------------------------------------
    */

    Route::post('/logout', [LoginController::class, 'logout'])
        ->name('logout');

});