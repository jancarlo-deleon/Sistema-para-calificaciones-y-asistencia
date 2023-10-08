@extends('adminlte::page')

@section('title', 'Listar Estudiantes')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Lista de Estudiantes</h1>

@stop

@section('content')




    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Profesor a Cargo</span>
                        <input type="text" disabled class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-default" name="nombreProfesor"
                            value="{{ Auth::user()->idUsuario . ' - ' . Auth::user()->nombreProfesor }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Grado</span>
                        <input type="text" disabled class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-default" name="grado"
                            value="{{ Auth::user()->grado->nombreGrado }}">
                    </div>
                </div>

            </div>
        </div>
    </div>




    <div class="card">
        <div class="card-header">
            Estudiantes
        </div>
        <div class="card-body">



            <table id="listaEstudiantes" class="table table-no-border text-center">
                <thead>
                    <tr style="text-align: center">
                        <th scope="col">No.</th>
                        <th scope="col">Nombre Completo</th>
                        <th scope="col">Genero</th>
                        <th scope="col">Nombre Completo del Encargado</th>
                        <th scope="col">Telefono #1 del Encargado</th>
                        <th scope="col">Telefono #2 del Encargado</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($estudiantes as $estudiante)
                        <tr style="text-align: center">
                            <th scope="row">{{ $loop->index + 1 }}</th>
                            <td>{{ $estudiante->nombreEstudiante }} {{ $estudiante->apellidosEstudiante }}</td>
                            <td>{{ $estudiante->genero }}</td>
                            <td>{{ $estudiante->nombreEncargado }} {{ $estudiante->apellidosEncargado }}</td>
                            <td>{{ $estudiante->telefono1 }}</td>
                            <td>{{ $estudiante->telefono2 }}</td>
                        </tr>
                    @endforeach

                </tbody>
            </table>





        </div>
        <div class="card-footer">
            Lista de Estudiantes
        </div>
    </div>






@stop

@section('css')


    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">


@endsection

@section('js')

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>



    <script>
        $(document).ready(function() {
            // Inicializa la tabla DataTable
            var table = $('#listaEstudiantes').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json',
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'Exportar a PDF',

                    },

                    {
                        extend: 'excelHtml5',
                        text: 'Exportar a Excel',

                    },

                    {
                        extend: 'print',
                        text: 'Imprimir',

                    }
                ]
            });


        });
    </script>


@endsection
