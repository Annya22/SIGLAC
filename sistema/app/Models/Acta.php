<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acta extends Model
{
    use HasFactory;

    protected $table = 'actas';

    protected $fillable = [
        'tipo_acta_id',
        'institucion_educativa_id',
        'nivel_id',
        'grado_id',
        'ubicacion_id',
        'numero_acta',
        'anio',
        'fecha_acta',
        'seccion',
        'observaciones',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'anio' => 'integer',
            'fecha_acta' => 'date',
            'activo' => 'boolean',
        ];
    }

    public function tipoActa()
    {
        return $this->belongsTo(
            TipoActa::class,
            'tipo_acta_id'
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

    public function grado()
    {
        return $this->belongsTo(
            Grado::class,
            'grado_id'
        );
    }

    public function ubicacion()
    {
        return $this->belongsTo(
            Ubicacion::class,
            'ubicacion_id'
        );
    }

    public function estudiantes()
    {
        return $this->belongsToMany(
            Estudiante::class,
            'acta_estudiantes',
            'acta_id',
            'estudiante_id'
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
            'acta_id'
        );
    }

    /**
     * Subsanaciones en las que esta acta funciona
     * como acta de subsanación.
     */
    public function subsanacionesComoActa()
    {
        return $this->hasMany(
            Subsanacion::class,
            'acta_subsanacion_id'
        );
    }

    /**
     * Documentos digitalizados vinculados a esta acta.
     */
    public function digitalizaciones()
    {
        return $this->hasMany(
            Digitalizacion::class,
            'acta_id'
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