<?php

namespace App\Http\Controllers;

use App\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        $permisos = Permission::withCount('roles')
            ->orderBy('modulo')
            ->orderBy('nombre')
            ->get()
            ->groupBy('modulo');

        return view('permisos.index', compact('permisos'));
    }
}