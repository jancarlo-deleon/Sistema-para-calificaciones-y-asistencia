@extends('adminlte::page')

@section('title', 'Eliminar Actividades')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Eliminar Actividades</h1>
@stop

@section('content')

    <form method="POST" action="{{ route('destroy-eliminar-actividades') }}" id="eliminar-actividades">
        @csrf
        @method('DELETE')


        <div class="card">
            <div class="card-body">

                <div class="row" style="justify-content: center">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Seleccione una Actividad</label>
                            <select required class="form-select" id="inputGroupSelect01">
                                <option disabled selected></option>
                                @foreach ($actividades as $actividad)
                                    <option value="{{ $actividad->idActividad }}">{{ $actividad->nombreActividad }}
                                    </option>
                                @endforeach
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
                                aria-describedby="inputGroup-sizing-default" required name="nombreActividad" disabled
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
                            <span class="input-group-text" id="inputGroup-sizing-default">Curso</span>
                            <input type="text" disabled class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="idCurso" id="idCurso">
                        </div>

                    </div>


                    <div class="row" style="justify-content: center">

                        <div class="col-md-4">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Calificación Obtenible</span>
                                <input type="number" class="form-control" aria-label="Sizing example input"
                                    aria-describedby="inputGroup-sizing-default" required name="calificacionObtenible"
                                    step="0.01" min="0" max="100" id="calificacionObtenible" disabled>
                            </div>

                        </div>

                    </div>

                    <div class="row" style="justify-content: center">

                        <div class="col-md-6">
                            <div class="input-group mb-3">
                                <span class="input-group-text" id="inputGroup-sizing-default">Descripción</span>
                                <textarea class="form-control" disabled name="descripcionActividad" rows="3" cols="30" id="descripcionActividad"></textarea>
                            </div>
                        </div>

                    </div>

                </div>
            </div>






            <div class="text-center">
                <button type="submit" class="btn btn-danger btn-lg" id="eliminarBtn">
                    Eliminar
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
            // Cuando se cambia la selección del estudiante
            $('#inputGroupSelect01').change(function() {
                var idActividad = $(this).val();

                console.log('ID de la Actividad seleccionada:', idActividad);
                // Actualiza el valor del campo oculto actividadId
                $('#actividadId').val(idActividad);

                // Especifica la URL directamente
                var url = '/obtener-datos-actividad/' + idActividad;


                // Realiza una solicitud AJAX para obtener los datos dela actividad seleccionada
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        console.log(data); // Agregar este console.log para depurar
                        // Llena los campos del formulario con los datos de la actividad
                        $('#nombreActividad').val(data.actividad.nombreActividad);
                        $('#idCurso').val(data.nombreCurso);
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
            $('#eliminarBtn').click(function(e) {
                e.preventDefault(); // Evitar el envío del formulario por defecto

                // Verificar si se ha seleccionado una actividad
                if ($('#inputGroupSelect01').val() === null) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, seleccione una actividad a eliminar.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si no se ha seleccionado una actividad
                }

                // Mostrar la alerta de confirmación
                Swal.fire({
                    title: 'Confirmación',
                    text: '¿Estás seguro de que deseas eliminar esta actividad?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí',
                    cancelButtonText: 'No'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario hizo clic en "Sí", enviar el formulario de eliminación
                        $('#eliminar-actividades').submit();
                    }
                });

            });



        });
    </script>
@stop
