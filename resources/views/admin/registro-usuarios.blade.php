@extends('adminlte::page')

@section('title', 'Registro de Usuarios')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Registrar Usuarios</h1>
@stop

@section('content')
    <form method="POST" action="{{ route('store-registro-usuarios') }}" id="registrar-usuario">
        @csrf


        <div class="card">
            <div class="card-body">

                <div class="row">

                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre de Usuario</span>
                            <input id="nombreUsuario" class="form-control" type="text" name="nombreUsuario"
                                value="{{ old('nombreUsuario') }}" required autofocus autocomplete="nombreUsuario" required
                                placeholder="Ingrese un nombre de usuario">
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre Personal</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="nombreProfesor"
                                value="{{ old('nombreProfesor') }}" placeholder="Ingrese nombre del profesor">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Apellidos</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="apellidosProfesor"
                                value="{{ old('apellidosProfesor') }}" placeholder="Ingrese apellidos del profesor">
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Contraseña</span>
                            <input id="password" class="form-control" type="password" name="password" required
                                autocomplete="new-password" placeholder="Ingrese una contraseña">
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

                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default"> Confirmar Contraseña</span>
                            <input id="password_confirmation" class="form-control" type="password"
                                name="password_confirmation" required autocomplete="new-password"
                                placeholder="Confirme la contaseña">
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Telefono</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="telefono"
                                value="{{ old('telefono') }}" placeholder="Ingrese número de telefono" maxlength="8"
                                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        </div>
                    </div>



                </div>

                <div class="row">

                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default"> Correo Electrónico</span>
                            <input id="email" class="form-control" type="email" name="email"
                                value="{{ old('email') }}" required autocomplete="email"
                                placeholder="Ingrese correo electrónico">
                        </div>
                    </div>

                </div>

                <div class="row">

                    <div class="col-md-12">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default"> Direccion</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="direccion"
                                value="{{ old('direccion') }}" placeholder="Ingresa una dirección">
                        </div>
                    </div>

                </div>



            </div>
        </div>






        <div class="text-center">
            <button type="submit" class="btn btn-primary btn-lg">
                Registrar Usuario
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
            const form = document.querySelector('#registrar-usuario'); // Obtener el formulario
            const passwordField = document.querySelector('#password');
            const confirmPasswordField = document.querySelector('#password_confirmation');
            const emailField = document.querySelector('#email');
            const generoSelect = document.querySelector('#genero'); // Obtener el select de género
            const gradoSelect = document.querySelector('#idGrado'); // Obtener el select de idGrado

            form.addEventListener('submit', function(event) {
                event.preventDefault(); // Prevenir el envío del formulario por defecto

                // Validar que las contraseñas coincidan
                if (passwordField.value !== confirmPasswordField.value) {
                    // Mostrar una alerta SweetAlert2 como error
                    Swal.fire({
                        icon: 'error',
                        title: 'Las contraseñas no coinciden',
                        text: 'Por favor, asegúrate de que las contraseñas coincidan.',
                    });
                    return; // Detener el envío del formulario
                }

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
                        text: 'Por favor, seleccione un género.',
                    });
                    return; // Evitar que el formulario se envíe
                }



                // Validar si el correo electrónico ya está registrado
                const userEmail = emailField.value;
                axios.get(`/verificar-correo/${userEmail}`)
                    .then(response => {
                        if (response.data.exists) {
                            // Mostrar una alerta SweetAlert2 como error
                            Swal.fire({
                                icon: 'error',
                                title: 'Correo Electrónico ya registrado',
                                text: 'El correo electrónico ya está en uso, elija otro.',
                            });
                        } else {
                            // Si las contraseñas coinciden y el correo no está registrado,
                            // mostrar una alerta de éxito y permitir que el formulario se envíe
                            Swal.fire({
                                icon: 'success',
                                title: 'Usuario Registrado Exitosamente',
                                showConfirmButton: false,
                                timer: 1500
                            });

                            setTimeout(() => {
                                form.submit();
                            }, 1500);
                        }
                    })
                    .catch(error => {
                        console.error(error);
                    });
            });
        });
    </script>




@stop
