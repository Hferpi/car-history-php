@extends('layouts.app')

@section('content')
    <?php
    $avatars = [
        'car1' => 'img/cars-icons/blue-rm.png',
        'car2' => 'img/cars-icons/cyan-rm.png',
        'car3' => 'img/cars-icons/jeep-rm.png',
        'car4' => 'img/cars-icons/red-rm.png',
    ];
    ?>

    <section
        class="relative w-full p-6 rounded-2xl flex justify-center bg-sky-200 dark:bg-gray-600
               text-left flex-col gap-4">
        <img id="img-avatar"
            class="hidden md:block md:w-1/3 lg:w-90 absolute bottom-4 right-30" />
        <h1 class="text-2xl m-2 font-semibold">Crear un coche</h1>

        <!--Depuracion de errores-->
        @if ($errors->any())
            <div style="background: rgba(255,0,0,0.2); color: red; padding: 10px; border-radius: 5px; margin-bottom: 20px;">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('vehicles.store') }}" method="POST" class="flex gap-4 flex-col w-60">
            @csrf

            <select id="marca" name="marca_id" class="cursor-pointer" required data-marcas='@json($marcas)'>
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

                    <input type="text" name="matricula" required
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
                    <button type="button" class="avatar-btn border-0 rounded p-1 hover:drop-shadow-xl/50 cursor-pointer"
                        data-value="{{ $ruta }}">
                        <img src="{{ asset($ruta) }}" class="w-12 h-12 object-contain" alt="{{ $key }}">
                    </button>
                @endforeach
            </div>

            <!-- Input oculto que se enviará al formulario -->
            <input type="hidden" name="avatar" id="avatar-hidden">


            <button type="submit" class="p-4 border-2 cursor-pointer  bg-blue-400 rounded hover:bg-blue-600 hover:text-white" rounded-2xl">Crear Vehículo</button>
        </form>


    </section>
    <script>
        // --- LÓGICA DE MARCAS Y MODELOS ---
        const marcaSelect = document.getElementById('marca');
        const modeloSelect = document.getElementById('modelo');
        const marcasData = JSON.parse(marcaSelect.getAttribute('data-marcas'));

        marcaSelect.addEventListener('change', function() {
            const marcaId = this.value;
            modeloSelect.innerHTML = '<option value="">Selecciona modelo</option>';

            if (marcaId) {
                // Buscamos la marca seleccionada en el JSON
                const marcaSeleccionada = marcasData.find(m => m.id == marcaId);

                if (marcaSeleccionada && marcaSeleccionada.modelos) {
                    marcaSeleccionada.modelos.forEach(modelo => {
                        const option = document.createElement('option');
                        option.value = modelo.id;
                        option.textContent = modelo.nombre;
                        modeloSelect.appendChild(option);
                    });
                    modeloSelect.disabled = false;
                }
            } else {
                modeloSelect.disabled = true;
            }
        });

        // --- LÓGICA DE SELECCIÓN DE AVATAR ---
        const avatarButtons = document.querySelectorAll('.avatar-btn');
        const avatarHiddenInput = document.getElementById('avatar-hidden');

        avatarButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                // Quitar el borde resaltado de todos
                avatarButtons.forEach(b => b.classList.remove('bg-blue-500', 'ring-2', 'ring-blue-600'));

                // Añadir resaltado al seleccionado
                this.classList.add('bg-blue-500', 'ring-2', 'ring-blue-600');

                // Guardar el valor en el input oculto
                avatarHiddenInput.value = this.getAttribute('data-value');
            });
        });
    </script>

    <style>
        /* Un pequeño estilo para que el botón seleccionado se note */
        .avatar-btn.bg-blue-500 {
            transition: all 0.2s;
            transform: scale(1.1);
        }
    </style>
@endsection
