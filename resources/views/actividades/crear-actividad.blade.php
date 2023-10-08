@extends('adminlte::page')

@section('title', 'Crear Actividades')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Crear Actividades</h1>
@stop

@section('content')

    <form method="POST" action="{{ route('almacenar-crear-actividad') }}" id="crear-actividades">
        @csrf


        <div class="card">
            <div class="card-body">



                <div class="row" style="justify-content: center">

                    <div class="col-md-7">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Nombre de la Actividad</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="nombreActividad" required>
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
                                value="{{ Auth::user()->grado->nombreGrado }} - {{ Auth::user()->grado->seccion }}">
                        </div>

                    </div>

                </div>

                <div class="row" style="justify-content: center">
                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelectCurso">Curso</label>
                            <select  class="form-select" id="inputGroupSelectCurso" name="idCurso">
                                <option disabled selected></option>
                                @foreach ($cursos as $curso)
                                    <option value="{{ $curso->idCurso }}">{{$curso->idCurso}} - {{ $curso->nombreCurso }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-4">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Calificación Obtenible</span>
                            <input type="number" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" required name="calificacionObtenible"
                                step="0.01" min="0" max="100">
                        </div>

                    </div>

                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Descripción</span>
                            <textarea class="form-control" name="descripcionActividad" rows="3" cols="30"  ></textarea>
                        </div>
                    </div>

                </div>

            </div>
        </div>






        <div class="text-center">
            <button type="submit" class="btn btn-info btn-lg" id="crearActividadBtn">
                Crear Actividad
            </button>
        </div>

    </form>

@stop

@section('css')

@stop

@section('js')

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('#crear-actividades'); // Obtener el formulario
            const selectCurso = document.querySelector('#inputGroupSelectCurso'); // Obtener el elemento select

            form.addEventListener('submit', function(e) {
                e.preventDefault();

                // Verificar si el valor del select es nulo
                if (selectCurso.value === null || selectCurso.value === "") {
                    // Mostrar una alerta de error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Por favor, seleccione un curso válido.',
                    });
                } else {
                    // Mostrar una alerta de éxito
                    Swal.fire({
                        icon: 'success',
                        title: 'Actividad Creada Exitosamente',
                        showConfirmButton: false,
                        timer: 1500
                    });

                    // Permitir que el formulario se envíe después de mostrar la alerta de éxito
                    setTimeout(() => {
                        form.submit();
                    }, 1500); // Asegúrate de que el temporizador coincida con el de la alerta
                }
            });
        });
    </script>

@stop
