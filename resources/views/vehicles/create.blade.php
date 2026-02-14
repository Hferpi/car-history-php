@extends('layouts.app')

@section('content')
    <section class="w-full rounded-2xl bg-blue-300/70 overflow-hidden flex flex-col md:flex-row min-h-100">


        <div class="w-full md:w-1/2 p-8 flex flex-col justify-center items-center md:items-start text-left gap-4">
            <h1 class="text-2xl m-2 font-semibold">Crear un coche</h1>
            <form action="{{ route('vehicles.store') }}" method="POST" class="flex flex-1 gap-4 flex-col w-60">
                @csrf

                <select id="marca" name="marca_id" class="cursor-pointer" required
                    data-marcas='@json($marcas)'>
                    <option value="">Selecciona una marca</option>
                    @foreach ($marcas as $marca)
                        <option value="{{ $marca->id }}">{{ $marca->nombre }}</option>
                    @endforeach
                </select>

                <select id="modelo" name="modelo_id" class="cursor-pointer" required disabled>
                    <option value="">Selecciona modelo</option>
                </select>


                <div class="flex items-center gap-2">
                    <label class="font-bold">Matrícula</label>
                    <span
                        class="relative bg-white px-4 py-2 rounded-md
             border border-gray-300 font-mono tracking-widest
             flex items-center gap-3 w-40 ">


                        <span class="absolute left-0 top-0 h-full w-5 bg-blue-700 rounded-l-md"></span>

                        <input type="text" name="matricula" required maxlength="7"
                            class="pl-3 ml-1.5  outline-none w-32 font-semibold text-lg text-black">


                    </span>
                </div>
                <div class="flex items-center gap-2">
                    <label class="font-bold">Kilómetros</label>
                    <input type="number" name="kilometros" class="w-32 bg-white  ">
                </div>
                <label class="text-center font-bold">Avatar</label>
                <div id="avatar-select" class="flex gap-2">
                    @foreach ($avatars as $key => $ruta)
                        <button type="button"
                            class="avatar-btn border-0 rounded p-1 hover:drop-shadow-xl/50 cursor-pointer"
                            data-value="{{ $ruta }}">
                            <img src="{{ asset($ruta) }}" class="w-12 h-12 object-contain" alt="{{ $key }}">
                        </button>
                    @endforeach
                </div>

                <!-- Input oculto que se enviará al formulario -->
                <input type="hidden" name="avatar" id="avatar-hidden">


                <button type="submit"
                    class="p-4 border-2 cursor-pointer  bg-blue-400 rounded hover:bg-blue-600 hover:text-white">Crear
                    Vehículo</button>
            </form>
        </div>
        <div class="hidden md:flex md:w-1/2 items-center justify-center p-6 bg-white/20">
            <img id="img-avatar" class="max-w-2/3 h-auto object-contain drop-shadow-xl" />
        </div>
    </section>
@endsection
