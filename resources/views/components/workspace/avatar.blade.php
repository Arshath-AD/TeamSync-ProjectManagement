@props(['user', 'sizeClass' => 'w-7 h-7'])

@php
    $avatarUrl = $user->avatarUrl();   // null when no photo uploaded
    $name      = $user->name ?? '?';

    // Deterministic hue from name for initials fallback
    $hash = 0;
    foreach (str_split($name) as $char) { $hash = ord($char) + (($hash << 5) - $hash); }
    $hue = abs($hash) % 360;

    $initials = collect(explode(' ', trim($name)))
        ->filter()
        ->take(2)
        ->map(fn($w) => strtoupper($w[0]))
        ->join('');
@endphp

{{-- Uploaded profile picture or default avatar --}}
<img
    src="{{ $avatarUrl }}"
    alt="{{ $name }}"
    loading="lazy"
    {{ $attributes->merge(['class' => "ts-avatar-img $sizeClass"]) }}
    style="object-fit:cover;border-radius:6px;display:block;flex-shrink:0;"
    onerror="this.style.display='none';this.nextElementSibling.style.display='flex';"
>
{{-- Hidden initials shown only if image fails to load --}}
<div {{ $attributes->merge(['class' => "ts-avatar $sizeClass"]) }}
     style="display:none;--av-hue:{{ $hue }};">
    {{ $initials ?: '?' }}
</div>

{{-- Scoped styles — rendered once per page --}}
@once
<style>
.ts-avatar     { display:flex;align-items:center;justify-content:center;border-radius:6px;font-weight:600;flex-shrink:0;font-size:11px;text-transform:uppercase;letter-spacing:0.02em;background:hsl(var(--av-hue,220),50%,20%);color:hsl(var(--av-hue,220),65%,75%); }
.ts-avatar-img { border-radius:6px;flex-shrink:0; }
html.light .ts-avatar { background:hsl(var(--av-hue,220),60%,88%); color:hsl(var(--av-hue,220),55%,30%); }
</style>
@endonce
