<div class="space-y-6 fade-in">

    {{-- ── KPI Strip ──────────────────────────────────────────────────────── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-6 gap-3">
        <x-workspace.metric-card value="{{ $metrics['total_projects'] }}"  label="Workspaces" />
        <x-workspace.metric-card value="{{ $metrics['active_projects'] }}" label="Active"      statusColor="accent" />
        <x-workspace.metric-card value="{{ $metrics['total_users'] }}"     label="Team Size" />
        <x-workspace.metric-card value="{{ $metrics['total_tasks'] }}"     label="Total Tasks" />
        <x-workspace.metric-card value="{{ $metrics['completed_tasks'] }}" label="Completed"   statusColor="success" />
        <x-workspace.metric-card value="{{ $metrics['overdue_tasks'] }}"   label="Overdue"
            statusColor="{{ $metrics['overdue_tasks'] > 0 ? 'danger' : 'success' }}"
            trend="{{ $metrics['overdue_tasks'] > 0 ? '⚠ Action needed' : '' }}" />
    </div>

    {{-- ── Mid: Active Workspaces + Upcoming Deadlines ────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <div class="lg:col-span-8">
            <div class="ts-panel p-5 h-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="ts-section-title">Active Workspaces</h3>
                    <a href="{{ route('projects.index') }}" class="ts-section-link">View all →</a>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-3">
                    @forelse($widgets['recent_projects'] as $project)
                        <x-workspace.project-card :project="$project" :url="route('projects.show', $project)" />
                    @empty
                        <div class="col-span-full ts-empty">No active workspaces yet.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="ts-panel p-5 h-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="ts-section-title">Upcoming Deadlines</h3>
                    <a href="{{ route('tasks.index') }}" class="ts-section-link">Board →</a>
                </div>
                <div class="space-y-2 max-h-80 overflow-y-auto ts-scroll">
                    @forelse($widgets['upcoming_deadlines'] as $task)
                        <x-workspace.task-card :task="$task" />
                    @empty
                        <div class="ts-empty">No upcoming deadlines.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- ── Bottom: Workforce Load + Recent Completions ─────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4">

        <div class="lg:col-span-8">
            <div class="ts-panel p-5 h-full">
                <div class="flex justify-between items-center mb-3">
                    <h3 class="ts-section-title">Workforce Load</h3>
                    <a href="{{ route('dashboard', ['section' => 'members']) }}" class="ts-section-link">Team directory →</a>
                </div>

                {{-- Legend --}}
                <div class="flex items-center gap-4 text-[11px] mb-4" style="color:var(--text-3);">
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full inline-block" style="background:var(--danger)"></span>Overloaded
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full inline-block" style="background:var(--warning)"></span>Busy
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full inline-block" style="background:var(--success)"></span>Active
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full inline-block" style="background:var(--border-3)"></span>Idle
                    </span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($widgets['capacity_overview'] as $userModel)
                        <x-workspace.member-card
                            :member="$userModel"
                            :metrics="[
                                'active_tasks'    => $userModel->active_tasks,
                                'completed_tasks' => $userModel->completed_tasks,
                                'project_count'   => $userModel->project_count,
                            ]"
                        />
                    @endforeach
                </div>
            </div>
        </div>

        <div class="lg:col-span-4">
            <div class="ts-panel p-5 h-full">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="ts-section-title">Recent Completions</h3>
                    <a href="{{ route('tasks.index') }}" class="ts-section-link">Board →</a>
                </div>
                <div class="space-y-2 max-h-80 overflow-y-auto ts-scroll">
                    @forelse($widgets['recent_completions'] as $task)
                        <x-workspace.task-card :task="$task" />
                    @empty
                        <div class="ts-empty">No recent completions.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

</div>
