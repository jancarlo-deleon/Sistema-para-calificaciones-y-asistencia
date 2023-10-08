@extends('adminlte::page')

@section('title', 'Toma de Asistencia')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Toma de Asistencia</h1>

@stop

@section('content')


    <form action="{{ route('store-toma-asistencia') }}" method="post">
        @csrf

        <div class="card">
            <div class="card-body">


                <div class="row">
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Profesor a cargo</span>
                            <input type="text" disabled class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default"
                                value="{{ Auth::user()->idUsuario . ' - ' . Auth::user()->nombreProfesor }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="inputGroup-sizing-default">Día</span>
                            <input type="text" class="form-control" aria-label="Sizing example input"
                                aria-describedby="inputGroup-sizing-default" name="fecha"
                                value="{{ \Carbon\Carbon::now()->format('d-m-Y') }}" disabled>
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
                Asistencia
            </div>
            <div class="card-body">



                <table id="tomaAsistencia" class="table table-no-border">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">No.</th>
                            <th scope="col" class="text-center">Nombre Completo</th>
                            <th scope="col" class="text-center">Asiste</th>
                            <th scope="col" class="text-center">No Asiste</th>
                            <th scope="col" class="text-center">Comentarios</th>
                        </tr>
                    </thead>

                    <tbody style="text-align: center;">
                        @foreach ($estudiantes as $estudiante)
                            <tr>
                                <td>{{ $loop->index + 1 }}</td> <!-- Número de fila -->
                                <td>{{ $estudiante->nombreEstudiante }} {{ $estudiante->apellidosEstudiante }}</td>
                                <!-- Nombre completo -->
                                <td><input type="radio" name="asistencia[{{ $estudiante->idEstudiante }}]" value="1">
                                </td>
                                <!-- Asiste -->
                                <td><input type="radio" name="asistencia[{{ $estudiante->idEstudiante }}]" value="0">
                                </td>
                                <!-- No Asiste -->
                                <td class="text-center">
                                    <textarea name="comentario[{{ $estudiante->idEstudiante }}]" class="form-control"></textarea>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                </table>





            </div>
            <div class="card-footer">
                <div class="text-center">
                    <button type="submit" class="btn btn-info btn-lg" id="asistenciaBtn">
                        Guardar Asistencia
                    </button>
                </div>
            </div>
        </div>




    </form>

@stop

@section('css')





@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('asistenciaBtn').addEventListener('click', function(event) {
                // Encuentra todos los radio buttons de asistencia
                const radioButtons = document.querySelectorAll('input[type="radio"][name^="asistencia["]');

                let radioCheckedCount = 0; // Variable para contar los radio buttons seleccionados

                // Verifica cuántos radio buttons están seleccionados
                radioButtons.forEach(function(radioButton) {
                    if (radioButton.checked) {
                        radioCheckedCount++;
                    }
                });

                // Obtiene el número total de estudiantes
                const totalEstudiantes = {!! json_encode(count($estudiantes)) !!};

                // Comprueba si todos los radio buttons han sido seleccionados
                if (radioCheckedCount !== totalEstudiantes) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Advertencia',
                        text: 'Por favor, seleccione la asistencia para todos los estudiantes.',
                    });

                    // Detiene el envío del formulario
                    event.preventDefault();
                } else {
                    // Si todos los radio buttons están seleccionados, muestra una alerta de éxito
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'La asistencia se ha guardado correctamente.',
                    });
                }
            });
        });
    </script>
    </script>


@endsection
