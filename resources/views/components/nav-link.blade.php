@props(['active', 'icon' => '', 'header' => ''])

@php
    $classes = 'nav-link';
@endphp

<li class="menu-header">{{ $header }}</li>
<li style="margin-right: 100%;" class="{{ $active ? 'active' : '' }}">
    <a {{ $attributes->merge(['class' => $classes]) }}><i class="fas {{ $icon }}"></i>{{ $slot }}
    </a>
</li>
