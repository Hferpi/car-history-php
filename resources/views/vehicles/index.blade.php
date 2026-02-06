@extends('layouts.app')

<?php
$car = [
    'id' => 1,
    'usuario_id' => 'hector',
    'matricula' => '3111GMB',
    'marca' => 'ford',
    'modelo' => 'focus',
    'kilometros' => 167896,
];
?>


@section('content')
    <div class="w-full max-w-4xl mx-auto p-6">
        <h1>Index vehiculos</h1>
        <div>
            <table>
                <tr>
                    <th>Marca</th>
                    <th>Modelo</th>
                    <th>Kilometros</th>
                </tr>
            </table>
        </div>
        <div>
            <a href="{{ route('vehicles.create') }}">
                <button class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md">Crear
                    vehiculo</button>
            </a>
            {{-- <a href="{{ route('vehicles.show') }}">

        </a> --}}
            <button class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md">Ver
                vehiculos</button>
        </div>
    </div>
@endsection
