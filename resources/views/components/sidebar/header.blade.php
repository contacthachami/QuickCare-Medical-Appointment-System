<div class="flex items-center justify-between flex-shrink-0 px-3">
    <!-- Logo -->
    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 transition-transform duration-300 transform hover:scale-105">
        <div class="flex items-center">
            <div class="relative">
                <div class="absolute -inset-1 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full blur opacity-30"></div>
                <div class="relative">
                    <x-application-logo aria-hidden="true" class="w-10 h-auto" />
                </div>
            </div>
            
            <span x-show="isSidebarOpen || isSidebarHovered" 
                  class="ml-2 text-xl font-bold tracking-wide text-gray-800 dark:text-white"
                  x-transition:enter="transition ease-out duration-300"
                  x-transition:enter-start="opacity-0 -translate-x-2"
                  x-transition:enter-end="opacity-100 translate-x-0">
                QuickCare
                <span class="text-blue-600 dark:text-blue-400">MA</span>
            </span>
        </div>
    </a>

    <!-- Toggle button -->

</div>
