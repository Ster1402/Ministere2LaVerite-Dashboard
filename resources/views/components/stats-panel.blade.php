@props(['icon' => ''])

@php
    $classes = 'metric-card';
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    <div class="metric-icon">
        <i class="fas {{ $icon }}"></i>
    </div>
    <div class="metric-content">
        <h3 class="metric-title">{{ $title }}</h3>
        <div class="card card-body">
            @foreach ($statItems as $item)
                @if ($loop->iteration <= 3)
                    <div class="metric-value">{{ $item->count }}</div>
                    <div class="metric-subtitle">{{ $item->name }}</div>
                @endif
            @endforeach
        </div>
    </div>
    <div class="metric-chart">
        <div class="sparkline-chart"></div>
    </div>
</div>
