<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'TeamSync') }}</title>
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">
    <script>
        (function () {
            var saved = localStorage.getItem('ts-theme') || 'dark';
            document.documentElement.classList.remove('dark', 'light');
            document.documentElement.classList.add(saved);
            window.toggleTheme = function () {
                var root = document.documentElement;
                var next = root.classList.contains('light') ? 'dark' : 'light';
                root.classList.remove('dark', 'light');
                root.classList.add(next);
                localStorage.setItem('ts-theme', next);
                document.querySelectorAll('.icon-sun').forEach(function (el) { el.classList.toggle('hidden', next !== 'dark'); });
                document.querySelectorAll('.icon-moon').forEach(function (el) { el.classList.toggle('hidden', next !== 'light'); });
            };
        })();
    </script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased min-h-screen flex flex-col" style="background:var(--bg);color:var(--text);">

    {{-- Top bar --}}
    <div class="fixed top-4 right-4 z-50">
        <button class="theme-btn" onclick="toggleTheme()" aria-label="Toggle theme">
            <svg class="icon-sun w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 3v1m0 16v1m8.66-9H21M3 12H2m15.36-6.36l-.71.71M6.34 17.66l-.71.71M17.66 17.66l-.71-.71M6.34 6.34l-.71-.71M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
            </svg>
            <svg class="icon-moon w-3.5 h-3.5 hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
            </svg>
        </button>
    </div>

    {{-- Centered auth card --}}
    <div class="flex flex-1 items-center justify-center p-4">
        <div class="w-full max-w-md">

            {{-- Logo --}}
            <div class="text-center mb-8">
                <a href="/" class="inline-flex flex-col items-center gap-3">
                    <x-logo class="h-10 w-auto rounded-xl overflow-hidden" />
                    <span class="text-base font-semibold" style="color:var(--text);letter-spacing:-0.03em;">TeamSync</span>
                </a>
            </div>

            {{-- Card --}}
            <div class="ts-panel px-10 py-12 sm:px-12 sm:py-14" style="box-shadow:var(--shadow-md);">
                {{ $slot }}
            </div>

            {{-- Footer --}}
            <p class="text-center text-[11px] mt-6" style="color:var(--text-3);">
                TeamSync Workspace Platform &middot; &copy; 2026 Arshath AD
            </p>
        </div>
    </div>

</body>
</html>
