<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActaEstudiante extends Model
{
    use HasFactory;

    protected $table = 'acta_estudiantes';

    protected $fillable = [
        'acta_id',
        'estudiante_id',
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

    public function acta()
    {
        return $this->belongsTo(
            Acta::class,
            'acta_id'
        );
    }

    public function estudiante()
    {
        return $this->belongsTo(
            Estudiante::class,
            'estudiante_id'
        );
    }

    public function cursosDesaprobados()
    {
        return $this->hasMany(
            CursoDesaprobado::class,
            'acta_estudiante_id'
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