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

                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <label class="input-group-text" for="inputGroupSelectActividadParaTabla">Actividad a
                                Calificar</label>
                            <select required class="form-select" id="inputGroupSelectActividadParaTabla" name="idActividad">
                                <option disabled selected></option>
                                @foreach ($actividades as $actividad)
                                    <option value="{{ $actividad->idActividad }}"
                                        data-calificacionobtenible="{{ $actividad->calificacionObtenible }}">
                                        {{ $actividad->nombreActividad }}
                                    </option>
                                @endforeach
                            </select>

                        </div>
                    </div>

                </div>

                <div class="row" style="justify-content: center">

                    <div class="col-md-5">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Grado</span>
                            <input type="text" disabled class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="grado"
                                value="{{ Auth::user()->grado->nombreGrado . ' - ' . Auth::user()->grado->seccion }}">
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
                                        min="0" max="100" style="margin: 0 auto; display: block;">
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


        </div>

        <div class="text-center">
            <button type="submit" class="btn btn-info btn-lg" id="calificarBtn">
                Calificar
            </button>
        </div>

    </form>
@stop

@section('css')

@stop

@section('js')


    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            // Manejar el cambio en el select
            $('#inputGroupSelectActividadParaTabla').change(function() {
                var calificacionObtenible = $(this).find(':selected').data('calificacionobtenible');

                // Actualizar todas las celdas con la clase "calificacion-obtenible"
                $('.calificacion-obtenible').text(calificacionObtenible);
            });
        });
    </script>

@stop
