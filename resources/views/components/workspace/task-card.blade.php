@props(['task', 'compact' => false])

@php
    $priorityAccent = match($task->priority) {
        'Critical' => 'var(--danger)',
        'High'     => 'var(--warning)',
        'Medium'   => 'var(--accent)',
        default    => 'var(--text-3)',
    };
    $priorityBadge = match($task->priority) {
        'Critical' => ['color' => 'var(--danger)',  'bg' => 'var(--danger-dim)',  'border' => 'rgba(248,113,113,0.25)'],
        'High'     => ['color' => 'var(--warning)', 'bg' => 'var(--warning-dim)', 'border' => 'rgba(251,191,36,0.25)'],
        'Medium'   => ['color' => 'var(--accent)',  'bg' => 'var(--accent-dim)',  'border' => 'var(--accent-ring)'],
        default    => ['color' => 'var(--text-3)',  'bg' => 'var(--elevated)',    'border' => 'var(--border-2)'],
    };
    $statusText = match($task->status) {
        'In Progress' => ['label' => 'In Progress', 'class' => 'status-in-progress'],
        'On Hold'     => ['label' => 'On Hold',     'class' => 'status-on-hold'],
        'Completed'   => ['label' => 'Done',        'class' => 'status-completed'],
        default       => ['label' => 'Todo',        'class' => 'status-pending'],
    };
    $isOverdue  = $task->status !== 'Completed' && $task->status !== 'On Hold' && $task->deadline && \Carbon\Carbon::parse($task->deadline)->isBefore(now()->startOfDay());
    $isDueToday = $task->status !== 'Completed' && $task->status !== 'On Hold' && $task->deadline && \Carbon\Carbon::parse($task->deadline)->isToday();
@endphp

<div {{ $attributes->merge(['class' => 'ts-card p-3.5 relative group fade-in']) }}
     style="padding-left:16px;">
    {{-- Priority indicator --}}
    <div style="position:absolute;left:0;top:8px;bottom:8px;width:2.5px;border-radius:2px;background:{{ $priorityAccent }};"></div>

    {{-- Title + assignee --}}
    <div class="flex items-start justify-between gap-2 mb-2.5">
        <a href="{{ route('tasks.edit', $task) }}"
           class="text-xs font-medium leading-snug line-clamp-2 flex-1 transition-colors"
           style="color:var(--text);text-decoration:none;letter-spacing:-0.01em;"
           onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--text)'">
            {{ $task->task_name }}
        </a>
        @if($task->assignee)
            <div title="{{ $task->assignee->name }}" class="flex-shrink-0">
                <x-workspace.avatar :user="$task->assignee" sizeClass="w-5 h-5" />
            </div>
        @endif
    </div>

    {{-- Meta row --}}
    <div class="flex items-center justify-between gap-2">
        <div class="flex items-center gap-1.5 min-w-0 flex-wrap">
            @if($task->project)
                <span class="text-[10px] truncate max-w-[90px]" style="color:var(--text-3);">
                    {{ $task->project->project_name }}
                </span>
                <span class="text-[10px]" style="color:var(--border-3);">/</span>
            @endif
            <span class="ts-badge text-[10px]"
                  style="color:{{ $priorityBadge['color'] }};background:{{ $priorityBadge['bg'] }};border-color:{{ $priorityBadge['border'] }};">
                {{ $task->priority }}
            </span>
        </div>
        <span class="{{ $statusText['class'] }} status-pill text-[10px] flex-shrink-0">{{ $statusText['label'] }}</span>
    </div>

    {{-- Deadline --}}
    @if($task->deadline)
        <div class="mt-2 flex items-center gap-1.5 text-[10px]"
             style="color:{{ $isOverdue ? 'var(--danger)' : ($isDueToday ? 'var(--warning)' : 'var(--text-3)') }};
                    font-weight:{{ $isOverdue ? '500' : '400' }}">
            <svg class="w-3 h-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            @if($isOverdue)
                Overdue · {{ \Carbon\Carbon::parse($task->deadline)->format('M j') }}
            @elseif($isDueToday)
                Due today
            @else
                {{ \Carbon\Carbon::parse($task->deadline)->format('M j') }}
            @endif
        </div>
    @endif
</div>
