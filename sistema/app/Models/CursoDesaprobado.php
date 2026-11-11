<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CursoDesaprobado extends Model
{
    protected $table = 'cursos_desaprobados';

    protected $fillable = [
        'acta_estudiante_id',
        'asignatura_id',
        'observacion',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'activo' => 'boolean',
    ];

    /**
     * Relación entre el estudiante y el acta histórica.
     */
    public function actaEstudiante(): BelongsTo
    {
        return $this->belongsTo(
            ActaEstudiante::class,
            'acta_estudiante_id'
        );
    }

    /**
     * Asignatura desaprobada.
     */
    public function asignatura(): BelongsTo
    {
        return $this->belongsTo(
            Asignatura::class,
            'asignatura_id'
        );
    }

    /**
     * Subsanaciones registradas para este curso desaprobado.
     */
    public function subsanaciones(): HasMany
    {
        return $this->hasMany(
            Subsanacion::class,
            'curso_desaprobado_id'
        );
    }

    /**
     * Usuario que creó el registro.
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /**
     * Usuario que realizó la última modificación.
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}