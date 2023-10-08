@extends('adminlte::page')

@section('title', 'Registro de Estudiantes')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Registrar Estudiantes</h1>
@stop

@section('content')

    <form method="POST" action="{{ route('store-registro-estudiantes') }}" id="registrar-estudiante">
        @csrf


        <div class="card">
            <div class="card-body">



                <div class="row">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre del Estudiante</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="nombreEstudiante"
                                placeholder="Ingrese nombre del estudiante">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Apellidos del Estudiante</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="apellidosEstudiante"
                                placeholder="Ingrese los apellidos del estudiante">
                        </div>
                    </div>

                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Profesor a Cargo</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="idUsuario" disabled
                                value="{{ Auth::user()->idUsuario . ' - ' . Auth::user()->nombreProfesor }}">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Grado</span>

                            <select class="form-select" aria-label="Large select example" required name="idGrado" id="idGrado">
                                <option disabled value="0" selected></option>
                                @foreach ($grados as $grado)
                                    <option value="{{ $grado->idGrado }}">
                                        {{ $grado->idGrado . ' - ' . $grado->nombreGrado }}</option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Genero</span>


                            <select class="form-select" aria-label="Large select example" required name="genero" id="genero">
                                <option disabled value="0" selected></option>
                                <option value="Masculino">Masculino</option>
                                <option value="Femenino">Femenino</option>
                            </select>
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre del Encargado</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="nombreEncargado"
                                placeholder="Ingrese el nombre del encargado">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Apellidos del Encargado</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="apellidosEncargado"
                                placeholder="Ingrese los apellidos del encargado">
                        </div>
                    </div>



                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default"> Telefono 1</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="telefono1" required
                                placeholder="Número de teléfono" maxlength="8"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>


                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default"> Telefono 2 (Opcional)</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="telefono2" placeholder="Opcional"
                                maxlength="8" oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>

                </div>




            </div>
        </div>






        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">
                Registrar Estudiante
            </button>
        </div>

    </form>

@stop

@section('css')

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">

@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#registrar-estudiante'); // Obtener el formulario
            const generoSelect = document.querySelector('#genero'); // Obtener el select de género
            const gradoSelect = document.querySelector('#idGrado'); // Obtener el select de idGrado

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir el envío del formulario por defecto


                // Verificar si la opción de género seleccionada es "Seleccion de Grado"
                if (gradoSelect.value === "0") {
                    // Mostrar una alerta SweetAlert 2 de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, seleccione un grado.',
                    });
                    return; // Evitar que el formulario se envíe
                }

                // Verificar si la opción de género seleccionada es "Seleccion de Genero"
                if (generoSelect.value === "0") {
                    // Mostrar una alerta SweetAlert 2 de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, selecciona un género.',
                    });
                    return; // Evitar que el formulario se envíe
                }



                // Mostrar una alerta SweetAlert 2 como éxito antes de enviar el formulario
                Swal.fire({
                    icon: 'success',
                    title: 'Estudiante Registrado Exitosamente',
                    showConfirmButton: false,
                    timer: 1500 // Cambia el valor del temporizador según tus preferencias
                });

                // Permitir que el formulario se envíe después de mostrar la alerta
                setTimeout(() => {
                    form.submit();
                }, 1500); // Asegúrate de que el temporizador coincida con el de la alerta
            });
        });
    </script>
@stop
