@extends('layouts.app')

@section('content')
    <?php

    $vehicles = [
        [
            'id' => 1,
            'foto' => '/img/cars-icons/jeep-rm.png',
            'marca' => 'ford',
            'modelo' => 'focus',
            'anyo' => '2008',
            'matricula' => '3111GMB',
            'gasto_total' => '3450',
        ],
        [
            'id' => 2,
            'foto' => '/img/cars-icons/red-rm.png',
            'marca' => 'ford',
            'modelo' => 'focus',
            'anyo' => '2008',
            'matricula' => '3111GMB',
            'gasto_total' => '3450',
        ],
        [
            'id' => 3,
            'foto' => '/img/cars-icons/cyan-rm.png',
            'marca' => 'ford',
            'modelo' => 'focus',
            'anyo' => '2008',
            'matricula' => '3111GMB',
            'gasto_total' => '3450',
        ],
    ];

    ?>

    <section class="w-full items-center grid lg:grid-cols-3 md:grid-cols-2 gap-2.5">

        @foreach ($vehicles as $vehicle)
            <div class="w-full rounded-2xl border flex flex-col items-center ">
                <img src="{{ $vehicle['foto'] }}" alt="icon-car">
                <div class="bg-gray-600 w-full rounded-b-2xl p-4 items-center flex justify-around">
                    <h3 class="text-2xl"><span>{{ $vehicle['anyo'] }}</span>
                        <span>{{ $vehicle['marca'] }}</span>
                        <span>{{ $vehicle['modelo'] }}</span>
                    </h3>
                    <span class="bg-gray-400 p-2 rounded-2xl">{{ $vehicle['matricula'] }}</span>
                </div>
            </div>
        @endforeach

    </section>
@endsection
