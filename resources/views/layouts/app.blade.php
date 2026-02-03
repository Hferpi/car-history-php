<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Car history') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body
    class="bg-white text-gray-900 dark:bg-gray-900 dark:text-gray-100 flex p-6 lg:p-8 items-center lg:justify-center min-h-screen flex-col">
    <header class="w-full lg:max-w-4xl max-w-[335px] text-sm mb-6">
        <h1 class="text-4xl font-bold text-cyan-900 dark:text-cyan-400 mb-2">
            Bienvenido, {{ session('usuario_nombre') ?? 'Invitado' }}
        </h1>
        <div class="flex justify-between p-4">
            <div>
                <a href="{{ route('home') }}">
                    <button
                        class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md transition">Inicio</button>
                </a>
                <a href="{{ route('garaje') }}">
                    <button
                        class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md transition">Garaje</button>
                </a>
                <a href="{{ route('history') }}">
                    <button
                        class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md transition">Historial</button>
                </a>
                <a href="{{ route('logout') }}">
                    <button type="button"
                        class="cursor-pointer bg-blue-400 p-2 hover:bg-blue-600 hover:text-white border rounded-md transition">Salir</button>
                </a>
            </div>
            <div>
                <button
                    class="text-gray-700 hover:text-cyan-600
           dark:text-yellow-400 dark:hover:text-yellow-300
           transition cursor-pointer"
                    type="button" id="theme-toggle" title="Toggle theme" aria-label="Toggle theme">
                    <svg xmlns="http://www.w3.org/2000/svg" aria-hidden="true" width="2em" height="2em"
                        fill="currentColor" stroke-linecap="round" viewBox="0 0 32 32"
                        class="transition-transform duration-300
           rotate-0 scale-100
           dark:rotate-180 dark:scale-90">

                        <clipPath id="theme-toggle__classic__cutout">
                            <path d="M0-5h30a1 1 0 0 0 9 13v24H0Z" />
                        </clipPath>
                        <g clip-path="url(#theme-toggle__classic__cutout)">
                            <circle cx="16" cy="16" r="9.34" />
                            <g stroke="currentColor" stroke-width="1.5">
                                <path d="M16 5.5v-4" />
                                <path d="M16 30.5v-4" />
                                <path d="M1.5 16h4" />
                                <path d="M26.5 16h4" />
                                <path d="m23.4 8.6 2.8-2.8" />
                                <path d="m5.7 26.3 2.9-2.9" />
                                <path d="m5.8 5.8 2.8 2.8" />
                                <path d="m23.4 23.4 2.9 2.9" />
                            </g>
                        </g>
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <div
        class="flex items-center justify-center w-full transition-opacity opacity-100 duration-750 lg:grow starting:opacity-0">
        <main class="flex w-full flex-col-reverse lg:max-w-4xl lg:flex-row">
            @yield('content')
        </main>
    </div>

    <x-footer />
</body>

</html>
