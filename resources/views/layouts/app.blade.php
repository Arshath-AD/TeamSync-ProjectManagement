<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ isset($pageTitle) ? $pageTitle . ' · ' : '' }}{{ config('app.name', 'TeamSync') }}</title>
    <meta name="description" content="TeamSync — professional team collaboration and project management platform.">
    <link rel="icon" type="image/svg+xml" href="{{ asset('images/logo.svg') }}">

    {{-- Sync theme before paint to prevent flash --}}
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
                document.querySelectorAll('.icon-sun').forEach(function (el) {
                    el.classList.toggle('hidden', next !== 'dark');
                });
                document.querySelectorAll('.icon-moon').forEach(function (el) {
                    el.classList.toggle('hidden', next !== 'light');
                });
            };

            // Sidebar collapse logic removed for new rail design
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,300;0,14..32,400;0,14..32,500;0,14..32,600;0,14..32,700;1,14..32,400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased h-screen overflow-hidden flex p-6 gap-6" style="background:var(--bg); color:var(--text);">

    {{-- 1. Floating Sculpted Rail --}}
    <div class="h-full flex-shrink-0 z-50 flex flex-col justify-center">
        @include('layouts.navigation')
    </div>

    {{-- Right Column (Header + Content) --}}
    <div class="flex-1 flex flex-col min-w-0 min-h-0 gap-6 h-full relative">

        {{-- 2. Floating Top Application Bar --}}
        <header class="flex-shrink-0 h-16 flex items-center justify-between px-6 rounded-lg" style="background: var(--surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
            
            {{-- Left: Page Title --}}
            <div class="flex items-center gap-3 min-w-0">
                @if(isset($header))
                    <h1 class="text-[15px] font-semibold truncate" style="color:var(--text); letter-spacing:-0.01em;">{{ $header }}</h1>
                @endif
            </div>

            {{-- Right: Actions & Search --}}
            <div class="flex items-center gap-4 flex-shrink-0">
                
                {{-- Integrated Search (Replaced with App Name) --}}
                <div class="hidden md:flex items-center justify-center gap-2 px-3 py-3 rounded-full w-[278px]" style="background: var(--bg); border: 1px solid var(--border-2); box-shadow: inset 0 1px 2px rgba(0,0,0,0.05);">
                    <span class="text-[13px] font-semibold tracking-wide" style="color:var(--text-2);">Team Sync</span>
                </div>

                <div class="w-px h-5 flex-shrink-0" style="background:var(--border);"></div>

                {{-- Theme Toggle --}}
                <button class="w-8 h-8 flex items-center justify-center rounded-full transition-colors hover:bg-[var(--elevated)]" onclick="toggleTheme()" aria-label="Toggle theme">
                    <svg class="icon-sun w-4 h-4 text-[var(--text-2)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m8.66-9H21M3 12H2m15.36-6.36l-.71.71M6.34 17.66l-.71.71M17.66 17.66l-.71-.71M6.34 6.34l-.71-.71M12 5a7 7 0 100 14A7 7 0 0012 5z"/>
                    </svg>
                    <svg class="icon-moon w-4 h-4 hidden text-[var(--text-2)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12.79A9 9 0 1111.21 3 7 7 0 0021 12.79z"/>
                    </svg>
                </button>

                {{-- Notifications --}}
                <button class="w-8 h-8 flex items-center justify-center rounded-full transition-colors hover:bg-[var(--elevated)] relative" aria-label="Notifications">
                    <svg class="w-4 h-4 text-[var(--text-2)]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-5-5.917V4a1 1 0 10-2 0v1.083A6 6 0 006 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    <span class="absolute top-1.5 right-1.5 w-2 h-2 rounded-full bg-[var(--danger)] border-2 border-[var(--surface)]"></span>
                </button>

                {{-- Profile Menu (Minimal) --}}
                <div class="relative ml-1">
                    <button id="profile-trigger" class="flex items-center justify-center w-8 h-8 rounded-full transition-transform hover:scale-105" style="border: 2px solid var(--border-2);">
                        <x-workspace.avatar :user="Auth::user()" sizeClass="w-full h-full" />
                    </button>
                    {{-- Profile Dropdown --}}
                    <div id="profile-menu" class="ts-dropdown hidden absolute right-0 mt-3 fade-in" style="min-width:200px;z-index:40;">
                        <div class="px-3 py-2.5 mb-1" style="border-bottom:1px solid var(--border);">
                            <div class="text-sm font-semibold" style="color:var(--text);letter-spacing:-0.01em;">{{ Auth::user()->name }}</div>
                            <div class="text-xs mt-0.5" style="color:var(--text-2);">{{ Auth::user()->email }}</div>
                        </div>
                        <a href="{{ route('profile.edit') }}" class="ts-dropdown-item">Profile</a>
                        <button type="button" class="ts-dropdown-item" onclick="window.dispatchEvent(new CustomEvent('open-about'))">About TeamSync</button>
                        <div class="ts-dropdown-divider"></div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="ts-dropdown-item danger">Sign out</button>
                        </form>
                    </div>
                </div>

            </div>
        </header>

        {{-- 3. Content Area --}}
        <main class="flex-1 min-h-0 overflow-y-auto ts-scroll rounded-lg" style="background: var(--surface); border: 1px solid var(--border); box-shadow: var(--shadow-sm);">
            <div class="p-8 min-h-full">
                @if(session('success'))
                    <div class="ts-alert-success mb-6 fade-in" data-flash>{{ session('success') }}</div>
                @endif
                @if(session('error'))
                    <div class="ts-alert-danger mb-6 fade-in" data-flash>{{ session('error') }}</div>
                @endif
                {{ $slot }}
            </div>
        </main>
    </div>

    {{-- About Modal --}}
    <div x-data="{ open: false }"
         @open-about.window="open = true"
         @keydown.escape.window="open = false"
         class="relative z-50"
         style="display: none;"
         x-show="open">

        <div x-show="open" x-transition.opacity class="fixed inset-0" style="background:rgba(0,0,0,0.6);backdrop-filter:blur(4px);"></div>

        <div class="fixed inset-0 overflow-y-auto">
            <div class="flex min-h-full items-center justify-center p-4">
                <div x-show="open"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 transform scale-95"
                     x-transition:enter-end="opacity-1 transform scale-100"
                     @click.away="open = false"
                     class="ts-panel w-full max-w-sm p-8 relative text-center"
                     style="box-shadow:var(--shadow-xl);">

                    <button @click="open = false"
                            class="absolute top-4 right-4 w-7 h-7 flex items-center justify-center rounded-lg transition-colors"
                            style="color:var(--text-2);"
                            onmouseover="this.style.background='var(--elevated)';" onmouseout="this.style.background='';">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex flex-col items-center mb-6">
                        <x-logo class="h-10 w-auto mb-4 rounded-xl overflow-hidden" />
                        <h2 class="text-base font-semibold" style="color:var(--text);letter-spacing:-0.02em;">TeamSync</h2>
                        <p class="text-xs mt-1" style="color:var(--text-2);">Version 1.0</p>
                    </div>

                    <div class="text-sm mb-6" style="color:var(--text-2);">
                        <p>Created and maintained by</p>
                        <p class="font-medium mt-1" style="color:var(--text);">Arshath AD</p>
                    </div>

                    <div class="h-px mb-6" style="background:var(--border);"></div>

                    <div class="flex items-center justify-center gap-5 mb-6">
                        <a href="https://linkedin.com/in/arshathad" target="_blank" rel="noopener" class="text-sm font-medium transition-colors" style="color:var(--accent);" onmouseover="this.style.color='var(--accent-hov)';" onmouseout="this.style.color='var(--accent)';">LinkedIn</a>
                        <div class="w-px h-4" style="background:var(--border);"></div>
                        <a href="https://github.com/Arshath-AD" target="_blank" rel="noopener" class="text-sm font-medium transition-colors" style="color:var(--accent);" onmouseover="this.style.color='var(--accent-hov)';" onmouseout="this.style.color='var(--accent)';">GitHub</a>
                        <div class="w-px h-4" style="background:var(--border);"></div>
                        <a href="https://arshath-ad.github.io" target="_blank" rel="noopener" class="text-sm font-medium transition-colors" style="color:var(--accent);" onmouseover="this.style.color='var(--accent-hov)';" onmouseout="this.style.color='var(--accent)';">Portfolio</a>
                    </div>

                    <div class="h-px mb-5" style="background:var(--border);"></div>

                    <div class="text-[11px]" style="color:var(--text-3);">
                        &copy; 2026 Arshath AD &middot; All Rights Reserved
                    </div>
                </div>
            </div>
        </div>
    </div>

</body>
</html>
