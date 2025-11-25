@props([
    'type' => 'success',
    'messages' => [],
])

@php
    $colors = [
        'success' => 'bg-green-600',
        'error' => 'bg-red-600',
        'warning' => 'bg-yellow-600',
        'info' => 'bg-blue-600',
    ];

    $color = $colors[$type] ?? $colors['success'];
@endphp

<div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
    class="fixed top-4 right-4 {{ $color }} text-white px-4 py-3 rounded shadow-lg z-50 min-w-[250px]">
    <button @click="show = false" class="absolute top-1 right-2 text-white text-sm focus:outline-none">
        ✕
    </button>

    @if (is_array($messages) && count($messages) > 1)
        <ul class="list-disc ml-4 text-sm">
            @foreach ($messages as $m)
                <li>{{ $m }}</li>
            @endforeach
        </ul>
    @else
        <span class="text-sm">
            {{ is_array($messages) ? $messages[0] ?? '' : $messages }}
        </span>
    @endif
</div>
