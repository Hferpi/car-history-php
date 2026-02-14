<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Car history') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="h-full flex flex-col items-center justify-center p-6 lg:p-8">

    <header class="w-full max-w-83 lg:max-w-4xl mb-8 text-center">
        <h1 class="text-4xl font-bold text-cyan-900 mb-2">
            Car History Tracker
        </h1>

        <p class="text-base text-gray-700 mb-1">
            Lleva un control completo del mantenimiento de tu vehículo.
        </p>

        <p class="text-sm text-gray-600">
            Seguimiento de cambios de aceite, neumáticos, reparaciones y facturas,
            todo en un solo lugar con un tracking claro y eficiente.
        </p>
    </header>


    <main class="w-full max-w-75 md:max-w-137.5  lg:max-w-3xl">
        @if (session('error'))
            <script>
                alert("{{ session('error') }}");
            </script>
        @endif


        <section class="w-full rounded-2xl bg-blue-300/70 overflow-hidden flex flex-col md:flex-row min-h-100">

            <div class="w-full md:w-1/2 p-8 flex flex-col justify-center items-center md:items-start text-left gap-4">
                <h1 class="text-3xl font-semibold text-cyan-900">Login</h1>

                <form method="POST" action="{{ route('login.post') }}" class="w-full max-w-xs flex-1 flex flex-col gap-4">
                    @csrf
                    <div class="flex flex-1 flex-col gap-4 justify-center">
                        <div class="flex flex-col gap-1">
                            <input type="email" name="email" placeholder="algo@gmail.com" required
                                class="rounded-lg border border-gray-300 p-3 outline-none focus:border-cyan-800 transition shadow-sm">
                        </div>

                        <div class="flex flex-col gap-1">
                            <input type="password" name="password" placeholder="Contraseña" required
                                class="rounded-lg border border-gray-300 p-3 outline-none focus:border-cyan-800 transition shadow-sm">
                        </div>
                    </div>
                    <div class="mt-4 flex gap-3 justify-center w-full">
                        <button type="submit"
                            class="w-1/2 border border-cyan-800 rounded-2xl bg-cyan-800 text-white py-2.5
                               transition cursor-pointer hover:bg-[oklch(0.86_0.07_249.31)] hover:text-cyan-800 font-medium">
                            Entrar
                        </button>

                        <a href="{{ route('register') }}" class="w-1/2">
                            <button type="button"
                                class="w-full border border-cyan-800 rounded-2xl bg-[oklch(0.86_0.07_249.31)] text-cyan-800 py-2.5
                                   transition cursor-pointer hover:bg-cyan-800 hover:text-white font-medium">
                                Regístrate
                            </button>
                        </a>
                    </div>
                </form>
            </div>

            <div class="hidden md:flex md:w-1/2 items-center justify-center p-6 bg-white/20">
                <img src="{{ asset('img/logoBg1.png') }}" alt="Logo Background"
                    class="max-w-full h-auto object-contain drop-shadow-xl" />
            </div>

        </section>
    </main>

</body>

</html>
