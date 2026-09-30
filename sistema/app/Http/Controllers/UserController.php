<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $busqueda = trim((string) $request->input('buscar'));

        $usuarios = User::with('roles')
            ->when($busqueda !== '', function ($query) use ($busqueda) {
                $query->where(function ($q) use ($busqueda) {
                    $q->where('name', 'like', "%{$busqueda}%")
                        ->orWhere('email', 'like', "%{$busqueda}%");
                });
            })
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.index', compact('usuarios', 'busqueda'));
    }

    public function create()
    {
        $roles = Role::where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('usuarios.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],

            'role_id' => [
                'required',
                'integer',
                Rule::exists('roles', 'id')->where(
                    fn ($query) => $query->where('activo', true)
                ),
            ],
        ], [
            'name.required' => 'El nombre completo es obligatorio.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Ya existe un usuario con ese correo electrónico.',

            'password.required' => 'La contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',

            'role_id.required' => 'Debe seleccionar un rol.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Solo un Administrador puede asignar el rol Administrador
        |--------------------------------------------------------------------------
        */
        $rolSeleccionado = Role::findOrFail($datos['role_id']);

        if (
            $rolSeleccionado->slug === 'administrador' &&
            !Auth::user()->hasRole('administrador')
        ) {
            abort(
                403,
                'No tiene permiso para asignar el rol Administrador.'
            );
        }

        $usuario = User::create([
            'name' => $datos['name'],
            'email' => strtolower($datos['email']),
            'password' => $datos['password'],
            'activo' => true,
            'created_by' => Auth::id(),
            'updated_by' => Auth::id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Un solo rol por usuario
        |--------------------------------------------------------------------------
        */
        $usuario->roles()->sync([
            $datos['role_id'],
        ]);

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario registrado correctamente.'
            );
    }

    public function edit(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Un usuario no Administrador no puede editar Administradores
        |--------------------------------------------------------------------------
        */
        if (
            $user->hasRole('administrador') &&
            !Auth::user()->hasRole('administrador')
        ) {
            abort(
                403,
                'No tiene permiso para editar una cuenta Administrador.'
            );
        }

        $user->load('roles');

        $puedeAsignarRoles = Auth::user()
            ->hasPermission('usuarios.asignar_roles');

        $roles = collect();

        if ($puedeAsignarRoles) {
            $roles = Role::where('activo', true)
                ->orderBy('nombre')
                ->get();
        }

        return view(
            'usuarios.edit',
            compact(
                'user',
                'roles',
                'puedeAsignarRoles'
            )
        );
    }

    public function update(Request $request, User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Un usuario no Administrador no puede modificar Administradores
        |--------------------------------------------------------------------------
        */
        if (
            $user->hasRole('administrador') &&
            !Auth::user()->hasRole('administrador')
        ) {
            abort(
                403,
                'No tiene permiso para modificar una cuenta Administrador.'
            );
        }

        $puedeAsignarRoles = Auth::user()
            ->hasPermission('usuarios.asignar_roles');

        /*
        |--------------------------------------------------------------------------
        | Reglas generales
        |--------------------------------------------------------------------------
        */
        $reglas = [
            'name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
        ];

        /*
        |--------------------------------------------------------------------------
        | Validación del único rol
        |--------------------------------------------------------------------------
        */
        if ($puedeAsignarRoles) {
            $reglas['role_id'] = [
                'required',
                'integer',
                Rule::exists('roles', 'id')->where(
                    fn ($query) => $query->where('activo', true)
                ),
            ];
        }

        $datos = $request->validate($reglas, [
            'name.required' => 'El nombre completo es obligatorio.',

            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'Ingrese un correo electrónico válido.',
            'email.unique' => 'Ya existe otro usuario con ese correo electrónico.',

            'role_id.required' => 'Debe seleccionar un rol.',
            'role_id.exists' => 'El rol seleccionado no es válido.',
        ]);

        /*
        |--------------------------------------------------------------------------
        | Validar el rol seleccionado
        |--------------------------------------------------------------------------
        */
        $rolSeleccionado = null;

        if ($puedeAsignarRoles) {
            $rolSeleccionado = Role::findOrFail(
                $datos['role_id']
            );

            /*
            |--------------------------------------------------------------------------
            | Solo un Administrador puede asignar el rol Administrador
            |--------------------------------------------------------------------------
            */
            if (
                $rolSeleccionado->slug === 'administrador' &&
                !Auth::user()->hasRole('administrador')
            ) {
                abort(
                    403,
                    'No tiene permiso para asignar el rol Administrador.'
                );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Evitar quitar el rol al último Administrador activo
        |--------------------------------------------------------------------------
        */
        if (
            $puedeAsignarRoles &&
            $user->activo &&
            $user->hasRole('administrador')
        ) {
            $conservaraAdministrador =
                $rolSeleccionado &&
                $rolSeleccionado->slug === 'administrador';

            if (
                !$conservaraAdministrador &&
                $this->cantidadAdministradoresActivos() <= 1
            ) {
                return back()
                    ->withInput()
                    ->with(
                        'error',
                        'No puede quitar el rol Administrador al último Administrador activo del sistema.'
                    );
            }
        }

        /*
        |--------------------------------------------------------------------------
        | Actualizar datos básicos
        |--------------------------------------------------------------------------
        */
        $user->update([
            'name' => $datos['name'],
            'email' => strtolower($datos['email']),
            'updated_by' => Auth::id(),
        ]);

        /*
        |--------------------------------------------------------------------------
        | Guardar solamente un rol
        |--------------------------------------------------------------------------
        */
        if ($puedeAsignarRoles) {
            $user->roles()->sync([
                $datos['role_id'],
            ]);
        }

        return redirect()
            ->route('usuarios.index')
            ->with(
                'success',
                'Usuario actualizado correctamente.'
            );
    }

    public function toggleStatus(User $user)
    {
        /*
        |--------------------------------------------------------------------------
        | Un usuario no Administrador no puede cambiar estado de Administradores
        |--------------------------------------------------------------------------
        */
        if (
            $user->hasRole('administrador') &&
            !Auth::user()->hasRole('administrador')
        ) {
            abort(
                403,
                'No tiene permiso para cambiar el estado de una cuenta Administrador.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evitar desactivar al último Administrador activo
        |--------------------------------------------------------------------------
        */
        if (
            $user->activo &&
            $user->hasRole('administrador') &&
            $this->cantidadAdministradoresActivos() <= 1
        ) {
            return back()->with(
                'error',
                'No puede desactivar al último Administrador activo del sistema.'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Evitar que un usuario se desactive a sí mismo
        |--------------------------------------------------------------------------
        */
        if (
            $user->id === Auth::id() &&
            $user->activo
        ) {
            return back()->with(
                'error',
                'No puede desactivar su propia cuenta mientras mantiene la sesión iniciada.'
            );
        }

        $user->update([
            'activo' => !$user->activo,
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            $user->activo
                ? 'Usuario activado correctamente.'
                : 'Usuario desactivado correctamente.'
        );
    }

    public function resetPassword(
        Request $request,
        User $user
    ) {
        /*
        |--------------------------------------------------------------------------
        | Un usuario no Administrador no puede cambiar contraseña de Administradores
        |--------------------------------------------------------------------------
        */
        if (
            $user->hasRole('administrador') &&
            !Auth::user()->hasRole('administrador')
        ) {
            abort(
                403,
                'No tiene permiso para restablecer la contraseña de una cuenta Administrador.'
            );
        }

        $datos = $request->validate([
            'password' => [
                'required',
                'confirmed',
                Password::min(8)
                    ->mixedCase()
                    ->numbers()
                    ->symbols(),
            ],
        ], [
            'password.required' => 'La nueva contraseña es obligatoria.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
        ]);

        $user->update([
            'password' => $datos['password'],
            'updated_by' => Auth::id(),
        ]);

        return back()->with(
            'success',
            'Contraseña restablecida correctamente.'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Contar Administradores activos
    |--------------------------------------------------------------------------
    */
    private function cantidadAdministradoresActivos(): int
    {
        return User::where('activo', true)
            ->whereHas('roles', function ($query) {
                $query->where('slug', 'administrador')
                    ->where('roles.activo', true);
            })
            ->count();
    }
}