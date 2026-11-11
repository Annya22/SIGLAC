<?php

namespace App\Http\Controllers;

use App\Models\Acta;
use App\Models\Digitalizacion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Throwable;

class DigitalizacionController extends Controller
{
    /**
     * Listado general del repositorio documental.
     */
    public function index(Request $request)
    {
        $buscar = trim((string) $request->get('buscar', ''));

        $digitalizaciones = Digitalizacion::query()
            ->with([
                'acta.tipoActa',
                'acta.institucionEducativa',
                'createdBy',
                'revisadoPor',
            ])
            ->when($buscar !== '', function ($query) use ($buscar) {
                $query->where(function ($subquery) use ($buscar) {
                    $subquery
                        ->where('nombre_original', 'like', "%{$buscar}%")
                        ->orWhere('tipo_documento', 'like', "%{$buscar}%")
                        ->orWhere('estado_ocr', 'like', "%{$buscar}%")
                        ->orWhere('estado_revision', 'like', "%{$buscar}%")
                        ->orWhereHas('acta', function ($actaQuery) use ($buscar) {
                            $actaQuery
                                ->where('numero_acta', 'like', "%{$buscar}%")
                                ->orWhere('anio', 'like', "%{$buscar}%")
                                ->orWhereHas(
                                    'institucionEducativa',
                                    function ($institucionQuery) use ($buscar) {
                                        $institucionQuery->where(
                                            'nombre',
                                            'like',
                                            "%{$buscar}%"
                                        );
                                    }
                                );
                        });
                });
            })
            ->orderByDesc('id')
            ->paginate(10)
            ->withQueryString();

        return view(
            'digitalizaciones.index',
            compact('digitalizaciones', 'buscar')
        );
    }

    /**
     * Formulario para subir un nuevo documento.
     */
    public function create()
    {
        $actas = Acta::query()
            ->with([
                'tipoActa',
                'institucionEducativa',
                'nivel',
                'grado',
            ])
            ->where('activo', true)
            ->orderByDesc('anio')
            ->orderByDesc('id')
            ->get();

        $tiposDocumento = $this->tiposDocumento();

        return view(
            'digitalizaciones.create',
            compact('actas', 'tiposDocumento')
        );
    }

