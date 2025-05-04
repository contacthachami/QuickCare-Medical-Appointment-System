@props([
    'isActive' => false,
    'title' => '',
    'collapsible' => false,
])

@php
    $isActiveClasses = $isActive
        ? 'text-white bg-gradient-to-r from-blue-500 to-blue-600 shadow-md hover:shadow-lg transform transition-all duration-300 hover:-translate-y-0.5 hover:from-blue-600 hover:to-blue-700'
        : 'text-gray-600 dark:text-gray-300 hover:text-blue-600 dark:hover:text-blue-400 hover:bg-blue-50 dark:hover:bg-opacity-10 dark:hover:bg-blue-900 group transform transition-all duration-200 hover:-translate-y-0.5';

    $classes =
        'flex-shrink-0 flex items-center gap-3 py-2.5 px-3 transition-all rounded-md overflow-hidden ' . $isActiveClasses;

    if ($collapsible) {
        $classes .= ' w-full';
    }
@endphp

@if ($collapsible)
    <button type="button" {{ $attributes->merge(['class' => $classes]) }}>
        <span class="relative">
            @if ($icon ?? false)
                {{ $icon }}
            @else
                <x-icons.empty-circle class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            @endif
        </span>

        <span class="text-base font-medium whitespace-nowrap transition-opacity duration-200" 
              x-show="isSidebarOpen || isSidebarHovered"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0"
              x-transition:enter-end="opacity-100">
            {{ $title }}
        </span>

        <span x-show="isSidebarOpen || isSidebarHovered" 
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 scale-90"
              x-transition:enter-end="opacity-100 scale-100"
              aria-hidden="true" 
              class="relative block ml-auto w-6 h-6">
            <span :class="open ? '-rotate-45' : 'rotate-45'"
                class="absolute right-[9px] bg-gray-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>

            <span :class="open ? 'rotate-45' : '-rotate-45'"
                class="absolute left-[9px] bg-gray-400 mt-[-5px] h-2 w-[2px] top-1/2 transition-all duration-200"></span>
        </span>
    </button>
@else
    <a {{ $attributes->merge(['class' => $classes]) }}>
        <span class="relative">
            @if ($icon ?? false)
                {{ $icon }}
            @else
                <x-icons.empty-circle class="flex-shrink-0 w-6 h-6" aria-hidden="true" />
            @endif
        </span>

        <span class="text-base font-medium whitespace-nowrap transition-all duration-200" 
              x-show="isSidebarOpen || isSidebarHovered"
              x-transition:enter="transition ease-out duration-300"
              x-transition:enter-start="opacity-0 -translate-x-2"
              x-transition:enter-end="opacity-100 translate-x-0">
            {{ $title }}
        </span>
    </a>
@endif
