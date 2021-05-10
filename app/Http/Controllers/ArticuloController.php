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
        //dd($request->file('file'));
        //Excel::import(new ArticulosImport(), $request->file('file')->store('temp'), null, \Maatwebsite\Excel\Excel::CSV);

        $articulos = Excel::toCollection(new ArticulosImport, $request->file('file'), null, \Maatwebsite\Excel\Excel::CSV);

        if ($articulos[0]) {
            foreach ($articulos[0] as $articulo) {
                //Comprobar si hay valores nuevos que insertar, si no, actualizar
                DB::table('articulos')
                    ->updateOrInsert(
                        ['REFERENCIA_COMERCIAL' => $articulo['referencia_comercial']],
                        [
                            'NOMBRE_GUANXE' => $articulo['nombre_guanxe'],
                            'NOMBRE_COMERCIAL' => $articulo['nombre_comercial'],
                            'REFERENCIA_COMERCIAL' => $articulo['referencia_comercial'],
                            'REFERENCIA' => $articulo['referencia'],
                            'REFERENCIA_COMBINACION' => $articulo['referencia_combinacion'],
                            'GENERO' => $articulo['genero'],
                            'COLOR' => $articulo['color'],
                            'TALLA' => $articulo['talla'],
                            'STOCK' => $articulo['stock'],
                            'PRECIO_BASE' => $articulo['precio_base'],
                            'DESCRIPCION_LARGA' => $articulo['descripcion_larga'],
                            'DESCRIPCION_CORTA' => $articulo['descripcion_corta'],
                            'CATEGORIA_POR_DEFECTO' => $articulo['categoria_por_defecto'],
                            'MARCA_FABRICANTE' => $articulo['marca_fabricante'],
                            'ESTADO' => $articulo['estado'],
                        ]
                    );
            }
        }

        return back();
    }

    public function exportFile()
    {
        return Excel::download(new ArticulosExport(), 'CSV_nuevo.csv');
    }

    public function uploadImages(Request $request)
    {
        $request->validate([
            'imageFile' => 'required',
            'imageFile.*' => 'mimes:jpeg,jpg,png|max:2048'
        ]);

        $articulo = Articulo::find($request->id_articulo);

        $count = 0;

        if ($request->hasfile('imageFile')) {
            foreach ($request->file('imageFile') as $file) {
                //$name = $file->getClientOriginalName();
                $count++;
                $name = str_replace([' ', '-'], '', substr($articulo->REFERENCIA_COMERCIAL, 4, 15)).'-'.$articulo->COLOR.'-'.$count.'.'.$file->getClientOriginalExtension();
                $ruta = '/img/'.str_replace([' ', '-'], '', substr($articulo->REFERENCIA_COMERCIAL, 4, 15)).'-'.$articulo->COLOR.'-'.$count.'.'.$file->getClientOriginalExtension();
                $file->move(public_path('img'), $name);
                $imgData[] = $ruta;
            }

            //$imgDataEncode = json_encode($imgData);

            $articulo->RUTA_IMAGENES = $imgData;

            //dd(json_decode($imgDataEncode));

            $articulo->save();

            return back();
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
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
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Models\Articulo $articulo
     * @return \Illuminate\Http\Response
     */
    public function show(Articulo $articulo)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Articulo $articulo
     * @return \Illuminate\Http\Response
     */
    public function edit(Articulo $articulo)
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
    public function update(Request $request, Articulo $articulo)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Models\Articulo $articulo
     * @return \Illuminate\Http\Response
     */
    public function destroy(Articulo $articulo)
    {
        //
    }
}
