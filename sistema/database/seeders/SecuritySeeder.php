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