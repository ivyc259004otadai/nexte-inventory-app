@props([
'status' => 'A',
'label' => 'A：適正在庫',
])

@php
$icons = [
'D' => '
<path d="M12 7.5v5" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" />
<circle cx="12" cy="16.2" r="1" fill="currentColor" />
<circle cx="12" cy="12" r="8" stroke="currentColor" stroke-width="1.6" />',
'C' => '
<path d="M7.2 11.3c1.8 0 1.8-2.2 3.6-2.2s1.8 2.2 3.6 2.2 1.8-2.2 3.6-2.2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
<path d="M7.2 15.3c1.8 0 1.8-2.2 3.6-2.2s1.8 2.2 3.6 2.2 1.8-2.2 3.6-2.2" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />',
'A' => '
<path d="M9 12.2l2.2 2.2L15.6 10" stroke="currentColor" stroke-width="1.9" stroke-linecap="round" stroke-linejoin="round" />',
'B' => '
<path d="M8 15.5 14.5 9l1.7 1.7 1.8-1.8" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />
<path d="M13.8 8.8h4.2V13" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" />',
];
@endphp

<span class="status-badge status-badge--{{ $status }}">
    <svg class="status-badge__icon" viewBox="0 0 24 24" fill="none" aria-hidden="true">
        {!! $icons[$status] ?? $icons['A'] !!}
    </svg>
    <span>{{ $label }}</span>
</span>