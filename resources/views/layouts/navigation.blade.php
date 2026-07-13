{{-- Sculpted Floating Rail Navigation --}}
<nav class="ts-rail flex-shrink-0 z-50 flex flex-col items-center py-5" 
     style="width: 68px; height: max-content; background: var(--surface); border: 1px solid var(--border); border-radius: 34px; box-shadow: var(--shadow-sm);">

    {{-- Logo / Top Section --}}
    <div class="flex-shrink-0 flex justify-center w-full relative group mb-8">
        <a href="{{ route('dashboard') }}" class="w-12 h-12 flex items-center justify-center rounded-[20px] transition-colors hover:bg-[var(--surface-2)]">
            <x-logo class="h-6 w-auto flex-shrink-0" />
        </a>
        
        {{-- Hover Panel (Contextual) --}}
        <div class="absolute left-[calc(100%+16px)] top-1/2 -translate-y-1/2 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 translate-x-[-8px] group-hover:translate-x-0 z-50">
            <div class="px-4 py-3 rounded-[16px] flex flex-col" style="background: var(--surface); border: 1px solid var(--border); box-shadow: var(--shadow-md); min-width: 160px;">
                <span class="text-sm font-semibold" style="color:var(--text);letter-spacing:-0.01em;">TeamSync</span>
                <span class="text-[11px]" style="color:var(--text-3);">Command Center</span>
            </div>
        </div>
    </div>

    {{-- Main Navigation Items --}}
    <div class="flex flex-col items-center w-full gap-4 mb-8 relative">
        @php
            $role = Auth::user()->role ?? 'user';

            $navItems = [
                [
                    'route'  => 'dashboard',
                    'label'  => 'Dashboard',
                    'sub'    => 'Overview & Metrics',
                    'active' => request()->routeIs('dashboard') && request('section') !== 'members',
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>',
                ],
                [
                    'route'  => 'projects.index',
                    'label'  => 'Projects',
                    'sub'    => 'Active Workspaces',
                    'active' => request()->routeIs('projects.*'),
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>',
                ],
                [
                    'route'  => 'tasks.index',
                    'label'  => 'Task Board',
                    'sub'    => 'Global Timeline',
                    'active' => request()->routeIs('tasks.index') || request()->routeIs('tasks.board'),
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>',
                ],
                [
                    'route'  => 'tasks.myTasks',
                    'label'  => 'My Tasks',
                    'sub'    => 'Assigned to You',
                    'active' => request()->routeIs('tasks.myTasks'),
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>',
                ],
            ];

            if ($role === 'admin') {
                $navItems[] = [
                    'is_divider' => true
                ];
                $navItems[] = [
                    'route'  => 'dashboard',
                    'route_params' => ['section' => 'members'],
                    'label'  => 'Team Directory',
                    'sub'    => 'Administration',
                    'active' => request('section') === 'members',
                    'icon'   => '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>',
                ];
            }
        @endphp

        @foreach($navItems as $item)
            @if(isset($item['is_divider']))
                <div class="w-6 h-px my-1" style="background:var(--border);"></div>
            @else
                <div class="relative group w-full flex justify-center">
                    <a href="{{ isset($item['route_params']) ? route($item['route'], $item['route_params']) : route($item['route']) }}"
                       class="ts-rail-item flex items-center justify-center transition-all duration-200 {{ $item['active'] ? 'active' : '' }}"
                       aria-label="{{ $item['label'] }}">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            {!! $item['icon'] !!}
                        </svg>
                    </a>
                    
                    {{-- Contextual Hover Panel --}}
                    <div class="absolute left-[calc(100%+16px)] top-1/2 -translate-y-1/2 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 translate-x-[-8px] group-hover:translate-x-0 z-50">
                        <div class="px-4 py-2.5 rounded-[14px] flex flex-col whitespace-nowrap" style="background: var(--surface); border: 1px solid var(--border); box-shadow: var(--shadow-md);">
                            <div class="flex items-center gap-2">
                                <div class="w-1.5 h-1.5 rounded-full {{ $item['active'] ? 'bg-[var(--accent)]' : 'bg-transparent' }}"></div>
                                <span class="text-[13px] font-medium" style="color:var(--text);letter-spacing:-0.01em;">{{ $item['label'] }}</span>
                            </div>
                            <span class="text-[10px] pl-3.5 mt-0.5 uppercase tracking-wide" style="color:var(--text-3);">{{ $item['sub'] }}</span>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>

    {{-- Bottom Section / User Profile --}}
    <div class="flex-shrink-0 w-full flex justify-center relative group">
        <a href="{{ route('profile.edit') }}" class="w-11 h-11 flex items-center justify-center rounded-full transition-transform duration-200 hover:scale-105" style="border: 2px solid var(--surface-2);">
            <x-workspace.avatar :user="Auth::user()" sizeClass="w-full h-full" />
        </a>
        
        {{-- Profile Hover Panel --}}
        <div class="absolute left-[calc(100%+16px)] top-1/2 -translate-y-1/2 opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-200 translate-x-[-8px] group-hover:translate-x-0 z-50">
            <div class="px-4 py-3 rounded-[16px] flex flex-col whitespace-nowrap" style="background: var(--surface); border: 1px solid var(--border); box-shadow: var(--shadow-md);">
                <span class="text-[13px] font-semibold" style="color:var(--text);letter-spacing:-0.01em;">{{ Auth::user()->name }}</span>
                <span class="text-[11px] capitalize mt-0.5" style="color:var(--text-3);">{{ Auth::user()->role ?? 'User' }} &middot; Settings</span>
            </div>
        </div>
    </div>
</nav>
