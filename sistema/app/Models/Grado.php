<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grado extends Model
{
    use HasFactory;

    protected $table = 'grados';

    protected $fillable = [
        'nivel_id',
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
    | Nivel al que pertenece el grado
    |--------------------------------------------------------------------------
    |
    | Ejemplo:
    |
    | 1.º → Primaria
    | 1.º → Secundaria
    |
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
