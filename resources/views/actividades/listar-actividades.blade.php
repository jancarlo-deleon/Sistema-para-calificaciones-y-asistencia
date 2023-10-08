@extends('adminlte::page')

@section('title', 'Listar Actividades')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Lista de Actividades</h1>

@stop

@section('content')




    <div class="card">
        <div class="card-body">
            <div class="row">

                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Profesor a Cargo</span>
                        <input type="text" disabled class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-default" name="nombreEstudiante"
                            value="{{ Auth::user()->idUsuario . ' - ' . Auth::user()->nombreProfesor }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Grado</span>
                        <input type="text" disabled class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-default" name="grado"
                            value="{{ Auth::user()->grado->nombreGrado }} - {{ Auth::user()->grado->seccion }}">
                    </div>
                </div>

            </div>

            <div class="row" style="justify-content: center">
                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelectCurso">Curso</label>
                        <select required class="form-select" id="inputGroupSelectCurso">
                            <option disabled selected></option>
                            @foreach ($cursos as $curso)
                                <option value="{{ $curso->idCurso }}">{{ $curso->idCurso }} - {{ $curso->nombreCurso }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

        </div>
    </div>




    <div class="card">
        <div class="card-header">
            Actividades
        </div>
        <div class="card-body">



            <table id="listaEstudiantes" class="table table-no-border text-center">
                <thead>
                    <tr style="text-align: center">
                        <th scope="col">No.</th>
                        <th scope="col">Nombre de la Actividad</th>
                        <th scope="col">Descripcion</th>
                        <th scope="col">Calificación Obtenible</th>

                    </tr>
                </thead>

                <tbody>
                    {{-- @foreach ($actividades as $actividad)
                    <tr style="text-align: center">
                        <th scope="row">{{ $loop->index + 1 }}</th>
                        <td>{{ $actividad->nombreActividad }} </td>
                        <td>{{ $actividad->descripcionActividad}}</td>
                        <td>{{ $actividad->calificacionObtenible}}</td>
                        <td>{{ $actividad->created_at }}</td>
                    </tr>
                @endforeach --}}

                </tbody>
            </table>





        </div>
        <div class="card-footer">
            Lista de Actividades
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
            var table; // Variable para almacenar la referencia a la DataTable

            function cargarActividades(idCurso) {
                // Limpia la tabla completamente
                $('#listaEstudiantes').empty();
                $('#listaEstudiantes').append(
                    '<thead><tr style="text-align: center"><th scope="col">No.</th><th scope="col">Nombre de la Actividad</th><th scope="col">Descripcion</th><th scope="col">Calificación Obtenible</th></tr></thead>'
                    );
                $('#listaEstudiantes').append('<tbody></tbody>');

                // Verifica si se ha seleccionado un curso
                if (!idCurso) {
                    return;
                }

                // Destruye la DataTable existente, si existe
                if (table) {
                    table.destroy();
                }

                // Realiza una solicitud AJAX para obtener las actividades del curso
                $.ajax({
                    url: '/obtener-actividades-por-curso/' + idCurso,
                    type: 'GET',
                    success: function(data) {
                        // Llena la tabla con los datos de las actividades
                        $.each(data.actividades, function(index, actividad) {
                            var fila = '<tr style="text-align: center">';
                            fila += '<th scope="row">' + (index + 1) + '</th>';
                            fila += '<td>' + actividad.nombreActividad + '</td>';
                            fila += '<td>' + actividad.descripcionActividad + '</td>';
                            fila += '<td>' + actividad.calificacionObtenible + '</td>';
                            fila += '</tr>';
                            $('#listaEstudiantes tbody').append(fila);
                        });

                        // Inicializa DataTable después de cargar los datos
                        table = $('#listaEstudiantes').DataTable({
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json',
                            },
                            dom: 'Bfrtip',
                            buttons: [{
                                    extend: 'pdfHtml5',
                                    text: 'Exportar a PDF',
                                },
                                {
                                    extend: 'print',
                                    text: 'Imprimir',
                                }
                            ],
                        });
                    },
                    error: function(xhr, status, error) {
                        var errorMessage = 'Hubo un error al cargar las actividades: ' + error;
                        console.error(errorMessage);
                        alert(errorMessage);
                    }
                });
            }

            // Cuando se cambia la selección de curso
            $('#inputGroupSelectCurso').change(function() {
                var idCurso = $(this).val();
                cargarActividades(idCurso);
            });
        });
    </script>



@endsection
