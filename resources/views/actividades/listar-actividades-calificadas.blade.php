@extends('adminlte::page')

@section('title', 'Listar Actividades Calificadas')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Lista de Actividades Calificadas</h1>
@stop

@section('content')



    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Profesor</span>
                        <input type="text" disabled class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-default"
                            value="{{ Auth::user()->idUsuario . ' - ' . Auth::user()->nombreProfesor }}">
                    </div>
                </div>




                <div class="col-md-5">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="inputGroup-sizing-default">Grado</span>
                        <input type="text" disabled class="form-control" aria-label="Sizing example input"
                            aria-describedby="inputGroup-sizing-default" name="grado"
                            value="{{ Auth::user()->grado->nombreGrado . ' - ' . Auth::user()->grado->seccion }}">
                    </div>
                </div>

            </div>

            <div class="row" style="justify-content: center">

                <div class="col-md-4">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelectCurso">Curso</label>
                        <select class="form-select" id="inputGroupSelectCurso">
                            <option disabled selected></option>
                            @foreach ($cursos as $curso)
                                <option value="{{ $curso->idCurso }}">{{ $curso->idCurso }} - {{ $curso->nombreCurso }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="input-group mb-3">
                        <label class="input-group-text" for="inputGroupSelectActividadParaTabla">Seleccione una
                            actividad</label>
                        <select required class="form-select" id="inputGroupSelectActividadParaTabla" name="idActividad">
                            <option disabled selected></option>
                            {{-- @foreach ($actividades as $actividad)
                                <option value="{{ $actividad->idActividad }}"
                                    data-calificacionobtenible="{{ $actividad->calificacionObtenible }}">
                                    {{ $actividad->nombreActividad }}
                                </option>
                            @endforeach --}}
                        </select>

                    </div>
                </div>

            </div>
        </div>
    </div>




    <div class="card">
        <div class="card-header">
            Calificar Actividades
        </div>
        <div class="card-body">



            <table id="calificarActividades" class="table table-no-border">
                <thead>
                    <tr>
                        <th scope="col" class="text-center">No.</th>
                        <th scope="col" class="text-center">Nombre Estudiante</th>
                        <th scope="col" class="text-center">Calificacion Obtenida</th>
                        <th scope="col" class="text-center">Calificacion Obtenible</th>
                        <th scope="col" class="text-center">Comentarios</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Aquí se mostrarán los datos -->
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
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

            var dataTable = null; // Declarar dataTable en este ámbito para que sea accesible en toda la función

            // Cuando se cambia la selección del curso
            $('#inputGroupSelectCurso').change(function() {
                var cursoId = $(this).val();

                console.log('ID del curso seleccionado:', cursoId);
                // Actualiza el valor del campo oculto actividadId
                $('#cursoId').val(cursoId);

                // Verificar si se ha seleccionado un curso
                if (cursoId !== null) {
                    // Especifica la URL para obtener las actividades del curso seleccionado
                    var url = '/obtener-actividades-por-curso/' + cursoId;

                    // Realiza una solicitud AJAX para obtener las actividades del curso seleccionado
                    $.ajax({
                        url: url,
                        type: 'GET',
                        success: function(data) {
                            // Vacía el select "inputGroupSelect01"
                            $('#inputGroupSelectActividadParaTabla').empty();

                            // Agrega la opción deshabilitada y seleccionada al principio
                            $('#inputGroupSelectActividadParaTabla').append($('<option>', {
                                disabled: true,
                                selected: true,
                                value: '',
                                text: ''
                            }));

                            // Agrega las opciones de actividades al select "inputGroupSelect01"
                            $.each(data.actividades, function(index, actividad) {
                                $('#inputGroupSelectActividadParaTabla').append($('<option>', {
                                    value: actividad.idActividad,
                                    text: actividad.nombreActividad,
                                    'data-calificacionobtenible': actividad.calificacionObtenible
                                }));
                            });
                        },
                        error: function(xhr, status, error) {
                            var errorMessage =
                                'Hubo un error al obtener las actividades: ' + error;
                            console.error(errorMessage);
                            alert(errorMessage);
                        }
                    });
                } else {
                    // Si no se ha seleccionado un curso, vacía el select "inputGroupSelect01"
                    $('#inputGroupSelectActividadParaTabla').empty();
                }
            });

            $('#inputGroupSelectActividadParaTabla').change(function() {
                // Obtener el valor seleccionado
                var selectedActividadParaTablaId = $(this).val();

                // Realizar una solicitud AJAX para obtener los datos relacionados con la actividad
                $.ajax({
                    url: "{{ route('obtener-datos-de-actividad') }}",
                    method: 'GET',
                    data: {
                        actividadId: selectedActividadParaTablaId
                    },
                    success: function(data) {
                        // Limpiar la tabla y destruir la instancia de DataTable si existe
                        if (dataTable !== null) {
                            dataTable.clear().destroy();
                        }

                        // Limpiar el cuerpo de la tabla
                        $('#calificarActividades tbody').empty();

                        // Llenar la tabla con los datos obtenidos
                        $.each(data, function(index, row) {
                            var newRow = '<tr style="text-align: center">';
                            newRow += '<th scope="row">' + (index + 1) + '</th>';
                            newRow += '<td>' + row.estudiante.nombreEstudiante + ' ' +
                                row.estudiante.apellidosEstudiante +
                                '</td>'; // Aquí se accede al nombre del estudiante
                            newRow += '<td>' + row.calificacionObtenida + '</td>';
                            newRow += '<td>' + row.actividade.calificacionObtenible +
                                '</td>';
                            // Verificar si el comentario es NULL y mostrar una cadena vacía en su lugar
                            newRow += '<td>' + (row.comentario !== null ? row
                                .comentario : '') + '</td>';

                            newRow += '</tr>';

                            $('#calificarActividades tbody').append(newRow);
                        });


                        // Inicializar DataTable en la tabla existente
                        dataTable = new DataTable('#calificarActividades', {
                            language: {
                                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json',
                            },
                            dom: 'Bfrtip',
                            buttons: [{
                                extend: 'pdfHtml5',
                                text: 'Exportar a PDF',
                            }, {
                                extend: 'print',
                                text: 'Imprimir',
                            }]
                        });

                    },
                    error: function() {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Hubo un error al obtener los datos de la actividad.',
                        });
                    }
                });
            });


            $('#inputGroupSelectActividad').change(function() {
                var selectedCalificacion = $(this).find('option:selected').data('calificacion');
                console.log("Calificación seleccionada: " + selectedCalificacion);
                $('#calificacionObtenible').val(selectedCalificacion);

                // Capturar el valor del select de actividad y asignarlo al campo oculto
                var selectedActividadId = $(this).val();
                $('#actividadId').val(selectedActividadId);
            });

            $('#inputGroupSelectEstudiante').change(function() {
                // Capturar el valor del select de estudiante y asignarlo al campo oculto
                var selectedEstudianteId = $(this).val();
                $('#estudianteId').val(selectedEstudianteId);
            });

            $('#calificacionObtenida').on('input', function() {
                var calificacionObtenida = parseFloat($(this).val());
                var calificacionObtenible = parseFloat($('#calificacionObtenible').val());

                if (calificacionObtenida > calificacionObtenible) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'La calificación obtenida no puede ser mayor que la calificación obtenible.',
                    }).then(function() {
                        // Limpia el input y establece el valor de calificacionObtenible
                        $('#calificacionObtenida').val('');
                    });
                }
            });


            // Agregar un evento al botón "Calificar"
            $('#calificarBtn').click(function(e) {
                e.preventDefault(); // Evitar el envío del formulario por defecto



                // Verificar si se ha seleccionado una actividad
                if ($('#inputGroupSelectActividad').val() === null) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, seleccione una actividad a calificar.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si no se ha seleccionado una actividad
                }

                // Verificar si se ha seleccionado un estudiante
                if ($('#inputGroupSelectEstudiante').val() === null) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, seleccione un estudiante para asignarle una calificación .',
                        icon: 'error',
                    });
                    return; // Detener el proceso si no se ha seleccionado un estudiante
                }

                // Verificar si el campo de calificación obtenida está vacío
                if ($('#calificacionObtenida').val() === '') {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, ingrese la calificación obtenida antes de guardar la calificación.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si el campo está vacío
                }

                // Mostrar la alerta de éxito
                Swal.fire({
                    title: 'Éxito',
                    text: 'El estudiante ha recibido una calificación.',
                    icon: 'success',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario hizo clic en "Aceptar", enviar el formulario de edición
                        $('#calificar-actividades').submit();
                    }
                });

            });


        });
    </script>
@stop
