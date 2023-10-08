@extends('adminlte::page')

@section('title', 'Listar Asistencia')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Editar Asistencia</h1>

@stop

@section('content')

    <form action="{{ route('update-editar-asistencia') }}" method="post" id="editarAsistencia">
        @csrf
        @method('put')



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
                                aria-describedby="inputGroup-sizing-default" name="diaAsistencia" id="datepicker"
                                placeholder="Presione este espacio para desplegar el calendario">
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">


@endsection

@section('js')

    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/es.js"></script> <!-- Archivo de traducción para español -->

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>




    <script>
        $(document).ready(function() {


            // Inicializa el datepicker en el campo de entrada de texto con el ID "datepicker"
            flatpickr("#datepicker", {
                dateFormat: "Y-m-d", // Formato deseado (año-mes-día)
                locale: "es",
            });

            // Captura el valor de la fecha seleccionada y guárdalo en una variable o realiza cualquier acción deseada
            document.getElementById("datepicker").addEventListener("change", function() {
                var selectedDate = this.value;
                console.log(selectedDate);

              // Realizar una solicitud AJAX al controlador para buscar asistencia y comentarios
                $.ajax({
                    type: "POST",
                    url: "/buscar-asistencia",
                    data: {
                        _token: "{{ csrf_token() }}", // Agrega el token CSRF aquí
                        diaAsistencia: selectedDate
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.asistencias.length === 0) {
                            // Mostrar una alerta de SweetAlert si no hay coincidencias
                            Swal.fire({
                                icon: 'warning',
                                title: 'No hay asistencia en estas fechas',
                                text: 'No se encontraron registros de asistencia para la fecha seleccionada.',
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Limpiar los radio buttons y los campos de comentario
                                    $('input[type="radio"]').prop('checked', false);
                                    $('textarea[name^="comentario"]').val('');
                                }
                            });
                        } else {
                            // Actualizar los radioButtons y los campos de comentario en la tabla
                            response.asistencias.forEach(function(asistencia) {
                                var estudianteId = asistencia.idEstudiante;
                                var asiste = asistencia.asistencia;
                                var comentario = asistencia.comentario;

                                // Actualizar el radioButton de asistencia
                                $("input[name='asistencia[" + estudianteId + "]'][value='" + asiste + "']").prop("checked", true);

                                // Actualizar el campo de comentario
                                $("textarea[name='comentario[" + estudianteId + "]']").val(comentario);
                            });
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });


            });

                 // Captura el clic en el botón "Guardar Asistencia" y muestra la alerta o envía el formulario
                $('#asistenciaBtn').on('click', function(e) {
                    e.preventDefault(); // Previene el envío normal del formulario

                    // Verifica si al menos uno de los radio buttons está seleccionado
                    var radioButtonsChecked = $('input[name^="asistencia"]:checked');

                    if (radioButtonsChecked.length > 0) {
                        // Obtiene el valor del campo diaAsistencia
                        var selectedDate = $('#datepicker').val();

                        if (selectedDate) {
                            // Realizar una solicitud AJAX al controlador para buscar asistencia y comentarios
                            $.ajax({
                                type: "POST",
                                url: "/buscar-asistencia",
                                data: {
                                    _token: "{{ csrf_token() }}", // Agrega el token CSRF aquí
                                    diaAsistencia: selectedDate
                                },
                                dataType: "json",
                                success: function(response) {
                                    if (response.asistencias.length === 0) {
                                        // Mostrar una alerta de SweetAlert si no hay coincidencias
                                        Swal.fire({
                                            icon: 'warning',
                                            title: 'No hay asistencia en estas fechas',
                                            text: 'No se encontraron registros de asistencia para la fecha seleccionada.',
                                        });
                                    } else {
                                        // Muestra la SweetAlert de éxito
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Éxito',
                                            text: 'La asistencia se editó correctamente.',
                                            showCancelButton: false,
                                            confirmButtonText: 'Aceptar',
                                        }).then((result) => {
                                            if (result.isConfirmed) {
                                                // Si el usuario hace clic en "Aceptar", envía el formulario
                                                $('#editarAsistencia').submit(); // Envía el formulario
                                            }
                                        });
                                    }
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            });
                        } else {
                            // Si la fecha está vacía, muestra una alerta de advertencia
                            Swal.fire({
                                icon: 'warning',
                                title: 'Fecha no seleccionada',
                                text: 'Debes seleccionar una fecha antes de guardar la asistencia.',
                            });
                        }
                    } else {
                        // Si no se ha seleccionado al menos un radio button, muestra una alerta de advertencia
                        Swal.fire({
                            icon: 'warning',
                            title: 'Selecciona asistencia',
                            text: 'No se puede guardar asistencias sin valor.',
                        });
                    }
                });


            // Inicializa la tabla DataTable
            var table = $('#tomaAsistencia').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json',
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'pdfHtml5',
                        text: 'Exportar a PDF',
                        customize: function(doc) {
                            // Agrega los valores de los radio buttons debajo de los encabezados
                            var rowCount = doc.content[1].table.body.length;
                            for (var i = 0; i < rowCount; i++) {
                                var radioSi = $('input[name="asistencia_' + (i + 1) +
                                    '"][value="Si"]:checked');
                                var radioNo = $('input[name="asistencia_' + (i + 1) +
                                    '"][value="No"]:checked');
                                if (radioSi.length > 0) {
                                    doc.content[1].table.body[i + 1][2].text = 'X';
                                    doc.content[1].table.body[i + 1][3].text = '';
                                } else if (radioNo.length > 0) {
                                    doc.content[1].table.body[i + 1][2].text = '';
                                    doc.content[1].table.body[i + 1][3].text = 'X';
                                }
                            }
                        }
                    },



                    {
                        extend: 'print',
                        text: 'Imprimir',
                        customize: function(win) {
                            // Agrega los valores de los radio buttons debajo de los encabezados
                            var body = $(win.document.body);
                            body.find('table tbody tr').each(function(i) {
                                var radioSi = $('input[name="asistencia_' + (i + 1) +
                                    '"][value="Si"]:checked');
                                var radioNo = $('input[name="asistencia_' + (i + 1) +
                                    '"][value="No"]:checked');
                                if (radioSi.length > 0) {
                                    $(this).find('td:eq(2)').text('X');
                                    $(this).find('td:eq(3)').text('');
                                } else if (radioNo.length > 0) {
                                    $(this).find('td:eq(2)').text('');
                                    $(this).find('td:eq(3)').text('X');
                                }
                            });
                        }
                    }
                ]
            });

            // Activa el evento para actualizar la tabla al cambiar la selección de radio buttons
            $('input[type="radio"]').on('change', function() {
                table.draw();
            });

        });
    </script>


@endsection
