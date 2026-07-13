<div class="space-y-6 fade-in">

    {{-- ── KPI Strip ──────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-3">
        <x-workspace.metric-card value="{{ $metrics['total_tasks'] }}"     label="My Tasks" />
        <x-workspace.metric-card value="{{ $metrics['active_tasks'] }}"    label="Active"      statusColor="accent" />
        <x-workspace.metric-card value="{{ $metrics['completed_tasks'] }}" label="Completed"   statusColor="success" />
        <x-workspace.metric-card value="{{ $metrics['due_today'] }}"       label="Due Today"
            statusColor="{{ $metrics['due_today'] > 0 ? 'warning' : 'accent' }}" />
        <x-workspace.metric-card value="{{ $metrics['overdue_tasks'] }}"   label="Overdue"
            statusColor="danger"
            trend="{{ $metrics['overdue_tasks'] > 0 ? '⚠ Urgent' : '' }}" />
    </div>

    {{-- ── Mid: Focus Queue + Completion Ring ─────────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        {{-- Needs Attention --}}
        <div class="lg:col-span-8">
            <div class="ts-panel p-5 h-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="ts-section-title">Needs Attention</h3>
                    <a href="{{ route('tasks.myTasks') }}" class="ts-section-link">All my tasks →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    @forelse($widgets['my_active_tasks'] as $task)
                        <x-workspace.task-card :task="$task" />
                    @empty
                        <div class="col-span-full ts-empty">You're all caught up. 🎉</div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Completion Ring --}}
        <div class="lg:col-span-4">
            <div class="ts-panel p-5 h-full flex flex-col items-center justify-center text-center">
                <div class="relative w-28 h-28 mb-4">
                    <svg class="w-full h-full -rotate-90" viewBox="0 0 100 100">
                        <circle stroke-width="7" cx="50" cy="50" r="42" fill="transparent"
                                style="stroke:var(--elevated)"></circle>
                        <circle stroke-width="7" stroke-linecap="round" cx="50" cy="50" r="42" fill="transparent"
                                style="stroke:var(--accent);transition:stroke-dashoffset 0.6s ease;"
                                stroke-dasharray="263.9"
                                stroke-dashoffset="{{ 263.9 - (263.9 * $metrics['completion_percentage']) / 100 }}">
                        </circle>
                    </svg>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <span class="text-2xl font-bold tabular" style="color:var(--text);letter-spacing:-0.04em;">
                            {{ $metrics['completion_percentage'] }}%
                        </span>
                    </div>
                </div>
                <h4 class="text-sm font-semibold" style="color:var(--text);letter-spacing:-0.01em;">Completion Rate</h4>
                <p class="text-xs mt-1" style="color:var(--text-2);">
                    {{ $metrics['completed_tasks'] }} of {{ $metrics['total_tasks'] }} tasks done
                </p>
                <div class="grid grid-cols-2 gap-2 w-full mt-5">
                    <div class="rounded-md px-3 py-2.5" style="background:var(--surface-2);border:1px solid var(--border);">
                        <div class="text-[10px] mb-1" style="color:var(--text-3);">Projects</div>
                        <div class="text-sm font-semibold tabular" style="color:var(--text);">{{ $metrics['assigned_projects'] }}</div>
                    </div>
                    <div class="rounded-md px-3 py-2.5" style="background:var(--surface-2);border:1px solid var(--border);">
                        <div class="text-[10px] mb-1" style="color:var(--text-3);">Active Proj.</div>
                        <div class="text-sm font-semibold tabular" style="color:var(--text);">{{ $metrics['my_active_projects'] }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── Upcoming Deadlines ──────────────────────────────────────────────── --}}
    <div>
        <div class="flex justify-between items-center mb-3">
            <h3 class="ts-section-title">Upcoming Deadlines</h3>
            <a href="{{ route('tasks.index') }}" class="ts-section-link">Board view →</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 xl:grid-cols-5 gap-2.5">
            @forelse($widgets['upcoming_deadlines'] as $task)
                <x-workspace.task-card :task="$task" />
            @empty
                <div class="col-span-full ts-empty">No upcoming deadlines.</div>
            @endforelse
        </div>
    </div>

</div>
