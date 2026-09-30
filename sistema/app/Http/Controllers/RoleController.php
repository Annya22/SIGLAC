<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::withCount(['users', 'permissions'])
            ->orderBy('nombre')
            ->get();

        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        $permisos = Permission::where('activo', true)
            ->orderBy('modulo')
            ->orderBy('nombre')
            ->get()
            ->groupBy('modulo');

        return view('roles.create', compact('permisos'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                'unique:roles,nombre',
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
            'permisos' => [
                'nullable',
                'array',
            ],
            'permisos.*' => [
                'integer',
                Rule::exists('permissions', 'id')->where(
                    fn ($query) => $query->where('activo', true)
                ),
            ],
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique' => 'Ya existe un rol con ese nombre.',
        ]);

        $slugBase = Str::slug($datos['nombre']);
        $slug = $slugBase;
        $contador = 2;

        while (Role::where('slug', $slug)->exists()) {
            $slug = $slugBase . '-' . $contador;
            $contador++;
        }

        $rol = Role::create([
            'nombre' => $datos['nombre'],
            'slug' => $slug,
            'descripcion' => $datos['descripcion'] ?? null,
            'activo' => true,
        ]);

        $rol->permissions()->sync($datos['permisos'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol registrado correctamente.');
    }

    public function edit(Role $role)
    {

    if ($role->slug === 'administrador') {
    abort(
        403,
        'El rol Administrador es un rol protegido del sistema y no puede modificarse.'
    );
}
        $role->load('permissions');

        $permisos = Permission::where('activo', true)
            ->orderBy('modulo')
            ->orderBy('nombre')
            ->get()
            ->groupBy('modulo');

        return view('roles.edit', compact('role', 'permisos'));
    }

    public function update(Request $request, Role $role)
    {

    if ($role->slug === 'administrador') {
    abort(
        403,
        'El rol Administrador es un rol protegido del sistema y no puede modificarse.'
    );
}
        $datos = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:100',
                Rule::unique('roles', 'nombre')->ignore($role->id),
            ],
            'descripcion' => [
                'nullable',
                'string',
                'max:255',
            ],
            'permisos' => [
                'nullable',
                'array',
            ],
            'permisos.*' => [
                'integer',
                Rule::exists('permissions', 'id')->where(
                    fn ($query) => $query->where('activo', true)
                ),
            ],
        ], [
            'nombre.required' => 'El nombre del rol es obligatorio.',
            'nombre.unique' => 'Ya existe otro rol con ese nombre.',
        ]);

        $role->update([
            'nombre' => $datos['nombre'],
            'descripcion' => $datos['descripcion'] ?? null,
        ]);

        $role->permissions()->sync($datos['permisos'] ?? []);

        return redirect()
            ->route('roles.index')
            ->with('success', 'Rol actualizado correctamente.');
    }

    public function toggleStatus(Role $role)
    {
        if ($role->slug === 'administrador' && $role->activo) {
            return back()->with(
                'error',
                'El rol Administrador principal no puede ser desactivado.'
            );
        }

        $role->update([
            'activo' => !$role->activo,
        ]);

        return back()->with(
            'success',
            $role->activo
                ? 'Rol activado correctamente.'
                : 'Rol desactivado correctamente.'
        );
    }
}
