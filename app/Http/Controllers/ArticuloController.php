<?php

namespace App\Http\Controllers;

use App\Exports\ArticulosExport;
use App\Imports\ArticulosImport;
use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ArticuloController extends Controller
{

    public function importFile(Request $request)
    {
        set_time_limit(600);

        $request->validate([
            'file' => 'required|mimes:csv,txt'
        ]);

        //dd($request->file('file'));
        //Excel::import(new ArticulosImport(), $request->file('file')->store('temp'), null, \Maatwebsite\Excel\Excel::CSV);

        if ($request->hasFile('file')) {
            Articulo::truncate();
            Excel::import(new ArticulosImport, $request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);


//            if ($articulos[0]) {
//                //dd($articulos[0]);
//                foreach ($articulos[0] as $articulo) {
//                    if (isset($articulo['inombre_guanxe'])) {
//                        //dd(explode(',', $articulo['ruta_de_las_imagenes']));
//                        //Comprobar si hay valores nuevos que insertar, si no, actualizar
//                        DB::table('articulos')
//                            ->updateOrInsert(
//                                ['REFERENCIA_COMERCIAL' => $articulo['referencia_comercial']],
//                                [
//                                    'NOMBRE_GUANXE' => $articulo['inombre_guanxe'],
//                                    'NOMBRE_COMERCIAL' => $articulo['nombre_comercial'],
//                                    'REFERENCIA_COMERCIAL' => $articulo['referencia_comercial'],
//                                    'REFERENCIA' => $articulo['referencia'],
//                                    'REFERENCIA_COMBINACION' => $articulo['referencia_combinacion'],
//                                    'GENERO' => $articulo['genero'],
//                                    'COLOR' => $articulo['color'],
//                                    'TALLA' => $articulo['talla'],
//                                    'STOCK' => $articulo['stock'],
//                                    'PRECIO_BASE' => $articulo['precio_base'],
//                                    'DESCRIPCION_LARGA' => $articulo['descripcion_larga'],
//                                    'DESCRIPCION_CORTA' => $articulo['descripcion_corta'],
//                                    'CATEGORIA' => $articulo['categoria'],
//                                    'CATEGORIA_POR_DEFECTO' => $articulo['categoria_por_defecto'],
//                                    'MARCA_FABRICANTE' => $articulo['marca_fabricante'],
//                                    'RUTA_IMAGENES' => empty($articulo['ruta_de_las_imagenes']) ? null : json_encode(explode(',', $articulo['ruta_de_las_imagenes'])),
//                                    'ESTADO' => $articulo['estado'],
//                                ]
//                            );
//                    } else {
//                        //Comprobar si hay valores nuevos que insertar, si no, actualizar
//                        DB::table('articulos')
//                            ->updateOrInsert(
//                                ['REFERENCIA_COMERCIAL' => $articulo['referencia_comercial']],
//                                [
//                                    'NOMBRE_GUANXE' => $articulo['nombre_guanxe'],
//                                    'NOMBRE_COMERCIAL' => $articulo['nombre_comercial'],
//                                    'REFERENCIA_COMERCIAL' => $articulo['referencia_comercial'],
//                                    'REFERENCIA' => $articulo['referencia'],
//                                    'REFERENCIA_COMBINACION' => $articulo['referencia_combinacion'],
//                                    'GENERO' => $articulo['genero'],
//                                    'COLOR' => $articulo['color'],
//                                    'TALLA' => $articulo['talla'],
//                                    'STOCK' => $articulo['stock'],
//                                    'PRECIO_BASE' => $articulo['precio_base'],
//                                    'DESCRIPCION_LARGA' => $articulo['descripcion_larga'],
//                                    'DESCRIPCION_CORTA' => $articulo['descripcion_corta'],
//                                    'CATEGORIA' => $articulo['categoria'],
//                                    'CATEGORIA_POR_DEFECTO' => $articulo['categoria_por_defecto'],
//                                    'MARCA_FABRICANTE' => $articulo['marca_fabricante'],
//                                    'ESTADO' => $articulo['estado'],
//                                ]
//                            );
//                    }
//                }
//            }
        }

        return back();
    }

    public function exportFile()
    {
        set_time_limit(600);
//        $contents = Excel::raw(new ArticulosExport(), \Maatwebsite\Excel\Excel::CSV);

//        ob_end_clean();
//        ob_start();

//        $csvContent = mb_convert_encoding($contents, 'UTF-8', 'auto');

//        dd($csvContent);

        return Excel::download(new ArticulosExport(), 'CSV_nuevo.csv', \Maatwebsite\Excel\Excel::CSV);
    }

    public function uploadImages(Request $request)
    {
        set_time_limit(600);
        // Validar request
        $request->validate([
            'imageFile' => 'required',
            'imageFile.*' => 'mimes:jpeg,jpg,png|max:2048'
        ]);

        // Diferentes colores de los productos con la misma referencia que el artículo inicial original
//        $colores = Articulo::where('REFERENCIA', $referencia)->distinct()->pluck('COLOR');

        // Artículo inicial original
        $articulo = Articulo::find($request->id_articulo);

        // Referencia del artículo inicial original
        $referencia = $articulo->REFERENCIA;

        // Color del artículo inicial original
        $color = $articulo->COLOR;

        // Todos los artículos con la misma referencia que el artículo inicial original
        $articulos = Articulo::where('REFERENCIA', $referencia)->get();

        // Todos los artículos con la misma referencia y color que el artículo inicial original
        $articulosColor = Articulo::where('REFERENCIA', $referencia)->where('COLOR', $color)->get();

        // Contador de imágenes
        $countImages = 0;

        // Si existen imágenes
        if ($request->hasfile('imageFile')) {
            // Recorrer cada imagen y guardarla en un array
            foreach ($request->file('imageFile') as $file) {
                $countImages++;

                // Nombre de cada imagen
                $name = str_replace([' ', '-'], '', substr($articulo->REFERENCIA_COMERCIAL, 4, 15)) . '-' . $countImages . '.' . $file->getClientOriginalExtension();

                // Rutas de las imágenes
                $imgsData[] = '/img/' . str_replace([' ', '-'], '', substr($articulo->REFERENCIA_COMERCIAL, 4, 15)) . '-' . $countImages . '.' . $file->getClientOriginalExtension();

                // Guardar cada imagen en carpeta /public/img
                $file->move(public_path('img'), $name);
            }
        }

        // Cuando se suban más de 3 imágenes, se aplicará el método de subida masiva de imágenes de manera automática, separando las imágenes por grupos según los colores de un mismo artículo
        if ($countImages > 3) {
            // Separar el array contenedor de todas las imágenes en múltiples arrays de 3 posiciones (3 imágenes por artículo)
            $newImgsData = array_chunk($imgsData, 3);

            // Numero de arrays de 3 imágenes
            $newImgsDataNumber = count($newImgsData);

            // Separar la colección de todos los artículos de una misma referencia en varias colecciones de 6 artículos (6 tallas por artículo)
            $newArticulos = $articulos->chunk(6);

            // Cantidad de arrays de artículos agrupados cada 6 tallas
            $newArticulosNumber = count($newArticulos);

            for ($i = 0; $i < $newImgsDataNumber; $i++) {
                // Recorrer cada grupo de 6 artículos para sacar sus colecciones y guardar en cada coleccion las rutas de las 3 imágenes correspondientes
                foreach ($newArticulos[$i] as $newArticulo) {
                    $newArticulo->RUTA_IMAGENES = $newImgsData[$i];
                    $newArticulo->save();
                }
            }
        } elseif ($countImages <= 3) { // Cuando se suban 3 o menos imágenes, se aplicará el método de suba manual de imágenes, asignando el grupo de imágenes a un determinado artículo de un único color
            foreach ($articulosColor as $articuloColor){
                $articuloColor->RUTA_IMAGENES = $imgsData;
                $articuloColor->save();
            }
        }

        return back();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function index()
    {
        $articulos = Articulo::all();

        //dd(json_decode($articulos[0]->RUTA_IMAGENES, true));
        //dd(collect($articulos[0]->RUTA_IMAGENES));

        return view('welcome', compact('articulos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public
    function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public
    function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Articulo $articulo
     * @return \Illuminate\Http\Response
     */
    public
    function show(Articulo $articulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Articulo $articulo
     * @return \Illuminate\Http\Response
     */
    public
    function edit(Articulo $articulo)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Models\Articulo $articulo
     * @return \Illuminate\Http\Response
     */
    public
    function update(Request $request, Articulo $articulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Articulo $articulo
     * @return \Illuminate\Http\Response
     */
    public
    function destroy(Articulo $articulo)
    {
        //
    }
}
