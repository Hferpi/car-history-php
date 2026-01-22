@extends('layouts.app')

@section('content')
    <?php
    $avatars = [
        'car1' => 'img/cars-icons/blue-rm.png',
        'car2' => 'img/cars-icons/cyan-rm.png',
        'car3' => 'img/cars-icons/jeep-rm.png',
        'car4' => 'img/cars-icons/red-bg.png',
    ];
    ?>
    <section
        class="relative w-full p-6 rounded-2xl h-[350px] flex justify-center bg-blue-300/70
               text-left flex-col gap-4">
        <img src="{{ asset('img/logoBg.png') }}"
            class="hidden md:block md:w-1/2 lg:w-3/5 absolute bottom-4 right-2  opacity-60 pointer-events-none" />
        <h1 class="text-2xl m-2 font-semibold">Crear un coche</h1>


        <form action="{{ route('vehicles.store') }}" method="POST" class="flex gap-4 flex-col w-60">
            @csrf

            <select id="marca" name="marca_id" required data-marcas='@json($marcas)'>
                <option value="">Selecciona una marca</option>
                @foreach ($marcas as $marca)
                    <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                @endforeach
            </select>

            <select id="modelo" name="modelo_id" required disabled>
                <option value="">Selecciona modelo</option>
            </select>


            <div class="flex items-center gap-2">
                <label>Matrícula</label>
                <span
                    class="relative bg-white px-4 py-2 rounded-md
             border border-gray-300 font-mono tracking-widest
             flex items-center gap-3 w-40 ">


                    <span class="absolute left-0 top-0 h-full w-5 bg-blue-700 rounded-l-md"></span>

                    <input type="text" name="matricula" required
                        class="pl-3 ml-1.5  outline-none w-32 font-semibold text-lg text-black">


                </span>
            </div>
            <div class="flex items-center gap-2">
                <label>Kilómetros</label>
                <input type="number" name="kilometros" class="w-32 outline-none ">
            </div>
            <label>Avatar</label>
            <div id="avatar-select" class="flex gap-2">
                @foreach ($avatars as $key => $ruta)
                    <button type="button" class="avatar-btn border-0 rounded p-1 hover:drop-shadow-xl/50 cursor-pointer"
                        data-value="{{ $ruta }}">
                        <img src="{{ asset($ruta) }}" class="w-12 h-12 object-contain" alt="{{ $key }}">
                    </button>
                @endforeach
            </div>

            <!-- Input oculto que se enviará al formulario -->
            <input type="hidden" name="avatar" id="avatar-hidden">


            <button type="submit">Crear Vehículo</button>
        </form>


    </section>
@endsection
