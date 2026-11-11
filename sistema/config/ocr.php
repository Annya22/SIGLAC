<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Motor OCR
    |--------------------------------------------------------------------------
    |
    | Define el proveedor que SIGLAC utilizará para procesar
    | los documentos históricos digitalizados.
    |
    */

    'driver' => env('OCR_DRIVER', 'textract'),

    /*
    |--------------------------------------------------------------------------
    | Amazon Textract
    |--------------------------------------------------------------------------
    */

    'textract' => [

        /*
        | Región AWS utilizada por Amazon Textract.
        */
        'region' => env(
            'AWS_TEXTRACT_REGION',
            env('AWS_DEFAULT_REGION', 'us-east-1')
        ),

        /*
        | Bucket temporal utilizado para documentos que necesiten
        | procesamiento mediante Amazon S3 y Textract.
        */
        'bucket' => env('AWS_TEXTRACT_BUCKET'),

        /*
        | Prefijo o carpeta lógica dentro del bucket.
        */
        'prefix' => env(
            'AWS_TEXTRACT_PREFIX',
            'siglac/ocr'
        ),

        /*
        | Permite eliminar de S3 la copia temporal una vez que
        | el procesamiento OCR haya terminado.
        |
        | El archivo original permanece almacenado de forma privada
        | dentro de SIGLAC.
        */
        'delete_after_processing' => env(
            'AWS_TEXTRACT_DELETE_AFTER_PROCESSING',
            true
        ),

    ],

    /*
    |--------------------------------------------------------------------------
    | Tipos de archivo admitidos
    |--------------------------------------------------------------------------
    */

    'allowed_mime_types' => [
        'application/pdf',
        'image/jpeg',
        'image/png',
    ],

    /*
    |--------------------------------------------------------------------------
    | Confianza mínima del OCR
    |--------------------------------------------------------------------------
    |
    | Los datos con una confianza inferior podrán marcarse
    | posteriormente para revisión manual por el técnico.
    |
    */

'minimum_confidence' => (int) env(
    'OCR_MINIMUM_CONFIDENCE',
    80
),

];