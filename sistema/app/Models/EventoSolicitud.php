<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventoSolicitud extends Model
{
    use HasFactory;

    protected $table = 'eventos_solicitud';

    protected $fillable = [
        'solicitud_id',
        'usuario_id',
        'tipo',
        'estado_anterior',
        'estado_nuevo',
        'detalle',
        'fecha',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
        ];
    }

    public function solicitud()
    {
        return $this->belongsTo(
            Solicitud::class,
            'solicitud_id'
        );
    }

    public function usuario()
    {
        return $this->belongsTo(
            User::class,
            'usuario_id'
        );
    }
}