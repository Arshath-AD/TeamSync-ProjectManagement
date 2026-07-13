@props(['project', 'url'])

@php
    $metrics = $project->metrics ?? app(\App\Services\ProjectService::class)->getProjectMetrics($project);
    $activeTasks = $metrics['active_tasks'] ?? ($metrics['task_count'] - $metrics['completed_task_count']);

    $hasOverdueTasks = $project->relationLoaded('tasks')
        ? $project->tasks->contains(fn($t) => !in_array($t->status, ['Completed', 'On Hold']) && $t->deadline && \Carbon\Carbon::parse($t->deadline)->isBefore(now()->startOfDay()))
        : $project->tasks()->whereNotIn('status', ['Completed', 'On Hold'])->whereNotNull('deadline')->whereDate('deadline', '<', now())->exists();

    $riskLevel = match(true) {
        $hasOverdueTasks => 'critical',
        $activeTasks > 8 && $metrics['completion_percentage'] < 40 => 'high',
        $metrics['completion_percentage'] < 30 && $metrics['task_count'] > 0 => 'medium',
        default => 'low',
    };

    $riskStyle = match($riskLevel) {
        'critical' => ['color' => 'var(--danger)',  'bg' => 'var(--danger-dim)',  'border' => 'rgba(248,113,113,0.25)',  'label' => 'At Risk'],
        'high'     => ['color' => 'var(--warning)', 'bg' => 'var(--warning-dim)', 'border' => 'rgba(251,191,36,0.25)',   'label' => 'High Load'],
        'medium'   => ['color' => 'var(--warning)', 'bg' => 'var(--warning-dim)', 'border' => 'rgba(251,191,36,0.20)',   'label' => 'Behind'],
        default    => ['color' => 'var(--success)', 'bg' => 'var(--success-dim)', 'border' => 'rgba(52,211,153,0.25)',   'label' => 'On Track'],
    };

    $priority = $project->priority ?? 'Medium';
    $priorityBadge = match($priority) {
        'Critical' => ['color' => 'var(--danger)',  'bg' => 'var(--danger-dim)',  'border' => 'rgba(248,113,113,0.25)'],
        'High'     => ['color' => 'var(--warning)', 'bg' => 'var(--warning-dim)', 'border' => 'rgba(251,191,36,0.25)'],
        'Medium'   => ['color' => 'var(--accent)',  'bg' => 'var(--accent-dim)',  'border' => 'var(--accent-ring)'],
        default    => ['color' => 'var(--success)', 'bg' => 'var(--success-dim)', 'border' => 'rgba(52,211,153,0.25)'],
    };

    $deadline = $project->deadline ?? $project->end_date ?? null;
    if (!$deadline && $project->relationLoaded('tasks')) {
        $nextDeadline = $project->tasks->where('status', '!=', 'Completed')->whereNotNull('deadline')->sortBy('deadline')->first();
        $deadline = $nextDeadline?->deadline;
    }
    $pct = $metrics['completion_percentage'];
    $barColor = $pct >= 80 ? 'var(--success)' : ($pct >= 40 ? 'var(--accent)' : 'var(--warning)');
    $members = $project->members ?? collect();
    $displayMembers = $members->take(4);
    $extraMembers = $members->count() - 4;
@endphp

<a href="{{ $url }}" class="ts-card block p-4 hover-lift" style="text-decoration:none;">
    {{-- Header --}}
    <div class="flex items-start justify-between gap-2 mb-3">
        <div class="min-w-0 flex-1">
            <h3 class="text-sm font-semibold leading-tight truncate transition-colors"
                style="color:var(--text);letter-spacing:-0.01em;"
                onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text)'">
                {{ $project->project_name }}
            </h3>
            @if($project->lead ?? null)
                <p class="text-[11px] mt-0.5 truncate" style="color:var(--text-3);">
                    {{ $project->lead->name }}
                </p>
            @endif
        </div>
        {{-- Health badge --}}
        <span class="ts-badge flex-shrink-0 text-[10px]"
              style="color:{{ $riskStyle['color'] }};background:{{ $riskStyle['bg'] }};border-color:{{ $riskStyle['border'] }};">
            {{ $riskStyle['label'] }}
        </span>
    </div>

    {{-- Priority --}}
    <div class="mb-3">
        <span class="ts-badge text-[10px]"
              style="color:{{ $priorityBadge['color'] }};background:{{ $priorityBadge['bg'] }};border-color:{{ $priorityBadge['border'] }};">
            {{ $priority }}
        </span>
    </div>

    {{-- Members --}}
    @if($members->count() > 0)
        <div class="flex items-center gap-1 mb-3">
            @foreach($displayMembers as $member)
                <div title="{{ $member->name }}">
                    <x-workspace.avatar :user="$member" sizeClass="w-6 h-6" />
                </div>
            @endforeach
            @if($extraMembers > 0)
                <div class="w-6 h-6 flex items-center justify-center rounded-md text-[9px] font-semibold"
                     style="background:var(--elevated);color:var(--text-2);border:1px solid var(--border-2);">
                    +{{ $extraMembers }}
                </div>
            @endif
        </div>
    @endif

    {{-- Stats --}}
    <div class="grid grid-cols-3 gap-1.5 mb-3">
        @foreach([
            ['label' => 'Progress', 'value' => $pct . '%', 'color' => $barColor],
            ['label' => 'Active',   'value' => $activeTasks, 'color' => 'var(--text)'],
            ['label' => 'Team',     'value' => $metrics['member_count'], 'color' => 'var(--text)'],
        ] as $stat)
            <div class="rounded-md px-2 py-2 text-center" style="background:var(--surface-2);border:1px solid var(--border);">
                <div class="text-[9px] font-medium uppercase tracking-wide mb-0.5" style="color:var(--text-3);">{{ $stat['label'] }}</div>
                <div class="text-xs font-semibold tabular" style="color:{{ $stat['color'] }};letter-spacing:-0.01em;">{{ $stat['value'] }}</div>
            </div>
        @endforeach
    </div>

    {{-- Progress bar --}}
    <div class="flex items-center gap-2">
        <div class="ts-progress-track flex-1">
            <div class="ts-progress-fill" style="width:{{ $pct }}%;background:{{ $barColor }};"></div>
        </div>
        @if($deadline)
            <span class="text-[10px] flex-shrink-0" style="color:var(--text-3);">
                {{ \Carbon\Carbon::parse($deadline)->format('M j') }}
            </span>
        @endif
    </div>
</a>
