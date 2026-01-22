<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Car history') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="h-full flex flex-col items-center justify-center p-6 lg:p-8">

    <header class="w-full max-w-[335px] lg:max-w-4xl mb-8 text-center">
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


    <main class="w-full max-w-[300px] md:max-w-[550px]  lg:max-w-3xl">
        @if (session('error'))
            <script>
                alert("{{ session('error') }}");
            </script>
        @endif


        <section
            class="relative w-full p-6 rounded-2xl h-[350px] flex justify-center bg-blue-300/70
               text-left flex-col gap-4">
            <img src="{{ asset('img/logoBg.png') }}"
                    class="hidden md:block md:w-1/2 lg:w-3/5 absolute bottom-4 right-2  opacity-60 pointer-events-none" />
            <h1 class="text-2xl m-2 font-semibold">Login</h1>


            <form method="POST" action="{{ route('login.post') }}" class="w-60 flex flex-col gap-3">
                @csrf

                <input type="email" name="email" placeholder="algo@gmail.com" required
                    class="rounded-lg border-b-gray-700 border p-2 outline-none">



                <input type="password" name="password" placeholder="Contraseña" required
                    class="rounded-lg p-2 border-b-gray-700 border outline-none">
                <div class="mt-4 flex gap-2 justify-center w-full">
                    <button type="submit"
                        class="w-1/2 border border-cyan-800 rounded-2xl bg-cyan-800 text-white p-2
                                   transition cursor-pointer hover:bg-[oklch(0.86_0.07_249.31)] hover:text-cyan-800">
                        Entrar
                    </button>

                    <a href="{{ route('register') }}" class="w-1/2">
                        <button type="button"
                            class="w-full border border-cyan-800 rounded-2xl bg-[oklch(0.86_0.07_249.31)] text-cyan-800 p-2
                                       transition cursor-pointer hover:bg-cyan-800 hover:text-white">
                            Regístrate
                        </button>
                    </a>
                </div>
            </form>

        </section>
    </main>

</body>

</html>
