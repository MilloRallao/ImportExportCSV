<?php

namespace App\Exports;

use App\Models\Articulo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ArticulosExport implements FromQuery, WithHeadings, WithCustomCsvSettings
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function query()
    {
        return Articulo::query()->select([
            'NOMBRE_GUANXE',
            'NOMBRE_COMERCIAL',
            'REFERENCIA_COMERCIAL',
            'REFERENCIA',
            'REFERENCIA_COMBINACION',
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
        ]);
    }

    public function headings(): array
    {
        return [
            'NOMBRE GUANXE',
            'NOMBRE COMERCIAL',
            'REFERENCIA COMERCIAL',
            'REFERENCIA',
            'REFERENCIA COMBINACION',
            'GENERO',
            'COLOR',
            'TALLA',
            'STOCK',
            'PRECIO BASE',
            'DESCRIPCION LARGA',
            'DESCRIPCION CORTA',
            'CATEGORIA',
            'CATEGORIA POR DEFECTO',
            'MARCA FABRICANTE',
            'RUTA IMAGENES',
            'ESTADO',
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ";"
        ];
    }
}
