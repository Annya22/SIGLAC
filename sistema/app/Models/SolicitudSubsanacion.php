<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SolicitudSubsanacion extends Model
{
    use HasFactory;

    protected $table = 'solicitud_subsanaciones';

    protected $fillable = [
        'solicitud_id',
        'subsanacion_id',
        'observaciones',
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

    public function solicitud()
    {
        return $this->belongsTo(
            Solicitud::class,
            'solicitud_id'
        );
    }

    public function subsanacion()
    {
        return $this->belongsTo(
            Subsanacion::class,
            'subsanacion_id'
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