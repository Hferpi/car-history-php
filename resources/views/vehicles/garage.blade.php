@extends('layouts.app')

@section('content')
<div class="w-full">
    <!-- Cabecera -->
    <div class="flex flex-col sm:flex-row justify-between items-center mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-extrabold text-gray-800 dark:text-white">Mi Garaje</h1>
            <p class="text-gray-500 dark:text-gray-400 text-sm">Listado de vehículos registrados</p>
        </div>
        <a href="{{ route('vehicles.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl transition-all shadow-md hover:shadow-blue-500/40">
            + Añadir Vehículo
        </a>
    </div>

    <!-- Rejilla de Vehículos -->
    <section class="grid lg:grid-cols-3 md:grid-cols-2 gap-6 w-full">
        @forelse ($vehiculos as $vehiculo)
            <div class="group rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col items-center bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">

                <!-- Área del Coche (Avatar) -->
                <div class="p-6 flex justify-center items-center h-40 w-full bg-gray-50/30 dark:bg-gray-900/10">
                    <img src="{{ asset($vehiculo->avatar) }}"
                         alt="Coche"
                         class="h-24 object-contain group-hover:scale-110 transition-transform duration-500">
                </div>

                <!-- Info Box (Gris en Light / Negro en Dark) -->
                <div class="bg-gray-600 dark:bg-gray-950 w-full p-4 flex flex-col gap-3 text-white">
                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="text-[10px] text-gray-300 dark:text-blue-400 uppercase font-bold tracking-widest">
                                {{ $vehiculo->marca }}
                            </span>
                            <h3 class="text-lg font-bold leading-tight">
                                {{ $vehiculo->modelo->nombre }}
                            </h3>
                            <span class="text-xs text-gray-300 opacity-80">
                                {{ number_format($vehiculo->kilometros, 0, ',', '.') }} km
                            </span>
                        </div>

                        <!-- MATRÍCULA COMPACTA ESTILO REAL -->
                        <div class="flex items-stretch bg-white rounded border border-gray-400 overflow-hidden h-8 shadow-sm">
                            <!-- Banda Azul lisa -->
                            <div class="bg-blue-700 w-3"></div>
                            <!-- Texto Matrícula (Siempre Blanco/Negro) -->
                            <div class="px-2 flex items-center justify-center bg-white">
                                <span class="text-black font-mono font-bold text-base tracking-wider uppercase">
                                    {{ $vehiculo->matricula }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full text-center py-16 bg-gray-50 dark:bg-gray-800/50 rounded-2xl border-2 border-dashed border-gray-300 dark:border-gray-700">
                <p class="text-gray-500 dark:text-gray-400">No hay coches en el garaje.</p>
            </div>
        @endforelse
    </section>
</div>

<style>
    /* Estilo de fuente para la matrícula */
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@700&display=swap');
    .font-mono {
        font-family: 'Roboto Mono', monospace;
    }
</style>
@endsection