<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-type" content="text/html"; charset="utf-8" />
        <title>Artículos</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/fixedheader/3.1.8/css/fixedHeader.dataTables.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.7/css/responsive.dataTables.min.css">


    </head>

    <body>
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

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
        <table id="table" class="table table-bordered">
            <thead>
                <tr>
                    <th>IMAGEN</th>
                    <th>NOMBRE GUANXE</th>
                    <th>NOMBRE COMERCIAL</th>
                    <th>REFERENCIA COMERCIAL</th>
{{--                    <th>REFERENCIA</th>--}}
{{--                    <th>REFERENCIA COMBINACION</th>--}}
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
                    <tr id="fila{{ $articulo->id }}">
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
{{--                        <td>{{ $articulo->REFERENCIA }}</td>--}}
{{--                        <td>{{ $articulo->REFERENCIA_COMBINACION }}</td>--}}
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
                                <div class="form-group mb-5" style="margin: 0 auto;">
                                    <div class="custom-file text-left">
                                        <input id="id_articulo" name="id_articulo" type="hidden" value="{{ $articulo->id }}">
                                        <input type="file" name="imageFile[]" class="custom-file-input" id="imagenes" multiple="multiple">
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

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/fixedheader/3.1.8/js/dataTables.fixedHeader.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.2.7/js/dataTables.responsive.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.0/jquery.validate.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<script>
    $(function(){
        $(document).ready(function() {
            $('#table').DataTable({
                autoWidth: true,
                fixedHeader: true,
                lengthChange: true, //Permitir cambiar ancho columnas7
                responsive: {
                    details: false
                },
                "order": [],
                columns: [
                    { orderable: false },
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { orderable: false },
                ]
            });
        } );

        $(document).on('click', '#imagenes', function(){
            var id_articulo = $(this).siblings().val();
            console.log(id_articulo);
            $('tr').removeAttr('style');
            $('#fila'+id_articulo).css('background-color', 'aliceblue');
        });
    });
</script>

</html>
