{{-- 
    Liquid Button Component
    Usage: <x-liquid-button href="/register">Sign Up</x-liquid-button>
--}}
@props(['variant' => 'liquid', 'href' => null, 'type' => 'button'])

@php
    $classes = match($variant) {
        'liquid' => 'btn btn-liquid',
        'glass' => 'btn btn-glass',
        'energy' => 'btn btn-energy',
        default => 'btn',
    };
    
    $tag = $href ? 'a' : 'button';
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
