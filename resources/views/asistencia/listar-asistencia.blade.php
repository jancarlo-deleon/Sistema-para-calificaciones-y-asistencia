@extends('adminlte::page')

@section('title', 'Listar Asistencia')

@section('content_header')
    <h1 class="display-4 text-center mt-4">Lista de Asistencia</h1>

@stop

@section('content')


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
                            <td><input disabled type="radio" name="asistencia[{{ $estudiante->idEstudiante }}]" value="1">
                            </td>
                            <!-- Asiste -->
                            <td><input disabled type="radio" name="asistencia[{{ $estudiante->idEstudiante }}]" value="0">
                            </td>
                            <!-- No Asiste -->
                            <td class="text-center">
                                <textarea disabled name="comentario[{{ $estudiante->idEstudiante }}]" class="form-control"></textarea>
                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>

        </div>
        <div class="card-footer">
            Lista de Estudiantes
        </div>
    </div>


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

               /// Realizar una solicitud AJAX al controlador para buscar asistencia y comentarios
                $.ajax({
                    type: "POST",
                    url: "/buscar-asistencia",
                    data: {
                        _token: "{{ csrf_token() }}",
                        diaAsistencia: selectedDate
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.asistencias.length === 0) {
                            // Mostrar SweetAlert de advertencia si no hay coincidencias
                            Swal.fire({
                                title: 'Advertencia',
                                text: 'No se encontraron registros para la fecha seleccionada.',
                                icon: 'warning',
                                confirmButtonText: 'Aceptar'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Formatear los radio buttons y las áreas de comentario en la tabla
                                    $('input[type="radio"]').prop("checked", false);
                                    $('textarea[name^="comentario"]').val("");
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


            // Inicializa la tabla DataTable
            var table = $('#tomaAsistencia').DataTable({
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/es-MX.json',
                },
                dom: 'Bfrtip',
                buttons: [
                    {
                        extend: 'pdf',
                        text: 'Exportar a PDF',
                        customize: function (doc) {
                            // Definir un estilo personalizado para la tabla en el PDF
                            var tableStyle = {
                                fontSize: 10,
                                margin: [0, 5, 0, 15] // [left, top, right, bottom] margins
                            };

                            // Crear una tabla nueva para el PDF
                            var newTable = [];

                            // Agregar la cabecera de la tabla
                            newTable.push([
                                { text: 'No.', style: 'tableHeader' },
                                { text: 'Nombre Completo', style: 'tableHeader' },
                                { text: 'Asiste', style: 'tableHeader' },
                                { text: 'No Asiste', style: 'tableHeader' },
                                { text: 'Comentarios', style: 'tableHeader' }
                            ]);

                            // Obtener todas las filas de la tabla original
                            var rows = $('#tomaAsistencia tbody tr');

                            // Recorrer las filas y agregar los datos a la nueva tabla
                            rows.each(function () {
                                var rowData = $(this).find('td');
                                var estudianteId = $(this).find('input[type="radio"]').attr('name').match(/\d+/)[0];
                                var radioAsiste = $(this).find('input[name="asistencia[' + estudianteId + ']"]:checked');
                                var comentario = $(this).find('textarea[name="comentario[' + estudianteId + ']"]').val();

                                newTable.push([
                                    rowData.eq(0).text(), // Supongo que la primera columna contiene el No.
                                    rowData.eq(1).text(), // Supongo que la segunda columna contiene el Nombre Completo
                                    (radioAsiste.val() == '1' ? 'X' : ''), // 'X' si el estudiante asiste
                                    (radioAsiste.val() == '0' ? 'X' : ''), // 'X' si el estudiante no asiste
                                    comentario // Agregar el comentario
                                ]);
                            });

                            // Aplicar estilos al PDF
                            doc.defaultStyle.fontSize = 12;
                            doc.styles.tableHeader = { fontSize: 12, bold: true, fillColor: '#007bff', color: '#fff' };

                            // Agregar la tabla al documento PDF
                            doc.content = [
                                {
                                    table: {
                                        body: newTable,
                                        headerRows: 1
                                    },
                                    layout: 'headerLineOnly',
                                    style: 'tableStyle'
                                }
                            ];
                        }
                    },

                    {
                        extend: 'print',
                        text: 'Imprimir',
                        customize: function (win) {
                            // Crea una tabla nueva para la impresión
                            var newTable = $('<table>').addClass('table table-no-border');

                            // Agrega estilos CSS para centrar el contenido
                            newTable.css('text-align', 'center');

                            // Agrega la cabecera de la tabla
                            var thead = $('<thead>').appendTo(newTable);
                            var headerRow = $('<tr>').appendTo(thead);
                            headerRow.append('<th class="text-center">No.</th>');
                            headerRow.append('<th class="text-center">Nombre Completo</th>');
                            headerRow.append('<th class="text-center">Asiste</th>');
                            headerRow.append('<th class="text-center">No Asiste</th>');
                            headerRow.append('<th class="text-center">Comentarios</th>');

                            // Obtén todas las filas de la tabla original
                            var rows = $('#tomaAsistencia tbody tr');

                            // Recorre las filas y agrega los datos a la nueva tabla
                            rows.each(function () {
                                var rowData = $(this).find('td');
                                var estudianteId = $(this).find('input[type="radio"]').attr('name').match(/\d+/)[0];
                                var radioAsiste = $(this).find('input[name="asistencia[' + estudianteId + ']"]:checked');
                                var comentario = $(this).find('textarea[name="comentario[' + estudianteId + ']"]').val();

                                var row = $('<tr>');
                                row.append('<td>' + rowData.eq(0).text() + '</td>'); // Supongo que la primera columna contiene el No.
                                row.append('<td>' + rowData.eq(1).text() + '</td>'); // Supongo que la segunda columna contiene el Nombre Completo
                                row.append('<td>' + (radioAsiste.val() == '1' ? 'X' : '') + '</td>'); // Muestra 'X' si el estudiante asiste
                                row.append('<td>' + (radioAsiste.val() == '0' ? 'X' : '') + '</td>'); // Muestra 'X' si el estudiante no asiste
                                row.append('<td>' + comentario + '</td>'); // Agrega el comentario
                                newTable.append(row);
                            });

                            // Reemplaza el contenido de la ventana de impresión con la nueva tabla
                            var body = $(win.document.body);
                            body.empty();
                            body.append(newTable);
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
