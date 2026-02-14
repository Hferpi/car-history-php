@extends('layouts.app')

@section('content')
    <div class="w-full p-6">
        <h1 class="text-3xl font-bold mb-4">Dashboard</h1>
        <p class="text-gray-600 dark:text-gray-400 mb-6">
            Bienvenido a Car History Tracker. Aquí podrás gestionar el mantenimiento
            y el historial completo de tus vehículos.
        </p>

        <section class="w-full flex place-content-center">
            @if ($ultimoVehiculo)
                <div
                    class="w-full md:w-3/5 rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col items-center bg-white dark:bg-gray-800 shadow-sm overflow-hidden">
                    <!-- Imagen real del último coche -->
                    <img src="{{ asset($ultimoVehiculo->avatar) }}" alt="icon-car" class="w-60 md:w-60 lg:w-80 m-6">

                    <div class="bg-sky-200 dark:bg-gray-600 w-full p-4 items-center flex justify-around">
                        <h3 class="text-2xl dark:text-white">
                            <span
                                class="font-bold text-blue-600 dark:text-blue-400 text-sm block uppercase">{{ $ultimoVehiculo->marca }}</span>
                            <span>{{ $ultimoVehiculo->modelo->nombre }}</span>
                        </h3>

                        <!-- MATRÍCULA ESTILO REAL -->
                        <div
                            class="flex items-stretch bg-white rounded border border-gray-400 overflow-hidden h-10 shadow-sm">
                            <div class="bg-blue-700 w-3"></div>
                            <div class="px-3 flex items-center justify-center bg-white">
                                <span class="text-black font-mono font-bold text-xl tracking-widest uppercase">
                                    {{ $ultimoVehiculo->matricula }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            @else
                <div
                    class="w-full p-10 text-center border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-2xl">
                    <p class="text-gray-500">No tienes vehículos todavía.</p>
                    <a href="{{ route('vehicles.create') }}" class="text-blue-500 underline">Añade tu primer coche</a>
                </div>
            @endif
        </section>

        <!-- Botonera -->
        <div class="mt-10 w-full flex justify-around gap-2">
            <a href="{{ route('vehicles.create') }}"
                class="flex items-center gap-2 bg-blue-400 p-4 lg:p-6 rounded-xl hover:bg-blue-600 hover:text-white transition shadow">
                <i data-lucide="plus" class="w-5 h-5"></i>
                Crear
            </a>

            @if ($ultimoVehiculo)
                <a href="{{ $ultimoVehiculo ? route('repairs.store', $ultimoVehiculo->id) : route('vehicles.create') }}"
                    class="flex items-center gap-2 bg-blue-400 p-4 lg:p-6 rounded-xl hover:bg-blue-600 hover:text-white transition shadow">
                    <i data-lucide="wrench" class="w-5 h-5"></i>
                    Reparacion
                </a>
            @endif


            <a href="{{ route('history') }}"
                class="flex items-center gap-2 bg-blue-400 p-4 lg:p-6 rounded-xl hover:bg-blue-600 hover:text-white transition shadow">
                <i data-lucide="history" class="w-5 h-5"></i>
                Historial
            </a>
        </div>

        <!-- Último servicio (Mock data por ahora) -->
        @if ($ultimoVehiculo)
            <div
                class="relative w-full mt-10 p-5 bg-sky-200 dark:bg-gray-600 rounded-2xl grid grid-cols-3 place-items-center text-center">
                <h4
                    class="absolute -top-4 left-1/2 -translate-x-1/2 bg-sky-400 dark:bg-sky-500 px-4 py-1.5 rounded-full text-sm font-semibold shadow">
                    Último servicio
                </h4>

                <div class="bg-white dark:bg-gray-700 p-2 rounded-full">
                    <i data-lucide="badge-check" class="w-6 h-6 text-green-600"></i>
                </div>

                <div class="mt-2">
                    <h3 class="font-bold">
                        {{ $ultimoServicio ? $ultimoServicio->tipo_servicio : 'Sin registro' }}
                    </h3>
                    <p class="text-sm">
                        {{ $ultimoServicio ? \Carbon\Carbon::parse($ultimoServicio->fecha)->format('d/m/Y') : '--/--/----' }}
                        •
                        {{ $ultimoVehiculo->kilometros }} km
                    </p>
                </div>

                <p class="font-bold text-lg">
                    {{ $ultimoServicio ? number_format($ultimoServicio->precio, 2) : '0.00' }} €
                </p>
            </div>

            <div class="text-center w-full bg-sky-200 dark:bg-gray-600 rounded-2xl mt-8 p-6">
                <h2 class="text-sm uppercase tracking-widest text-gray-600 dark:text-gray-300">Gasto total </h2>
                <p class="text-3xl text-red-600 font-bold">
                    {{ $ultimoVehiculo->repairs->sum('precio') ?? 0 }} €
                </p>
            </div>
        @endif

    </div>
@endsection
