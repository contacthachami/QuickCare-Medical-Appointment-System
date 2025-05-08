<head>
    <title>MA | Home</title>
</head>

<x-patient-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('Patient Dashboard') }}
        </h2>
    </x-slot>
    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    <!-- Start of Tawk.to Script -->

    <div
        class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1 flex justify-left animate__animated animate__fadeIn">
        <div class="md:flex justify-center items-center md:w-1/2">
            <div class="p-8 flex items-center">
                <div class="mr-12">
                    @php
                        $user = Auth::user()->img;
                        $patient = Auth::user()->patient;
                        $fullName = Auth::user()->name;
                        $nameParts = explode(' ', $fullName);
                        $firstName = isset($nameParts[0]) ? $nameParts[0] : '';
                        $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                        $initials = strtoupper(substr($firstName, 0, 1) . substr($lastName, 0, 1));
                    @endphp
                    @if($user)
                        <div class="w-24 h-24 md:w-36 md:h-36 lg:w-48 lg:h-48 rounded-2xl overflow-hidden shadow-md animate__animated animate__fadeInLeft">
                            <img src="{{ asset('storage/profile_pictures/' . $user) }}"
                                alt="Profile Picture"
                                class="w-full h-full object-cover object-center">
                        </div>
                    @else
                        <div class="w-24 h-24 md:w-36 md:h-36 lg:w-48 lg:h-48 rounded-2xl shadow-md bg-gray-200 flex items-center justify-center animate__animated animate__fadeInLeft user-initials">
                            <span class="text-4xl md:text-6xl font-bold text-gray-700">{{ $initials }}</span>
                        </div>
                    @endif
                </div>
                <div>
                    <div class="uppercase tracking-wide text-sm text-blue-500 font-semibold">WELCOME TO MA | HOME</div>
                    <div>
                        <p class="mt-2 text-gray-500">Welcome {{ auth()->user()->name }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-around">
        <div
            class="w-full md:w-1/2 p-6 mt-7 mr-4 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1 animate__animated animate__fadeInRight">
            <div class="flex flex-col justify-center ">
                <h2 class="mb-7 font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    <span class="mr-2"><i class="fa-regular fa-calendar-check" style="color: #74C0FC;"></i></span> My
                    Appointments
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="appoinments-cards">
                    @forelse ($patient->appointments as $index => $appointment)
                        <div
                            class="bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-4 appoinments-cards @if ($index >= 6) hidden @endif animate__animated animate__fadeInUp patient-card">
                            <h3 class="text-lg font-semibold mb-2">
                                <span class="mr-2">
                                    <i class="fa-solid fa-{{ $index }}" style="color: #74C0FC;"></i>
                                </span>
                                {{ $appointment->reason }}
                            </h3>

                            <p class="text-gray-600 mb-2 dark:text-white">Date: {{ $appointment->appointment_date }}</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Doctor: {{ $appointment->doctor->user->name }}</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Status: {{ $appointment->status }}</p>
                            <p class="text-gray-600 dark:text-white">Comment: {{ $appointment->doctor_comment }}</p>
                        </div>
                    @empty
                        <div
                            class="bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-4 appoinments-cards animate__animated animate__fadeInUp patient-card">
                            <h3 class="text-lg font-semibold mb-2">
                                <span class="mr-2">
                                    <i class="fa-solid fa-0" style="color: #74C0FC;"></i>
                                </span>
                                TEST
                            </h3>
                            <p class="text-gray-600 mb-2 dark:text-white">Date: 2025-04-21</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Doctor: el mehdi hachami</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Status: Pending</p>
                            <p class="text-gray-600 dark:text-white">Comment: Urgt</p>
                        </div>
                        <div
                            class="bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-4 appoinments-cards animate__animated animate__fadeInUp patient-card">
                            <h3 class="text-lg font-semibold mb-2">
                                <span class="mr-2">
                                    <i class="fa-solid fa-1" style="color: #74C0FC;"></i>
                                </span>
                                urgent rendez vous
                            </h3>
                            <p class="text-gray-600 mb-2 dark:text-white">Date: 2025-04-12</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Doctor: el mehdi hachami</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Status: Pending</p>
                            <p class="text-gray-600 dark:text-white">Comment: none</p>
                        </div>
                    @endforelse
                </div>
                @if ($patient->appointments->count() > 6)
                    <button id="show-more-btn-2"
                        class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md animate__animated animate__fadeInUp">Show
                        More</button>
                @endif
            </div>
        </div>
        <div
            class="w-full md:w-1/2 p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1 animate__animated animate__fadeInRight">
            <div class="flex flex-col justify-center">
                <h2 class="mb-7 font-semibold text-xl text-gray-800 leading-tight dark:text-white">
                    <span class="mr-2"><i class="fa-solid fa-suitcase-medical" style="color: #74C0FC;"></i></span> My
                    Medical History
                </h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="medical-history-cards">
                    @forelse ($patient->appointments as $index => $appointment)
                        <div
                            class="bg-white rounded-lg shadow-md dark:bg-dark-eval-1 dark:text-white p-4 medical-history-card @if ($index >= 9) hidden @endif animate__animated animate__fadeInUp patient-card">
                            <span class="mb-4">
                                <i class="fa-solid fa-{{ $index }}" style="color: #74C0FC;"></i>
                            </span>
                            <p class="text-gray-600 mb-2 dark:text-white">Doctor: {{ $appointment->doctor->user->name }}</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Status: {{ $appointment->status }}</p>
                        </div>
                    @empty
                        <div
                            class="bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-4 medical-history-card animate__animated animate__fadeInUp patient-card">
                            <h3 class="text-lg font-semibold mb-2">
                                <span class="mr-2">
                                    <i class="fa-solid fa-0" style="color: #74C0FC;"></i>
                                </span>
                            </h3>
                            <p class="text-gray-600 mb-2 dark:text-white">Doctor: el mehdi hachami</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Status: Pending</p>
                        </div>
                        <div
                            class="bg-white dark:bg-dark-eval-1 rounded-lg shadow-md p-4 medical-history-card animate__animated animate__fadeInUp patient-card">
                            <h3 class="text-lg font-semibold mb-2">
                                <span class="mr-2">
                                    <i class="fa-solid fa-1" style="color: #74C0FC;"></i>
                                </span>
                            </h3>
                            <p class="text-gray-600 mb-2 dark:text-white">Doctor: el mehdi hachami</p>
                            <p class="text-gray-600 mb-2 dark:text-white">Status: Pending</p>
                        </div>
                    @endforelse
                </div>
                @if ($patient->appointments->count() > 9)
                    <button id="show-more-btn"
                        class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md animate__animated animate__fadeInUp">Show
                        More</button>
                @endif
            </div>
        </div>
    </div>
    <!-- Additional Features Section -->
    <div class="p-6 mt-7 bg-white rounded-md shadow-md dark:bg-dark-eval-1 animate__animated animate__fadeIn">
        <h2 class="mb-4 font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            <span class="mr-2"><i class="fa-brands fa-pagelines" style="color: #74C0FC;"></i></span> Additional
            Features
        </h2>
        <div class="flex flex-col md:flex-row justify-between">
            <!-- Feature 1: Appointment Reminder -->
            <div
                class="w-full md:w-1/3 dark:bg-dark-eval-1 p-4 bg-gray-100 rounded-md shadow-md mb-4 md:mb-0 animate__animated animate__fadeInLeft feature-card">
                <div class="text-center">
                    <i class="fas fa-clock text-3xl text-blue-500 mb-2 feature-icon"></i>
                    <p class="text-gray-700 font-semibold mb-2 dark:text-white">Appointment Reminder</p>
                    <p class="text-gray-600 dark:text-white">Never miss an Appointment with our Appointment Reminder
                        feature. Take Your Appointment and receive notifications when it's time.</p>
                </div>
            </div>
            <!-- Feature 2: Emergency Contact -->
            <div
                class="w-full dark:bg-dark-eval-1 md:w-1/3 p-4 bg-gray-100 rounded-md shadow-md mb-4 md:mb-0 animate__animated animate__fadeInRight feature-card">
                <div class="text-center">
                    <i class="fas fa-phone-alt text-3xl text-blue-500 mb-2 feature-icon"></i>
                    <p class="text-gray-700 font-semibold mb-2 dark:text-white">Emergency Contact</p>
                    <p class="text-gray-600 dark:text-white">Have peace of mind knowing that emergency assistance is
                        just a call away. Save important emergency contacts for quick access.</p>
                </div>
            </div>
            <!-- Feature 3: Discover Doctors -->
            <div
                class="w-full dark:bg-dark-eval-1 md:w-1/3 p-4 bg-gray-100 rounded-md shadow-md animate__animated animate__fadeInLeft feature-card">
                <div class="text-center">
                    <i class="fas fa-user-md text-3xl text-blue-500 mb-2 feature-icon"></i>
                    <p class="text-gray-700 font-semibold mb-2 dark:text-white">Discover Doctors</p>
                    <p class="text-gray-600 dark:text-white">Explore our list of doctors, select your preferred
                        physician, and book appointments based on their specialization and rating.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Add JavaScript to handle "Show More" buttons -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // For Appointments section
            const showMoreBtn2 = document.getElementById('show-more-btn-2');
            if(showMoreBtn2) {
                showMoreBtn2.addEventListener('click', function() {
                    const hiddenCards = document.querySelectorAll('.appoinments-cards.hidden');
                    hiddenCards.forEach(card => {
                        card.classList.remove('hidden');
                        card.classList.add('animate__animated', 'animate__fadeInUp');
                    });
                    this.classList.add('hidden');
                });
            }

            // For Medical History section
            const showMoreBtn = document.getElementById('show-more-btn');
            if(showMoreBtn) {
                showMoreBtn.addEventListener('click', function() {
                    const hiddenCards = document.querySelectorAll('.medical-history-card.hidden');
                    hiddenCards.forEach(card => {
                        card.classList.remove('hidden');
                        card.classList.add('animate__animated', 'animate__fadeInUp');
                    });
                    this.classList.add('hidden');
                });
            }
        });
    </script>
    <!-- End JavaScript -->
