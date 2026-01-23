@extends('layouts.app')

@section('content')
    <?php
    $vehicle = [
        'id' => 1,
        'foto' => '/img/cars-icons/jeep-rm.png',
        'marca' => 'ford',
        'modelo' => 'focus',
        'anyo' => '2008',
        'matricula' => '3111GMB',
        'gasto_total' => '3450',
    ];
    $repair = [
        'fecha' => '01/03/2025',
        'precio' => '500',
        'tipo_servicio' => 'Cambio aceite y filtro',
        'observaciones' => 'muy bien hecho',
        'km' => '160590',
    ];

    ?>

    <div class="w-full p-6 ">

        <h1 class="text-3xl font-bold mb-4">
            Dashboard
        </h1>
        <p class="text-gray-600 mb-6">
            Bienvenido a Car History Tracker. Aquí podrás gestionar el mantenimiento
            y el historial completo de tus vehículos.
        </p>


        <section class="w-full items-center ">

            <div class="w-full rounded-2xl border flex flex-col items-center ">
                <img src="{{ $vehicle['foto'] }}" alt="icon-car" class="w-60 md:w-80 lg:w-90 m-6 ">
                <div class="bg-sky-200 dark:bg-gray-600 w-full rounded-b-2xl p-4 items-center flex justify-around">
                    <h3 class="text-2xl"><span>{{ $vehicle['anyo'] }}</span>
                        <span>{{ $vehicle['marca'] }}</span>
                        <span>{{ $vehicle['modelo'] }}</span>
                    </h3>
                    <span
                        class="relative bg-white px-4 py-2 rounded-md
             border border-gray-300 font-mono tracking-widest
             flex items-center gap-3">


                        <span class="absolute left-0 top-0 h-full w-5 bg-blue-700 rounded-l-md"></span>

                        <span class="pl-3 font-semibold text-lg text-black">
                            {{ $vehicle['matricula'] }}
                        </span>
                    </span>

                </div>
            </div>
        </section>


        <div class="mt-10 w-full flex justify-around ">
            <a href="{{ route('vehicles.create') }}"
                class="flex items-center gap-2 bg-blue-400 p-6 rounded hover:bg-blue-600 hover:text-white">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Crear
            </a>

    

            <a href="{{ route('vehicles.repair', $vehicle['id']) }}"
                class="flex items-center gap-2 bg-blue-400 p-2 rounded hover:bg-blue-600 hover:text-white">
                <i data-lucide="wrench"></i>
                Reparacion
            </a>
            <a href="{{ route('history') }}"
                class="flex items-center gap-2 bg-blue-400 p-2 rounded hover:bg-blue-600 hover:text-white">

                <i data-lucide="history"></i>
                Historial
            </a>

        </div>

        <div
            class="relative w-full mt-10 p-5 bg-sky-200 dark:bg-gray-600 rounded-2xl
            grid grid-cols-3 place-items-center text-center">

            <h4
                class="absolute -top-4 left-1/2 -translate-x-1/2
               bg-sky-400 dark:bg-sky-500
               px-4 py-1.5 rounded-full
               text-sm font-semibold shadow">
                Último servicio
            </h4>

            <i data-lucide="badge-check" class="w-6 h-6 text-green-700"></i>

            <div class="mt-2">
                <h3>{{ $repair['tipo_servicio'] }}</h3>
                <p>
                    {{ $repair['fecha'] }}

                </p>
                <p>
                    {{ $repair['km'] }}km
                </p>
            </div>

            <p class="font-semibold">{{ $repair['precio'] }}€</p>
        </div>


        <div class="text-center w-full bg-sky-200 dark:bg-gray-600 rounded-2xl mt-8 p-6">
            <h2>Gasto total / año</h2>
            <p class="text-3xl text-red-600">{{ $vehicle['gasto_total'] }}€</p>
        </div>
    </div>
@endsection
