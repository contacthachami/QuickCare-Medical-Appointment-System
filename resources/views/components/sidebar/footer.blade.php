<div class="px-3 flex-shrink-0">
    <!-- Toggle button for mobile -->
    <div class="lg:hidden">
        <x-button type="button" icon-only variant="secondary" 
            x-on:click="isSidebarOpen = !isSidebarOpen" 
            class="transition-all duration-300 hover:bg-blue-100 dark:hover:bg-gray-800" 
            sr-text="Toggle sidebar">
            <x-icons.menu-fold-left x-show="isSidebarOpen" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
            <x-icons.menu-fold-right x-show="!isSidebarOpen" class="w-6 h-6 text-blue-600 dark:text-blue-400" />
        </x-button>
    </div>
    
    <!-- Doctor info footer -->
    @if(auth()->user()->user_type === 'doctor')
    <div x-show="isSidebarOpen || isSidebarHovered" 
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
        <div class="flex items-center p-2 rounded-lg bg-blue-50 dark:bg-gray-800">
            <div class="mr-3 relative">
                @php
                    $nameParts = explode(' ', Auth::user()->name);
                    $firstName = $nameParts[0] ?? '';
                    $lastName = end($nameParts);
                    $initials = strtoupper(substr($firstName, 0, 1) . (count($nameParts) > 1 ? substr($lastName, 0, 1) : ''));
                @endphp
                
                @if(Auth::user()->img)
                    <img src="{{ asset('storage/profile_pictures/' . Auth::user()->img) }}" alt="Profile" 
                         class="w-10 h-10 rounded-full border-2 border-blue-200 dark:border-blue-800 object-cover">
                @else
                    <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden" 
                         style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); position: relative;">
                        <!-- Highlight effect -->
                        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 45%);"></div>
                        <span class="text-white font-semibold text-sm relative z-10" style="text-transform: uppercase; letter-spacing: -0.5px;">{{ $initials }}</span>
                    </div>
                @endif
                <!-- Online indicator -->
                <span class="absolute bottom-0 right-0 block w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                    Dr. {{ Auth::user()->name }}
                </p>
                <p class="text-xs text-gray-500 dark:text-gray-400 truncate">
                    @if(isset(auth()->user()->doctor->speciality))
                        {{ auth()->user()->doctor->speciality->name ?? 'Medical Doctor' }}
                    @else
                        Medical Doctor
                    @endif
                </p>
            </div>
        </div>
    </div>
    @endif
</div>
