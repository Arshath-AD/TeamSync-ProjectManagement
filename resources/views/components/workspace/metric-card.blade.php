@props(['value', 'label', 'trend' => null, 'statusColor' => 'accent', 'icon' => null])

@php
    $colorMap = match($statusColor) {
        'success' => ['text' => 'var(--success)', 'dot' => 'var(--success)', 'dim' => 'var(--success-dim)'],
        'warning' => ['text' => 'var(--warning)', 'dot' => 'var(--warning)', 'dim' => 'var(--warning-dim)'],
        'danger'  => ['text' => 'var(--danger)',  'dot' => 'var(--danger)',  'dim' => 'var(--danger-dim)'],
        default   => ['text' => 'var(--accent)',  'dot' => 'var(--accent)',  'dim' => 'var(--accent-dim)'],
    };
    $trendClass = $trend && str_starts_with($trend, '+') ? 'var(--danger)' :
                 ($trend && str_starts_with($trend, '-') ? 'var(--success)' : 'var(--warning)');
@endphp

<div {{ $attributes->merge(['class' => 'ts-metric']) }}>
    {{-- Colored left border indicator --}}
    <div style="position:absolute;left:0;top:0;bottom:0;width:3px;border-radius:10px 0 0 10px;background:{{ $colorMap['dot'] }};opacity:0.7;"></div>

    <div class="flex items-start justify-between gap-2 mb-3">
        <span class="text-xs font-medium" style="color:var(--text-2);letter-spacing:-0.01em;">{{ $label }}</span>
        @if($trend)
            <span class="text-[10px] font-medium px-1.5 py-0.5 rounded flex-shrink-0"
                  style="color:{{ $trendClass }};background:var(--danger-dim);border:1px solid rgba(248,113,113,0.25);">{{ $trend }}</span>
        @endif
    </div>
    <div class="text-2xl font-bold tabular leading-none" style="color:var(--text);letter-spacing:-0.03em;">{{ $value }}</div>
</div>
