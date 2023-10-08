@extends('adminlte::page')

@section('title', 'Editar Estudiantes')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Editar Datos de Estudiantes</h1>
@stop

@section('content')

    <form method="POST" action="{{ route('update-editar-estudiantes') }}" id="editar-estudiantes">
        @csrf
        @method('put')

        <div class="card">
            <div class="card-body">

                <div class="row" style="justify-content: center">

                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Profesor a Cargo</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="nombreProfesor" disabled
                                value="{{ Auth::user()->idUsuario . ' - ' . Auth::user()->nombreProfesor }}">
                        </div>
                    </div>

                </div>

                <!-- Campo oculto para almacenar el ID del estudiante seleccionado -->
                <input type="hidden" id="estudianteId" name="estudianteId">


                <div class="row" style="justify-content: center">

                    <div class="col-md-6">

                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelect01">Seleccione un Estudiante</label>
                            <select required class="form-select" id="inputGroupSelect01" name="idEstudiante">
                                <option disabled selected></option>
                                @foreach ($estudiantes as $estudiante)
                                    <option value="{{ $estudiante->idEstudiante }}">{{ $estudiante->nombreEstudiante }}
                                        {{ $estudiante->apellidosEstudiante }}</option>
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>


                <div class="row">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre del Estudiante</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="nombreEstudiante"
                                id="nombreEstudiante">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Apellidos del Estudiante</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="apellidosEstudiante"
                                id="apellidosEstudiante">
                        </div>
                    </div>

                </div>

                <div class="row" style="justify-content: center">



                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Grado Asignado</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="idGrado" disabled
                                id="idGrado">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Genero</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="genero" id="genero"
                                disabled>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre del Encargado</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="nombreEncargado"
                                id="nombreEncargado">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Apellidos del Encargado</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="apellidosEncargado"
                                id="apellidosEncargado">
                        </div>
                    </div>



                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default"> Telefono 1</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="telefono1" id="telefono1"
                                maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default"> Telefono 2 (Opcional)</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="telefono2" id="telefono2"
                                maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>

                </div>


            </div>
        </div>






        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg" id="actualizarBtn">
                Actualizar Datos
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
                var idEstudiante = $(this).val();

                console.log('ID del estudiante seleccionado:', idEstudiante);
                // Actualiza el valor del campo oculto estudianteId
                $('#estudianteId').val(idEstudiante);

                // Especifica la URL directamente
                var url = '/obtener-datos-estudiante/' + idEstudiante;

                // Realiza una solicitud AJAX para obtener los datos del estudiante seleccionado
                $.ajax({
                    url: url,
                    type: 'GET',
                    success: function(data) {
                        // Llena los campos del formulario con los datos del estudiante
                        $('#nombreEstudiante').val(data.nombreEstudiante);
                        $('#apellidosEstudiante').val(data.apellidosEstudiante);
                        $('#genero').val(data.genero);
                        $('#nombreEncargado').val(data.nombreEncargado);
                        $('#apellidosEncargado').val(data.apellidosEncargado);
                        $('#telefono1').val(data.telefono1);
                        $('#telefono2').val(data.telefono2);

                        // Obtén el nombre del grado correspondiente al ID de grado
                        $.ajax({
                            url: '/obtener-nombre-grado/' + data
                                .idGrado, // Ruta para obtener el nombre del grado
                            type: 'GET',
                            success: function(gradoData) {
                                // Actualiza el valor del campo de entrada "idGrado" con el nombre del grado
                                $('#idGrado').val(gradoData.nombreGrado);
                            },
                            error: function(xhr, status, error) {
                                var errorMessage =
                                    'Hubo un error al obtener el nombre del grado: ' +
                                    error;
                                console.error(errorMessage);
                                alert(errorMessage);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        var errorMessage =
                            'Hubo un error al obtener los datos del estudiante: ' + error;
                        console.error(errorMessage);
                        alert(errorMessage);
                    }
                });
            });

            // Agregar un evento al botón "Editar"
            $('#actualizarBtn').click(function(e) {
                e.preventDefault(); // Evitar el envío del formulario por defecto

                // Verificar si se ha seleccionado un estudiante
                if ($('#inputGroupSelect01').val() === null) {
                    // Mostrar una alerta de error
                    Swal.fire({
                        title: 'Error',
                        text: 'Por favor, selecciona un estudiante a editar.',
                        icon: 'error',
                    });
                    return; // Detener el proceso si no se ha seleccionado un estudiante
                }

                // Mostrar la alerta de éxito
                Swal.fire({
                    title: 'Éxito',
                    text: 'El estudiante se ha editado correctamente.',
                    icon: 'success',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Si el usuario hizo clic en "Aceptar", enviar el formulario de edición
                        $('#editar-estudiantes').submit();
                    }
                });

            });

        });
    </script>

@stop
