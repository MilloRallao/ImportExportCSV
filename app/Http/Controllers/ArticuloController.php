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

        // Diferentes colores de los productos con la misma referencia que el art??culo inicial original
//        $colores = Articulo::where('REFERENCIA', $referencia)->distinct()->pluck('COLOR');

        // Art??culo inicial original
        $articulo = Articulo::find($request->id_articulo);

        // Referencia del art??culo inicial original
        $referencia = $articulo->REFERENCIA;

        // Color del art??culo inicial original
        $color = $articulo->COLOR;

        // Todos los art??culos con la misma referencia que el art??culo inicial original
        $articulos = Articulo::where('REFERENCIA', $referencia)->get();

        // Todos los art??culos con la misma referencia y color que el art??culo inicial original
        $articulosColor = Articulo::where('REFERENCIA', $referencia)->where('COLOR', $color)->get();

        // Contador de im??genes
        $countImages = 0;

        // Si existen im??genes
        if ($request->hasfile('imageFile')) {
            // Recorrer cada imagen y guardarla en un array
            foreach ($request->file('imageFile') as $file) {
                $countImages++;

                // Nombre de cada imagen
                $name = str_replace([' ', '-'], '', substr($articulo->REFERENCIA_COMERCIAL, 4, 15)) . '-' . $countImages . '.' . $file->getClientOriginalExtension();

                // Rutas de las im??genes
                $imgsData[] = '/img/' . str_replace([' ', '-'], '', substr($articulo->REFERENCIA_COMERCIAL, 4, 15)) . '-' . $countImages . '.' . $file->getClientOriginalExtension();

                // Guardar cada imagen en carpeta /public/img
                $file->move(public_path('img'), $name);
            }
        }

        // Cuando se suban m??s de 3 im??genes, se aplicar?? el m??todo de subida masiva de im??genes de manera autom??tica, separando las im??genes por grupos seg??n los colores de un mismo art??culo
        if ($countImages > 3) {
            // Separar el array contenedor de todas las im??genes en m??ltiples arrays de 3 posiciones (3 im??genes por art??culo)
            $newImgsData = array_chunk($imgsData, 3);

            // Numero de arrays de 3 im??genes
            $newImgsDataNumber = count($newImgsData);

            // Separar la colecci??n de todos los art??culos de una misma referencia en varias colecciones de 6 art??culos (6 tallas por art??culo)
            $newArticulos = $articulos->chunk(6);

            // Cantidad de arrays de art??culos agrupados cada 6 tallas
            $newArticulosNumber = count($newArticulos);

            for ($i = 0; $i < $newImgsDataNumber; $i++) {
                // Recorrer cada grupo de 6 art??culos para sacar sus colecciones y guardar en cada coleccion las rutas de las 3 im??genes correspondientes
                foreach ($newArticulos[$i] as $newArticulo) {
                    $newArticulo->RUTA_IMAGENES = $newImgsData[$i];
                    $newArticulo->save();
                }
            }
        } elseif ($countImages <= 3) { // Cuando se suban 3 o menos im??genes, se aplicar?? el m??todo de suba manual de im??genes, asignando el grupo de im??genes a un determinado art??culo de un ??nico color
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
