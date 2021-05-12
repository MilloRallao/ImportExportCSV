<?php

namespace App\Exports;

use App\Models\Articulo;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\DefaultValueBinder;

class ArticulosExport extends DefaultValueBinder implements FromQuery, WithHeadings, WithCustomCsvSettings, ShouldAutoSize, WithMapping, WithCustomValueBinder
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
            'RUTA DE LAS IMÁGENES',
            'ESTADO',
        ];
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ";",
            'enclosure' => ""
        ];
    }

    public function map($articulo): array
    {
        //dd($articulo->RUTA_IMAGENES);
        return [
            $articulo->NOMBRE_GUANXE,
            $articulo->NOMBRE_COMERCIAL,
            $articulo->REFERENCIA_COMERCIAL,
            $articulo->REFERENCIA,
            $articulo->REFERENCIA_COMBINACION,
            $articulo->GENERO,
            $articulo->COLOR,
            $articulo->TALLA,
            $articulo->STOCK,
            $articulo->PRECIO_BASE,
            $articulo->DESCRIPCION_LARGA,
            $articulo->DESCRIPCION_CORTA,
            $articulo->CATEGORIA,
            $articulo->CATEGORIA_POR_DEFECTO,
            $articulo->MARCA_FABRICANTE,
            $articulo->RUTA_IMAGENES = is_null($articulo->RUTA_IMAGENES) ? '' : str_replace(['[', ']', '\\', '"'], '', json_encode($articulo->RUTA_IMAGENES)),
            $articulo->ESTADO,
        ];
    }

    public function bindValue(Cell $cell, $value)
    {
        $value = mb_convert_encoding($value, "UTF-8");
        return parent::bindValue($cell, $value);
    }
}
