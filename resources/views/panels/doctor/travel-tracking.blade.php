<x-doctor-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fas fa-route mr-2 text-blue-600"></i>{{ __('Travel Tracking') }}
            </h2>
            <div class="text-right">
                <span class="text-sm text-gray-600">Current Date:</span>
                <span class="ml-1 font-medium">{{ \Carbon\Carbon::now()->format('M d, Y') }}</span>
            </div>
        </div>
    </x-slot>

    @php 
        // Make sure we have consistent variable names with what the controller provides
        // The controller provides: $todaysAppointments, $activeTravelAppointment, $hasActiveAppointments
        
        // For backward compatibility with existing code
        $todayAppointments = $todaysAppointments ?? collect();
        $currentlyTraveling = isset($activeTravelAppointment) && $activeTravelAppointment !== null;
        $currentAppointment = $activeTravelAppointment ?? null;
        
        // Ensure $hasActiveAppointments is defined
        $hasActiveAppointments = $hasActiveAppointments ?? ($todayAppointments->count() > 0);
    @endphp

    <!-- CSS Styles -->
    <style>
        .timer-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            letter-spacing: 1px;
        }
        .appointment-card {
            transition: all 0.3s ease;
            border: 1px solid #e5e7eb;
        }
        .appointment-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .active-travel {
            animation: pulse-border 2s infinite;
        }
        .btn-action {
            transition: all 0.2s ease;
        }
        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        @keyframes pulse-border {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.7);
                border-color: rgba(37, 99, 235, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
                border-color: rgba(37, 99, 235, 0.9);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
                border-color: rgba(37, 99, 235, 0.7);
            }
        }
    </style>

    <!-- Success and Error Messages -->
    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    <div class="container mx-auto">
        <h1 class="text-2xl font-bold text-gray-900 mb-6">Travel Tracking</h1>

        <!-- Debug Section - START -->
        <div class="mb-8 bg-gray-100 border border-gray-300 rounded-lg p-5">
            <h2 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-bug mr-2 text-red-500"></i> Debug Information
            </h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <h3 class="font-medium text-sm text-gray-500 mb-2">Active Appointment Data</h3>
                    <div class="text-xs font-mono bg-gray-50 p-2 rounded border border-gray-200 max-h-28 overflow-y-auto">
                        @if($hasActiveAppointments)
                            @foreach($todayAppointments as $index => $appt)
                                <div class="mb-1 pb-1 border-b border-dashed border-gray-200">
                                    <div><span class="text-gray-500">ID:</span> {{ $appt->id }}</div>
                                    <div><span class="text-gray-500">Patient:</span> {{ $appt->patient->user->name }}</div>
                                    <div><span class="text-gray-500">Check-in:</span> {{ $appt->check_in_time ? $appt->check_in_time : 'Not checked in' }}</div>
                                    <div><span class="text-gray-500">Check-out:</span> {{ $appt->check_out_time ? $appt->check_out_time : 'Not checked out' }}</div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-gray-500 italic">No active appointments found</div>
                        @endif
                    </div>
                </div>
                
                <div class="p-3 bg-white rounded-lg shadow-sm">
                    <h3 class="font-medium text-sm text-gray-500 mb-2">Test Actions</h3>
                    <div class="flex flex-wrap gap-2">
                        @if($hasActiveAppointments && isset($todayAppointments[0]))
                            @if(!$todayAppointments[0]->check_in_time)
                                <form action="{{ route('doctor.appointment.check-in', $todayAppointments[0]->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white text-xs py-1 px-2 rounded">
                                        Test Check-In
                                    </button>
                                </form>
                            @elseif(!$todayAppointments[0]->check_out_time)
                                <form action="{{ route('doctor.appointment.check-out', $todayAppointments[0]->id) }}" method="POST" class="inline-block">
                                    @csrf
                                    <button type="submit" class="bg-green-500 hover:bg-green-600 text-white text-xs py-1 px-2 rounded">
                                        Test Check-Out
                                    </button>
                                </form>
                            @endif
                        @else
                            <div class="text-gray-500 italic text-xs">No appointments available for testing</div>
                        @endif
                    </div>
                    <div class="mt-2 text-xs text-gray-500">
                        These buttons can be used to test the check-in/out functionality for the first appointment.
                    </div>
                </div>
            </div>
            
            <div class="text-xs text-gray-500 italic">
                This section is for debugging purposes only and should be removed in production.
            </div>
        </div>
        <!-- Debug Section - END -->
        
        <div class="bg-white rounded-lg shadow-md p-6">
            @if(isset($activeTravelAppointment))
                <!-- Currently Traveling Section -->
                <div class="bg-blue-50 p-5 rounded-lg border border-blue-200 mb-6">
                    <h2 class="text-lg font-bold text-blue-800 mb-2">Currently Traveling</h2>
                    <div class="flex items-center mb-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-user text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium">{{ $activeTravelAppointment->patient->user->name }}</p>
                            <p class="text-sm text-gray-600">{{ $activeTravelAppointment->patient->user->address->ville }}, {{ $activeTravelAppointment->patient->user->address->rue }}</p>
                        </div>
                    </div>
                    
                    <div class="flex items-center mb-4">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-clock text-blue-600"></i>
                        </div>
                        <div>
                            <p class="font-medium">Check-in time: {{ \Carbon\Carbon::parse($activeTravelAppointment->check_in_time)->format('h:i A') }}</p>
                            <p class="text-sm text-gray-600">Traveling for: <span id="travelTime" data-checkin="{{ $activeTravelAppointment->check_in_time }}">0 minutes</span></p>
                        </div>
                    </div>
                    
                    <div class="mt-5">
                        <form action="{{ route('doctor.appointment.check-out', $activeTravelAppointment->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-lg flex items-center justify-center font-bold text-xl shadow-lg border-2 border-green-700 transition duration-200 ease-in-out transform hover:scale-102">
                                <i class="fas fa-flag-checkered mr-3 text-2xl"></i> COMPLETE TRAVEL (CHECK-OUT)
                            </button>
                        </form>
                        <p class="text-center mt-2 text-sm text-gray-600">Click this button when you arrive at your destination to stop tracking travel time</p>
                    </div>
                </div>
            @endif

            <!-- Quick Action Buttons -->
            <div class="p-6 bg-white rounded-lg shadow-lg border border-gray-200 mt-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <i class="fas fa-tachometer-alt mr-2 text-blue-600"></i> Quick Travel Actions
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Check-in Button -->
                    <div class="bg-blue-50 rounded-lg p-5 border-2 border-blue-300">
                        <h3 class="text-lg font-semibold text-blue-800 mb-3">Start Travel Recording</h3>
                        <p class="text-sm text-blue-700 mb-4">Click the button below when you begin traveling to your next appointment</p>
                        
                        @php
                            $nextAppointment = null;
                            foreach ($appointments as $appt) {
                                if ($appt->status == 'Approved' && !$appt->check_in_time) {
                                    $appointmentDate = \Carbon\Carbon::parse($appt->appointment_date)->toDateString();
                                    $today = \Carbon\Carbon::now()->toDateString();
                                    if ($appointmentDate == $today) {
                                        $nextAppointment = $appt;
                                        break;
                                    }
                                }
                            }
                        @endphp
                        
                        @if($nextAppointment && !$currentlyTraveling)
                            <form action="{{ route('doctor.appointment.check-in', $nextAppointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-4 px-6 rounded-lg flex items-center justify-center text-xl font-bold shadow-lg border-b-4 border-blue-800 btn-action">
                                    <i class="fas fa-play-circle mr-3 text-2xl"></i> START TRAVEL
                                </button>
                                <p class="text-center text-blue-600 text-sm mt-2">Next appointment: {{ $nextAppointment->patient->user->name }} at {{ \Carbon\Carbon::parse($nextAppointment->appointment_date)->format('h:i A') }}</p>
                            </form>
                        @elseif($currentlyTraveling)
                            <div class="w-full bg-gray-400 text-white py-4 px-6 rounded-lg flex items-center justify-center text-xl font-bold opacity-50 cursor-not-allowed">
                                <i class="fas fa-play-circle mr-3 text-2xl"></i> START TRAVEL
                            </div>
                            <p class="text-center text-blue-600 text-sm mt-2">You are already tracking travel for an appointment</p>
                        @else
                            <div class="w-full bg-gray-400 text-white py-4 px-6 rounded-lg flex items-center justify-center text-xl font-bold opacity-50 cursor-not-allowed">
                                <i class="fas fa-play-circle mr-3 text-2xl"></i> START TRAVEL
                            </div>
                            <p class="text-center text-blue-600 text-sm mt-2">No upcoming appointments available for travel</p>
                        @endif
                    </div>
                    
                    <!-- Check-out Button -->
                    <div class="bg-green-50 rounded-lg p-5 border-2 border-green-300">
                        <h3 class="text-lg font-semibold text-green-800 mb-3">Complete Travel Recording</h3>
                        <p class="text-sm text-green-700 mb-4">Click the button below when you arrive at your appointment location</p>
                        
                        @if($currentlyTraveling)
                            <form action="{{ route('doctor.appointment.check-out', $currentAppointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-4 px-6 rounded-lg flex items-center justify-center text-xl font-bold shadow-lg border-b-4 border-green-800 btn-action">
                                    <i class="fas fa-flag-checkered mr-3 text-2xl"></i> COMPLETE TRAVEL
                                </button>
                                <p class="text-center text-green-600 text-sm mt-2">Currently traveling to: {{ $currentAppointment->patient->user->name }}</p>
                            </form>
                        @else
                            <div class="w-full bg-gray-400 text-white py-4 px-6 rounded-lg flex items-center justify-center text-xl font-bold opacity-50 cursor-not-allowed">
                                <i class="fas fa-flag-checkered mr-3 text-2xl"></i> COMPLETE TRAVEL
                            </div>
                            <p class="text-center text-green-600 text-sm mt-2">No active travel to complete</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Travel Analytics Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
                @php
                    $completedAppointments = collect();
                    foreach ($appointments as $appt) {
                        if ($appt->check_in_time && $appt->check_out_time) {
                            $completedAppointments->push($appt);
                        }
                    }
                    
                    $totalTrips = $completedAppointments->count();
                    $totalTravelTime = $completedAppointments->sum('travel_time_minutes');
                    $avgTravelTime = $totalTrips > 0 ? round($totalTravelTime / $totalTrips, 1) : 0;
                    
                    // Get the last 7 days of travel data
                    $last7DaysTrips = $completedAppointments->filter(function($appt) {
                        return \Carbon\Carbon::parse($appt->appointment_date)->gt(\Carbon\Carbon::now()->subDays(7));
                    });
                    
                    $lastWeekTravelTime = $last7DaysTrips->sum('travel_time_minutes');
                @endphp

                <!-- Travel Time Stats -->
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-pie mr-2 text-blue-600"></i> Travel Summary
                    </h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <div class="text-xs uppercase text-blue-600 font-semibold">Total Time</div>
                            <div class="text-2xl font-bold text-blue-800">{{ $totalTravelTime }} min</div>
                            <div class="text-xs text-blue-600 mt-1">All time</div>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <div class="text-xs uppercase text-green-600 font-semibold">Avg Time</div>
                            <div class="text-2xl font-bold text-green-800">{{ $avgTravelTime }} min</div>
                            <div class="text-xs text-green-600 mt-1">Per visit</div>
                        </div>
                        <div class="bg-purple-50 p-4 rounded-lg">
                            <div class="text-xs uppercase text-purple-600 font-semibold">Total Trips</div>
                            <div class="text-2xl font-bold text-purple-800">{{ $totalTrips }}</div>
                            <div class="text-xs text-purple-600 mt-1">Completed</div>
                        </div>
                        <div class="bg-indigo-50 p-4 rounded-lg">
                            <div class="text-xs uppercase text-indigo-600 font-semibold">Last 7 Days</div>
                            <div class="text-2xl font-bold text-indigo-800">{{ $lastWeekTravelTime }} min</div>
                            <div class="text-xs text-indigo-600 mt-1">Travel time</div>
                        </div>
                    </div>
                </div>

                <!-- Travel Time Chart -->
                <div class="bg-white p-6 rounded-lg shadow-md border border-gray-200 md:col-span-2">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-blue-600"></i> Travel Time Trends
                    </h3>
                    
                    <div class="h-52">
                        <canvas id="travelChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Travel Records History Section -->
            <div class="mt-8 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Travel Records History</h2>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white divide-y divide-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Appointment</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-In Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Check-Out Time</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travel Duration</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @forelse ($appointments as $appointment)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $appointment->date ? \Carbon\Carbon::parse($appointment->date)->format('M j, Y') : 'N/A' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        {{ $appointment->patient ? $appointment->patient->full_name : 'Unknown Patient' }} - 
                                        {{ $appointment->service ? $appointment->service->name : 'Unknown Service' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appointment->check_in_time)
                                            <span class="text-blue-600 font-medium">
                                                {{ \Carbon\Carbon::parse($appointment->check_in_time)->format('g:i A') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Not recorded</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appointment->check_out_time)
                                            <span class="text-green-600 font-medium">
                                                {{ \Carbon\Carbon::parse($appointment->check_out_time)->format('g:i A') }}
                                            </span>
                                        @else
                                            <span class="text-gray-400">Not recorded</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appointment->travel_time_minutes)
                                            <span class="font-semibold">
                                                {{ $appointment->travel_time_minutes }} minutes
                                            </span>
                                        @else
                                            <span class="text-gray-400">N/A</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($appointment->check_in_time && $appointment->check_out_time)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Completed
                                            </span>
                                        @elseif($appointment->check_in_time && !$appointment->check_out_time)
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                In Progress
                                            </span>
                                        @else
                                            <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                Not Started
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        No travel records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <div class="mt-4">
                    {{ $appointments->links() }}
                </div>
            </div>

            <!-- Export Section -->
            <div class="mt-6 bg-white p-6 rounded-lg shadow-md">
                <h2 class="text-xl font-bold mb-4">Export Travel Records</h2>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('doctor.travel.export.excel') }}" class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition">
                        <i class="fas fa-file-excel mr-2"></i> Export to Excel
                    </a>
                    <a href="{{ route('doctor.travel.export.csv') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition">
                        <i class="fas fa-file-csv mr-2"></i> Export to CSV
                    </a>
                    <a href="{{ route('doctor.travel.export.pdf') }}" class="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition">
                        <i class="fas fa-file-pdf mr-2"></i> Export to PDF
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript for Travel Timer -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get all timer elements on the page
            const timerElements = document.querySelectorAll('[id^="timer"]');
            const travelTimeElements = document.querySelectorAll('[id^="travelTime"]');
            
            // Function to format time as HH:MM:SS
            function formatTime(totalSeconds) {
                const hours = Math.floor(totalSeconds / 3600);
                const minutes = Math.floor((totalSeconds % 3600) / 60);
                const seconds = totalSeconds % 60;
                
                return `${hours.toString().padStart(2, '0')}:${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
            }
            
            // Setup timers for all elements that need them
            timerElements.forEach((timerElement, index) => {
                // Get corresponding travel time element
                const travelTimeElement = travelTimeElements[index] || null;
                
                if (travelTimeElement) {
                    const checkInTime = travelTimeElement.getAttribute('data-checkin');
                    
                    if (checkInTime) {
                        const startTime = new Date(checkInTime);
                        
                        function updateTimer() {
                            const now = new Date();
                            const diffMs = now - startTime;
                            
                            // Calculate seconds
                            const diffSecs = Math.floor(diffMs / 1000);
                            
                            // Update timer display
                            timerElement.textContent = formatTime(diffSecs);
                            
                            // Update travel time text if it exists
                            if (travelTimeElement) {
                                const totalMinutes = Math.floor(diffMs / 60000);
                                travelTimeElement.textContent = `${totalMinutes} minute${totalMinutes !== 1 ? 's' : ''}`;
                            }
                        }
                        
                        // Update immediately and then every second
                        updateTimer();
                        setInterval(updateTimer, 1000);
                        
                        // Add pulsing animation to the timer for visual emphasis
                        timerElement.classList.add('timer-pulse');
                    }
                }
            });
            
            // Initialize the travel time chart if it exists
            const ctx = document.getElementById('travelChart');
            if (ctx) {
                // Sample data - in a real app, this would come from the backend
                const travelData = @json($completedAppointments->take(7)->map(function($appt) {
                    return [
                        'date' => \Carbon\Carbon::parse($appt->appointment_date)->format('M j'),
                        'duration' => $appt->travel_time_minutes
                    ];
                }));
                
                new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: travelData.map(item => item.date),
                        datasets: [{
                            label: 'Travel Minutes',
                            data: travelData.map(item => item.duration),
                            backgroundColor: 'rgba(59, 130, 246, 0.6)',
                            borderColor: 'rgba(59, 130, 246, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Minutes'
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>

    <!-- Add CSS for timer animation -->
    <style>
        .timer-display {
            font-family: 'Courier New', monospace;
            font-weight: bold;
            letter-spacing: 1px;
            border-radius: 8px;
            background-color: rgba(37, 99, 235, 0.1);
            padding: 8px 16px;
            display: inline-block;
            border: 2px solid rgba(37, 99, 235, 0.3);
        }
        
        .timer-pulse {
            animation: timer-pulse-animation 2s infinite;
        }
        
        @keyframes timer-pulse-animation {
            0% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0.5);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(37, 99, 235, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(37, 99, 235, 0);
            }
        }
    </style>
</x-doctor-layout>