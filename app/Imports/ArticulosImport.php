<?php

namespace App\Imports;

use App\Models\Articulo;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ArticulosImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    /**
    * @param array $row
    *
    * @return Articulo
    */
    public function model(array $row)
    {
        //dd($row);
        return new Articulo([
            'NOMBRE_GUANXE' => $row['nombre_guanxe'],
            'NOMBRE_COMERCIAL' => $row['nombre_comercial'],
            'REFERENCIA_COMERCIAL' => $row['referencia_comercial'],
            'REFERENCIA' => $row['referencia'],
            'REFERENCIA_COMBINACION' => $row['referencia_combinacion'],
            //'TIPO' => $row['tipo'],
            'GENERO' => $row['genero'],
            'COLOR' => $row['color'],
            'TALLA' => $row['talla'],
            'STOCK' => $row['stock'],
            'PRECIO_BASE' => $row['precio_base'],
            'DESCRIPCION_LARGA' => $row['descripcion_larga'],
            'DESCRIPCION_CORTA' => $row['descripcion_corta'],
            'CATEGORIA' => $row['categoria'],
            'CATEGORIA_POR_DEFECTO' => $row['categoria_por_defecto'],
            'MARCA_FABRICANTE' => $row['marca_fabricante'],
            //'RUTA_IMAGENES' => $row['ruta_de_las_imagenes'],
            'ESTADO' => $row['estado'],
        ]);
    }

    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ";"
        ];
    }
}
