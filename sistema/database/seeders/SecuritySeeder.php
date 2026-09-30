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
    'descripcion' => 'Permite asignar o modificar los roles de los usuarios.',
],

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

            [
                'nombre' => 'Ver permisos',
                'slug' => 'permisos.ver',
                'modulo' => 'Permisos',
                'descripcion' => 'Permite consultar los permisos disponibles en SIGLAC.',
            ],
        ];

        foreach ($permisos as $permiso) {
            Permission::updateOrCreate(
                ['slug' => $permiso['slug']],
                [
                    'nombre' => $permiso['nombre'],
                    'modulo' => $permiso['modulo'],
                    'descripcion' => $permiso['descripcion'],
                    'activo' => true,
                ]
            );
        }

        $administrador = Role::updateOrCreate(
            ['slug' => 'administrador'],
            [
                'nombre' => 'Administrador',
                'descripcion' => 'Rol con acceso administrativo completo al sistema.',
                'activo' => true,
            ]
        );

        $administrador->permissions()->sync(
            Permission::where('activo', true)->pluck('id')
        );
    }
}