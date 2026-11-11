<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Digitalizacion extends Model
{
    protected $table = 'digitalizaciones';

    protected $fillable = [
        'acta_id',
        'tipo_documento',
        'nombre_original',
        'nombre_archivo',
        'ruta_archivo',
        'mime_type',
        'tamanio_bytes',
        'numero_paginas',
        'hash_sha256',
        'motor_ocr',
        'estado_ocr',
        'texto_ocr',
        'datos_extraidos',
        'confianza_ocr',
        'error_ocr',
        'estado_revision',
        'revisado_por',
        'fecha_revision',
        'observacion_revision',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'tamanio_bytes' => 'integer',
            'numero_paginas' => 'integer',
            'datos_extraidos' => 'array',
            'confianza_ocr' => 'decimal:2',
            'fecha_revision' => 'datetime',
            'activo' => 'boolean',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Documento estructurado relacionado
    |--------------------------------------------------------------------------
    */

    public function acta(): BelongsTo
    {
        return $this->belongsTo(Acta::class, 'acta_id');
    }

    /*
    |--------------------------------------------------------------------------
    | Revisión humana
    |--------------------------------------------------------------------------
    */

    public function revisadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'revisado_por');
    }

    /*
    |--------------------------------------------------------------------------
    | Auditoría
    |--------------------------------------------------------------------------
    */

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}