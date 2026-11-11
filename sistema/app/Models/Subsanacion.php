<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subsanacion extends Model
{
    protected $table = 'subsanaciones';

    protected $fillable = [
        'curso_desaprobado_id',
        'acta_subsanacion_id',
        'fecha_subsanacion',
        'resultado',
        'calificacion',
        'observacion',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'fecha_subsanacion' => 'date',
        'activo' => 'boolean',
    ];

    /**
     * Curso desaprobado que origina la subsanación.
     */
    public function cursoDesaprobado(): BelongsTo
    {
        return $this->belongsTo(
            CursoDesaprobado::class,
            'curso_desaprobado_id'
        );
    }

    /**
     * Acta de subsanación asociada.
     */
    public function actaSubsanacion(): BelongsTo
    {
        return $this->belongsTo(
            Acta::class,
            'acta_subsanacion_id'
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