<form method="GET" action="{{ url()->current() }}" id="task-filters-form" class="ts-panel p-3 mb-4 flex flex-col xl:flex-row gap-4 items-start xl:items-center justify-between fade-in">
    @php
        $selectedPriorities = request('priority', []);
    @endphp
    
    {{-- Filters Label --}}
    <div class="flex items-center gap-2 flex-shrink-0 mr-2">
        <svg class="w-4 h-4 text-[var(--text-3)]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
        <span class="text-xs font-semibold text-[var(--text-2)] uppercase tracking-wider">Filters</span>
    </div>

    {{-- Priority Filters (Checkbox group) --}}
    <div class="flex flex-wrap gap-2">
        @foreach(['High', 'Medium', 'Low', 'Overdue'] as $p)
            @php
                $isActive = in_array($p, $selectedPriorities);
                $activeColor = match($p) {
                    'High', 'Overdue' => 'var(--danger)',
                    'Medium'          => 'var(--warning)',
                    'Low'             => 'var(--success)',
                    default           => 'var(--accent)',
                };
            @endphp
            <label class="cursor-pointer">
                <input type="checkbox" name="priority[]" value="{{ $p }}" class="hidden" onchange="document.getElementById('task-filters-form').submit()" {{ $isActive ? 'checked' : '' }}>
                <span class="px-3 py-1.5 rounded-md text-[11px] font-medium transition-all border block" 
                      style="{{ $isActive ? 'background:color-mix(in srgb, '.$activeColor.' 10%, transparent); color:'.$activeColor.'; border-color:color-mix(in srgb, '.$activeColor.' 30%, transparent);' : 'background:transparent; color:var(--secondary); border-color:var(--border);' }}"
                      onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) this.style.background='var(--elevated)'"
                      onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) this.style.background='transparent'">
                    {{ $p }}
                </span>
            </label>
        @endforeach
    </div>

    {{-- Dropdowns & Controls --}}
    <div class="flex flex-wrap gap-3 items-center w-full xl:w-auto">
        
        {{-- Assignee (Standalone Resource Row with Multiselect) - ADMIN ONLY --}}
        @if(isset($users) && Auth::user()->role === 'admin')
        @php
            $selectedAssignees = (array) request('assignee', [(string)Auth::id()]);
            $isAllActive = in_array('all', $selectedAssignees);
        @endphp
        <div class="flex flex-wrap items-center gap-1.5 p-1 rounded-lg" style="background:var(--surface); border:1px solid var(--border);">
            {{-- All Users --}}
            <label class="cursor-pointer group flex items-center justify-center m-0" title="All Tasks">
                <input type="checkbox" name="assignee[]" value="all" id="assignee_all" class="hidden" 
                       onchange="document.querySelectorAll('.assignee-chk').forEach(e => e.checked = false); document.getElementById('task-filters-form').submit()" 
                       {{ $isAllActive ? 'checked' : '' }}>
                <div class="w-8 h-8 rounded-full flex items-center justify-center transition-all"
                     style="{{ $isAllActive ? 'background:var(--accent); color:white;' : 'color:var(--secondary); background:transparent;' }}"
                     onmouseover="if(!{{ $isAllActive ? 'true' : 'false' }}) this.style.background='var(--elevated)'" 
                     onmouseout="if(!{{ $isAllActive ? 'true' : 'false' }}) this.style.background='transparent'">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </label>

            <div class="w-px h-5 mx-1" style="background:var(--border);"></div>

            @foreach($users as $u)
                @php 
                    $isActive = in_array((string)$u->id, $selectedAssignees) && !$isAllActive; 
                @endphp
                <label class="cursor-pointer group flex items-center justify-center m-0" title="{{ $u->name }}">
                    <input type="checkbox" name="assignee[]" value="{{ $u->id }}" class="hidden assignee-chk" 
                           onchange="document.getElementById('assignee_all').checked = false; document.getElementById('task-filters-form').submit()" 
                           {{ $isActive ? 'checked' : '' }}>
                    <div class="transition-all rounded-full p-[2px]"
                         style="{{ $isActive ? 'border: 2px solid var(--accent);' : 'border: 2px solid transparent; opacity: 0.5; filter: grayscale(100%);' }}"
                         onmouseover="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.opacity='1'; this.style.filter='grayscale(0%)'; }" 
                         onmouseout="if(!{{ $isActive ? 'true' : 'false' }}) { this.style.opacity='0.5'; this.style.filter='grayscale(100%)'; }">
                        <x-workspace.avatar :user="$u" sizeClass="w-7 h-7" />
                    </div>
                </label>
            @endforeach
        </div>
        @endif

        {{-- Status --}}
        @php
            $filterStatusOpts = [
                ['value' => '', 'label' => 'All Statuses'],
                ['value' => 'Todo', 'label' => 'Todo', 'color' => '#6b7280'],
                ['value' => 'In Progress', 'label' => 'In Progress', 'color' => '#3b82f6'],
                ['value' => 'On Hold', 'label' => 'On Hold', 'color' => '#f59e0b'],
                ['value' => 'Completed', 'label' => 'Completed', 'color' => '#22c55e'],
            ];
        @endphp
        <div class="relative flex-1 sm:flex-none sm:w-36">
            <x-workspace.dropdown 
                name="status" 
                id="filter_status" 
                :value="request('status')" 
                :options="$filterStatusOpts" 
                placeholder="All Statuses" 
                onChange="this.form.submit()" />
        </div>

        {{-- Due Date --}}
        <div class="relative flex-1 sm:flex-none">
            <input type="date" name="due_date" value="{{ request('due_date') }}" class="ts-input text-xs py-1.5 px-3 w-full sm:w-36 bg-[var(--surface)]" onchange="this.form.submit()" title="Filter by Due Date">
        </div>

        {{-- Clear Filters --}}
        @if(request()->anyFilled(['priority', 'assignee', 'status', 'due_date']))
            <a href="{{ url()->current() }}" class="ts-btn-secondary text-[11px] py-1.5 px-3 whitespace-nowrap flex-shrink-0" title="Clear all filters">
                <svg class="w-3 h-3 inline-block mr-1 -mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                Clear
            </a>
        @endif
    </div>
</form>
