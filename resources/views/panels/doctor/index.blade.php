<head>
    <title>Doctor's Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css?v=1628755089081">
    <style>
        .animate-fade-in {
            animation: fadeIn 0.8s ease-in-out forwards;
        }
        
        .animate-slide-up {
            animation: slideUp 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .animate-slide-right {
            animation: slideRight 0.6s ease-out forwards;
            opacity: 0;
        }
        
        .animate-pulse-slow {
            animation: pulseSlow 3s infinite;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes slideRight {
            from { transform: translateX(-20px); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes pulseSlow {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .medical-border {
            border-left: 4px solid #3b82f6;
            border-radius: 0.375rem;
        }
        
        .status-pill {
            transition: all 0.3s ease;
        }
        
        .status-pill:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .card-hover {
            transition: all 0.3s ease;
        }
        
        .card-hover:hover {
            transform: translateY(-4px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .doctor-avatar {
            position: relative;
            overflow: hidden;
            border-radius: 50%;
        }
        
        .doctor-avatar::after {
            content: '';
            position: absolute;
            top: -10px;
            left: -10px;
            right: -10px;
            bottom: -10px;
            background: linear-gradient(45deg, rgba(59, 130, 246, 0.3) 0%, rgba(147, 51, 234, 0.3) 100%);
            border-radius: 50%;
            z-index: -1;
            animation: rotateBg 10s linear infinite;
        }

        .doctor-initials {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%);
            color: white;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: -1px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        .doctor-initials::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 45%);
            z-index: 1;
        }
        
        @keyframes rotateBg {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>

<x-doctor-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Doctor\'s Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <!-- Welcome & Stats Section -->
    <div class="p-6 bg-white rounded-lg shadow-md overflow-hidden flex flex-col md:flex-row justify-around dark:bg-dark-eval-1 animate-fade-in">
        <div class="md:flex items-center md:w-1/2">
            <div class="p-8 flex items-center">
                <div class="mr-12 doctor-avatar">
                    @php
                        $user = Auth::user();
                        $name = $user->name;
                        $nameParts = explode(' ', $name);
                        $firstName = $nameParts[0] ?? '';
                        $lastName = end($nameParts);
                        $initials = strtoupper(substr($firstName, 0, 1) . (count($nameParts) > 1 ? substr($lastName, 0, 1) : ''));
                    @endphp
                    
                    @if(View::exists('components.profile-image'))
                        <x-profile-image :user="$user" size="2xl" rounded="full" containerClass="shadow-md border-4 border-white" />
                    @else
                        @if ($user->img)
                            <div class="w-24 h-24 md:w-36 md:h-36 lg:w-48 lg:h-48 rounded-full overflow-hidden shadow-md border-4 border-white">
                                <img src="{{ asset('storage/profile_pictures/' . $user->img) }}" alt="Profile Picture"
                                   class="w-full h-full object-cover object-center">
                            </div>
                        @else
                            <div class="w-24 h-24 md:w-36 md:h-36 lg:w-48 lg:h-48 rounded-full doctor-initials">
                                <span class="text-4xl md:text-6xl">{{ $initials }}</span>
                            </div>
                        @endif
                    @endif
                </div>
                <div class="animate-slide-right" style="animation-delay: 0.2s;">
                    <div class="uppercase tracking-wide text-sm text-blue-600 font-semibold">
                        <span class="inline-block bg-blue-100 text-blue-800 rounded-full px-3 py-1 text-xs font-semibold mr-2 animate-pulse-slow">
                            <i class="fas fa-user-md mr-1"></i> Medical Professional
                        </span>
                        <span class="mt-2 block">Welcome to the Doctor Panel</span>
                    </div>
                    <div>
                        <p class="mt-2 text-gray-700 text-lg font-medium dark:text-gray-300">{!! __('Dr. <strong class="text-blue-600 dark:text-blue-400">:name</strong>', ['name' => auth()->user()->name]) !!}</p>
                        <!-- Add specialty if available -->
                        @if(isset(auth()->user()->doctor->speciality))
                            <p class="text-gray-500 dark:text-gray-400 mt-1">
                                <i class="fas fa-stethoscope mr-1 text-blue-500"></i> 
                                {{ auth()->user()->doctor->speciality->name ?? 'General Medicine' }}
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-dark-eval-1 rounded-md md:w-1/2 animate-slide-up" style="animation-delay: 0.3s;">
            <div class="flex items-center mb-3">
                <div class="w-1 h-6 bg-blue-500 rounded-r-full mr-2"></div>
                <strong class="text-lg text-gray-800 dark:text-gray-200">Quick Stats</strong>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow p-4 flex items-center justify-between card-hover">
                    <div>
                        <p class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ count($schedule) }}</p>
                        <p class="text-gray-600 dark:text-gray-300">Schedules</p>
                    </div>
                    <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                        <i class="fas fa-calendar text-2xl text-blue-600 dark:text-blue-400"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow p-4 flex items-center justify-between card-hover">
                    <div>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ count($patients) }}</p>
                        <p class="text-gray-600 dark:text-gray-300">My patients</p>
                    </div>
                    <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                        <i class="fas fa-solid fa-bed-pulse text-2xl text-purple-600 dark:text-purple-400"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow p-4 flex items-center justify-between card-hover">
                    <div>
                        <p class="text-3xl font-bold text-green-600 dark:text-green-400">{{ count($appointments) }}</p>
                        <p class="text-gray-600 dark:text-gray-300">Bookings</p>
                    </div>
                    <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                        <i class="fas fa-calendar-check text-2xl text-green-600 dark:text-green-400"></i>
                    </div>
                </div>

                <div class="bg-gradient-to-br from-amber-50 to-amber-100 dark:from-gray-800 dark:to-gray-700 rounded-lg shadow p-4 flex items-center justify-between card-hover">
                    @if ($ratings->isNotEmpty())
                    <div>
                        <p class="text-3xl font-bold text-amber-600 dark:text-amber-400">{{ number_format($ratings->avg('rating'), 1) }}</p>
                        <p class="text-gray-600 dark:text-gray-300">Rating</p>
                    </div>
                    <div class="p-3 bg-amber-100 dark:bg-amber-900 rounded-full">
                        <i class="fas fa-solid fa-star text-2xl text-amber-600 dark:text-amber-400"></i>
                    </div>
                    @else
                    <div>
                        <p class="text-3xl font-bold text-gray-600 dark:text-gray-400">-</p>
                        <p class="text-gray-600 dark:text-gray-300">No Rating</p>
                    </div>
                    <div class="p-3 bg-gray-100 dark:bg-gray-700 rounded-full">
                        <i class="fas fa-solid fa-star text-2xl text-gray-400 dark:text-gray-500"></i>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Appointment & Patient Visits Section -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Upcoming Appointments -->
        <div class="p-6 bg-white dark:bg-dark-eval-1 rounded-lg shadow-md animate-slide-up" style="animation-delay: 0.4s;">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <div class="w-1 h-6 bg-blue-500 rounded-r-full mr-2"></div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Upcoming Appointments</h3>
                </div>
                <span class="p-2 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <i class="fa-solid fa-calendar-check text-blue-600 dark:text-blue-400"></i>
                </span>
            </div>
            
            <div class="space-y-4 mt-4 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-blue-500 scrollbar-track-blue-100">
                @if (count($upcommingAppointments) == 0)
                    <div class="flex items-center justify-center h-40 bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                        <div class="text-center p-4">
                            <i class="fa-regular fa-calendar text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400">No upcoming appointments</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Your schedule is clear for now</p>
                        </div>
                    </div>
                @else
                    @foreach($upcommingAppointments as $appointment)
                        <div class="p-4 rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-200 medical-border">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center">
                                    <div class="mr-3">
                                        @if($appointment->patient->user->img)
                                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-blue-200">
                                                <img src="{{ asset('storage/profile_pictures/' . $appointment->patient->user->img) }}" 
                                                     alt="Patient" 
                                                     class="w-full h-full object-cover object-center">
                                            </div>
                                        @else
                                            @php
                                                $patientName = $appointment->patient->user->name;
                                                $patientNameParts = explode(' ', $patientName);
                                                $patientFirstName = $patientNameParts[0] ?? '';
                                                $patientLastName = end($patientNameParts);
                                                $patientInitials = strtoupper(substr($patientFirstName, 0, 1) . (count($patientNameParts) > 1 ? substr($patientLastName, 0, 1) : ''));
                                            @endphp
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden" 
                                                 style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); position: relative;">
                                                <!-- Highlight effect -->
                                                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 45%);"></div>
                                                <span class="text-white font-semibold text-sm relative z-10" style="text-transform: uppercase; letter-spacing: -0.5px;">{{ $patientInitials }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->patient->user->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Patient ID: {{ $appointment->patient->id }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300 status-pill">
                                        {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-3 grid grid-cols-2 gap-2">
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Appointment Time</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">
                                        {{ $appointment->schedule->start ?? \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}
                                    </p>
                                </div>
                                <div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">Reason</p>
                                    <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->reason }}</p>
                                </div>
                            </div>
                            
                            @if(\Carbon\Carbon::parse($appointment->appointment_date)->isToday())
                                <div class="mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300 animate-pulse-slow status-pill">
                                        <i class="fas fa-clock-rotate-left mr-1"></i> Today
                                    </span>
                                </div>
                            @endif
                    </div>
                @endforeach
                @endif
            </div>

            <div class="mt-5">
                <a href="{{route('doctor.appointments')}}" class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 transition-all duration-200 transform hover:-translate-y-0.5">
                    <i class="fas fa-calendar-alt mr-2"></i> View all appointments
                </a>
            </div>
        </div>

        <!-- Recent Patient Visits -->
        <div class="p-6 bg-white dark:bg-dark-eval-1 rounded-lg shadow-md animate-slide-up" style="animation-delay: 0.5s;">
            <div class="flex justify-between items-center mb-4">
                <div class="flex items-center">
                    <div class="w-1 h-6 bg-purple-500 rounded-r-full mr-2"></div>
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Recent Patient Visits</h3>
                </div>
                <span class="p-2 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <i class="fa-solid fa-bed-pulse text-purple-600 dark:text-purple-400"></i>
                </span>
            </div>
            
            <div class="space-y-4 mt-4 max-h-80 overflow-y-auto scrollbar-thin scrollbar-thumb-purple-500 scrollbar-track-purple-100">
                @if (count($recentVisits) == 0)
                    <div class="flex items-center justify-center h-40 bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                        <div class="text-center p-4">
                            <i class="fa-solid fa-clipboard-user text-3xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500 dark:text-gray-400">No recent patient visits</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Visit data will appear here</p>
                        </div>
                </div>
                @else
                    @foreach($recentVisits as $visit)
                        <div class="p-4 rounded-lg border border-gray-100 dark:border-gray-700 bg-white dark:bg-gray-800 shadow-sm hover:shadow-md transition-all duration-200" style="border-left: 4px solid #a855f7;">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center">
                                    <div class="mr-3">
                                        @if($visit->patient->user->img)
                                            <img src="{{ asset('storage/profile_pictures/' . $visit->patient->user->img) }}" 
                                                 alt="Patient" 
                                                 class="w-10 h-10 rounded-full border-2 border-purple-200">
                                        @else
                                            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center">
                                                <span class="text-purple-600 font-medium">{{ substr($visit->patient->user->name, 0, 2) }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-800 dark:text-gray-200">{{ $visit->patient->user->name }}</h4>
                                        <p class="text-sm text-gray-500 dark:text-gray-400">
                                            Visit ID: {{ $visit->id }}
                                        </p>
                                    </div>
                                </div>
                                <div>
                                    <span class="px-2 py-1 text-xs rounded-full bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300 status-pill">
                                        {{ \Carbon\Carbon::parse($visit->appointment_date)->format('M d') }}
                                    </span>
                                </div>
                            </div>
                            
                            <div class="mt-3">
                                <p class="text-xs text-gray-500 dark:text-gray-400">Visit Time</p>
                                <p class="font-medium text-gray-800 dark:text-gray-200">
                                    {{ $visit->schedule->start ?? \Carbon\Carbon::parse($visit->appointment_date)->format('h:i A') }}
                                </p>
                            </div>
                            
                            <div class="mt-3 pt-2 border-t border-gray-100 dark:border-gray-700">
                                <span class="px-2 py-1 text-xs rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 status-pill">
                                    <i class="fas fa-check-circle mr-1 text-green-500"></i> Completed
                                </span>
                            </div>
                    </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>


    <!-- Reviews Section -->
    <div class="p-6 bg-white dark:bg-dark-eval-1 rounded-lg shadow-md mt-6 animate-slide-up" style="animation-delay: 0.6s;">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <div class="w-1 h-6 bg-yellow-500 rounded-r-full mr-2"></div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Patient Reviews</h3>
            </div>
            <span class="p-2 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                <i class="fa-solid fa-star text-yellow-600 dark:text-yellow-400"></i>
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mt-4">
            @if (count($ratings) == 0)
                <div class="col-span-4 flex items-center justify-center h-40 bg-gray-50 dark:bg-gray-800 rounded-lg border border-dashed border-gray-300 dark:border-gray-600">
                    <div class="text-center p-4">
                        <i class="fa-regular fa-star text-3xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500 dark:text-gray-400">No reviews yet</p>
                        <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">Your patient reviews will appear here</p>
                    </div>
                </div>
            @else
                @php $reviewCount = 0; @endphp
                @foreach($ratings as $index => $review)
                    @if($reviewCount < 4) <!-- Display only 4 reviews -->
                        <div class="bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm hover:shadow-md transition-all duration-200 border border-gray-100 dark:border-gray-700 card-hover">
                            <div class="flex items-start justify-between mb-3">
                                <div class="flex items-center">
                                    <div class="mr-3">
                                        @if($review->patient->user->img)
                                            <img src="{{ asset('storage/profile_pictures/' . $review->patient->user->img) }}" 
                                                 alt="Patient" 
                                                 class="w-8 h-8 rounded-full border-2 border-yellow-200">
                                        @else
                                            <div class="w-8 h-8 rounded-full bg-yellow-100 flex items-center justify-center">
                                                <span class="text-yellow-600 font-medium">{{ substr($review->patient->user->name, 0, 2) }}</span>
                                            </div>
                            @endif
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-sm text-gray-800 dark:text-gray-200">{{ $review->patient->user->name }}</h4>
                                    </div>
                                </div>
                                <div class="flex items-center">
                                    <span class="text-yellow-500">{{ $review->rating }}</span>
                                    <i class="fas fa-star text-yellow-500 text-sm ml-1"></i>
                                </div>
                            </div>
                            
                            <div class="bg-gray-50 dark:bg-gray-750 p-3 rounded-md">
                                <p class="text-gray-700 dark:text-gray-300 text-sm">
                                    "{{ \Illuminate\Support\Str::limit($review->comment, 80) }}"
                                </p>
                            </div>
                            
                            <div class="mt-3 pt-2 text-right">
                                <span class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ \Carbon\Carbon::parse($review->created_at)->format('M d, Y') }}
                                </span>
                            </div>
                        </div>
                        @php $reviewCount++; @endphp
                    @endif
                @endforeach
            @endif
        </div>

        <div class="mt-5">
            <a href="{{route('doctor.myreviews')}}" class="inline-flex items-center justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-yellow-600 hover:bg-yellow-700 transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-comment-dots mr-2"></i> View all reviews
        </a>
        </div>
    </div>

    <!-- Travel Tracking Widget -->
    <div class="p-6 bg-white rounded-lg shadow-md overflow-hidden dark:bg-dark-eval-1 mt-6 animate-slide-up" style="animation-delay: 0.7s;">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center">
                <div class="w-1 h-6 bg-indigo-500 rounded-r-full mr-2"></div>
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">Travel Tracking</h3>
                <span class="ml-2 text-sm text-gray-500 dark:text-gray-400">Record your travel time to patient appointments</span>
            </div>
            <a href="{{ route('doctor.appointments') }}?travel=active" class="text-sm text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center transition-all duration-200 transform hover:-translate-y-0.5">
                <span>View all travel records</span>
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="bg-indigo-50 dark:bg-gray-800 rounded-lg p-5">
            @php 
                $todayAppointments = $upcommingAppointments->filter(function($appointment) {
                    return \Carbon\Carbon::parse($appointment->appointment_date)->isToday();
                });
                $hasActiveAppointments = count($todayAppointments) > 0;
            @endphp
            
            @if($hasActiveAppointments)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                @foreach($todayAppointments->take(3) as $appointment)
                        <div class="bg-white dark:bg-gray-750 border border-indigo-100 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm card-hover">
                            <div class="p-3 bg-indigo-100 dark:bg-indigo-900 border-b border-indigo-200 dark:border-gray-700 flex justify-between items-center">
                                <span class="font-medium text-indigo-700 dark:text-indigo-300">
                                    {{ $appointment->schedule->start ?? \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}
                                </span>
                                <span class="text-xs px-2 py-1 bg-white dark:bg-indigo-800 text-indigo-800 dark:text-indigo-300 rounded-full animate-pulse-slow">
                                    <i class="fas fa-calendar-day mr-1"></i> Today
                                </span>
                        </div>
                        <div class="p-4">
                                <div class="mb-2 flex items-center">
                                    <div class="mr-3">
                                        @if($appointment->patient->user->img)
                                            <div class="w-10 h-10 rounded-full overflow-hidden border-2 border-blue-200">
                                                <img src="{{ asset('storage/profile_pictures/' . $appointment->patient->user->img) }}" 
                                                     alt="Patient" 
                                                     class="w-full h-full object-cover object-center">
                                            </div>
                                        @else
                                            @php
                                                $patientName = $appointment->patient->user->name;
                                                $patientNameParts = explode(' ', $patientName);
                                                $patientFirstName = $patientNameParts[0] ?? '';
                                                $patientLastName = end($patientNameParts);
                                                $patientInitials = strtoupper(substr($patientFirstName, 0, 1) . (count($patientNameParts) > 1 ? substr($patientLastName, 0, 1) : ''));
                                            @endphp
                                            <div class="w-10 h-10 rounded-full flex items-center justify-center overflow-hidden" 
                                                 style="background: linear-gradient(135deg, #3b82f6 0%, #60a5fa 100%); position: relative;">
                                                <!-- Highlight effect -->
                                                <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background: radial-gradient(circle at 30% 30%, rgba(255, 255, 255, 0.2) 0%, transparent 45%);"></div>
                                                <span class="text-white font-semibold text-sm relative z-10" style="text-transform: uppercase; letter-spacing: -0.5px;">{{ $patientInitials }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->patient->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $appointment->reason }}</p>
                                    </div>
                            </div>
                            
                                <div class="mb-3 mt-4">
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">Status:</p>
                                @if(!$appointment->check_in_time)
                                        <div class="mt-1 text-xs inline-flex items-center px-3 py-1.5 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300 status-pill">
                                        <i class="fas fa-clock mr-1"></i> Travel not started
                                    </div>
                                @elseif($appointment->check_in_time && !$appointment->check_out_time)
                                        <div class="mt-1 text-xs inline-flex items-center px-3 py-1.5 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300 status-pill">
                                        <i class="fas fa-route mr-1"></i> Currently traveling
                                    </div>
                                @else
                                        <div class="mt-1 text-xs inline-flex items-center px-3 py-1.5 rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300 status-pill">
                                        <i class="fas fa-check-circle mr-1"></i> Travel completed ({{ $appointment->travel_time_minutes }} min)
                                    </div>
                                @endif
                            </div>
                            
                                <div class="mt-4 pt-3 border-t border-gray-100 dark:border-gray-700 flex justify-end">
                                    <a href="{{ route('doctor.travel-tracking') }}" class="text-xs text-indigo-600 hover:text-indigo-800 dark:text-indigo-400 dark:hover:text-indigo-300 flex items-center">
                                        <i class="fas fa-location-dot mr-1"></i>
                                        <span>Manage travel</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    </div>
            @else
                <div class="flex items-center justify-center h-48 bg-white dark:bg-gray-800 rounded-lg border border-dashed border-indigo-300 dark:border-gray-600">
                    <div class="text-center p-4">
                        <div class="p-3 bg-indigo-100 dark:bg-indigo-900 inline-block rounded-full mb-2">
                            <i class="fas fa-route text-2xl text-indigo-600 dark:text-indigo-400"></i>
                        </div>
                        <p class="text-gray-700 dark:text-gray-300 font-medium">No appointments for today</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">You don't have any appointments scheduled for today.</p>
                        <a href="{{ route('doctor.travel-tracking') }}" class="inline-flex items-center justify-center mt-4 py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 transition-all duration-200 transform hover:-translate-y-0.5">
                            <i class="fas fa-car mr-2"></i> View all travel records
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-doctor-layout>
