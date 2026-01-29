@extends('layouts.app')


@section('content')
<section
    class="relative w-full p-6 rounded-2xl flex justify-center
           bg-sky-200 dark:bg-gray-600 text-left flex-col gap-6">

    <div class="w-full flex flex-col item-center max-w-4xl mx-auto bg-white dark:bg-gray-700 p-6 rounded-xl shadow">

        {{-- VEHÍCULO --}}
        <h1 class="text-2xl font-semibold mb-4">
            Reparaciones de {{ $vehicle->marca }} {{ $vehicle->modelo->nombre }}
        </h1>

        <div class="mb-6 text-sm text-gray-700 dark:text-gray-200">
            <p><strong>Matrícula:</strong> {{ $vehicle ->matricula}}</p>
            <p><strong>Kilómetros:</strong> {{ $vehicle->kilometros }} km</p>
        </div>

        {{-- ERRORES --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- FORMULARIO REPARACIÓN --}}
        <form
            action="{{ route('repairs.store', $vehicle->id) }}"
            method="POST"
            enctype="multipart/form-data"
            class="flex items-center flex-col gap-4 w-full"
        >
            @csrf

            {{-- TALLER --}}
            <div class="flex flex-col w-64 gap-1">
                <label class="font-semibold">Taller</label>
                <input type="text"
                name="taller_id"
                placeholder="Añade el taller"
                class="p-2 rounded border ">
            </div>

            {{-- FECHA --}}
            <div class="flex flex-col w-64 gap-1">
                <label class="font-semibold">Fecha</label>
                <input
                    type="date"
                    name="fecha"
                    required
                    class="p-2 rounded border "
                >
            </div>

            {{-- TIPO SERVICIO --}}
            <div class="flex flex-col w-64 gap-1">
                <label class="font-semibold">Tipo de servicio</label>
                <input
                    type="text"
                    name="tipo_servicio"
                    placeholder="Cambio de aceite, frenos, ITV..."
                    class="p-2 rounded border"
                >
            </div>

            {{-- OBSERVACIONES --}}
            <div class="flex flex-col w-64 gap-1">
                <label class="font-semibold">Observaciones</label>
                <textarea
                    name="observaciones"
                    rows="4"
                    class="p-2 rounded border"
                ></textarea>
            </div>

              {{-- PRECIO --}}
            <div class="flex flex-col w-64 gap-1">
                <label class="font-semibold">Precio (€)</label>
                <input
                    type="number"
                    step="0.01"
                    name="precio"
                    class="p-2 rounded border"
                >
            </div>

            {{-- FOTO --}}
            <div class="flex flex-col w-64 gap-1">
                <label class="font-semibold">Foto del recibo</label>
                <input
                    type="file"
                    name="foto"
                    accept="image/*"
                    class="p-2 rounded border cursor-pointer"
                >
            </div>

            {{-- BOTÓN --}}
            <div class="w-64 flex items-center gap-2">
            <button
                type="submit"
                class="mt-4 p-3 w-36 bg-blue-500 text-white rounded
                       hover:bg-blue-600 transition cursor-pointer"
            >
                Guardar reparación
            </button>
              <button
                type="reset"
                class="mt-4 p-3 w-26 bg-blue-500 text-white rounded
                       hover:bg-blue-800 transition cursor-pointer"
            >
                Borrar datos
            </button>
            </div>
        </form>
    </div>
</section>
@endsection
