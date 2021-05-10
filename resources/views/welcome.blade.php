<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Artículos</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    </head>

    <body>

    <div class="container mt-5 text-center">
        <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group mb-5" style="max-width: 600px; margin: 0 auto;">
                <div class="custom-file text-left">
                    <input type="file" name="file" class="custom-file-input" id="customFile">
                    <label class="custom-file-label" for="customFile">Browse file</label>
                </div>
            </div>
            <button class="btn btn-danger">Click to Import</button>
            <a class="btn btn-primary" href="{{ route('export') }}">Click to Export</a>
        </form>
    </div>

    <div class="container-fluid mt-5">
        <h2 class="mb-4 text-center">Artículos</h2>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>IMAGEN</th>
                    <th>NOMBRE GUANXE</th>
                    <th>NOMBRE COMERCIAL</th>
                    <th>REFERENCIA COMERCIAL</th>
                    <th>REFERENCIA</th>
                    <th>REFERENCIA COMBINACION</th>
                    <th>GENERO</th>
                    <th>COLOR</th>
                    <th>TALLA</th>
                    <th>STOCK</th>
                    <th>PRECIO BASE</th>
{{--                    <th>DESCRIPCION LARGA</th>--}}
{{--                    <th>DESCRIPCION CORTA</th>--}}
                    <th>CATEGORIA</th>
{{--                    <th>CATEGORIA POR DEFECTO</th>--}}
                    <th>MARCA FABRICANTE</th>
{{--                    <th>RUTA IMAGENES</th>--}}
                    <th>ESTADO</th>
                    <th>UPLOAD</th>
                </tr>
            </thead>
            <tbody>
                @foreach($articulos as $articulo)
                    <tr>
                        <td>
                            @isset($articulo->RUTA_IMAGENES)
                                @foreach($articulo->RUTA_IMAGENES as $imagen)
                                    <img class="img-thumbnail" src="{{ $imagen }}">
                                @endforeach
                            @endisset
                        </td>
                        <td>{{ $articulo->NOMBRE_GUANXE }}</td>
                        <td>{{ $articulo->NOMBRE_COMERCIAL }}</td>
                        <td>{{ $articulo->REFERENCIA_COMERCIAL }}</td>
                        <td>{{ $articulo->REFERENCIA }}</td>
                        <td>{{ $articulo->REFERENCIA_COMBINACION }}</td>
                        <td>{{ $articulo->GENERO }}</td>
                        <td>{{ $articulo->COLOR }}</td>
                        <td>{{ $articulo->TALLA }}</td>
                        <td>{{ $articulo->STOCK }}</td>
                        <td>{{ $articulo->PRECIO_BASE }}</td>
{{--                        <td>{{ $articulo->DESCRIPCION_LARGA }}</td>--}}
{{--                        <td>{{ $articulo->DESCRIPCION_CORTA }}</td>--}}
                        <td>{{ $articulo->CATEGORIA }}</td>
{{--                        <td>{{ $articulo->CATEGORIA_POR_DEFECTO }}</td>--}}
                        <td>{{ $articulo->MARCA_FABRICANTE }}</td>
{{--                        <td>{{ json_encode($articulo->RUTA_IMAGENES) }}</td>--}}
                        <td>{{ $articulo->ESTADO }}</td>
                        <td>
                            <form action="{{ route('upload') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="form-group mb-5" style="max-width: 200px; margin: 0 auto;">
                                    <div class="custom-file text-left">
                                        <input id="id_articulo" name="id_articulo" type="hidden" value="{{ $articulo->id }}">
                                        <input type="file" name="imageFile[]" class="custom-file-input" id="customFile" multiple="multiple">
                                        <label class="custom-file-label" for="customFile">Browse images</label>
                                    </div>
                                </div>
                                <button class="btn btn-danger">Click to upload</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    </body>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>

</html>
