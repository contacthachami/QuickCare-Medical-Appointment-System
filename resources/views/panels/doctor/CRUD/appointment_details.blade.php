<head>
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">

        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


<x-doctor-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Appointment details') }}
        </h2>
    </x-slot>

    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    <div class="p-6 mb-2 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <nav class="flex" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2 rtl:space-x-reverse">
                <li class="inline-flex items-center">
                    <a href="{{ route('doctor.appointments') }}"
                        class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-blue-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 me-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                            fill="currentColor" viewBox="0 0 20 20">
                            <path
                                d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z" />
                        </svg>
                        My Appointments
                    </a>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="rtl:rotate-180 w-3 h-3 text-gray-400 mx-1" aria-hidden="true"
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 9 4-4-4-4" />
                        </svg>
                        <span class="ms-1 text-sm font-medium text-gray-500 md:ms-2 dark:text-gray-400">
                            Appointment details</span>
                    </div>
                </li>
            </ol>
        </nav>

    </div>

    <div class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Appointment Details') }}
        </h2>
        
        <!-- Appointment Info -->
        <div class="mt-4 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                <p class="text-sm text-gray-500 dark:text-gray-400">Appointment Date</p>
                <p class="font-medium text-gray-800 dark:text-gray-300">
                    {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('M d, Y') }}
                </p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                <p class="text-sm text-gray-500 dark:text-gray-400">Scheduled Time</p>
                <p class="font-medium text-gray-800 dark:text-gray-300">
                    {{ $appointment->schedule->start ?? 'Time not specified' }}
                </p>
            </div>
            <div class="p-3 bg-blue-50 dark:bg-blue-900/20 rounded-md">
                <p class="text-sm text-gray-500 dark:text-gray-400">Appointment Reason</p>
                <p class="font-medium text-gray-800 dark:text-gray-300">{{ $appointment->reason }}</p>
            </div>
        </div>
        
        <p class="mt-4 text-gray-700 dark:text-gray-400">Status:
            @if ($appointment->status == 'Pending')
                <span
                    class="bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300 text-xs font-medium px-2.5 py-0.5 rounded">{{ $appointment->status }}</span>
            @elseif ($appointment->status == 'Expired')
                <span
                    class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium px-2.5 py-0.5 rounded">{{ $appointment->status }}</span>
            @elseif ($appointment->status == 'Cancelled')
                <span
                    class="bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300 text-xs font-medium px-2.5 py-0.5 rounded">{{ $appointment->status }}</span>
            @else
                <span
                    class="bg-green-100 text-green-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-green-900 dark:text-green-300">{{ $appointment->status }}</span>
            @endif
        </p>
        
        <!-- Travel Tracking System -->
        <div class="mt-6 border border-gray-200 rounded-lg p-5 bg-gray-50">
            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center">
                <i class="fas fa-route mr-2 text-blue-600"></i> Travel Tracking
            </h3>
            
            <!-- Travel Timeline -->
            <div class="relative">
                <!-- Timeline Track -->
                <div class="absolute h-full w-0.5 bg-gray-200 left-6 top-0"></div>
                
                <!-- Check-in Node -->
                <div class="relative flex items-center mb-6">
                    <div class="z-10 flex items-center justify-center w-12 h-12 rounded-full border-2 {{ $appointment->check_in_time ? 'bg-blue-100 border-blue-500' : 'bg-white border-gray-300' }}">
                        <i class="fas fa-sign-in-alt {{ $appointment->check_in_time ? 'text-blue-600' : 'text-gray-400' }}"></i>
                    </div>
                    
                    <div class="ml-4 flex-grow">
                        <div class="flex justify-between items-center">
                            <h4 class="text-md font-medium text-gray-700">Check-In</h4>
                            @if($appointment->check_in_time)
                                <span class="text-sm text-blue-600 font-medium">
                                    {{ \Carbon\Carbon::parse($appointment->check_in_time)->format('h:i A, d M Y') }}
                                </span>
                            @endif
                        </div>
                        
                        @if(!$appointment->check_in_time)
                            <p class="text-sm text-gray-500 mb-2">Record when you start traveling to this appointment</p>
                            <form action="{{ route('doctor.appointment.check-in', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 transition duration-200 flex items-center text-sm">
                                    <i class="fas fa-clock mr-2"></i> Record Check-In Time
                                </button>
                            </form>
                        @else
                            <p class="text-sm text-green-600">Travel started</p>
                        @endif
                    </div>
                </div>
                
                <!-- Check-out Node -->
                <div class="relative flex items-center {{ $appointment->check_out_time ? 'mb-6' : '' }}">
                    <div class="z-10 flex items-center justify-center w-12 h-12 rounded-full border-2 {{ $appointment->check_out_time ? 'bg-green-100 border-green-500' : ($appointment->check_in_time ? 'bg-white border-gray-300' : 'bg-white border-gray-200') }}">
                        <i class="fas fa-sign-out-alt {{ $appointment->check_out_time ? 'text-green-600' : ($appointment->check_in_time ? 'text-gray-400' : 'text-gray-300') }}"></i>
                    </div>
                    
                    <div class="ml-4 flex-grow">
                        <div class="flex justify-between items-center">
                            <h4 class="text-md font-medium {{ $appointment->check_in_time ? 'text-gray-700' : 'text-gray-400' }}">Check-Out</h4>
                            @if($appointment->check_out_time)
                                <span class="text-sm text-green-600 font-medium">
                                    {{ \Carbon\Carbon::parse($appointment->check_out_time)->format('h:i A, d M Y') }}
                                </span>
                            @endif
                        </div>
                        
                        @if(!$appointment->check_out_time && $appointment->check_in_time)
                            <p class="text-sm text-gray-500 mb-2">Record when you complete your travel to this appointment</p>
                            <form action="{{ route('doctor.appointment.check-out', $appointment->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700 transition duration-200 flex items-center text-sm">
                                    <i class="fas fa-flag-checkered mr-2"></i> Record Check-Out Time
                                </button>
                            </form>
                        @elseif($appointment->check_out_time)
                            <p class="text-sm text-green-600">Travel completed</p>
                        @else
                            <p class="text-sm text-gray-400">Please check in first before checking out</p>
                        @endif
                    </div>
                </div>
                
                <!-- Travel Summary -->
                @if($appointment->check_in_time && $appointment->check_out_time)
                    <div class="mt-6 bg-white p-4 rounded-lg border border-green-200 shadow-sm">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="p-2 rounded-full bg-green-100 mr-3">
                                    <i class="fas fa-clock text-green-600"></i>
                                </div>
                                <div>
                                    <h5 class="font-medium text-gray-800">Travel Summary</h5>
                                    <p class="text-sm text-gray-600">
                                        From {{ \Carbon\Carbon::parse($appointment->check_in_time)->format('h:i A') }} 
                                        to {{ \Carbon\Carbon::parse($appointment->check_out_time)->format('h:i A') }}
                                    </p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-bold text-green-600">
                                    @if($appointment->travel_time_minutes >= 60)
                                        {{ floor($appointment->travel_time_minutes / 60) }}h {{ $appointment->travel_time_minutes % 60 }}m
                                    @else
                                        {{ $appointment->travel_time_minutes }} min
                                    @endif
                                </span>
                                <p class="text-sm text-gray-500">Total travel time</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
            
            <!-- Travel Info Note -->
            <div class="mt-4 text-xs text-gray-500 italic">
                <p>Note: Travel time data is collected for administrative purposes to optimize appointment scheduling.</p>
            </div>
        </div>
    </div>


    <div class=" mt-7 overflow-hidden bg-white p-6 rounded-md shadow-md dark:bg-dark-eval-1">

        <h2 class="text-xl font-semibold leading-tight">
            {{ __('Patient Details : ') }}
        </h2>

        <div class="p-4 flex flex-col md:flex-row justify-between">
            <div
                class="md:mr-3 md:mb-0 mb-4 md:w-auto w-full md:flex-none flex items-center justify-center md:flex-row">
                <span class="mr-4">
                    @if ($appointment->patient->user->img)
                        <img src="{{ asset('storage/profile_pictures/' . $appointment->patient->user->img) }}"
                            alt="Profile Picture" class="w-32 h-32 md:rounded-2xl sm:rounded-2xl ">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ $appointment->patient->user->name }}"
                            alt="test" class="w-32 h-32 rounded-2xl  md:rounded-2xl sm:rounded-2xl">
                    @endif
                </span>

                <div class="md:mt-0 mt-4 md:ml-4 flex flex-col justify-between">
                    <div>
                        <h3 class="text-lg font-medium leading-tight text-gray-900 dark:text-white">
                            {{ $appointment->patient->user->name }}
                        </h3>
                    </div>
                </div>
            </div>
            <div class="ml-1 mt-2 md:mt-4 md:text-center md:flex md:flex-col md:items-center">
                <div class="flex flex-col md:items-start items-center ">
                    <div class="flex flex-col items-start " style="width: 400px">
                        <span
                            class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-400 uppercase space-x-2 inline-block md:mr-9 mr-9">
                            @if ($appointment->patient->user->gender == 'male')
                                <span><i class="fa-solid fa-mars" style="color: #74C0FC;"></i> Gender :
                                    {{ $appointment->patient->user->gender }} </span>
                            @else
                                <span><i class="fa-solid fa-venus" style="color: #74C0FC;"></i> Gender :
                                    {{ $appointment->patient->user->gender }} </span>
                            @endif
                        </span>
                        <span
                            class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-400 uppercase space-x-2 inline-block">
                            <span><i class="fa-solid fa-phone" style="color: #74C0FC;"></i> Phone :
                                {{ $appointment->patient->user->phone }}</span>
                        </span>
                        <span class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-400 uppercase">
                            <i class="fa-solid fa-at" style="color: #74C0FC;"></i> Mail : <a
                                href="mailto:{{ $appointment->patient->user->email }}">{{ $appointment->patient->user->email }}</a></span>
                        <span
                            class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-400 uppercase ">
                            <i class="fa-solid fa-location-dot" style="color: #74C0FC;"></i> Address :
                            <span class=" break-words ">{{ $appointment->patient->user->address->ville }}
                                ,
                                {{ $appointment->patient->user->address->rue }}</span>
                        </span>

                        <span class="text-sm font-medium tracking-wide text-gray-600 dark:text-gray-400 uppercase">
                            <i class="fa-solid fa-cake-candles" style="color: #74C0FC;"></i> Birthdate :
                            {{ $appointment->patient->birth_date }}</span>
                    </div>
                </div>
            </div>
        </div>
        </div>


    </div>



</x-doctor-layout>
