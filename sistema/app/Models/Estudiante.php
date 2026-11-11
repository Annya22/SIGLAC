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

    public function actas()
    {
        return $this->belongsToMany(
            Acta::class,
            'acta_estudiantes',
            'estudiante_id',
            'acta_id'
        )
            ->withPivot([
                'id',
                'activo',
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }

    public function actaEstudiantes()
    {
        return $this->hasMany(
            ActaEstudiante::class,
            'estudiante_id'
        );
    }

    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}