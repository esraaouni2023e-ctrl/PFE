{{-- 
    Liquid Glass Card Component
    Usage: <x-liquid-glass class="p-6">Content</x-liquid-glass>
--}}
@props(['variant' => 'default'])

@php
    $classes = match($variant) {
        'hero' => 'liquid-glass-hero',
        'strong' => 'liquid-glass-strong',
        default => 'liquid-glass',
    };
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</div>