    /**
     * Guarda un archivo histórico en almacenamiento privado.
     */
    public function store(Request $request)
    {
        $datos = $request->validate([
            'tipo_documento' => [
                'required',
                'string',
                Rule::in(array_keys($this->tiposDocumento())),
            ],

            'acta_id' => [
                'nullable',
                'integer',
                'exists:actas,id',
            ],

            'archivo' => [
                'required',
                'file',
                'mimes:pdf,jpg,jpeg,png',
                'max:20480',
            ],
        ], [
            'tipo_documento.required' =>
                'Debe seleccionar el tipo de documento.',

            'tipo_documento.in' =>
                'El tipo de documento seleccionado no es válido.',

            'acta_id.exists' =>
                'El acta seleccionada no existe.',

            'archivo.required' =>
                'Debe seleccionar un documento para digitalizar.',

            'archivo.file' =>
                'El archivo seleccionado no es válido.',

            'archivo.mimes' =>
                'Solo se permiten archivos PDF, JPG, JPEG o PNG.',

            'archivo.max' =>
                'El archivo no debe superar los 20 MB.',
        ]);

        if (! empty($datos['acta_id'])) {
            $acta = Acta::findOrFail($datos['acta_id']);

            if (! $acta->activo) {
                throw ValidationException::withMessages([
                    'acta_id' =>
                        'El acta seleccionada se encuentra inactiva.',
                ]);
            }
        }

        $archivo = $request->file('archivo');

        $hash = hash_file(
            'sha256',
            $archivo->getRealPath()
        );

        $duplicado = Digitalizacion::query()
            ->where('hash_sha256', $hash)
            ->first();

        if ($duplicado) {
            throw ValidationException::withMessages([
                'archivo' =>
                    'Este archivo ya fue registrado anteriormente en SIGLAC.',
            ]);
        }

        $extension = strtolower(
            $archivo->getClientOriginalExtension()
        );

        $nombreArchivo =
            Str::uuid()->toString() . '.' . $extension;

        $anio = now()->format('Y');

        if (! empty($datos['acta_id'])) {
            $acta = Acta::find($datos['acta_id']);

            if ($acta && $acta->anio) {
                $anio = (string) $acta->anio;
            }
        }

        $directorio =
            'digitalizaciones/' .
            $anio . '/' .
            $datos['tipo_documento'];

        $rutaArchivo = null;

        try {
            DB::beginTransaction();

            $rutaArchivo = Storage::disk('local')->putFileAs(
                $directorio,
                $archivo,
                $nombreArchivo
            );

            if (! $rutaArchivo) {
                throw new \RuntimeException(
                    'No fue posible almacenar el archivo.'
                );
            }

            Digitalizacion::create([
                'acta_id' => $datos['acta_id'] ?? null,

                'tipo_documento' =>
                    $datos['tipo_documento'],

                'nombre_original' =>
                    $archivo->getClientOriginalName(),

                'nombre_archivo' =>
                    $nombreArchivo,

                'ruta_archivo' =>
                    $rutaArchivo,

                'mime_type' =>
                    $archivo->getMimeType()
                        ?: 'application/octet-stream',

                'tamanio_bytes' =>
                    $archivo->getSize(),

                'numero_paginas' =>
                    null,

                'hash_sha256' =>
                    $hash,

                'motor_ocr' =>
                    null,

                'estado_ocr' =>
                    'pendiente',

                'texto_ocr' =>
                    null,

                'datos_extraidos' =>
                    null,

                'confianza_ocr' =>
                    null,

                'error_ocr' =>
                    null,

                'estado_revision' =>
                    'pendiente',

                'revisado_por' =>
                    null,

                'fecha_revision' =>
                    null,

                'observacion_revision' =>
                    null,

                'activo' =>
                    true,

                'created_by' =>
                    Auth::id(),

                'updated_by' =>
                    Auth::id(),
            ]);

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();

            if (
                $rutaArchivo &&
                Storage::disk('local')->exists($rutaArchivo)
            ) {
                Storage::disk('local')->delete($rutaArchivo);
            }

            throw $e;
        }

        return redirect()
            ->route('digitalizaciones.index')
            ->with(
                'success',
                'Documento digitalizado registrado correctamente.'
            );
    }

    /**
     * Descarga protegida del archivo almacenado.
     */
    public function descargar(
        Digitalizacion $digitalizacion
    ) {
        if (
            ! Storage::disk('local')
                ->exists($digitalizacion->ruta_archivo)
        ) {
            return redirect()
                ->route('digitalizaciones.index')
                ->with(
                    'error',
                    'El archivo físico no fue encontrado en el almacenamiento.'
                );
        }

        return Storage::disk('local')->download(
            $digitalizacion->ruta_archivo,
            $digitalizacion->nombre_original
        );
    }

    /**
     * Activar o desactivar un documento digitalizado.
     */
    public function cambiarEstado(
        Digitalizacion $digitalizacion
    ) {
        $digitalizacion->update([
            'activo' => ! $digitalizacion->activo,
            'updated_by' => Auth::id(),
        ]);

        return redirect()
            ->route('digitalizaciones.index')
            ->with(
                'success',
                $digitalizacion->activo
                    ? 'Digitalización activada correctamente.'
                    : 'Digitalización desactivada correctamente.'
            );
    }

    /**
     * Tipos documentales admitidos inicialmente por SIGLAC.
     */
    private function tiposDocumento(): array
    {
        return [
            'acta_historica' =>
                'Acta histórica',

            'acta_subsanacion' =>
                'Acta de subsanación',

            'certificado_historico' =>
                'Certificado histórico',

            'otro_documento' =>
                'Otro documento histórico',
        ];
    }
}