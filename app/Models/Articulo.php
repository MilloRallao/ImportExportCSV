<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @mixin Builder
 */
class Articulo extends Model
{
    use HasFactory;

    protected $guarded = [];

    public $fillable = [
        'NOMBRE_GUANXE',
        'NOMBRE_COMERCIAL',
        'REFERENCIA_COMERCIAL',
        'REFERENCIA',
        'REFERENCIA_COMBINACION',
        'TIPO',
        'GENERO',
        'COLOR',
        'TALLA',
        'STOCK',
        'PRECIO_BASE',
        'DESCRIPCION_LARGA',
        'DESCRIPCION_CORTA',
        'CATEGORIA',
        'CATEGORIA_POR_DEFECTO',
        'MARCA_FABRICANTE',
        'RUTA_IMAGENES',
        'ESTADO'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'RUTA_IMAGENES' => 'array',
    ];
}
