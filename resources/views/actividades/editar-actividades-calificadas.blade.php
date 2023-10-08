@extends('adminlte::page')

@section('title', 'Editar Calificaciones')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Editar Calificaciones</h1>
@stop

@section('content')

    <form action="{{ route('updatecali-editar-actividades') }}" method="post" id="editar-calificaciones">
        @csrf
        @method('put')

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
                <div class="text-center">
                    <button type="submit" class="btn btn-info btn-lg" id="calificarBtn">
                        Guardar Cambios
                    </button>
                </div>
            </div>
        </div>



    </form>
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
                        // Limpiar el cuerpo de la tabla
                        $('#calificarActividades tbody').empty();

                        // Llenar la tabla con los datos obtenidos
                        $.each(data, function(index, row) {
                            var newRow = '<tr style="text-align: center">';
                            newRow += '<th scope="row">' + (index + 1) + '</th>';
                            newRow += '<td>' + row.estudiante.nombreEstudiante + ' ' +
                                row.estudiante.apellidosEstudiante +
                                '</td>';
                            newRow +=
                                '<td><input type="number" class="form-control" name="calificacionObtenida[]" value="' +
                                row.calificacionObtenida + '"></td>';
                            newRow += '<td>' + row.actividade.calificacionObtenible +
                                '</td>';
                            newRow +=
                                '<td><textarea class="form-control" name="comentario[]">' +
                                (row.comentario !== null ? row
                                    .comentario : '') + '</textarea></td>';
                            // Agrega un campo oculto con el idEstudiante
                            newRow +=
                                '<input type="hidden" name="idEstudiante[]" value="' +
                                row.estudiante.idEstudiante + '">';
                            newRow += '</tr>';

                            $('#calificarActividades tbody').append(newRow);
                        });

                        $('input[name="calificacionObtenida[]"]').on('input', function() {
                            console.log('Evento input activado');
                            var $input = $(this);
                            var calificacionObtenida = parseFloat($input.val()); // Convertir el valor a número

                            // Obtener el contenido de la columna calificacionObtenible y convertirlo a número
                            var calificacionObtenibleText = $input.closest('tr').find('td:eq(2)').text().trim();
                            var calificacionObtenible = parseFloat(calificacionObtenibleText);

                            console.log('Calificación Obtenida:', calificacionObtenida);
                            console.log('Calificación Obtenible:', calificacionObtenibleText);

                            // Verificar si la calificación obtenida es mayor que la calificación obtenible
                            if (!isNaN(calificacionObtenida) && !isNaN(calificacionObtenible) && calificacionObtenida > calificacionObtenible) {
                                // Mostrar una alerta de error
                                Swal.fire({
                                    title: 'Error',
                                    text: 'La calificación obtenida no puede ser mayor que la calificación obtenible.',
                                    icon: 'error',
                                    showConfirmButton: false,
                                    timer: 1500 // Duración de la alerta en milisegundos
                                });

                                // Limpiar el campo de calificación
                                $input.val('');
                            }
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



            // Agregar un evento al botón "Calificar"
            $('#calificarBtn').click(function(e) {
                e.preventDefault(); // Evitar el envío del formulario por defecto

                // Verificar si se ha seleccionado una curso
                if ($('#inputGroupSelectCurso').val() === null) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, selecciona una curso.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si no se ha seleccionado un curso
                }

                // Verificar si se ha seleccionado una actividad
                if ($('#inputGroupSelectActividadParaTabla').val() === null) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, selecciona una actividad a calificar.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si no se ha seleccionado una actividad
                }


                // Verificar si algún campo de calificación está vacío
                var calificacionObtenida = $('input[name="calificacionObtenida[]"]');
                var isEmpty = false;
                calificacionObtenida.each(function() {
                    if ($(this).val() === '') {
                        isEmpty = true;
                        return false; // Salir del bucle si se encontró un campo vacío
                    }
                });

                if (isEmpty) {
                    // Mostrar una alerta de error si se encontró un campo de calificación vacío
                    Swal.fire({
                        title: 'Advertencia',
                        text: 'Por favor, complete todas las calificaciones obtenidas.',
                        icon: 'warning',
                    });
                    return; // Detener el proceso si hay un campo de calificación vacío
                }

                // Mostrar la alerta de éxito
                Swal.fire({
                    title: 'Éxito',
                    text: 'El estudiante ha recibido una calificación.',
                    icon: 'success',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario hizo clic en "Aceptar", enviar el formulario de edición
                        $('#editar-calificaciones').submit();
                    }
                });

            });

        });
    </script>

@stop
