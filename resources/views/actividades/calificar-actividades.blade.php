@extends('adminlte::page')

@section('title', 'Calificar Actividades')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Calificar Actividades</h1>
@stop

@section('content')

    <form action="{{ route('almacecali-calificar-actividad') }}" method="post" id="calificar-actividades">
        @csrf

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
                            <th scope="col" class="text-center">Nombre Completo</th>
                            <th scope="col" class="text-center">Calificacion Obtenida</th>
                            <th scope="col" class="text-center">Calificacion Obtenible</th>
                            <th scope="col" class="text-center">Comentarios</th>
                        </tr>
                    </thead>
                    <tbody style="text-align: center;">
                        @foreach ($estudiantes as $index => $estudiante)
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $estudiante->nombreEstudiante }}
                                    {{ $estudiante->apellidosEstudiante }}</td>
                                <td style="text-align: center;">
                                    <input type="hidden" name="idEstudiante[]" value="{{ $estudiante->idEstudiante }}">
                                    <input type="number" name="calificacionObtenida[]" class="form-control" step="0.01"
                                        id="calificacionObtenida" min="0" max="100"
                                        style="margin: 0 auto; display: block;">
                                </td>
                                <td class="calificacion-obtenible text-center"></td>
                                <td class="text-center">
                                    <textarea name="comentario[]" class="form-control"></textarea>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>


                </table>


            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-info btn-lg" id="calificarBtn">
                        Calificar Actividad
                    </button>
                </div>
            </div>

        </div>

    </form>
@stop

@section('css')

@stop

@section('js')


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>

    <script>
        $(document).ready(function() {

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





            // Manejar el cambio en el select
            $('#inputGroupSelectActividadParaTabla').change(function() {
                var calificacionObtenible = $(this).find(':selected').data('calificacionobtenible');
                console.log("la calificacionObtenible es ",calificacionObtenible);

                // Actualizar todas las celdas con la clase "calificacion-obtenible"
                $('.calificacion-obtenible').text(calificacionObtenible);

                // Limpiar los campos de calificación obtenida y comentarios
                $('input[name="calificacionObtenida[]"]').val('');
                $('textarea[name="comentario[]"]').val('');
            });


            // Manejar el evento input en los campos de calificación obtenida
            $('input[name="calificacionObtenida[]"]').on('input', function() {
                var calificacionObtenible = parseFloat($(this).closest('tr').find('.calificacion-obtenible')
                    .text());
                var calificacionIngresada = parseFloat($(this).val());

                if (isNaN(calificacionIngresada)) {
                    return; // No hacer nada si no es un número válido
                }

                if (calificacionIngresada > calificacionObtenible) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'La calificación obtenida no puede ser mayor que la calificación obtenible.',
                        icon: 'error',
                    });

                    // Limpiar el input
                    $(this).val('');
                }
            });


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
                    text: 'La actividad se ha guardado correctamente.',
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
