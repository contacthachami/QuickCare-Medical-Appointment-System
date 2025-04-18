<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'K UI') }}</title>

    <!-- Fonts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,200;0,300;0,400;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@10/dist/sweetalert2.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">

    <!-- Styles -->
    <style>
        ::-webkit-scrollbar {
            width: 5px;
        }

        /* Track */
        ::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        /* Handle */
        ::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 12px;
        }

        /* Handle on hover */
        ::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        [x-cloak] {
            display: none;
        }
    </style>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="icon" type="image/png" href="{{ asset('img/app-logo.png') }}">

</head>

<body class="font-sans antialiased">
    <div x-data="{ isDarkMode: false, isSidebarOpen: true }" x-cloak>
        <div class="min-h-screen text-gray-900 bg-gray-100 dark:bg-dark-eval-0 dark:text-gray-200">
            <div class="flex">
            <!-- Sidebar -->
                <aside class="fixed left-0 top-0 z-10 h-screen w-64 bg-white dark:bg-gray-800 shadow-lg transition-all duration-300" 
                      class="translate-x-0" id="sidebar-container">
                    <div class="flex flex-col h-full">
                        <!-- Logo -->
                        <div class="p-4 flex items-center justify-center border-b dark:border-gray-700">
                            <a href="/" class="flex items-center">
                                <img src="{{ asset('img/app-logo.png') }}" alt="QuickCare Logo" class="h-8 w-auto">
                                <span class="ml-2 text-xl font-bold text-blue-600 dark:text-blue-400">QuickCare</span>
                            </a>
                        </div>

                        <!-- Navigation Links -->
                        <nav class="flex-1 pt-5 pb-4 overflow-y-auto">
                            <div class="px-4 mb-8">
                                <h2 class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide font-semibold mb-3">
                                    MENU
                                </h2>
                                <div class="space-y-2" id="main-nav-links">
                                    <a href="{{ route('patient_dashboard') }}" class="nav-link group p-3 flex items-center text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg {{ request()->routeIs('patient_dashboard') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                        <i class="fas fa-home mr-3 text-lg {{ request()->routeIs('patient_dashboard') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                        <span class="nav-text">Home</span>
                                    </a>
                                    
                                    <div class="nav-links-header text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wide font-semibold mt-6 mb-3 px-3">
                                        LINKS
                                    </div>
                                    
                                    <a href="{{ route('patiens.doctors') }}" class="nav-link group p-3 flex items-center text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg {{ request()->routeIs('patiens.doctors') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                        <i class="fas fa-user-md mr-3 text-lg {{ request()->routeIs('patiens.doctors') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                        <span class="nav-text">Doctor List</span>
                                    </a>
                                    
                                    <a href="{{ route('patiens.my.appointments', Auth::user()->patient->id) }}" class="nav-link group p-3 flex items-center text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg {{ request()->routeIs('patiens.my.appointments') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                        <i class="fas fa-calendar-check mr-3 text-lg {{ request()->routeIs('patiens.my.appointments') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                        <span class="nav-text">My Appointments</span>
                                    </a>
                                    
                                    <a href="{{ route('patiens.health.tips.view') }}" class="nav-link group p-3 flex items-center text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg {{ request()->routeIs('patiens.health.tips.view') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                        <i class="fas fa-book-medical mr-3 text-lg {{ request()->routeIs('patiens.health.tips.view') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                        <span class="nav-text">Health Articles</span>
                                    </a>
                                    
                                    <a href="{{ route('patients.emergency.contacts.view') }}" class="nav-link group p-3 flex items-center text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg {{ request()->routeIs('patients.emergency.contacts.view') ? 'bg-blue-50 dark:bg-blue-900/20 text-blue-600 dark:text-blue-400' : '' }}">
                                        <i class="fas fa-ambulance mr-3 text-lg {{ request()->routeIs('patients.emergency.contacts.view') ? 'text-blue-600 dark:text-blue-400' : 'text-gray-400 dark:text-gray-500 group-hover:text-blue-600 dark:group-hover:text-blue-400' }}"></i>
                                        <span class="nav-text">Emergency Contacts</span>
                                    </a>
                                </div>
                            </div>
                        </nav>
                    </div>
                </aside>

            <!-- Page Wrapper -->
                <div class="flex flex-col min-h-screen w-full pl-64">
                    <!-- Navbar -->
                    <div class="flex justify-between items-center p-4 bg-white dark:bg-gray-800 border-b dark:border-gray-700 shadow-sm">
                        <div>
                            <!-- Sidebar toggle button removed -->
                        </div>
                        <div class="flex items-center space-x-4">
                            <!-- Theme Toggle -->
                            <button id="theme-toggle" type="button" @click="isDarkMode = !isDarkMode" class="text-gray-500 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none focus:ring-4 focus:ring-gray-200 dark:focus:ring-gray-700 rounded-lg text-sm p-2">
                                <svg id="theme-toggle-dark-icon" class="w-5 h-5" :class="isDarkMode ? 'hidden' : ''" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path>
                                </svg>
                                <svg id="theme-toggle-light-icon" class="w-5 h-5" :class="isDarkMode ? '' : 'hidden'" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" fill-rule="evenodd" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                            
                            <!-- Notifications -->
                            <button type="button" class="relative p-2 text-gray-500 hover:text-gray-600 focus:outline-none focus:text-gray-600">
                                <svg class="h-6 w-6" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15 17H20L18.5951 15.5951C18.2141 15.2141 18 14.6973 18 14.1585V11C18 8.38757 16.3304 6.16509 14 5.34142V5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5V5.34142C7.66962 6.16509 6 8.38757 6 11V14.1585C6 14.6973 5.78595 15.2141 5.40493 15.5951L4 17H9M15 17V18C15 19.6569 13.6569 21 12 21C10.3431 21 9 19.6569 9 18V17M15 17H9" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                </svg>
                                <span class="absolute top-0 right-0 h-2 w-2 rounded-full bg-red-500"></span>
                            </button>
                            
                            <!-- User Menu -->
                            <div class="relative" x-data="{ open: false }">
                                <button type="button" @click="open = !open" class="flex items-center focus:outline-none">
                                    <div class="h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-700 text-lg font-semibold">
                                        @php
                                            $fullName = Auth::user()->name;
                                            $nameParts = explode(' ', $fullName);
                                            $firstName = isset($nameParts[0]) ? $nameParts[0] : '';
                                            $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                                            $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                                        @endphp
                                        {{ $initials }}
                                    </div>
                                    <span class="ml-2 text-gray-700 dark:text-gray-300">{{ Auth::user()->name }}</span>
                                    <svg class="ml-1 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-md shadow-lg py-1 z-50" style="display: none;">
                                    <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">Your Profile</a>
                                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                            Sign out
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                <!-- Page Heading -->
                <header>
                    <div class="p-4 sm:p-6">
                        {{ $header }}
                    </div>
                </header>

                <!-- Page Content -->
                <main class="px-4 sm:px-6 flex-1">
                    {{ $slot }}
                </main>

                <!-- Page Footer -->
                <x-footer />
            </div>
        </div>
    </div>
    </div>

    <!-- JavaScript Libraries -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.11.1/dist/cdn.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Apply Dark mode 
            if (localStorage.getItem('color-theme') === 'dark' || 
                (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
            
            // Hover animations for nav links
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('mouseenter', () => {
                    const navText = link.querySelector('.nav-text');
                    const icon = link.querySelector('i');
                    if (navText) {
                        gsap.to(navText, {
                            duration: 0.3,
                            x: 5,
                            fontWeight: 600,
                            ease: "power1.out"
                        });
                    }
                    
                    if (icon) {
                        gsap.to(icon, {
                            duration: 0.3,
                            scale: 1.2,
                            ease: "back.out(1.7)"
                        });
                    }
                });
                
                link.addEventListener('mouseleave', () => {
                    const navText = link.querySelector('.nav-text');
                    const icon = link.querySelector('i');
                    if (navText) {
                        gsap.to(navText, {
                            duration: 0.3,
                            x: 0,
                            fontWeight: 400,
                            ease: "power1.in"
                        });
                    }
                    
                    if (icon) {
                        gsap.to(icon, {
                            duration: 0.3,
                            scale: 1,
                            ease: "back.in(1.7)"
                        });
                    }
                });
            });
            
            // Theme toggle
            const themeToggleBtn = document.getElementById('theme-toggle');
            themeToggleBtn.addEventListener('click', function() {
                // Toggle the theme
                if (localStorage.getItem('color-theme') === 'dark') {
                    document.documentElement.classList.remove('dark');
                    localStorage.setItem('color-theme', 'light');
                } else {
                    document.documentElement.classList.add('dark');
                    localStorage.setItem('color-theme', 'dark');
                }
            });
        });
    </script>
    
    @stack('scripts')
</body>

</html>
