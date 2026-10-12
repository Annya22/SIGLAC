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

    /*
    |--------------------------------------------------------------------------
    | Relación con niveles educativos
    |--------------------------------------------------------------------------
    |
    | Una institución educativa puede ofrecer uno o varios niveles.
    | Ejemplo:
    |
    | IE X → Primaria
    | IE X → Secundaria
    |
    */
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

    /*
    |--------------------------------------------------------------------------
    | Registros de la tabla intermedia
    |--------------------------------------------------------------------------
    */
    public function institucionNiveles()
    {
        return $this->hasMany(
            InstitucionNivel::class,
            'institucion_educativa_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Usuario que creó el registro
    |--------------------------------------------------------------------------
    */
    public function createdBy()
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Usuario que realizó la última modificación
    |--------------------------------------------------------------------------
    */
    public function updatedBy()
    {
        return $this->belongsTo(
            User::class,
            'updated_by'
        );
    }
}