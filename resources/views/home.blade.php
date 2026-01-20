
¡
@extends('layouts.app')

@section('content')

<div class="w-full max-w-4xl mx-auto p-6">

    <h1 class="text-3xl font-bold mb-4">
        Dashboard
    </h1>

    <p class="text-gray-600 mb-6">
        Bienvenido a Car History Tracker. Aquí podrás gestionar el mantenimiento
        y el historial completo de tus vehículos.
    </p>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

        <div class="p-4 rounded-xl bg-cyan-100">
            <h2 class="font-semibold text-lg">Vehículos</h2>
            <p class="text-sm text-gray-600">Gestiona tus coches registrados</p>
        </div>

        <div class="p-4 rounded-xl bg-cyan-100">
            <h2 class="font-semibold text-lg">Mantenimientos</h2>
            <p class="text-sm text-gray-600">Aceite, neumáticos, revisiones</p>
        </div>

        <div class="p-4 rounded-xl bg-cyan-100">
            <h2 class="font-semibold text-lg">Facturas</h2>
            <p class="text-sm text-gray-600">Control de gastos y recibos</p>
        </div>

    </div>

</div>

@endsection