</x-patient-layout>

<!-- New Advanced Healthcare Chat Assistant -->
<div id="quickcare-chat-widget" class="fixed z-50 hidden">
    <!-- Chat Container -->
    <div id="chat-container" class="bg-white rounded-lg shadow-xl overflow-hidden w-80 sm:w-96 transition-all duration-300 flex flex-col max-h-[600px]">
        <!-- Chat Header -->
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 p-4 flex justify-between items-center">
            <div class="flex items-center">
                <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                        <path d="M10 4a1 1 0 100 2 1 1 0 000-2zm0 8a1 1 0 100 2 1 1 0 000-2zm-2-4a1 1 0 100 2 1 1 0 000-2zm4 0a1 1 0 100 2 1 1 0 000-2z" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-white font-semibold">QuickCare Assistant</h3>
                    <span class="text-blue-100 text-xs">Healthcare Assistant</span>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <button id="chat-minimize" class="text-white hover:text-blue-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4 10a1 1 0 011-1h10a1 1 0 110 2H5a1 1 0 01-1-1z" clip-rule="evenodd" />
                    </svg>
                </button>
                <button id="chat-close" class="text-white hover:text-blue-200 transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
        
        <!-- Chat Messages -->
        <div id="chat-messages" class="flex-1 p-4 overflow-y-auto bg-gray-50">
            <!-- Messages will be dynamically added here -->
            <div class="flex mb-4">
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                        <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3 bg-white p-3 rounded-lg shadow-sm max-w-[85%]">
                    <p class="text-gray-800">Hi there, {{ Auth::user()->name }}! 👋 How can I help you today with QuickCare services?</p>
                </div>
            </div>
        </div>
        
        <!-- Suggested Questions -->
        <div id="suggested-questions" class="p-2 bg-gray-100 border-t border-gray-200">
            <p class="text-xs text-gray-500 mb-2 font-medium">Quick help with:</p>
            <div class="flex flex-wrap gap-2">
                <button class="suggested-question px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs hover:bg-blue-200 transition whitespace-nowrap">Find a doctor</button>
                <button class="suggested-question px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs hover:bg-blue-200 transition whitespace-nowrap">Appointment info</button>
                <button class="suggested-question px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs hover:bg-blue-200 transition whitespace-nowrap">Medical history</button>
                <button class="suggested-question px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs hover:bg-blue-200 transition whitespace-nowrap">Emergency help</button>
            </div>
        </div>
        
        <!-- Chat Input -->
        <div class="p-3 bg-white border-t border-gray-200">
            <div class="flex items-center bg-gray-100 rounded-full px-4 py-2">
                <input type="text" id="chat-input" placeholder="Type your question here..." class="bg-transparent flex-1 focus:outline-none text-gray-700">
                <button id="chat-send" class="ml-2 text-blue-500 hover:text-blue-600">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-8.707l-3-3a1 1 0 00-1.414 0l-3 3a1 1 0 001.414 1.414L9 9.414V13a1 1 0 102 0V9.414l1.293 1.293a1 1 0 001.414-1.414z" clip-rule="evenodd" />
                    </svg>
                </button>
            </div>
        </div>
    </div>
    
    <!-- Chat Button -->
    <button id="chat-button" class="w-14 h-14 rounded-full bg-gradient-to-r from-blue-500 to-blue-600 text-white shadow-lg flex items-center justify-center fixed bottom-6 right-6 z-50 hover:shadow-xl transform hover:scale-105 transition-all duration-300">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
        </svg>
    </button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Chat widget elements
        const chatWidget = document.getElementById('quickcare-chat-widget');
        const chatButton = document.getElementById('chat-button');
        const chatContainer = document.getElementById('chat-container');
        const chatClose = document.getElementById('chat-close');
        const chatMinimize = document.getElementById('chat-minimize');
        const chatMessages = document.getElementById('chat-messages');
        const chatInput = document.getElementById('chat-input');
        const chatSend = document.getElementById('chat-send');
        const suggestedQuestions = document.querySelectorAll('.suggested-question');
        
        // Show chat widget
        chatWidget.classList.remove('hidden');
        
        // Toggle chat window
        let isChatOpen = false;
        
        function toggleChat() {
            if (isChatOpen) {
                chatContainer.classList.add('hidden');
                chatButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                    </svg>`;
            } else {
                chatContainer.classList.remove('hidden');
                chatButton.innerHTML = `
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>`;
                chatInput.focus();
            }
            isChatOpen = !isChatOpen;
        }
        
        chatButton.addEventListener('click', toggleChat);
        chatClose.addEventListener('click', () => {
            chatContainer.classList.add('hidden');
            isChatOpen = false;
            chatButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>`;
        });
        
        chatMinimize.addEventListener('click', toggleChat);
        
        // Send message function
        function sendMessage(message) {
            if (!message.trim()) return;
            
            // Add user message
            const userMessageHtml = `
                <div class="flex justify-end mb-4">
                    <div class="bg-blue-500 text-white p-3 rounded-lg shadow-sm max-w-[85%]">
                        <p>${message}</p>
                    </div>
                </div>
            `;
            chatMessages.innerHTML += userMessageHtml;
            
            // Clear input
            chatInput.value = '';
            
            // Scroll to bottom
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Show typing indicator
            const typingIndicatorHtml = `
                <div id="typing-indicator" class="flex mb-4">
                    <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                            <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3 bg-white p-3 rounded-lg shadow-sm max-w-[85%]">
                        <div class="flex space-x-1">
                            <div class="typing-dot bg-gray-500 rounded-full w-2 h-2 animate-pulse"></div>
                            <div class="typing-dot bg-gray-500 rounded-full w-2 h-2 animate-pulse" style="animation-delay: 0.2s"></div>
                            <div class="typing-dot bg-gray-500 rounded-full w-2 h-2 animate-pulse" style="animation-delay: 0.4s"></div>
                        </div>
                    </div>
                </div>
            `;
            chatMessages.innerHTML += typingIndicatorHtml;
            chatMessages.scrollTop = chatMessages.scrollHeight;
            
            // Process message and generate response
            setTimeout(() => {
                const typingIndicator = document.getElementById('typing-indicator');
                if (typingIndicator) {
                    typingIndicator.remove();
                }
                
                let botResponse = processUserMessage(message);
                const botMessageHtml = `
                    <div class="flex mb-4">
                        <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center flex-shrink-0">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" viewBox="0 0 20 20" fill="currentColor">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z" />
                                <path fill-rule="evenodd" d="M10 2a8 8 0 100 16 8 8 0 000-16zm0 14a6 6 0 100-12 6 6 0 000 12z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3 bg-white p-3 rounded-lg shadow-sm max-w-[85%]">
                            <p class="text-gray-800">${botResponse}</p>
                        </div>
                    </div>
                `;
                chatMessages.innerHTML += botMessageHtml;
                chatMessages.scrollTop = chatMessages.scrollHeight;
            }, 1000);
        }
        
        // Process user message and return appropriate response
        function processUserMessage(message) {
            const lowerMessage = message.toLowerCase();
            
            // Process appointment related queries
            if (lowerMessage.includes('appointment') || lowerMessage.includes('book') || lowerMessage.includes('schedule')) {
                return `You can book or manage appointments in a few ways:<br>
                1. Go to <a href="{{ route('patiens.doctors') }}" class="text-blue-500 hover:underline">Doctor List</a> to find and book with a specific doctor<br>
                2. Check your <a href="{{ route('patiens.my.appointments', Auth::user()->patient->id) }}" class="text-blue-500 hover:underline">My Appointments</a> section to view existing appointments<br>
                3. Need to reschedule? You can cancel your current appointment and book a new one.`;
            }
            
            // Process doctor related queries
            else if (lowerMessage.includes('doctor') || lowerMessage.includes('specialist') || lowerMessage.includes('physician')) {
                return `You can find doctors by:<br>
                1. Browsing our complete <a href="{{ route('patiens.doctors') }}" class="text-blue-500 hover:underline">Doctor List</a><br>
                2. Filtering by specialty or name<br>
                3. Reading reviews and ratings from other patients<br>
                Would you like me to suggest some highly rated doctors in a specific field?`;
            }
            
            // Process medical history queries
            else if (lowerMessage.includes('medical history') || lowerMessage.includes('records') || lowerMessage.includes('health data')) {
                return `Your medical history is kept secure and private. You can access your records in the 'My Medical History' section of your dashboard. This includes past appointments, diagnoses, and treatment notes from your healthcare providers.`;
            }
            
            // Process emergency related queries
            else if (lowerMessage.includes('emergency') || lowerMessage.includes('urgent') || lowerMessage.includes('critical')) {
                return `For medical emergencies, please dial <strong>15</strong> immediately for ambulance services or <strong>190</strong> for general emergency services.<br><br>You can also find nearby emergency facilities in our <a href="{{ route('patients.emergency.contacts.view') }}" class="text-blue-500 hover:underline">Emergency Contacts</a> section.`;
            }
            
            // Process health tip queries
            else if (lowerMessage.includes('health tip') || lowerMessage.includes('advice') || lowerMessage.includes('recommendation')) {
                return `For health advice and latest articles, visit our <a href="{{ route('patiens.health.tips.view') }}" class="text-blue-500 hover:underline">Health Articles</a> section. We regularly update it with valuable information from medical professionals. Remember, these tips are general in nature - always consult your doctor for personalized advice.`;
            }
            
            // Default response for other queries
            else {
                return `I'm here to help with any questions about appointments, finding doctors, accessing medical records, or navigating QuickCare services. Is there something specific you'd like to know about your healthcare options?`;
            }
        }
        
        // Send message on button click
        chatSend.addEventListener('click', () => {
            sendMessage(chatInput.value);
        });
        
        // Send message on Enter key
        chatInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                sendMessage(chatInput.value);
            }
        });
        
        // Handle suggested questions
        suggestedQuestions.forEach(button => {
            button.addEventListener('click', () => {
                sendMessage(button.textContent);
            });
        });
    });
