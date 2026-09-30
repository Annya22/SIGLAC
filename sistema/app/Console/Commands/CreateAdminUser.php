<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class CreateAdminUser extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'siglac:create-admin';

    /**
     * The console command description.
     */
    protected $description = 'Crea el usuario administrador inicial de SIGLAC';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $role = Role::where('slug', 'administrador')
            ->where('activo', true)
            ->first();

        if (!$role) {
            $this->error(
                'No existe el rol Administrador. Ejecute primero: php artisan db:seed'
            );

            return self::FAILURE;
        }

        $nombre = trim((string) $this->ask('Nombre completo'));
        $email = strtolower(trim((string) $this->ask('Correo electrónico')));

        if (User::where('email', $email)->exists()) {
            $this->error('Ya existe un usuario registrado con ese correo.');

            return self::FAILURE;
        }

        $password = (string) $this->secret('Contraseña');
        $confirmacion = (string) $this->secret('Confirmar contraseña');

        if ($password !== $confirmacion) {
            $this->error('Las contraseñas no coinciden.');

            return self::FAILURE;
        }

        $validator = Validator::make(
            [
                'name' => $nombre,
                'email' => $email,
                'password' => $password,
            ],
            [
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'email', 'max:255'],
                'password' => [
                    'required',
                    Password::min(8)
                        ->mixedCase()
                        ->numbers()
                        ->symbols(),
                ],
            ]
        );

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }

        $user = User::create([
            'name' => $nombre,
            'email' => $email,
            'password' => $password,
            'activo' => true,
        ]);

        $user->roles()->attach($role->id);

        $this->newLine();
        $this->info('Administrador creado correctamente.');
        $this->line("Usuario: {$user->email}");
        $this->line('Rol: Administrador');

        return self::SUCCESS;
    }
}