@props([
    'type' => 'info',
    'message'
])

@php
    $types = [
        'success' => 'bg-green-100 border-green-400 text-green-800',
        'error' => 'bg-red-100 border-red-400 text-red-400',
        'info' => 'bg-slate-100 border-slate-400 border-slate-400'
        ];
    $class = $types[$type]
@endphp

@if(session($type) || $message)
    <div class="rounded-sm border px-4 py-3 mb-4 flex items-center-safe gap-1.5 {{ $class }}" role="alert">
        <p class="text-sm font-medium">
            {{ session($type) ?? $message }}
        </p>
    </div>
@endif
