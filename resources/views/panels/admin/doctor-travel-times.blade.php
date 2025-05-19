<x-admin-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Doctor Travel Analytics') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Dashboard Header -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="flex flex-col md:flex-row justify-between items-center">
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800">Doctor Travel Time Analytics</h2>
                            <p class="text-gray-600 mt-1">Monitor and analyze travel times for optimal scheduling</p>
                        </div>
                        
                        @if(count($doctorStats) > 0)
                            <div class="mt-4 md:mt-0 bg-blue-50 px-4 py-2 rounded-lg border border-blue-200">
                                <div class="flex items-center">
                                    <div class="p-2 bg-blue-100 rounded-full">
                                        <i class="fas fa-chart-line text-blue-700"></i>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-blue-800">
                                            <span class="font-bold">{{ count($doctorStats) }}</span> doctors with travel data
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(count($doctorStats) > 0)
                <!-- Summary Statistics -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    @php
                        $totalDoctors = count($doctorStats);
                        $totalAppointments = array_sum(array_column($doctorStats, 'totalAppointments'));
                        $totalTravelTime = array_sum(array_column($doctorStats, 'totalTravelTime'));
                        $avgTimePerAppointment = $totalAppointments > 0 ? round($totalTravelTime / $totalAppointments, 1) : 0;
                    @endphp
                    
                    <!-- Total Appointments with Travel Data -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-indigo-100 mr-4">
                                    <i class="fas fa-calendar-check text-indigo-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 uppercase">Total Appointments</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $totalAppointments }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Total Travel Time -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-green-100 mr-4">
                                    <i class="fas fa-stopwatch text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 uppercase">Total Travel Time</p>
                                    <p class="text-2xl font-bold text-gray-800">
                                        {{ floor($totalTravelTime / 60) }}h {{ $totalTravelTime % 60 }}m
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Average Time Per Appointment -->
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <div class="flex items-center">
                                <div class="p-3 rounded-full bg-blue-100 mr-4">
                                    <i class="fas fa-hourglass-half text-blue-600 text-xl"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600 uppercase">Avg Travel Time</p>
                                    <p class="text-2xl font-bold text-gray-800">{{ $avgTimePerAppointment }} min</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Doctor Cards Section -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4">Doctor Travel Statistics</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($doctorStats as $stats)
                                <div class="bg-white rounded-lg shadow border border-gray-200 overflow-hidden hover:shadow-md transition-shadow duration-300">
                                    <div class="flex items-center p-4 border-b border-gray-100 bg-gray-50">
                                        @if($stats['doctor']->user->img)
                                            <img src="{{ asset('storage/profile_pictures/' . $stats['doctor']->user->img) }}" 
                                                alt="Profile" class="w-14 h-14 rounded-full mr-4 object-cover border border-gray-200">
                                        @else
                                            <div class="w-14 h-14 rounded-full bg-blue-100 text-blue-800 flex items-center justify-center mr-4 border border-blue-200">
                                                <span class="text-xl font-bold">{{ substr($stats['doctor']->user->name, 0, 1) }}</span>
                                            </div>
                                        @endif
                                        <div>
                                            <h4 class="text-lg font-medium text-gray-900">{{ $stats['doctor']->user->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $stats['doctor']->speciality->name }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="p-4">
                                        <div class="grid grid-cols-2 gap-4 mb-3">
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-xs text-gray-500 uppercase">Appointments</p>
                                                <p class="text-lg font-semibold">{{ $stats['totalAppointments'] }}</p>
                                            </div>
                                            <div class="bg-gray-50 p-3 rounded">
                                                <p class="text-xs text-gray-500 uppercase">Avg Time</p>
                                                <p class="text-lg font-semibold">
                                                    @if($stats['averageTravelTime'] >= 60)
                                                        {{ floor($stats['averageTravelTime'] / 60) }}h {{ round($stats['averageTravelTime'] % 60) }}m
                                                    @else
                                                        {{ $stats['averageTravelTime'] }} min
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                        
                                        <!-- Travel Time Progress -->
                                        <div class="mb-3">
                                            <div class="flex justify-between items-center mb-1">
                                                <p class="text-xs text-gray-500">Travel Efficiency</p>
                                                <p class="text-xs font-medium text-gray-700">
                                                    {{ $stats['averageTravelTime'] < 30 ? 'Excellent' : ($stats['averageTravelTime'] < 45 ? 'Good' : 'Needs Improvement') }}
                                                </p>
                                            </div>
                                            @php
                                                $efficiencyPercentage = min(100, max(0, 100 - (($stats['averageTravelTime'] - 15) * 1.5)));
                                                $barColor = $efficiencyPercentage > 70 ? 'bg-green-500' : ($efficiencyPercentage > 40 ? 'bg-yellow-500' : 'bg-red-500');
                                            @endphp
                                            <div class="w-full bg-gray-200 rounded-full h-2">
                                                <div class="{{ $barColor }} h-2 rounded-full" style="width: {{ $efficiencyPercentage }}%"></div>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-center justify-between text-sm text-gray-600">
                                            <div>
                                                <span class="font-medium">Min:</span> 
                                                @if($stats['minTravelTime'] >= 60)
                                                    {{ floor($stats['minTravelTime'] / 60) }}h {{ $stats['minTravelTime'] % 60 }}m
                                                @else
                                                    {{ $stats['minTravelTime'] }} min
                                                @endif
                                            </div>
                                            <div>
                                                <span class="font-medium">Max:</span> 
                                                @if($stats['maxTravelTime'] >= 60)
                                                    {{ floor($stats['maxTravelTime'] / 60) }}h {{ $stats['maxTravelTime'] % 60 }}m
                                                @else
                                                    {{ $stats['maxTravelTime'] }} min
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <button type="button" 
                                                class="view-details-btn mt-4 w-full px-4 py-2 bg-blue-50 text-blue-700 rounded hover:bg-blue-100 transition-colors border border-blue-200 text-sm font-medium"
                                                data-doctor-id="{{ $stats['doctor']->id }}">
                                            View detailed records
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Detailed Records Section (Initially Hidden) -->
                @foreach($doctorStats as $stats)
                    <div id="details-{{ $stats['doctor']->id }}" class="doctor-details hidden bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                        <div class="p-6 bg-white border-b border-gray-200">
                            <div class="flex justify-between items-center mb-4">
                                <h3 class="text-xl font-bold text-gray-800 flex items-center">
                                    <i class="fas fa-user-md mr-2 text-blue-600"></i>
                                    {{ $stats['doctor']->user->name }}'s Travel Records
                                </h3>
                                <button type="button" 
                                        class="hide-details-btn px-3 py-1 bg-gray-100 text-gray-700 rounded hover:bg-gray-200 transition-colors text-sm flex items-center"
                                        data-doctor-id="{{ $stats['doctor']->id }}">
                                    <i class="fas fa-times mr-1"></i> Close
                                </button>
                            </div>
                            
                            <div class="overflow-x-auto bg-white rounded-lg border border-gray-200">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Patient
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Check-In
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Check-Out
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Travel Time
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($stats['appointments'] as $appointment)
                                            <tr class="hover:bg-gray-50">
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="flex items-center">
                                                        <div class="flex-shrink-0 h-8 w-8">
                                                            @if($appointment->patient->user->img)
                                                                <img src="{{ asset('storage/profile_pictures/' . $appointment->patient->user->img) }}" 
                                                                     alt="Profile" class="h-8 w-8 rounded-full">
                                                            @else
                                                                <div class="h-8 w-8 rounded-full bg-gray-100 flex items-center justify-center text-gray-600">
                                                                    {{ substr($appointment->patient->user->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                        </div>
                                                        <div class="ml-3">
                                                            <div class="text-sm font-medium text-gray-900">{{ $appointment->patient->user->name }}</div>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm text-blue-600">{{ \Carbon\Carbon::parse($appointment->check_in_time)->format('h:i A') }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="text-sm text-green-600">{{ \Carbon\Carbon::parse($appointment->check_out_time)->format('h:i A') }}</span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                        {{ $appointment->travel_time_minutes <= 30 ? 'bg-green-100 text-green-800' : 
                                                           ($appointment->travel_time_minutes <= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                                        @if($appointment->travel_time_minutes >= 60)
                                                            {{ floor($appointment->travel_time_minutes / 60) }}h {{ $appointment->travel_time_minutes % 60 }}m
                                                        @else
                                                            {{ $appointment->travel_time_minutes }} minutes
                                                        @endif
                                                    </span>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div class="bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 rounded-md flex items-start">
                            <i class="fas fa-info-circle mt-1 mr-3 text-yellow-600"></i>
                            <div>
                                <h4 class="font-medium mb-1">No travel data available</h4>
                                <p>Once doctors start using the check-in and check-out feature for appointments, the travel analytics will appear here. This data helps optimize scheduling and improve overall service efficiency.</p>
                                <a href="#" class="mt-2 inline-block text-sm text-blue-600 hover:underline">Learn more about travel tracking</a>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Show details buttons
            document.querySelectorAll('.view-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const doctorId = this.getAttribute('data-doctor-id');
                    document.querySelectorAll('.doctor-details').forEach(details => {
                        details.classList.add('hidden');
                    });
                    document.getElementById(`details-${doctorId}`).classList.remove('hidden');
                    
                    // Scroll to the details section
                    document.getElementById(`details-${doctorId}`).scrollIntoView({behavior: 'smooth'});
                });
            });
            
            // Hide details buttons
            document.querySelectorAll('.hide-details-btn').forEach(button => {
                button.addEventListener('click', function() {
                    const doctorId = this.getAttribute('data-doctor-id');
                    document.getElementById(`details-${doctorId}`).classList.add('hidden');
                });
            });
        });
    </script>
</x-admin-layout> 