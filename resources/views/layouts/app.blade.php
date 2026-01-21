<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Car history') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-[#FDFDFC] dark:bg-[#0a0a0a] text-[#1b1b18] flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
        <h1 class="text-4xl font-bold text-cyan-900 mb-2">
            Bienvenido, {{ session('usuario_nombre') ?? 'Invitado' }}
        </h1>
        <div>
            <a href="{{ route('home') }}">
                <button class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md">Inicio</button>
            </a>
            <a href="{{ route('vehicles.index') }}">
                <button class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md">Vehiculos</button>
            </a>
            <a href="{{ route('history') }}">
                <button class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md">Historial</button>
            </a>
            <a href="{{ route('logout') }}">
                <button type="button" class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md">Salir</button>
            </a>
        </div>
    </header>

    <div class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            @yield('content')
        </main>
    </div>
</body>
</html>
