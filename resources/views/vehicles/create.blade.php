@extends('layouts.app')

@section('content')
<!-- Contenedor general -->
<div class="w-full max-w-[335px] lg:max-w-4xl mb-8 text-center mx-auto mt-8">
    <h1 class="text-4xl font-bold text-cyan-900 dark:text-cyan-300 mb-2">Nuevo Registro</h1>
    <p class="text-base text-gray-700 dark:text-gray-300">
        Selecciona los detalles de tu vehículo para empezar el seguimiento.
    </p>

    <!-- Botón toggle de tema -->
    <button
        onclick="toggleTheme()"
        class="btn btn-primary mt-4"
    >
        Cambiar tema
    </button>
</div>

<main class="w-full max-w-[300px] md:max-w-[550px] lg:max-w-3xl mx-auto">
    <section class="relative w-full p-8 rounded-2xl min-h-[400px] flex flex-col justify-center bg-blue-300/70 dark:bg-gray-800 text-left shadow-xl">

        @if(file_exists(public_path('img/logoBg.png')))
            <img src="{{ asset('img/logoBg.png') }}"
                 class="hidden md:block md:w-1/2 lg:w-3/5 absolute bottom-4 right-2 opacity-40 pointer-events-none" />
        @endif

        <h2 class="text-2xl m-2 font-semibold text-cyan-900 dark:text-cyan-300">Buscador de Vehículo</h2>

        <!-- Formulario -->
        <form action="{{ route('vehiculos.store') }}" method="POST" class="w-full md:w-72 flex flex-col gap-4 mt-4">
            @csrf

            <!-- MARCA (API) -->
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-cyan-900 dark:text-cyan-300 ml-1">Marca</label>
                <select id="marcas" name="marca_nombre" required
                        class="w-full rounded-lg border border-gray-300 p-2.5 bg-white/90 dark:bg-gray-700 dark:text-white">
                    <option value="">Cargando marcas...</option>
                </select>
            </div>

            <!-- MODELO (API) -->
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-cyan-900 dark:text-cyan-300 ml-1">Modelo</label>
                <select id="modelos" name="modelo_id" disabled required
                        class="w-full rounded-lg border border-gray-300 p-2.5 bg-white/90 dark:bg-gray-700 dark:text-white">
                    <option value="">Selecciona una marca primero</option>
                </select>
            </div>

            <!-- MATRICULA -->
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-cyan-900 dark:text-cyan-300 ml-1">Matrícula</label>
                <input type="text" name="matricula" placeholder="1234ABC" required
                       class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white/90 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-800">
            </div>

            <!-- KILOMETROS -->
            <div class="flex flex-col gap-1">
                <label class="text-sm font-medium text-cyan-900 dark:text-cyan-300 ml-1">Kilómetros actuales</label>
                <input type="number" name="kilometros" placeholder="Ej: 50000" required
                       class="w-full rounded-lg border border-gray-300 p-2.5 outline-none bg-white/90 dark:bg-gray-700 dark:text-white focus:ring-2 focus:ring-cyan-800">
            </div>

            <div id="msg" class="text-xs text-red-600 font-medium mt-1 min-h-[1rem]"></div>

            <div class="mt-4 flex gap-2 w-full">
                <button type="submit"
                        class="w-full border border-cyan-800 rounded-2xl bg-cyan-800 text-white p-2.5
                               transition cursor-pointer hover:bg-cyan-900 shadow-md">
                    Guardar Vehículo
                </button>
            </div>
        </form>
    </section>
</main>

<!-- JS DaisyUI toggle + API -->
<script>
    // Toggle de tema con DaisyUI
    function toggleTheme() {
        const html = document.documentElement
        const current = html.getAttribute('data-theme')
        const newTheme = current === 'dark' ? 'light' : 'dark'
        html.setAttribute('data-theme', newTheme)
        localStorage.setItem('theme', newTheme)
    }

    // Cargar preferencia guardada
    const savedTheme = localStorage.getItem('theme')
    if (savedTheme) {
        document.documentElement.setAttribute('data-theme', savedTheme)
    }

    // Código API de marcas y modelos
    document.addEventListener('DOMContentLoaded', function() {
        const selMarcas = document.getElementById('marcas');
        const selModelos = document.getElementById('modelos');
        const msg = document.getElementById('msg');

        const API_BASE = 'https://parallelum.com.br/fipe/api/v1/carros/marcas';

        async function cargarMarcas() {
            try {
                const res = await fetch(API_BASE);
                if (!res.ok) throw new Error("Error al conectar con la API");
                const marcas = await res.json();

                selMarcas.innerHTML = '<option value="">-- Selecciona Marca --</option>';
                marcas.forEach(m => {
                    let opt = document.createElement('option');
                    opt.value = m.codigo;
                    opt.textContent = m.nome;
                    selMarcas.appendChild(opt);
                });
            } catch (e) {
                msg.textContent = "Error: No se pudieron cargar las marcas.";
                console.error(e);
            }
        }

        selMarcas.addEventListener('change', async () => {
            const marcaId = selMarcas.value;
            msg.textContent = "";

            if (!marcaId) {
                selModelos.disabled = true;
                selModelos.innerHTML = '<option value="">Selecciona una marca</option>';
                return;
            }

            selModelos.disabled = false;
            selModelos.innerHTML = '<option value="">Cargando modelos...</option>';

            try {
                const res = await fetch(`${API_BASE}/${marcaId}/modelos`);
                if (!res.ok) throw new Error("Error al obtener modelos");
                const data = await res.json();

                if (data && data.modelos) {
                    selModelos.innerHTML = '<option value="">-- Selecciona Modelo --</option>';
                    data.modelos.forEach(mod => {
                        let opt = document.createElement('option');
                        opt.value = mod.codigo;
                        opt.textContent = mod.nome;
                        selModelos.appendChild(opt);
                    });
                }
            } catch (e) {
                msg.textContent = "Error al cargar los modelos.";
                console.error(e);
            }
        });

        cargarMarcas();
    });
</script>
@endsection
