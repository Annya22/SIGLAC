<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class SecuritySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 1 - USUARIOS
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver usuarios',
                'slug' => 'usuarios.ver',
                'modulo' => 'Usuarios',
                'descripcion' => 'Permite consultar usuarios registrados.',
            ],
            [
                'nombre' => 'Crear usuarios',
                'slug' => 'usuarios.crear',
                'modulo' => 'Usuarios',
                'descripcion' => 'Permite registrar nuevos usuarios.',
            ],
            [
                'nombre' => 'Editar usuarios',
                'slug' => 'usuarios.editar',
                'modulo' => 'Usuarios',
                'descripcion' => 'Permite modificar información de usuarios.',
            ],
            [
                'nombre' => 'Cambiar estado de usuarios',
                'slug' => 'usuarios.cambiar_estado',
                'modulo' => 'Usuarios',
                'descripcion' => 'Permite activar o desactivar usuarios.',
            ],
            [
                'nombre' => 'Restablecer contraseña',
                'slug' => 'usuarios.restablecer_password',
                'modulo' => 'Usuarios',
                'descripcion' => 'Permite establecer una nueva contraseña a un usuario.',
            ],
            [
                'nombre' => 'Asignar roles a usuarios',
                'slug' => 'usuarios.asignar_roles',
                'modulo' => 'Usuarios',
                'descripcion' => 'Permite asignar o modificar el rol de los usuarios.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 1 - ROLES
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver roles',
                'slug' => 'roles.ver',
                'modulo' => 'Roles',
                'descripcion' => 'Permite consultar los roles registrados.',
            ],
            [
                'nombre' => 'Crear roles',
                'slug' => 'roles.crear',
                'modulo' => 'Roles',
                'descripcion' => 'Permite registrar nuevos roles.',
            ],
            [
                'nombre' => 'Editar roles',
                'slug' => 'roles.editar',
                'modulo' => 'Roles',
                'descripcion' => 'Permite modificar roles existentes.',
            ],
            [
                'nombre' => 'Cambiar estado de roles',
                'slug' => 'roles.cambiar_estado',
                'modulo' => 'Roles',
                'descripcion' => 'Permite activar o desactivar roles.',
            ],
            [
                'nombre' => 'Asignar permisos a roles',
                'slug' => 'roles.asignar_permisos',
                'modulo' => 'Roles',
                'descripcion' => 'Permite configurar los permisos correspondientes a un rol.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 1 - PERMISOS
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver permisos',
                'slug' => 'permisos.ver',
                'modulo' => 'Permisos',
                'descripcion' => 'Permite consultar los permisos disponibles en SIGLAC.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 2 - INSTITUCIONES EDUCATIVAS
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver instituciones educativas',
                'slug' => 'instituciones.ver',
                'modulo' => 'Instituciones educativas',
                'descripcion' => 'Permite consultar las instituciones educativas registradas.',
            ],
            [
                'nombre' => 'Crear instituciones educativas',
                'slug' => 'instituciones.crear',
                'modulo' => 'Instituciones educativas',
                'descripcion' => 'Permite registrar nuevas instituciones educativas.',
            ],
            [
                'nombre' => 'Editar instituciones educativas',
                'slug' => 'instituciones.editar',
                'modulo' => 'Instituciones educativas',
                'descripcion' => 'Permite modificar la información de las instituciones educativas.',
            ],
            [
                'nombre' => 'Cambiar estado de instituciones educativas',
                'slug' => 'instituciones.cambiar_estado',
                'modulo' => 'Instituciones educativas',
                'descripcion' => 'Permite activar o desactivar instituciones educativas.',
            ],
            [
                'nombre' => 'Asignar niveles a instituciones educativas',
                'slug' => 'instituciones.asignar_niveles',
                'modulo' => 'Instituciones educativas',
                'descripcion' => 'Permite asignar o modificar los niveles educativos ofrecidos por una institución.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 2 - NIVELES
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver niveles',
                'slug' => 'niveles.ver',
                'modulo' => 'Niveles',
                'descripcion' => 'Permite consultar los niveles educativos registrados.',
            ],
            [
                'nombre' => 'Crear niveles',
                'slug' => 'niveles.crear',
                'modulo' => 'Niveles',
                'descripcion' => 'Permite registrar nuevos niveles educativos.',
            ],
            [
                'nombre' => 'Editar niveles',
                'slug' => 'niveles.editar',
                'modulo' => 'Niveles',
                'descripcion' => 'Permite modificar los niveles educativos registrados.',
            ],
            [
                'nombre' => 'Cambiar estado de niveles',
                'slug' => 'niveles.cambiar_estado',
                'modulo' => 'Niveles',
                'descripcion' => 'Permite activar o desactivar niveles educativos.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 2 - GRADOS
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver grados',
                'slug' => 'grados.ver',
                'modulo' => 'Grados',
                'descripcion' => 'Permite consultar los grados registrados.',
            ],
            [
                'nombre' => 'Crear grados',
                'slug' => 'grados.crear',
                'modulo' => 'Grados',
                'descripcion' => 'Permite registrar nuevos grados.',
            ],
            [
                'nombre' => 'Editar grados',
                'slug' => 'grados.editar',
                'modulo' => 'Grados',
                'descripcion' => 'Permite modificar los grados registrados.',
            ],
            [
                'nombre' => 'Cambiar estado de grados',
                'slug' => 'grados.cambiar_estado',
                'modulo' => 'Grados',
                'descripcion' => 'Permite activar o desactivar grados.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 2 - ESTUDIANTES
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver estudiantes',
                'slug' => 'estudiantes.ver',
                'modulo' => 'Estudiantes',
                'descripcion' => 'Permite consultar los estudiantes registrados.',
            ],
            [
                'nombre' => 'Crear estudiantes',
                'slug' => 'estudiantes.crear',
                'modulo' => 'Estudiantes',
                'descripcion' => 'Permite registrar nuevos estudiantes.',
            ],
            [
                'nombre' => 'Editar estudiantes',
                'slug' => 'estudiantes.editar',
                'modulo' => 'Estudiantes',
                'descripcion' => 'Permite modificar la información de los estudiantes.',
            ],
            [
                'nombre' => 'Cambiar estado de estudiantes',
                'slug' => 'estudiantes.cambiar_estado',
                'modulo' => 'Estudiantes',
                'descripcion' => 'Permite activar o desactivar estudiantes.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 3 - TIPOS DE ACTA
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver tipos de acta',
                'slug' => 'tipos_acta.ver',
                'modulo' => 'Tipos de acta',
                'descripcion' => 'Permite consultar los tipos de acta registrados.',
            ],
            [
                'nombre' => 'Crear tipos de acta',
                'slug' => 'tipos_acta.crear',
                'modulo' => 'Tipos de acta',
                'descripcion' => 'Permite registrar nuevos tipos de acta.',
            ],
            [
                'nombre' => 'Editar tipos de acta',
                'slug' => 'tipos_acta.editar',
                'modulo' => 'Tipos de acta',
                'descripcion' => 'Permite modificar los tipos de acta registrados.',
            ],
            [
                'nombre' => 'Cambiar estado de tipos de acta',
                'slug' => 'tipos_acta.cambiar_estado',
                'modulo' => 'Tipos de acta',
                'descripcion' => 'Permite activar o desactivar tipos de acta.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 3 - UBICACIONES
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver ubicaciones',
                'slug' => 'ubicaciones.ver',
                'modulo' => 'Ubicaciones',
                'descripcion' => 'Permite consultar las ubicaciones físicas registradas.',
            ],
            [
                'nombre' => 'Crear ubicaciones',
                'slug' => 'ubicaciones.crear',
                'modulo' => 'Ubicaciones',
                'descripcion' => 'Permite registrar nuevas ubicaciones físicas documentales.',
            ],
            [
                'nombre' => 'Editar ubicaciones',
                'slug' => 'ubicaciones.editar',
                'modulo' => 'Ubicaciones',
                'descripcion' => 'Permite modificar las ubicaciones físicas registradas.',
            ],
            [
                'nombre' => 'Cambiar estado de ubicaciones',
                'slug' => 'ubicaciones.cambiar_estado',
                'modulo' => 'Ubicaciones',
                'descripcion' => 'Permite activar o desactivar ubicaciones físicas.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 3 - ACTAS
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver actas',
                'slug' => 'actas.ver',
                'modulo' => 'Actas',
                'descripcion' => 'Permite consultar las actas históricas registradas.',
            ],
            [
                'nombre' => 'Crear actas',
                'slug' => 'actas.crear',
                'modulo' => 'Actas',
                'descripcion' => 'Permite registrar nuevas actas históricas.',
            ],
            [
                'nombre' => 'Editar actas',
                'slug' => 'actas.editar',
                'modulo' => 'Actas',
                'descripcion' => 'Permite modificar la información de las actas registradas.',
            ],
            [
                'nombre' => 'Cambiar estado de actas',
                'slug' => 'actas.cambiar_estado',
                'modulo' => 'Actas',
                'descripcion' => 'Permite activar o desactivar actas registradas.',
            ],
            [
                'nombre' => 'Asignar estudiantes a actas',
                'slug' => 'actas.asignar_estudiantes',
                'modulo' => 'Actas',
                'descripcion' => 'Permite relacionar o modificar los estudiantes asociados a un acta.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 4 - ASIGNATURAS
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver asignaturas',
                'slug' => 'asignaturas.ver',
                'modulo' => 'Asignaturas',
                'descripcion' => 'Permite consultar las asignaturas registradas.',
            ],
            [
                'nombre' => 'Crear asignaturas',
                'slug' => 'asignaturas.crear',
                'modulo' => 'Asignaturas',
                'descripcion' => 'Permite registrar nuevas asignaturas.',
            ],
            [
                'nombre' => 'Editar asignaturas',
                'slug' => 'asignaturas.editar',
                'modulo' => 'Asignaturas',
                'descripcion' => 'Permite modificar las asignaturas registradas.',
            ],
            [
                'nombre' => 'Cambiar estado de asignaturas',
                'slug' => 'asignaturas.cambiar_estado',
                'modulo' => 'Asignaturas',
                'descripcion' => 'Permite activar o desactivar asignaturas.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 4 - CURSOS DESAPROBADOS
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver cursos desaprobados',
                'slug' => 'cursos_desaprobados.ver',
                'modulo' => 'Cursos desaprobados',
                'descripcion' => 'Permite consultar los cursos desaprobados registrados para los estudiantes.',
            ],
            [
                'nombre' => 'Crear cursos desaprobados',
                'slug' => 'cursos_desaprobados.crear',
                'modulo' => 'Cursos desaprobados',
                'descripcion' => 'Permite registrar cursos desaprobados asociados a estudiantes y actas históricas.',
            ],
            [
                'nombre' => 'Editar cursos desaprobados',
                'slug' => 'cursos_desaprobados.editar',
                'modulo' => 'Cursos desaprobados',
                'descripcion' => 'Permite modificar la información de los cursos desaprobados registrados.',
            ],
            [
                'nombre' => 'Cambiar estado de cursos desaprobados',
                'slug' => 'cursos_desaprobados.cambiar_estado',
                'modulo' => 'Cursos desaprobados',
                'descripcion' => 'Permite activar o desactivar cursos desaprobados registrados.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 4 - SUBSANACIONES
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver subsanaciones',
                'slug' => 'subsanaciones.ver',
                'modulo' => 'Subsanaciones',
                'descripcion' => 'Permite consultar las subsanaciones registradas.',
            ],
            [
                'nombre' => 'Crear subsanaciones',
                'slug' => 'subsanaciones.crear',
                'modulo' => 'Subsanaciones',
                'descripcion' => 'Permite registrar nuevas subsanaciones de cursos desaprobados.',
            ],
            [
                'nombre' => 'Editar subsanaciones',
                'slug' => 'subsanaciones.editar',
                'modulo' => 'Subsanaciones',
                'descripcion' => 'Permite modificar la información de las subsanaciones registradas.',
            ],
            [
                'nombre' => 'Cambiar estado de subsanaciones',
                'slug' => 'subsanaciones.cambiar_estado',
                'modulo' => 'Subsanaciones',
                'descripcion' => 'Permite activar o desactivar subsanaciones registradas.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 5 - DIGITALIZACIÓN DOCUMENTAL
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver digitalizaciones',
                'slug' => 'digitalizaciones.ver',
                'modulo' => 'Digitalización documental',
                'descripcion' => 'Permite consultar el repositorio de documentos digitalizados.',
            ],
            [
                'nombre' => 'Crear digitalizaciones',
                'slug' => 'digitalizaciones.crear',
                'modulo' => 'Digitalización documental',
                'descripcion' => 'Permite cargar documentos históricos en formato PDF, JPG o PNG.',
            ],
            [
                'nombre' => 'Procesar OCR',
                'slug' => 'digitalizaciones.procesar_ocr',
                'modulo' => 'Digitalización documental',
                'descripcion' => 'Permite iniciar o repetir el procesamiento OCR de documentos digitalizados.',
            ],
            [
                'nombre' => 'Revisar digitalizaciones',
                'slug' => 'digitalizaciones.revisar',
                'modulo' => 'Digitalización documental',
                'descripcion' => 'Permite revisar, corregir y validar los datos obtenidos mediante OCR.',
            ],
            [
                'nombre' => 'Descargar digitalizaciones',
                'slug' => 'digitalizaciones.descargar',
                'modulo' => 'Digitalización documental',
                'descripcion' => 'Permite visualizar o descargar los archivos digitalizados almacenados.',
            ],
            [
                'nombre' => 'Cambiar estado de digitalizaciones',
                'slug' => 'digitalizaciones.cambiar_estado',
                'modulo' => 'Digitalización documental',
                'descripcion' => 'Permite activar o desactivar documentos digitalizados.',
            ],

            /*
            |--------------------------------------------------------------------------
            | MÓDULO 6 - BÚSQUEDA Y LOCALIZACIÓN
            |--------------------------------------------------------------------------
            */

            [
                'nombre' => 'Ver búsqueda y localización',
                'slug' => 'busquedas.ver',
                'modulo' => 'Búsqueda y localización',
                'descripcion' => 'Permite buscar documentos históricos y consultar su ubicación física.',
            ],

        ];

        /*
        |--------------------------------------------------------------------------
        | Registrar o actualizar permisos
        |--------------------------------------------------------------------------
        */

        foreach ($permisos as $permiso) {
            Permission::updateOrCreate(
                [
                    'slug' => $permiso['slug'],
                ],
                [
                    'nombre' => $permiso['nombre'],
                    'modulo' => $permiso['modulo'],
                    'descripcion' => $permiso['descripcion'],
                    'activo' => true,
                ]
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Rol Administrador
        |--------------------------------------------------------------------------
        */

        $administrador = Role::updateOrCreate(
            [
                'slug' => 'administrador',
            ],
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Rol con acceso administrativo completo al sistema.',
                'activo' => true,
            ]
        );

        /*
        |--------------------------------------------------------------------------
        | El Administrador recibe todos los permisos activos
        |--------------------------------------------------------------------------
        */

        $administrador->permissions()->sync(
            Permission::where('activo', true)->pluck('id')
        );
    }
}