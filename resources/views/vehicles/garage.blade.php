@extends('layouts.app')

@section('content')
<div class="w-full">
    <!-- Input oculto para guardar el ID -->
    <input type="hidden" name="vehiculo_seleccionado" id="vehiculo_seleccionado" value="{{ session('selected_vehicle_id') }}">

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
        @php
            // Obtenemos el ID seleccionado de la sesión para marcarlo al cargar la página
            $idSeleccionado = session('selected_vehicle_id');
        @endphp

        @forelse ($vehiculos as $vehiculo)
            @php
                $esSeleccionado = ($idSeleccionado == $vehiculo->id);
            @endphp

            <div id="card-{{ $vehiculo->id }}"
                 onclick="seleccionarVehiculo({{ $vehiculo->id }}, this)"
                 class="vehiculo-card cursor-pointer group rounded-2xl border transition-all duration-300 overflow-hidden flex flex-col items-center
                 {{ $esSeleccionado
                    ? 'border-transparent ring-4 ring-blue-500 bg-white shadow-lg'
                    : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg' }}">

                <!-- Área del Coche (Avatar) -->
                <div class="p-6 flex justify-center items-center h-40 w-full bg-gray-50/30 dark:bg-gray-900/10">
                    <img src="{{ asset($vehiculo->avatar) }}"
                         alt="Coche"
                         class="h-24 object-contain group-hover:scale-110 transition-transform duration-500">
                </div>

                <!-- Info Box -->
                <div class="info-box w-full p-4 flex flex-col gap-3 transition-all duration-300
                    {{ $esSeleccionado
                        ? 'bg-white text-gray-900'
                        : 'bg-gray-600 dark:bg-gray-950 text-white' }}">

                    <div class="flex justify-between items-center">
                        <div class="flex flex-col">
                            <span class="marca-text text-[10px] uppercase font-bold tracking-widest
                                {{ $esSeleccionado ? 'text-blue-600' : 'text-gray-300 dark:text-blue-400' }}">
                                {{ $vehiculo->marca }}
                            </span>
                            <h3 class="modelo-text text-lg font-bold leading-tight">
                                {{ $vehiculo->modelo->nombre }}
                            </h3>
                            <span class="km-text text-xs text-gray-400 opacity-60">
                                {{ number_format($vehiculo->kilometros, 0, ',', '.') }} km
                            </span>
                        </div>

                        <!-- MATRÍCULA -->
                        <div class="flex items-stretch bg-white rounded border border-gray-400 overflow-hidden h-8 shadow-sm">
                            <div class="bg-blue-700 w-3"></div>
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
    @import url('https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@700&display=swap');
    .font-mono { font-family: 'Roboto Mono', monospace; }
</style>

<script>
function seleccionarVehiculo(id, elemento) {
    // 1. UI: Limpiar estilos de todos los coches (Volver al estado normal)
    document.querySelectorAll('.vehiculo-card').forEach(card => {
        card.className = "vehiculo-card cursor-pointer group rounded-2xl border border-gray-200 dark:border-gray-700 flex flex-col items-center bg-white dark:bg-gray-800 shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden";

        const ib = card.querySelector('.info-box');
        ib.className = "info-box bg-gray-600 dark:bg-gray-950 w-full p-4 flex flex-col gap-3 text-white transition-colors duration-300";

        const mt = card.querySelector('.marca-text');
        mt.className = "marca-text text-[10px] text-gray-300 dark:text-blue-400 uppercase font-bold tracking-widest";
    });

    // 2. UI: Aplicar estilos al seleccionado (Contenedor blanco y marca azul)
    elemento.className = "vehiculo-card cursor-pointer group rounded-2xl border border-transparent ring-4 ring-blue-500 flex flex-col items-center bg-white shadow-lg transition-all duration-300 overflow-hidden";

    const infoBox = elemento.querySelector('.info-box');
    infoBox.className = "info-box bg-white w-full p-4 flex flex-col gap-3 text-gray-900 transition-colors duration-300";

    const marcaText = elemento.querySelector('.marca-text');
    marcaText.className = "marca-text text-[10px] text-blue-600 uppercase font-bold tracking-widest";

    // Nota: El color de los KM no se toca porque ya tiene la clase text-gray-400 fija en el HTML.

    // 3. PERSISTENCIA: Llamada al servidor para guardar en sesión
    fetch("{{ route('vehicles.select') }}", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "{{ csrf_token() }}"
        },
        body: JSON.stringify({ id: id })
    })
    .then(res => res.json())
    .then(data => {
        console.log("Coche " + id + " seleccionado y guardado.");
    });
}
</script>
@endsection