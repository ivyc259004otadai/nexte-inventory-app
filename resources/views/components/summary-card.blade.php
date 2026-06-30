@props([
'title' => '',
'count' => 0,
'description' => '',
'tone' => 'success',
])

@php
$icons = [
'danger' => '
<path d="M12 7.5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
<circle cx="12" cy="16.2" r="1" fill="currentColor" />
<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6" />',
'warning' => '
<path d="M7 12.5c2 0 2-2.5 4-2.5s2 2.5 4 2.5 2-2.5 4-2.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
<path d="M7 16c2 0 2-2.5 4-2.5s2 2.5 4 2.5 2-2.5 4-2.5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6" />',
'success' => '
<path d="M9 12.2l2.2 2.2L15.6 10" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" />
<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6" />',
'violet' => '
<path d="M8 15.5 14.5 9l1.7 1.7 1.8-1.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
<path d="M13.8 8.8h4.2V13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6" />',
];
@endphp

<article class="summary-card summary-card--{{ $tone }}">
    <div class="summary-card__icon" aria-hidden="true">{!! $icons[$tone] ?? $icons['success'] !!}</div>
    <div class="summary-card__body">
        <p class="summary-card__title">{{ $title }}</p>
        <p class="summary-card__count">{{ $count }}<span>件</span></p>
        <p class="summary-card__description">{{ $description }}</p>
    </div>
</article>