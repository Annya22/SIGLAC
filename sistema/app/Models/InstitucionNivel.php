<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstitucionNivel extends Model
{
    use HasFactory;

    protected $table = 'institucion_niveles';

    protected $fillable = [
        'institucion_educativa_id',
        'nivel_id',
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
    | Institución educativa
    |--------------------------------------------------------------------------
    */
    public function institucionEducativa()
    {
        return $this->belongsTo(
            InstitucionEducativa::class,
            'institucion_educativa_id'
        );
    }

    /*
    |--------------------------------------------------------------------------
    | Nivel educativo
    |--------------------------------------------------------------------------
    */
    public function nivel()
    {
        return $this->belongsTo(
            Nivel::class,
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