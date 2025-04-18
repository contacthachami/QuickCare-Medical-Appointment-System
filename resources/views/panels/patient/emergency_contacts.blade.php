<x-patient-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white flex items-center">
            <span class="mr-2"><i class="fas fa-first-aid text-red-500"></i></span>
            {{ __('Emergency Contacts') }}
        </h2>
    </x-slot>

    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- National Emergency Numbers -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6 relative">
                <div id="particles-emergency" class="absolute inset-0 z-0"></div>
                <div class="p-6 relative z-10">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center" data-aos="fade-right">
                        <span class="mr-2"><i class="fas fa-phone-alt text-red-500"></i></span>
                        National Emergency Numbers
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-red-50 dark:bg-red-900/20 p-4 rounded-lg border border-red-200 dark:border-red-800" data-aos="zoom-in" data-aos-delay="100">
                            <div class="flex items-center mb-2">
                                <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 mr-3 dark:bg-red-800 dark:text-red-300">
                                    <i class="fas fa-ambulance text-lg"></i>
                                </div>
                                <h4 class="text-lg font-medium text-red-800 dark:text-red-300">Ambulance</h4>
                            </div>
                            <div class="pl-12">
                                <p class="text-xl font-bold text-red-700 dark:text-red-400">15</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">National Emergency Service</p>
                            </div>
                        </div>
                        
                        <div class="bg-blue-50 dark:bg-blue-900/20 p-4 rounded-lg border border-blue-200 dark:border-blue-800" data-aos="zoom-in" data-aos-delay="200">
                            <div class="flex items-center mb-2">
                                <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 mr-3 dark:bg-blue-800 dark:text-blue-300">
                                    <i class="fas fa-phone-volume text-lg"></i>
                                </div>
                                <h4 class="text-lg font-medium text-blue-800 dark:text-blue-300">General Emergency</h4>
                            </div>
                            <div class="pl-12">
                                <p class="text-xl font-bold text-blue-700 dark:text-blue-400">190</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">National Emergency Number</p>
                            </div>
                        </div>
                        
                        <div class="bg-green-50 dark:bg-green-900/20 p-4 rounded-lg border border-green-200 dark:border-green-800" data-aos="zoom-in" data-aos-delay="300">
                            <div class="flex items-center mb-2">
                                <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600 mr-3 dark:bg-green-800 dark:text-green-300">
                                    <i class="fas fa-first-aid text-lg"></i>
                                </div>
                                <h4 class="text-lg font-medium text-green-800 dark:text-green-300">Additional Ambulance</h4>
                            </div>
                            <div class="pl-12">
                                <p class="text-xl font-bold text-green-700 dark:text-green-400">141</p>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Region-dependent service</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Major Hospitals by City -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="mr-2"><i class="fas fa-hospital text-blue-500"></i></span>
                        Major Public Hospitals by City
                    </h3>
                    
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                        <!-- Casablanca -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Casablanca
                                </h4>
                            </div>
                            <div class="p-4">
                                <div class="mb-5">
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">Cheikh Khalifa Ibn Zaid Hospital</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Emergency: +212 529 004 488</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Additional: +212 608 982 871</span>
                                    </div>
                                </div>
                                
                                <div class="mb-5">
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">Ibn Rochd Hospital</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Emergency: +212 522 470 063</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Emergency: +212 522 470 078</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">SAMU (Ambulance Service)</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-ambulance text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 522 25 25 25</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-ambulance text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 522 98 98 98</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Rabat -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Rabat
                                </h4>
                            </div>
                            <div class="p-4">
                                <div class="mb-5">
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">Cheikh Zaïd Hospital</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Emergency: +212 80 2000 606</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">Alternate: +212 537 131 400</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">Military Hospital Mohamed V</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 537 714 419</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 537 714 417</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Marrakesh -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                    <i class="fas fa-map-marker-alt text-red-500 mr-2"></i> Marrakesh
                                </h4>
                            </div>
                            <div class="p-4">
                                <div>
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">Military Hospital Avicenne</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 524 43 1001</span>
                                    </div>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 524 43 9072</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 524 43 9073</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- UNHCR Partners -->
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden">
                            <div class="bg-gray-50 dark:bg-gray-700 px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <h4 class="font-medium text-gray-800 dark:text-white flex items-center">
                                    <i class="fas fa-hands-helping text-blue-500 mr-2"></i> UNHCR Medical Partners (AMPF)
                                </h4>
                            </div>
                            <div class="p-4">
                                <div class="mb-4">
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">Rabat</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 6 61 93 43 54</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 7 67 79 65 50</span>
                                    </div>
                                </div>
                                
                                <div>
                                    <h5 class="font-medium text-gray-800 dark:text-white mb-2">Casablanca</h5>
                                    <div class="flex items-start mb-1">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 7 67 79 63 38</span>
                                    </div>
                                    <div class="flex items-start">
                                        <i class="fas fa-phone-alt text-gray-500 mt-1 mr-2 w-4 text-center"></i>
                                        <span class="text-gray-700 dark:text-gray-300">+212 6 66 14 12 58</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Search Form -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white mb-4 flex items-center">
                        <span class="mr-2"><i class="fas fa-search text-blue-500"></i></span>
                        Find Local Emergency Contacts
                    </h3>
                    
                    <!-- Search Form -->
                    <form method="GET" action="{{ route('patients.emergency.contacts.process') }}" class="mb-8">
                        <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search Type</label>
                                    <select name="search_type" id="search_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-white">
                                        <option value="nearby" {{ isset($searchType) && $searchType == 'nearby' ? 'selected' : '' }}>Nearby Emergency Services</option>
                                        <option value="specialty" {{ isset($searchType) && $searchType == 'specialty' ? 'selected' : '' }}>By Specialty</option>
                                        <option value="location" {{ isset($searchType) && $searchType == 'location' ? 'selected' : '' }}>By Location</option>
                                    </select>
                                </div>
                                
                                <div id="specialty-field" class="{{ isset($searchType) && $searchType == 'specialty' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Medical Specialty</label>
                                    <input type="text" name="specialty" id="specialty" value="{{ $specialty ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="e.g., cardiology, pediatrics">
                                </div>
                                
                                <div id="location-field" class="{{ isset($searchType) && $searchType == 'location' ? '' : 'hidden' }}">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                                    <input type="text" name="location" id="location" value="{{ $location ?? '' }}" class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring focus:ring-blue-500 focus:ring-opacity-50 dark:bg-gray-900 dark:border-gray-700 dark:text-white" placeholder="City or area">
                                </div>
                            </div>
                            
                            <div class="mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 active:bg-red-900 focus:outline-none focus:border-red-900 focus:ring ring-red-300 dark:ring-red-700 disabled:opacity-25 transition ease-in-out duration-150">
                                    <i class="fas fa-search mr-2"></i> FIND EMERGENCY SERVICES
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Important Information -->
                    <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg p-4 mb-8">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <i class="fas fa-info-circle text-xl text-blue-600 dark:text-blue-400"></i>
                            </div>
                            <div class="ml-3">
                                <h4 class="text-sm font-medium text-blue-800 dark:text-blue-300">Important Information</h4>
                                <div class="mt-2 text-sm text-blue-700 dark:text-blue-200">
                                    <ul class="list-disc pl-5 space-y-1">
                                        <li>Emergency contact numbers may be updated by authorities. Always verify when possible.</li>
                                        <li>Some emergency lines may differ by region, especially in rural areas.</li>
                                        <li>In addition to public services, private providers offer 24/7 emergency support in major cities.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    @if(isset($emergencyContacts) && $emergencyContacts->count() > 0)
                        <h3 class="text-lg font-semibold text-gray-800 dark:text-white mb-4">Local Emergency Contacts Near You</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                            @foreach($emergencyContacts as $contact)
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg shadow-sm hover:shadow-md transition duration-300 overflow-hidden">
                                <div class="flex items-center space-x-4 p-4">
                                    <div class="rounded-full bg-red-100 p-3">
                                        <i class="fas fa-user-md text-red-500"></i>
                                    </div>
                                    <div>
                                        <h4 class="text-lg font-medium text-gray-900 dark:text-white">Dr. {{ str_replace('Dr. ', '', $contact->user->name) }}</h4>
                                        <p class="text-gray-600 dark:text-gray-400">{{ $contact->speciality->name }}</p>
                                    </div>
                                </div>
                                
                                <div class="px-4 pb-4 space-y-3">
                                    <div class="flex items-start">
                                        <i class="fas fa-map-marker-alt mt-1 text-gray-500 w-5"></i>
                                        <span class="text-gray-700 dark:text-gray-300 ml-2">{{ $contact->user->address->adresse }}, {{ $contact->user->address->ville }}</span>
                                    </div>
                                    
                                    <div class="flex items-center">
                                        <i class="fas fa-envelope text-gray-500 w-5"></i>
                                        <a href="mailto:{{ $contact->user->email }}" class="text-blue-600 dark:text-blue-400 hover:underline ml-2">{{ $contact->user->email }}</a>
                                    </div>
                                    
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center">
                                            <i class="fas fa-phone-alt text-gray-500 w-5"></i>
                                            <span class="text-gray-700 dark:text-gray-300 ml-2">{{ $contact->user->phone }}</span>
                                        </div>
                                        <button onclick="copyPhoneNumber('{{ $contact->user->phone }}')" class="text-blue-600 hover:text-blue-800 flex items-center">
                                            <i class="far fa-copy"></i>
                                        </button>
                                    </div>
                                    
                                    <div class="pt-2 grid grid-cols-2 gap-2">
                                        <a href="tel:{{ $contact->user->phone }}" class="inline-flex items-center justify-center px-4 py-2 bg-blue-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring ring-blue-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <i class="fas fa-phone-alt mr-2"></i> Call
                                        </a>
                                        <button onclick="showCallOptions('{{ $contact->user->phone }}', 'Dr. {{ str_replace('Dr. ', '', $contact->user->name) }}')" class="inline-flex items-center justify-center px-4 py-2 bg-green-600 rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-700 active:bg-green-900 focus:outline-none focus:border-green-900 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150">
                                            <i class="fas fa-ellipsis-h mr-2"></i> Options
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize tsParticles for emergency section
            tsParticles.load("particles-emergency", {
                particles: {
                    number: {
                        value: 15,
                        density: {
                            enable: true,
                            value_area: 800
                        }
                    },
                    color: {
                        value: ["#ff0000", "#3b82f6", "#10b981"]
                    },
                    shape: {
                        type: "circle"
                    },
                    opacity: {
                        value: 0.1,
                        random: true
                    },
                    size: {
                        value: 6,
                        random: true
                    },
                    move: {
                        enable: true,
                        speed: 1,
                        direction: "none",
                        random: true,
                        out_mode: "out"
                    }
                },
                interactivity: {
                    detect_on: "canvas",
                    events: {
                        onhover: {
                            enable: true,
                            mode: "bubble"
                        }
                    },
                    modes: {
                        bubble: {
                            distance: 150,
                            size: 8,
                            duration: 2,
                            opacity: 0.3
                        }
                    }
                },
                retina_detect: true
            });
            
            // Add 3D tilt effect to the emergency contact cards
            VanillaTilt.init(document.querySelectorAll(".emergency-contact-card"), {
                max: 5,
                speed: 300,
                glare: true,
                "max-glare": 0.1,
                scale: 1.02
            });
            
            // Animate doctor icons with GSAP
            const doctorIcons = document.querySelectorAll('.doctor-icon');
            doctorIcons.forEach(icon => {
                // Create a pulse animation
                gsap.to(icon, {
                    scale: 1.1,
                    duration: 1.5,
                    repeat: -1,
                    yoyo: true,
                    ease: "sine.inOut"
                });
            });
            
            // Enhanced hover animation for call buttons
            const callButtons = document.querySelectorAll('.emergency-contact-card a');
            callButtons.forEach(button => {
                button.addEventListener('mouseenter', () => {
                    gsap.to(button, {
                        scale: 1.05,
                        duration: 0.3,
                        ease: "back.out(1.7)"
                    });
                });
                button.addEventListener('mouseleave', () => {
                    gsap.to(button, {
                        scale: 1,
                        duration: 0.3,
                        ease: "back.in(1.7)"
                    });
                });
            });
        });
        
        // Create modal for call options if it doesn't exist
        if (!document.getElementById('callOptionsModal')) {
            const modal = document.createElement('div');
            modal.id = 'callOptionsModal';
            modal.className = 'fixed inset-0 z-50 overflow-auto bg-black bg-opacity-50 flex items-center justify-center hidden';
            modal.innerHTML = `
                <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-sm mx-auto shadow-xl">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white" id="contactName"></h3>
                        <button type="button" class="text-gray-400 hover:text-gray-500" onclick="closeCallModal()">
                            <span class="sr-only">Close</span>
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="space-y-4">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Choose how you want to contact this doctor:</p>
                        <div class="flex items-center justify-between">
                            <p class="text-gray-700 dark:text-gray-300 font-medium" id="contactPhone"></p>
                            <button onClick="copyModalPhoneNumber()" class="ml-2 text-blue-600 hover:text-blue-800 flex items-center" id="copyBtn">
                                <i class="far fa-copy mr-1"></i> Copy
                            </button>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <a id="normalCallBtn" href="#" class="inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-colors duration-200">
                                <i class="fas fa-phone-alt mr-2"></i> Call
                            </a>
                            <a id="whatsappBtn" href="#" class="inline-flex items-center justify-center px-4 py-3 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 transition-colors duration-200">
                                <i class="fab fa-whatsapp mr-2"></i> WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }
        
        function showCallOptions(phone, name) {
            const modal = document.getElementById('callOptionsModal');
            const contactName = document.getElementById('contactName');
            const contactPhone = document.getElementById('contactPhone');
            const normalCallBtn = document.getElementById('normalCallBtn');
            const whatsappBtn = document.getElementById('whatsappBtn');
            
            // Format phone number - remove any spaces or special characters
            const cleanPhone = phone.replace(/\s+/g, '').replace(/[-+]/g, '');
            // Ensure phone has the international format for WhatsApp
            const whatsappPhone = cleanPhone.startsWith('212') ? cleanPhone : '212' + cleanPhone.replace(/^0+/, '');
            
            // Set content
            contactName.textContent = name;
            contactPhone.textContent = phone;
            
            // Store the original phone number for copying
            contactPhone.dataset.phone = phone;
            
            // Set button links
            normalCallBtn.href = `tel:${cleanPhone}`;
            whatsappBtn.href = `https://wa.me/${whatsappPhone}`;
            
            // Show modal
            modal.classList.remove('hidden');
        }
        
        function closeCallModal() {
            const modal = document.getElementById('callOptionsModal');
            modal.classList.add('hidden');
            
            // Reset the copy button text
            const copyBtn = document.getElementById('copyBtn');
            copyBtn.innerHTML = '<i class="far fa-copy mr-1"></i> Copy';
        }
        
        function copyModalPhoneNumber() {
            const phoneElement = document.getElementById('contactPhone');
            const copyBtn = document.getElementById('copyBtn');
            const phone = phoneElement.dataset.phone;
            
            // Create a temporary textarea element to copy from
            const textarea = document.createElement('textarea');
            textarea.value = phone;
            textarea.style.position = 'fixed';  // Prevent scrolling to bottom
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            
            try {
                // Execute copy command
                const successful = document.execCommand('copy');
                
                // Show feedback
                if (successful) {
                    copyBtn.innerHTML = '<i class="fas fa-check mr-1"></i> Copied!';
                    setTimeout(() => {
                        copyBtn.innerHTML = '<i class="far fa-copy mr-1"></i> Copy';
                    }, 2000);
                } else {
                    copyBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Failed';
                }
            } catch (err) {
                console.error('Unable to copy', err);
                copyBtn.innerHTML = '<i class="fas fa-times mr-1"></i> Failed';
            }
            
            // Clean up
            document.body.removeChild(textarea);
        }
        
        function copyPhoneNumber(phone) {
            // Create a temporary textarea element to copy from
            const textarea = document.createElement('textarea');
            textarea.value = phone;
            textarea.style.position = 'fixed';  // Prevent scrolling to bottom
            document.body.appendChild(textarea);
            textarea.focus();
            textarea.select();
            
            try {
                // Execute copy command
                const successful = document.execCommand('copy');
                
                // Show feedback using SweetAlert2
                if (successful) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Phone number copied!',
                        text: phone,
                        timer: 1500,
                        showConfirmButton: false
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Copy failed',
                        text: 'Please try again manually'
                    });
                }
            } catch (err) {
                console.error('Unable to copy', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Copy failed',
                    text: 'Please try again manually'
                });
            }
            
            // Clean up
            document.body.removeChild(textarea);
        }

        // Toggle search fields based on selected search type
        document.addEventListener('DOMContentLoaded', function() {
            const searchTypeSelect = document.getElementById('search_type');
            const specialtyField = document.getElementById('specialty-field');
            const locationField = document.getElementById('location-field');
            
            // Initial state setup
            updateSearchFields();
            
            // Listen for changes to the search type dropdown
            searchTypeSelect.addEventListener('change', updateSearchFields);
            
            function updateSearchFields() {
                const searchType = searchTypeSelect.value;
                
                // Hide all fields first
                specialtyField.classList.add('hidden');
                locationField.classList.add('hidden');
                
                // Show the appropriate field based on selection
                if (searchType === 'specialty') {
                    specialtyField.classList.remove('hidden');
                } else if (searchType === 'location') {
                    locationField.classList.remove('hidden');
                }
            }
        });
    </script>
</x-patient-layout>
