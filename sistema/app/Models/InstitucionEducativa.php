<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionEducativa extends Model
{
    use HasFactory;

    protected $table = 'instituciones_educativas';

    protected $fillable = [
        'codigo_modular',
        'nombre',
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

    public function niveles()
    {
        return $this->belongsToMany(
            Nivel::class,
            'institucion_niveles',
            'institucion_educativa_id',
            'nivel_id'
        )
            ->withPivot([
                'id',
                'activo',
                'created_by',
                'updated_by',
            ])
            ->withTimestamps();
    }

    public function institucionNiveles()
    {
        return $this->hasMany(
            InstitucionNivel::class,
            'institucion_educativa_id'
        );
    }

    public function actas()
    {
        return $this->hasMany(
            Acta::class,
            'institucion_educativa_id'
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