</script>

<style>
    #chat-messages {
        max-height: 300px;
    }
    
    #chat-container {
        position: fixed;
        bottom: 80px;
        right: 20px;
        box-shadow: 0 10px 25px -5px rgba(59, 130, 246, 0.5);
        max-height: 500px;
    }
    
    @keyframes pulse {
        0%, 100% {
            opacity: 0.5;
        }
        50% {
            opacity: 1;
        }
    }
    
    .animate-pulse {
        animation: pulse 1.5s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
    
    @media (max-width: 640px) {
        #chat-container {
            width: calc(100% - 40px);
            bottom: 70px;
        }
    }
</style>

@push('scripts')
    <!-- Animation Libraries -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vanilla-tilt@1.8.0/dist/vanilla-tilt.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.9.3/tsparticles.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@lottiefiles/lottie-player@1.7.1/dist/lottie-player.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.11.1/dist/cdn.min.js" defer></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize AOS
            AOS.init({
                duration: 800,
                once: true
            });
            
            // GSAP animations for dashboard elements
            gsap.from(".welcome-text", {
                duration: 0.8, 
                y: -20, 
                opacity: 0, 
                ease: "power3.out",
                delay: 0.2
            });
            
            gsap.from(".dashboard-card", {
                duration: 0.6, 
                opacity: 0, 
                y: 20, 
                stagger: 0.15,
                ease: "back.out(1.7)"
            });
            
            // Anime.js for appointment cards
            anime({
                targets: '.appointment-item',
                translateY: [40, 0],
                opacity: [0, 1],
                duration: 1200,
                delay: anime.stagger(100),
                easing: 'easeOutExpo'
            });
            
            // Vanilla-tilt for 3D hover effect on cards
            VanillaTilt.init(document.querySelectorAll(".feature-card"), {
                max: 10,
                speed: 300,
                glare: true,
                "max-glare": 0.1,
                scale: 1.03
            });
            
            // Initialize Particles.js for the welcome section background
            if (document.getElementById('particles-background')) {
                tsParticles.load("particles-background", {
                    particles: {
                        number: {
                            value: 30,
                            density: {
                                enable: true,
                                value_area: 800
                            }
                        },
                        color: {
                            value: "#3b82f6"
                        },
                        shape: {
                            type: "circle"
                        },
                        opacity: {
                            value: 0.3,
                            random: true
                        },
                        size: {
                            value: 5,
                            random: true
                        },
                        move: {
                            enable: true,
                            speed: 1.5,
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
                                mode: "grab"
                            },
                            onclick: {
                                enable: true,
                                mode: "push"
                            }
                        },
                        modes: {
                            grab: {
                                distance: 140,
                                line_linked: {
                                    opacity: 0.5
                                }
                            },
                            push: {
                                particles_nb: 3
                            }
                        }
                    },
                    retina_detect: true
                });
            }
        });
    </script>
@endpush
