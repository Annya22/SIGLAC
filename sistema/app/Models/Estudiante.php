<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'tipo_documento',
        'numero_documento',
        'apellido_paterno',
        'apellido_materno',
        'nombres',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Usuario que creó el registro
    |--------------------------------------------------------------------------
    */
    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Usuario que realizó la última modificación
    |--------------------------------------------------------------------------
    */
    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}
