@extends('layouts.app')

@section('content')
    <section
        class="relative w-full p-6 rounded-2xl flex justify-center bg-sky-200 dark:bg-gray-600 text-left flex-col gap-6">

        <div class="w-full flex flex-col items-center max-w-4xl mx-auto bg-white dark:bg-gray-700 p-6 rounded-xl shadow">

            {{-- VEHÍCULO --}}
            <h1 class="text-2xl font-semibold mb-4">
                Reparaciones de {{ $vehicle->marca }} {{ $vehicle->modelo->nombre }}
            </h1>

            <div class="mb-6 text-sm text-gray-700 dark:text-gray-200">
                <p><strong>Matrícula:</strong> {{ $vehicle->matricula }}</p>
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

            {{-- FORMULARIO OCR --}}
            <div class="mb-6 w-full flex flex-col items-center">
                <h2 class="text-xl font-semibold mb-2">Extraer datos con OCR</h2>

                {{-- ✅ Ruta corregida: vehicles.repairs.ocr --}}
                <form action="{{ route('vehicles.repairs.ocr', $vehicle->id) }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col items-center gap-2 w-64">
                    @csrf
                    <input type="file" name="foto" accept="image/*" required
                        class="p-2 rounded border cursor-pointer">
                    <button type="submit" class="mt-2 p-2 bg-green-600 text-white rounded hover:bg-green-700">
                        Extraer datos
                    </button>
                </form>
            </div>

            {{-- FORMULARIO REPARACIÓN --}}
            <div class="w-full flex flex-col items-center">
                <h2 class="text-xl font-semibold mb-2">Rellenar o revisar datos</h2>

                {{-- ✅ Ruta corregida: repairs.store (o vehicles.repairs.store si la renombras) --}}
                <form action="{{ route('repairs.store', $vehicle->id) }}" method="POST" enctype="multipart/form-data"
                    class="flex flex-col items-center gap-4 w-full">
                    @csrf

                    {{-- Si venimos del OCR, pasar la foto --}}
                    @if (!empty($ocrData['image_path'] ?? null))
                        <input type="hidden" name="foto_guardada" value="{{ $ocrData['image_path'] }}">
                    @endif

                    {{-- TALLER --}}
                    <div class="flex flex-col w-64 gap-1">
                        <label class="font-semibold">Taller</label>
                        <input type="text" name="taller_nombre"
                            value="{{ old('taller_nombre', $ocrData['taller_nombre'] ?? '') }}"
                            placeholder="Nombre del taller" class="p-2 rounded border">
                    </div>

                    {{-- FECHA --}}
                    <div class="flex flex-col w-64 gap-1">
                        <label class="font-semibold">Fecha</label>
                        <input type="date" name="fecha" {{-- Usamos Carbon para formatear a Y-m-d obligatoriamente --}}
                            value="{{ old('fecha', isset($ocrData['fecha']) ? \Carbon\Carbon::parse($ocrData['fecha'])->format('Y-m-d') : '') }}"
                            required class="p-2 rounded border">
                    </div>

                    {{-- TIPO SERVICIO --}}
                    <div class="flex flex-col w-64 gap-1">
                        <label class="font-semibold">Tipo de servicio</label>
                        <input type="text" name="tipo_servicio"
                            value="{{ old('tipo_servicio', $ocrData['tipo_servicio'] ?? '') }}"
                            placeholder="Cambio de aceite, frenos, ITV..." class="p-2 rounded border">
                    </div>

                    {{-- OBSERVACIONES --}}
                    <div class="flex flex-col w-64 gap-1">
                        <label class="font-semibold">Observaciones</label>
                        <textarea name="observaciones" rows="4" class="p-2 rounded border">{{ old('observaciones', $ocrData['observaciones'] ?? '') }}</textarea>
                    </div>

                    {{-- PRECIO --}}
                    <div class="flex flex-col w-64 gap-1">
                        <label class="font-semibold">Precio (€)</label>
                        <input type="number" step="0.01" name="precio"
                            value="{{ old('precio', $ocrData['precio'] ?? '') }}" class="p-2 rounded border">
                    </div>

                    {{-- FOTO DEL RECIBO --}}
                    <div class="flex flex-col w-64 gap-1">
                        <label class="font-semibold">Foto del recibo</label>

                        {{-- Usamos @if(!empty(...)) para asegurar que detecta el string del path --}}
                        @if (!empty($ocrData['image_path']))
                            <div class="mb-2">
                                {{-- Prueba a poner el alt para debuggear si la imagen no carga --}}
                                <img src="{{ asset('storage/' . $ocrData['image_path']) }}"
                                    alt="Imagen: {{ $ocrData['image_path'] }}"
                                    class="w-48 h-auto rounded-lg border-2 border-blue-400 shadow-md">

                                <p class="text-xs text-blue-600 mt-1 font-medium">✓ Imagen detectada</p>
                            </div>

                            {{-- Importante: Estos campos envían la info al botón "Guardar" --}}
                            <input type="hidden" name="foto_patch" value="{{ $ocrData['image_path'] }}">
                            <input type="hidden" name="foto_disk" value="{{ $ocrData['foto_disk'] ?? 'public' }}">
                        @else
                            <div class="mb-2 p-4 border-2 border-dashed border-gray-300 rounded-lg text-center bg-gray-50">
                                <p class="text-xs text-gray-500">No hay imagen cargada.</p>
                            </div>
                        @endif

                        {{-- Input para cambiar la foto si el OCR falló --}}
                        <input type="file" name="foto" accept="image/*" class="p-2 rounded border cursor-pointer text-xs">
                    </div>

                    {{-- BOTONES --}}
                    <div class="w-64 flex items-center gap-2">
                        <button type="submit"
                            class="mt-4 p-3 w-36 bg-blue-500 text-white rounded hover:bg-blue-600 transition cursor-pointer">
                            Guardar reparación
                        </button>
                        <button type="reset"
                            class="mt-4 p-3 w-36 bg-gray-400 text-white rounded hover:bg-gray-600 transition cursor-pointer">
                            Borrar datos
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
