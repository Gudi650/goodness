{{-- 
    Loading Component
    
    Usage:
    <x-loading id="loader1" message="Processing..." />
    <x-loading size="lg" color="amber" full-page="true" message="Loading data..." />
    <x-loading size="sm" message="Saving..." />
    
    Props:
    - id: Optional HTML id for targeting in JavaScript
    - message: Optional loading message text (defaults to "Loading...")
    - size: sm, md, lg (defaults to md)
    - color: amber, blue, slate, green, red (defaults to amber for brand color)
    - full-page: true/false - displays as full-page overlay (defaults to false)
    - show: true/false - initial visibility (defaults to true)
--}}

@props([
    'id' => 'loader',
    'message' => 'Please wait while we process your request...',
    'size' => 'md',
    'color' => 'amber',
    'fullPage' => false,
    'show' => true
])

{{-- Size mappings --}}
@php
    $sizeMap = [
        'sm' => 'w-6 h-6',
        'md' => 'w-8 h-8',
        'lg' => 'w-12 h-12',
    ];
    $sizeClass = $sizeMap[$size] ?? $sizeMap['md'];
    
    $colorMap = [
        'amber' => 'border-t-amber-600',
        'blue' => 'border-t-blue-600',
        'slate' => 'border-t-slate-600',
        'green' => 'border-t-green-600',
        'red' => 'border-t-red-600',
    ];
    $colorClass = $colorMap[$color] ?? $colorMap['amber'];
@endphp

<div 
    id="{{ $id }}"
    {{ $attributes->merge(['class' => ($show ? '' : 'hidden ') . ($fullPage ? 'fixed inset-0 z-[999] bg-black/40 backdrop-blur-sm flex items-center justify-center' : '')]) }}
>
    {{-- Full-page overlay mode --}}
    @if($fullPage)
        <div class="bg-white shadow-2xl p-8 sm:p-10 md:p-12 w-full max-w-sm sm:max-w-md md:max-w-xl mx-4 min-h-72 sm:min-h-80 md:min-h-96 border border-slate-200 flex flex-col items-center justify-center">
            <div class="flex flex-col items-center justify-center gap-5 text-center">
                {{-- Spinner --}}
                <div class="flex items-center justify-center">
                    <div class="rounded-full border-4 border-slate-200 {{ $sizeClass }} animate-spin {{ $colorClass }}"></div>
                </div>

                <div class="space-y-1">
                    <p class="text-sm sm:text-base font-semibold text-slate-900">Loading in progress</p>
                    <p class="text-xs sm:text-sm text-slate-500">This may take a few seconds. Please do not close this window.</p>
                </div>
                
                {{-- Message text --}}
                @if($message)
                    <p id="{{ $id }}Text" class="text-slate-700 text-sm sm:text-base font-medium text-center leading-relaxed">{{ $message }}</p>
                @endif
            </div>
        </div>
    @else
        {{-- Inline mode (non-full-page) --}}
        <div class="flex flex-col items-center justify-center gap-3 text-center">
            {{-- Spinner --}}
            <div class="flex items-center justify-center">
                <div class="rounded-full border-4 border-slate-200 {{ $sizeClass }} animate-spin {{ $colorClass }}"></div>
            </div>

            <div class="space-y-1">
                <p class="text-sm font-semibold text-slate-800">Loading</p>
                <p class="text-xs text-slate-500">Please wait while we finish up.</p>
            </div>
            
            {{-- Message text --}}
            @if($message)
                <p id="{{ $id }}Text" class="text-slate-600 text-sm font-medium text-center leading-relaxed">{{ $message }}</p>
            @endif
        </div>
    @endif
</div>

<style>
    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }
    
    .animate-spin {
        animation: spin 1s linear infinite;
    }
</style>
