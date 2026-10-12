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

    /*
    |--------------------------------------------------------------------------
    | Grados del nivel
    |--------------------------------------------------------------------------
    |
    | Ejemplo:
    |
    | Primaria → 1.º, 2.º, 3.º, 4.º, 5.º, 6.º
    | Secundaria → 1.º, 2.º, 3.º, 4.º, 5.º
    |
    */
    public function grados()
    {
        return $this->hasMany(
            Grado::class,
            'nivel_id'
        )->orderBy('orden');
    }

    /*
    |--------------------------------------------------------------------------
    | Instituciones educativas que ofrecen este nivel
    |--------------------------------------------------------------------------
    */
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

    /*
    |--------------------------------------------------------------------------
    | Registros de la tabla intermedia
    |--------------------------------------------------------------------------
    */
    public function institucionNiveles()
    {
        return $this->hasMany(
            InstitucionNivel::class,
            'nivel_id'
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
