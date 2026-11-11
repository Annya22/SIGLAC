<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nivel extends Model
{
    use HasFactory;

    protected $table = 'niveles';

    protected $fillable = [
        'nombre',
        'orden',
        'activo',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'activo' => 'boolean',
            'orden' => 'integer',
        ];
    }

    public function grados()
    {
        return $this->hasMany(
            Grado::class,
            'nivel_id'
        )->orderBy('orden');
    }

    public function institucionesEducativas()
    {
        return $this->belongsToMany(
            InstitucionEducativa::class,
            'institucion_niveles',
            'nivel_id',
            'institucion_educativa_id'
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
            'nivel_id'
        );
    }

    public function actas()
    {
        return $this->hasMany(
            Acta::class,
            'nivel_id'
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