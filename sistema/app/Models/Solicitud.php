<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Solicitud extends Model
{
    use HasFactory;

    protected $table = 'solicitudes';

    protected $fillable = [
        'codigo',
        'fecha_ingreso',

        'tipo_documento',
        'numero_documento',
        'nombres_solicitante',
        'apellidos_solicitante',
        'celular',
        'correo',

        'nombres_estudiante_declarados',
        'institucion_declarada',
        'nivel_declarado',

        'estudiante_id',
        'institucion_educativa_id',
        'nivel_id',

        'anio_inicio_declarado',
        'anio_fin_declarado',
        'declara_desaprobado',

        'estado',
        'observaciones',
        'respuesta',
        'fecha_cierre',

        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'fecha_ingreso' => 'datetime',
            'fecha_cierre' => 'datetime',
            'anio_inicio_declarado' => 'integer',
            'anio_fin_declarado' => 'integer',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Datos identificados
    |--------------------------------------------------------------------------
    */

    public function estudiante()
    {
        return $this->belongsTo(
            Estudiante::class,
            'estudiante_id'
        );
    }

    public function institucionEducativa()
    {
        return $this->belongsTo(
            InstitucionEducativa::class,
            'institucion_educativa_id'
        );
    }

    public function nivel()
    {
        return $this->belongsTo(
            Nivel::class,
            'nivel_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Actas vinculadas
    |--------------------------------------------------------------------------
    */

    public function solicitudActas()
    {
        return $this->hasMany(
            SolicitudActa::class,
            'solicitud_id'
        );
    }

    public function actas()
    {
        return $this->belongsToMany(
            Acta::class,
            'solicitud_actas',
            'solicitud_id',
            'acta_id'
        )
            ->withPivot([
                'id',
                'observaciones',
                'activo',
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Subsanaciones vinculadas
    |--------------------------------------------------------------------------
    */

    public function solicitudSubsanaciones()
    {
        return $this->hasMany(
            SolicitudSubsanacion::class,
            'solicitud_id'
        );
    }

    public function subsanaciones()
    {
        return $this->belongsToMany(
            Subsanacion::class,
            'solicitud_subsanaciones',
            'solicitud_id',
            'subsanacion_id'
        )
            ->withPivot([
                'id',
                'observaciones',
                'activo',
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }

    /*
    |--------------------------------------------------------------------------
    | Historial de atención
    |--------------------------------------------------------------------------
    */

    public function eventos()
    {
        return $this->hasMany(
            EventoSolicitud::class,
            'solicitud_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Auditoría
    |--------------------------------------------------------------------------
    */

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

    /*
    |--------------------------------------------------------------------------
    | Estado de atención
    |--------------------------------------------------------------------------
    */

    public function estaAbierta(): bool
    {
        return in_array(
            $this->estado,
            [
                'en_busqueda',
                'observada',
            ],
            true
        );
    }

    public function estaCerrada(): bool
    {
        return in_array(
            $this->estado,
            [
                'atendida',
                'no_localizada',
            ],
            true
        );
    }
}