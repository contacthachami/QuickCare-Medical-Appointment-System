<head>
    <title>Doctor's Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css?v=1628755089081">
</head>

<x-doctor-layout>

    <x-slot name="header">
        <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-semibold leading-tight">
                {{ __('Doctor\'s Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="p-6 bg-white rounded-md shadow-md overflow-hidden flex flex-col md:flex-row justify-around dark:bg-dark-eval-1">
        <div class="md:flex justify-center items-center md:w-1/2">
            <div class="p-8 flex items-center">
                <div class="mr-12">
                    @php
                        $user = Auth::user()->img;
                    @endphp
                    @if ($user)
                        <img src="{{ asset('storage/profile_pictures/' . $user) }}" alt="Profile Picture"
                            class="w-24 h-24 md:w-36 md:h-36 lg:w-48 lg:h-48 rounded-3xl">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name }}" alt="test"
                            class="w-24 h-24 md:w-36 md:h-36 lg:w-48 lg:h-48 rounded-3xl">
                    @endif
                </div>
                <div>
                    <div class="uppercase tracking-wide text-sm text-blue-500 font-semibold">Welcome to the Doctor Panel
                    </div>
                    <div>
                        <p class="mt-2 text-gray-500">{!! __('Dr. <strong>:name</strong>', ['name' => auth()->user()->name]) !!}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="p-6 bg-white dark:bg-dark-eval-1 rounded-md md:w-1/2">
            <strong class="text-lg text-gray-800 dark:text-gray-200">Counts:</strong>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div class="bg-white dark:bg-gray-800 rounded-md shadow-md p-4 flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ count($schedule) }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Schedules</p>
                    </div>
                    <i class="fas fa-calendar text-3xl text-blue-500 dark:text-blue-300"></i>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-md shadow-md p-4 flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ count($patients) }}</p>
                        <p class="text-gray-500 dark:text-gray-400">My patients</p>
                    </div>
                    <i class="fas fa-solid fa-bed-pulse text-3xl text-blue-500 dark:text-blue-300"></i>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-md shadow-md p-4 flex items-center justify-between">
                    <div>
                        <p class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ count($appointments) }}</p>
                        <p class="text-gray-500 dark:text-gray-400">Bookings</p>
                    </div>
                    <i class="fas fa-calendar-check text-3xl text-purple-500 dark:text-purple-300"></i>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-md shadow-md p-4 flex items-center justify-between">
                    @if ($ratings->isNotEmpty())

                    <div>
                        <p class="text-2xl font-semibold text-gray-800 dark:text-gray-200">{{ $ratings->avg('rating') }}</p>
                        <p class="text-gray-500 dark:text-gray-400">My Rating</p>
                    </div>
                    <i class="fas fa-solid fa-star text-3xl text-blue-500 dark:text-blue-300"></i>

                    @else
                    <p>No Rating</p>

                    @endif

                </div>

            </div>

        </div>

    </div>

    <div class=" m-4  rounded-md  overflow-hidden flex flex-col md:flex-row  dark:bg-dark-eval-1 ">
        <div class=" p-6 bg-white m-3 dark:bg-dark-eval-1 rounded-md md:w-1/2 w-full  ">
            <strong class="text-lg text-gray-800 dark:text-gray-200"> Upcoming Appointments: </strong> <i class="fa-solid fa-calendar-check"></i>
            <div class="m-4">
                @if (count($upcommingAppointments) == 0)

                    <div class="border-b border-gray-200 dark:border-gray-600 py-2">
                        <p class="text-gray-800 dark:text-gray-200">No Upcomming Appointments</p>
                    </div>

                @else

                @foreach($upcommingAppointments  as $appointment)
                    <div class="border-b border-gray-200 dark:border-gray-600 py-2">
                        <p class="text-gray-800 dark:text-gray-200"><strong>Patient  name : </strong> </p>
                        <p class="text-gray-800 dark:text-gray-200">{{ $appointment->patient->user->name }}</p>
                        <p class="text-gray-800 dark:text-gray-200"> <strong> Appointment date : </strong> </p>
                        <p class="text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }} 
                            at {{ $appointment->schedule->start ?? 'unknown time' }}
                        </p>
                        <p class="text-gray-800 dark:text-gray-200"> <strong> Reason : </strong> </p>
                        <p class="text-gray-500 dark:text-gray-400">{{ $appointment->reason }}</p>
                    </div>
                @endforeach

                @endif

            </div>

            <a href="{{route('doctor.appointments')}}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 ">
                View all appointments</a>


        </div>

        <div class="p-6 bg-white dark:bg-dark-eval-1 rounded-md md:w-1/2 m-3 w-full ">
            <strong class="text-lg text-gray-800 dark:text-gray-200">Recent Patient Visits:</strong> <i class="fa-solid fa-bed-pulse"></i>
            <div class="m-4">

                @if (count($recentVisits) == 0)

                <div class="border-b border-gray-200 dark:border-gray-600 py-2">
                    <p class="text-gray-800 dark:text-gray-200">No recent patient visits</p>
                </div>

                @else

                @foreach($recentVisits as $visit)
                    <div class="border-b border-gray-200 dark:border-gray-600 py-2">
                        <p class="text-gray-800 dark:text-gray-200"><strong>Patient  name : </strong> </p>
                        <p class="text-gray-800 dark:text-gray-200">{{ $visit->patient->user->name }}</p>
                        <p class="text-gray-800 dark:text-gray-200"> <strong> Visit date : </strong> </p>
                        <p class="text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($visit->appointment_date)->format('M d, Y') }} 
                            at {{ $visit->schedule->start ?? 'unknown time' }}
                        </p>
                    </div>
                @endforeach

                @endif
            </div>


        </div>

    </div>


    <div class="p-6 bg-white dark:bg-dark-eval-1 rounded-md m-3 w-full">
        <strong class="text-lg text-gray-800 dark:text-gray-200">My reviews:</strong>
        <i class="fa-solid fa-star"></i>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            @if (count($ratings) == 0)
                <div class="border-b border-gray-200 dark:border-gray-600 py-2">
                    <p class="text-gray-800 dark:text-gray-200">No reviews</p>
                </div>
            @else
                @php $reviewCount = 0; @endphp
                @foreach($ratings as $index => $review)
                    @if($reviewCount < 4) <!-- Display only 4 reviews -->
                        <div class="border-b border-gray-200 dark:border-gray-600 py-2">
                            <p class="text-gray-800 dark:text-gray-200"><strong>Patient name:</strong> {{ $review->patient->user->name }}</p>
                            <p class="text-gray-800 dark:text-gray-200"><strong>Review:</strong> {{ $review->comment }}</p>
                            @if(($index + 1) % 2 == 0)
                                <style>
                                    .review-item:nth-child({{ $index + 1 }}) p strong::after {
                                        content: ":";
                                        float: right;
                                        margin-left: 5px;
                                    }
                                </style>
                            @endif
                        </div>
                        @php $reviewCount++; @endphp
                    @endif
                @endforeach
            @endif
        </div>

        <a href="{{route('doctor.myreviews')}}" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700">
            View more
        </a>
    </div>

    <!-- Travel Tracking Widget -->
    <div class="p-6 bg-white rounded-md shadow-md overflow-hidden dark:bg-dark-eval-1 m-3">
        <div class="flex justify-between items-center mb-4">
            <div>
                <strong class="text-lg text-gray-800 dark:text-gray-200 flex items-center">
                    <i class="fas fa-route mr-2 text-blue-500"></i> Travel Tracking
                </strong>
                <p class="text-sm text-gray-500">Record your travel time to patient appointments</p>
            </div>
            <a href="{{ route('doctor.appointments') }}?travel=active" class="text-sm text-blue-600 hover:text-blue-800 flex items-center">
                <span>View all travel records</span>
                <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @php 
                $todayAppointments = $upcommingAppointments->filter(function($appointment) {
                    return \Carbon\Carbon::parse($appointment->appointment_date)->isToday();
                });
                $hasActiveAppointments = count($todayAppointments) > 0;
            @endphp
            
            @if($hasActiveAppointments)
                @foreach($todayAppointments->take(3) as $appointment)
                    <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg overflow-hidden shadow-sm">
                        <div class="p-3 bg-blue-50 dark:bg-blue-900 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
                            <span class="font-medium text-blue-700 dark:text-blue-300">{{ $appointment->schedule->start ?? \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}</span>
                            <span class="text-xs px-2 py-1 bg-blue-100 dark:bg-blue-800 text-blue-800 dark:text-blue-300 rounded-full">Today</span>
                        </div>
                        <div class="p-4">
                            <div class="mb-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Patient:</p>
                                <p class="font-medium text-gray-800 dark:text-gray-200">{{ $appointment->patient->user->name }}</p>
                            </div>
                            
                            <div class="mb-3">
                                <p class="text-sm text-gray-600 dark:text-gray-400">Status:</p>
                                @if(!$appointment->check_in_time)
                                    <div class="mt-1 text-xs inline-flex items-center px-2 py-1 rounded-full bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-300">
                                        <i class="fas fa-clock mr-1"></i> Travel not started
                                    </div>
                                @elseif($appointment->check_in_time && !$appointment->check_out_time)
                                    <div class="mt-1 text-xs inline-flex items-center px-2 py-1 rounded-full bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-300">
                                        <i class="fas fa-route mr-1"></i> Currently traveling
                                    </div>
                                @else
                                    <div class="mt-1 text-xs inline-flex items-center px-2 py-1 rounded-full bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-300">
                                        <i class="fas fa-check-circle mr-1"></i> Travel completed ({{ $appointment->travel_time_minutes }} min)
                                    </div>
                                @endif
                            </div>
                            
                            @if(!$appointment->check_in_time)
                                <form action="{{ route('doctor.appointment.check-in', $appointment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white py-2 px-3 rounded text-sm flex items-center justify-center">
                                        <i class="fas fa-play-circle mr-1"></i> Start Travel
                                    </button>
                                </form>
                            @elseif($appointment->check_in_time && !$appointment->check_out_time)
                                <form action="{{ route('doctor.appointment.check-out', $appointment->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-2 px-3 rounded text-sm flex items-center justify-center">
                                        <i class="fas fa-flag-checkered mr-1"></i> Complete Travel
                                    </button>
                                </form>
                            @else
                                <a href="{{ route('doctor.CRUD.appointment.details', [$appointment->id]) }}" class="w-full block text-center bg-gray-100 hover:bg-gray-200 text-gray-800 py-2 px-3 rounded text-sm">
                                    <i class="fas fa-eye mr-1"></i> View Details
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="col-span-full bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg p-6 text-center">
                    <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-200 dark:bg-gray-700 mb-4">
                        <i class="fas fa-calendar-times text-gray-400 dark:text-gray-500 text-xl"></i>
                    </div>
                    <h3 class="text-md font-medium text-gray-700 dark:text-gray-300 mb-1">No appointments for today</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">You don't have any appointments scheduled for today.</p>
                    <a href="{{ route('doctor.appointments') }}?travel=active" class="mt-3 inline-block text-blue-600 hover:text-blue-800 text-sm">
                        View all travel records
                    </a>
                </div>
            @endif
        </div>
    </div>

</x-doctor-layout>
