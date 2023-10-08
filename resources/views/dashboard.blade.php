@extends('adminlte::page')

@section('title', 'Bienvenida')

@section('content_header')
<h1 class="display-4 text-center mt-4">Tablero</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            Hola
        </div>

        <div class="card-body">
            [Pendiente]
        </div>
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
