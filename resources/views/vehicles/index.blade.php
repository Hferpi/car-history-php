@extends('layouts.app')

@section('content')
    <h1>Index vehiculos</h1>
    <div>
        <a href="{{ route('vehicles.create') }}">
            <button>Crear vehiculo</button>
        </a>
        {{-- <a href="{{ route('vehicles.show') }}">

            <button>Ver vehiculos</button>
        </a> --}}
    </div>
@endsection
