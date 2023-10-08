@extends('adminlte::page')

@section('title', 'Editar Actividades')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Editar Actividades</h1>
@stop

@section('content')

    <form method="POST" action="{{ route('update-editar-actividades') }}" id="editar-actividades">
        @csrf
        @method('put')


        <div class="card">
            <div class="card-body">


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
                </div>

                <!-- Campo oculto para almacenar el ID de la actividad seleccionada -->
                <input type="hidden" id="cursoId" name="cursoId">

                <div class="row" style="justify-content: center">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Seleccione una Actividad</label>
                            <select required class="form-select" id="inputGroupSelect01">
                                <option disabled selected></option>
                                {{-- @foreach ($actividades as $actividad)
                                    <option value="{{ $actividad->idActividad }}">{{ $actividad->nombreActividad }}
                                    </option>
                                @endforeach --}}
                            </select>
                        </div>
                    </div>


                </div>

                <!-- Campo oculto para almacenar el ID de la actividad seleccionada -->
                <input type="hidden" id="actividadId" name="actividadId">


                <div class="row" style="justify-content: center">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre de la Actividad</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="nombreActividad"
                                id="nombreActividad">
                        </div>
                    </div>

                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Grado al que pertenece la
                                Actividad</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="grado" disabled
                                value="{{ Auth::user()->grado->nombreGrado }}">
                        </div>

                    </div>

                </div>


                <div class="row" style="justify-content: center">

                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Calificación Obtenible</span>
                            <input type="number" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="calificacionObtenible"
                                step="0.01" min="0" max="100" id="calificacionObtenible">
                        </div>

                    </div>

                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Descripción</span>
                            <textarea class="form-control" name="descripcionActividad" rows="3" cols="30" id="descripcionActividad"></textarea>
                        </div>
                    </div>

                </div>

            </div>
        </div>



        <div class="text-center">
            <button type="submit" class="btn btn-info btn-lg" id="actualizarBtn">
                Actualizar Información
            </button>
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
                            $('#inputGroupSelect01').empty();

                            // Agrega la opción deshabilitada y seleccionada al principio
                            $('#inputGroupSelect01').append($('<option>', {
                                disabled: true,
                                selected: true,
                                value: '',
                                text: ''
                            }));

                            // Agrega las opciones de actividades al select "inputGroupSelect01"
                            $.each(data.actividades, function(index, actividad) {
                                $('#inputGroupSelect01').append($('<option>', {
                                    value: actividad.idActividad,
                                    text: actividad.nombreActividad
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
                    $('#inputGroupSelect01').empty();
                }
            });

            // Cuando se cambia la selección de la actividad
            $('#inputGroupSelect01').change(function() {
                var idActividad = $(this).val();

                console.log('ID de la Actividad seleccionada:', idActividad);
                // Actualiza el valor del campo oculto actividadId
                $('#actividadId').val(idActividad);

                // Especifica la URL directamente
                var url = '/obtener-datos-actividad/' + idActividad;

                // Realiza una solicitud AJAX para obtener los datos de la actividad seleccionada
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        console.log(data); // Agregar este console.log para depurar
                        // Llena los campos del formulario con los datos de la actividad
                        $('#nombreActividad').val(data.actividad.nombreActividad);
                        $('#calificacionObtenible').val(data.actividad.calificacionObtenible);
                        $('#descripcionActividad').val(data.actividad.descripcionActividad);
                    },
                    error: function(xhr, status, error) {
                        var errorMessage =
                            'Hubo un error al obtener los datos de la actividad: ' + error;
                        console.error(errorMessage);
                        alert(errorMessage);
                    }
                });
            });

            // Agregar un evento al botón "Editar"
            $('#actualizarBtn').click(function(e) {
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
                if ($('#inputGroupSelect01').val() === null) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, selecciona una actividad a editar.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si no se ha seleccionado una actividad
                }

                // Verificar si el campo nombreActividad está vacío
                if ($('#nombreActividad').val() === '') {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'La actividad debe tener un nombre.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si el campo está vacío
                }

                // Verificar si el campo calificacionObtenible está vacío o no es un número válido
                var calificacionObtenible = parseFloat($('#calificacionObtenible').val());
                if (isNaN(calificacionObtenible) || calificacionObtenible <= 0 || calificacionObtenible >
                    100) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Debe de colocar una calificación con un valor válido entre 0 y 100.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si el campo no es válido
                }

                // Mostrar la alerta de éxito
                Swal.fire({
                    title: 'Éxito',
                    text: 'La actividad se ha editado correctamente.',
                    icon: 'success',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario hizo clic en "Aceptar", enviar el formulario de edición
                        $('#editar-actividades').submit();
                    }
                });

            });



        });
    </script>

@stop
