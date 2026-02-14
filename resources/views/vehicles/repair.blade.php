@extends('layouts.app')

@section('content')
    <section class="w-full p-4 md:p-8 rounded-2xl bg-sky-200 dark:bg-gray-600 flex flex-col gap-8">

        {{-- CABECERA DEL VEHÍCULO --}}
        <div
            class="w-full max-w-4xl mx-auto flex flex-col md:flex-row justify-around items-center bg-white/40 dark:bg-gray-800/40 backdrop-blur-sm p-6 rounded-2xl border border-white/20 shadow-sm">
            <div>
                <h1 class="text-3xl font-bold text-cyan-900 dark:text-white">
                    {{ $vehicle->marca->nombre ?? $vehicle->marca }} {{ $vehicle->modelo->nombre }}
                </h1>
                <div class="flex gap-4 mt-2 text-sm font-medium text-gray-600 dark:text-gray-300">
                    <span
                        class="relative bg-white px-4 py-2 rounded-md border border-gray-300 font-mono tracking-widest flex items-center gap-3 w-36 ">
                        <span class="absolute left-0 top-0 h-full w-5 bg-blue-700 rounded-l-md"></span>

                        <span class="ml-4 dark:text-black">{{ $vehicle->matricula }}</span>
                    </span>
                    <span
                        class="relative bg-white px-4 py-2 rounded-md border border-gray-300 font-mono tracking-widest flex items-center gap-3 w-36 ">

                        <span class="dark:text-black">{{ number_format($vehicle->kilometros, 0, ',', '.') }} km</span>
                    </span>
                </div>
            </div>
            <div class="mt-4 md:mt-0">
                <img src="{{ asset($vehicle->avatar ?? 'img/default-car.png') }}" class="w-24 h-auto drop-shadow-lg">
            </div>
        </div>

        {{-- SECCIÓN IA (OCR) --}}
        <div
            class="w-full md:w-2/3 mx-auto p-8 rounded-2xl bg-cyan-100/50 dark:bg-cyan-900/30 border border-cyan-200 dark:border-cyan-800 shadow-xl transition-all animate-fade-in">
            <div class="flex flex-col items-center text-center">
                <div class="bg-purple-500 p-2 rounded-lg mb-3 shadow-lg shadow-purple-500/30">
                    <i data-lucide="bot" class="w-7 h-7"></i>
                </div>
                <h2 class="text-2xl font-bold text-cyan-900 dark:text-cyan-100 mb-2">Extracción Inteligente</h2>
                <p class="text-sm text-gray-600 dark:text-cyan-200/70 mb-6">Sube la foto de tu factura y Gemini rellenará
                    los datos por ti.</p>

                <form action="{{ route('vehicles.repairs.ocr', $vehicle->id) }}" method="POST"
                    enctype="multipart/form-data" class="flex flex-col items-center gap-4 w-full max-w-xs">
                    @csrf
                    <div class="w-full group">
                        <input type="file" name="foto" accept="image/*" required
                            class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                               file:rounded-full file:border-0 file:text-sm file:font-semibold
                               file:bg-purple-100 file:text-purple-700 hover:file:bg-purple-200
                               cursor-pointer border-2 border-dashed border-purple-300 dark:border-purple-800/50
                               bg-purple-50/30 dark:bg-purple-900/10 p-4 rounded-xl transition-all">
                    </div>
                    <button type="submit"
                        class="w-full py-3 px-6 bg-linear-to-r from-purple-600 to-purple-700 text-white font-bold rounded-xl hover:from-purple-700 hover:to-purple-800 shadow-lg shadow-purple-500/20 transition-all cursor-pointer">
                        ⚡ Analizar Factura
                    </button>
                </form>
            </div>
        </div>

        {{-- FORMULARIO DE REPARACIÓN --}}
        <div class="w-full max-w-4xl mx-auto bg-white dark:bg-gray-800 p-8 rounded-2xl shadow-2xl">
            <h2
                class="text-xl font-bold text-gray-800 dark:text-white mb-8 border-b pb-4 border-gray-100 dark:border-gray-700">
                Detalles de la Reparación
            </h2>

            <form action="{{ route('repairs.store', $vehicle->id) }}" method="POST" enctype="multipart/form-data"
                class="grid grid-cols-1 md:grid-cols-2 gap-8">
                @csrf

                @if (!empty($ocrData['image_path'] ?? null))
                    <input type="hidden" name="foto_guardada" value="{{ $ocrData['image_path'] }}">
                @endif

                {{-- COLUMNA IZQUIERDA --}}
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-bold text-gray-600 dark:text-gray-400">Nombre del Taller</label>
                        <input type="text" name="taller_nombre"
                            value="{{ old('taller_nombre', $ocrData['taller_nombre'] ?? '') }}"
                            class="p-3 rounded-xl border border-gray-200 dark:bg-gray-700 dark:border-gray-600 outline-none focus:ring-2 focus:ring-cyan-500 transition">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-bold text-gray-600 dark:text-gray-400">Fecha del Servicio</label>
                        <input type="date" name="fecha"
                            value="{{ old('fecha', isset($ocrData['fecha']) ? \Carbon\Carbon::parse($ocrData['fecha'])->format('Y-m-d') : '') }}"
                            required
                            class="p-3 rounded-xl border border-gray-200 dark:bg-gray-700 dark:border-gray-600 outline-none focus:ring-2 focus:ring-cyan-500 transition">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-bold text-gray-600 dark:text-gray-400">Tipo de Servicio</label>
                        <input type="text" name="tipo_servicio"
                            value="{{ old('tipo_servicio', $ocrData['tipo_servicio'] ?? '') }}"
                            placeholder="Ej: Cambio Aceite"
                            class="p-3 rounded-xl border border-gray-200 dark:bg-gray-700 dark:border-gray-600 outline-none focus:ring-2 focus:ring-cyan-500 transition">
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-bold text-gray-600 dark:text-gray-400">Importe Total (€)</label>
                        <input type="number" step="0.01" name="precio"
                            value="{{ old('precio', $ocrData['precio'] ?? '') }}"
                            class="p-3 rounded-xl border border-gray-200 dark:bg-gray-700 dark:border-gray-600 font-bold text-cyan-600 outline-none focus:ring-2 focus:ring-cyan-500 transition">
                    </div>
                </div>

                {{-- COLUMNA DERECHA --}}
                <div class="flex flex-col gap-5">
                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-bold text-gray-600 dark:text-gray-400">Observaciones Técnicas</label>
                        <textarea name="observaciones" rows="5"
                            class="p-3 rounded-xl border border-gray-200 dark:bg-gray-700 dark:border-gray-600 outline-none focus:ring-2 focus:ring-cyan-500 transition resize-none">{{ old('observaciones', $ocrData['observaciones'] ?? '') }}</textarea>
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="text-sm font-bold text-gray-600 dark:text-gray-400">Documento de Factura</label>
                        @if (!empty($ocrData['image_path']))
                            <div class="relative group mt-2">
                                <img src="{{ asset('storage/' . $ocrData['image_path']) }}"
                                    class="w-full h-40 object-cover rounded-xl border-2 border-purple-400 shadow-md">
                                <div
                                    class="absolute inset-0 bg-purple-600/20 rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                    <span
                                        class="bg-white text-purple-700 px-3 py-1 rounded-lg text-xs font-bold shadow-xl">Imagen
                                        cargada por IA</span>
                                </div>
                                <input type="hidden" name="foto_patch" value="{{ $ocrData['image_path'] }}">
                            </div>
                        @else
                            <div
                                class="border-2 border-dashed border-gray-200 dark:border-gray-700 rounded-xl p-8 text-center bg-gray-50 dark:bg-gray-900/50">
                                <p class="text-xs text-gray-400">No se ha subido factura manual</p>
                            </div>
                        @endif
                        <input type="file" name="foto" accept="image/*" class="mt-3 text-xs text-gray-500">
                    </div>
                </div>

                {{-- BOTONES DE ACCIÓN --}}
                <div
                    class="md:col-span-2 flex flex-col md:flex-row gap-4 mt-4 border-t pt-8 border-gray-100 dark:border-gray-700">
                    <button type="submit"
                        class="flex-1 py-4 bg-cyan-800 text-white font-bold rounded-xl hover:bg-cyan-900 shadow-lg shadow-cyan-900/20 transition-all cursor-pointer">
                        💾 Registrar Reparación
                    </button>
                    <button type="reset"
                        class="px-8 py-4 bg-gray-100 dark:bg-gray-700 text-gray-500 dark:text-gray-300 font-bold rounded-xl hover:bg-gray-200 dark:hover:bg-gray-600 transition-all cursor-pointer">
                        Limpiar
                    </button>
                </div>
            </form>
        </div>
    </section>
@endsection
