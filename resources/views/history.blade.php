@extends('layouts.app')
@section('content')
    <div class="flex flex-col items-center w-full">
        <h1 class="text-2xl font-bold mb-6">Historial de reparaciones</h1>
        @if ($ultimoVehiculo)
            {{-- Vehículo --}}
            <div class="bg-sky-200 dark:bg-gray-600 p-4 rounded-2xl mb-6">
                <h2 class="font-semibold text-lg">
                    <p>Vehiculo: {{ $ultimoVehiculo->modelo->nombre ?? 'Vehículo' }}</p>
                </h2>
                <p class="text-sm text-gray-700 dark:text-gray-300">
                <p>Kilometros: {{ $ultimoVehiculo->kilometros }} km </p>
                </p>
            </div>
            {{-- Reparaciones --}}
            @if ($ultimoVehiculo->repairs->count())
                <div class="space-y-4">
                    @foreach ($ultimoVehiculo->repairs as $repair)
                        <div class="bg-sky-200 dark:bg-gray-700 p-4 gap-3 rounded-xl shadow flex justify-between items-center">
                            <div class="w-full flex flex-col item-center">
                                <h3 class="font-semibold text-center">
                                    {{ $repair->tipo_servicio ?? 'Reparación' }}
                                </h3>
                                <p class="text-sm text-gray-600 text-center text-balance dark:text-gray-300">
                                    {{ \Carbon\Carbon::parse($repair->fecha)->format('d/m/Y') }}
                                    @if ($repair->taller_nombre)
                                        • {{ $repair->taller_nombre }}
                                    @endif
                                </p>
                                <div class="font-bold text-lg text-red-600 text-center">
                                    {{ number_format($repair->precio ?? 0, 2) }} €
                                </div>
                            </div>
                            @if ($repair->foto_patch)
                                <img src="{{ asset('storage/' . $repair->foto_patch) }}"
                                    class="w-48 rounded shadow cursor-pointer hover:scale-105 transition"
                                    onclick="openImageModal('{{ asset('storage/' . $repair->foto_patch) }}')" alt="Recibo">
                            @endif
                        </div>
                    @endforeach
                </div>
                {{-- Total --}}
                <div class="mt-8 bg-sky-300 dark:bg-sky-600 p-5 rounded-2xl text-center">
                    <h2 class="text-sm uppercase tracking-widest">Gasto total</h2>
                    <p class="text-3xl font-bold text-red-700">
                        {{ number_format($ultimoVehiculo->repairs->sum('precio'), 2) }} €
                    </p>
                </div>
            @else
                <p class="text-gray-500">Este vehículo no tiene reparaciones registradas.</p>
            @endif
        @else
            <p class="text-gray-500">No hay vehículos disponibles.</p>
        @endif
    </div>
    {{-- MODAL IMAGEN --}}
    <div id="imageModal" class="fixed inset-0 bg-black/80 hidden z-50 flex items-center justify-center"
        onclick="closeImageModal()">
        <div class="relative max-w-5xl max-h-[90vh]">
            <button class="absolute -top-10 right-0 text-white text-3xl font-bold" onclick="closeImageModal()">
                ✕
            </button>
            <img id="modalImage" src="" class="max-h-[90vh] max-w-full rounded shadow-lg">
        </div>
    </div>
@endsection
