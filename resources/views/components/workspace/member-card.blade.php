@props(['member', 'metrics', 'isAdmin' => false, 'userColor' => null])

@php
    $activeTasks      = $metrics['active_tasks'] ?? 0;
    $completedTasks   = $metrics['completed_tasks'] ?? 0;
    $assignedProjects = $metrics['assigned_projects'] ?? ($metrics['project_count'] ?? null);

    $weightedWorkload = $metrics['weighted_workload'] ?? $member->weighted_workload ?? ($activeTasks * 2);
    $capacityPct      = min(100, (int) round(($weightedWorkload / 20) * 100));

    $barColor   = $capacityPct > 80 ? 'var(--danger)' : ($capacityPct > 50 ? 'var(--warning)' : 'var(--success)');
    $statusBadge = match(true) {
        $capacityPct > 80 => ['color' => 'var(--danger)',  'bg' => 'var(--danger-dim)',  'border' => 'rgba(248,113,113,0.25)',  'label' => 'Overloaded'],
        $capacityPct > 50 => ['color' => 'var(--warning)', 'bg' => 'var(--warning-dim)', 'border' => 'rgba(251,191,36,0.25)',   'label' => 'Busy'],
        $activeTasks > 0  => ['color' => 'var(--success)', 'bg' => 'var(--success-dim)', 'border' => 'rgba(52,211,153,0.25)',   'label' => 'Active'],
        default           => ['color' => 'var(--text-3)',  'bg' => 'var(--elevated)',     'border' => 'var(--border-2)',         'label' => 'Idle'],
    };

    $roleBadge = $member->role === 'admin'
        ? ['color' => 'var(--accent)', 'bg' => 'var(--accent-dim)', 'border' => 'var(--accent-ring)', 'label' => 'Admin']
        : ['color' => 'var(--text-3)', 'bg' => 'var(--elevated)',   'border' => 'var(--border-2)',    'label' => 'Member'];
@endphp

<div {{ $attributes->merge(['class' => 'ts-card p-4 flex flex-col gap-3 relative']) }}>

    {{-- Header: avatar + info + badges --}}
    <div class="flex items-start gap-3">
        <x-workspace.avatar :user="$member" sizeClass="w-8 h-8" class="flex-shrink-0" />
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-1.5 flex-wrap mb-0.5">
                @if($userColor)
                    <span class="w-2 h-2 rounded-full flex-shrink-0" style="background:{{ $userColor }};"></span>
                @endif
                <h4 class="text-xs font-semibold truncate" style="color:var(--text);letter-spacing:-0.01em;">{{ $member->name }}</h4>
                <span class="ts-badge text-[9px]"
                      style="color:{{ $roleBadge['color'] }};background:{{ $roleBadge['bg'] }};border-color:{{ $roleBadge['border'] }};">
                    {{ $roleBadge['label'] }}
                </span>
            </div>
            <p class="text-[10px] truncate" style="color:var(--text-3);">{{ $member->email }}</p>
        </div>

        <div class="flex items-center gap-1.5 flex-shrink-0">
            <span class="ts-badge text-[10px]"
                  style="color:{{ $statusBadge['color'] }};background:{{ $statusBadge['bg'] }};border-color:{{ $statusBadge['border'] }};">
                {{ $statusBadge['label'] }}
            </span>

            @if($isAdmin)
            <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                <button @click="open = !open"
                        class="w-6 h-6 flex items-center justify-center rounded-md transition-colors"
                        style="color:var(--text-3);"
                        onmouseover="this.style.background='var(--elevated)';this.style.color='var(--text-2)';"
                        onmouseout="this.style.background='';this.style.color='var(--text-3)';"
                        title="Actions">
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 6a2 2 0 110-4 2 2 0 010 4zM10 12a2 2 0 110-4 2 2 0 010 4zM10 18a2 2 0 110-4 2 2 0 010 4z"/>
                    </svg>
                </button>
                <div x-show="open" x-transition class="absolute right-0 top-7 z-50 ts-dropdown w-40">
                    <a href="{{ route('users.edit', $member) }}" class="ts-dropdown-item text-xs">
                        <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color:var(--text-3);"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit User
                    </a>
                    <div class="ts-dropdown-divider"></div>
                    <form action="{{ route('users.destroy', $member) }}" method="POST"
                          onsubmit="return confirm('Remove {{ addslashes($member->name) }}? Their tasks will be unassigned.')">
                        @csrf @method('DELETE')
                        <button type="submit" class="ts-dropdown-item danger text-xs"
                                {{ $member->id === Auth::id() ? 'disabled title=Cannot delete yourself' : '' }}>
                            <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Remove
                        </button>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-1.5">
        @foreach([
            ['label' => 'Active', 'value' => $activeTasks, 'color' => 'var(--text)'],
            ['label' => 'Done',   'value' => $completedTasks, 'color' => 'var(--success)'],
            ['label' => 'Proj.',  'value' => $assignedProjects ?? '—', 'color' => 'var(--text)'],
        ] as $stat)
            <div class="rounded-md px-2 py-2 text-center" style="background:var(--surface-2);border:1px solid var(--border);">
                <div class="text-[9px] font-medium uppercase tracking-wide mb-0.5" style="color:var(--text-3);">{{ $stat['label'] }}</div>
                <div class="text-xs font-semibold tabular" style="color:{{ $stat['color'] }};letter-spacing:-0.01em;">{{ $stat['value'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Capacity bar --}}
    <div>
        <div class="flex justify-between text-[10px] mb-1.5" style="color:var(--text-3);">
            <span>Capacity</span>
            <span class="tabular font-medium" style="color:{{ $barColor }};">{{ $capacityPct }}%</span>
        </div>
        <div class="ts-progress-track">
            <div class="ts-progress-fill" style="width:{{ $capacityPct }}%;background:{{ $barColor }};"></div>
        </div>
    </div>
</div>
