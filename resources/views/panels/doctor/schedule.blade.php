<head>
    <title>Doctor's Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        .schedule-card {
            transition: all 0.3s ease;
            border-radius: 10px;
        }
        .schedule-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }
        .time-slot {
            transition: all 0.2s ease;
            cursor: pointer;
            border-radius: 6px;
        }
        .time-slot:hover {
            background-color: #f0f9ff;
            border-color: #93c5fd;
        }
        .time-slot.selected {
            background-color: #3b82f6;
            color: white;
            border-color: #2563eb;
        }
        .day-card {
            border-radius: 10px;
            border-top: 5px solid #3b82f6;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        .tab-active {
            border-bottom: 2px solid #3b82f6;
            color: #3b82f6;
        }
        .delete-btn {
            opacity: 0;
            transition: opacity 0.2s ease;
        }
        .schedule-time-card:hover .delete-btn {
            opacity: 1;
        }
        input[type="date"] {
            border-radius: 6px;
            padding: 8px 12px;
        }
        /* Calendar customization */
        .fc-event {
            border-radius: 6px !important;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1) !important;
        }
        .fc-toolbar-title {
            font-size: 1.5rem !important;
            font-weight: 600 !important;
        }
        .fc-button {
            border-radius: 6px !important;
            text-transform: uppercase !important;
            font-weight: 500 !important;
            letter-spacing: 0.5px !important;
        }
    </style>
</head>

<x-doctor-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight flex items-center">
                <i class="fa-regular fa-calendar text-blue-500 mr-2"></i>{{ __('Schedule Management') }}
            </h2>
            <div class="text-right">
                <span class="text-sm text-gray-600">Current Date:</span>
                <span class="ml-1 font-medium">{{ \Carbon\Carbon::now()->format('M d, Y') }}</span>
            </div>
        </div>
    </x-slot>

    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    <!-- View Mode Tabs -->
    <div class="bg-white rounded-lg shadow-md mb-6 p-4">
        <div class="flex border-b border-gray-200">
            <button id="tab-list" class="px-4 py-2 tab-active font-medium text-sm">List View</button>
            <button id="tab-calendar" class="px-4 py-2 text-gray-500 font-medium text-sm">Calendar View</button>
            <button id="tab-add" class="px-4 py-2 text-gray-500 font-medium text-sm">Add Schedule</button>
        </div>
    </div>

    <!-- List View -->
    <div id="list-view" class="p-6 mb-2 overflow-hidden bg-white rounded-lg shadow-md">
        <h2 class="mb-4 font-semibold text-xl text-gray-800 flex items-center">
            <i class="fa-solid fa-list-ul text-blue-500 mr-2"></i>{{ __('My Schedule') }}
        </h2>
        @php
            $groupedSchedule = [];
            foreach ($schedule as $item) {
                $groupedSchedule[$item->day][] = $item;
            }
            
            // Order days of the week correctly
            $orderedDays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
            $orderedGroupedSchedule = [];
            foreach ($orderedDays as $day) {
                if (isset($groupedSchedule[$day])) {
                    $orderedGroupedSchedule[$day] = $groupedSchedule[$day];
                }
            }
            $groupedSchedule = $orderedGroupedSchedule;
        @endphp

        @if (count($groupedSchedule) > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($groupedSchedule as $day => $items)
                    <div class="day-card border border-gray-100 bg-white p-5">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-gray-800">{{ ucfirst($day) }}</h3>
                            <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded-full">{{ count($items) }} slots</span>
                        </div>
                        <div class="space-y-3">
                            @foreach ($items as $item)
                                <div class="schedule-card schedule-time-card bg-gray-50 p-4 rounded-lg border border-gray-100 flex justify-between items-center">
                                    <div>
                                        <div class="flex items-center text-gray-700 font-medium">
                                            <i class="fa-regular fa-clock text-blue-500 mr-2"></i>
                                            {{ $item->start }} - {{ $item->end }}
                                        </div>
                                        @if($item->specific_date)
                                            <div class="text-xs text-gray-500 mt-1 flex items-center">
                                                <i class="fa-regular fa-calendar text-blue-500 mr-2"></i>
                                                {{ \Carbon\Carbon::parse($item->specific_date)->format('M d, Y') }}
                                            </div>
                                        @endif
                                    </div>
                                    <div class="delete-btn">
                                        <form id="deleteForm_{{ $item->id }}" action="{{ route('doctor.schedule.delete', $item->id) }}" method="POST" style="display: none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>
                                        <button type="button" class="text-red-500 hover:text-red-700 bg-red-50 hover:bg-red-100 p-2 rounded-full"
                                            onclick="confirmDelete({{ $item->id }})">
                                            <i class="fa-solid fa-trash-can"></i>
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-10 text-center bg-gray-50 rounded-lg">
                <div class="text-blue-500 mb-3"><i class="fas fa-calendar-times fa-3x"></i></div>
                <p class="text-lg font-semibold text-gray-800">No schedules available</p>
                <p class="text-gray-500 mt-1">Add your availability using the "Add Schedule" tab</p>
                <button id="add-schedule-btn" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition">
                    <i class="fas fa-plus mr-2"></i>Add Schedule
                </button>
            </div>
        @endif
    </div>

    <!-- Calendar View -->
    <div id="calendar-view" class="p-6 mb-6 overflow-hidden bg-white rounded-lg shadow-md" style="display: none;">
        <h2 class="mb-4 font-semibold text-xl text-gray-800 flex items-center">
            <i class="fa-regular fa-calendar-alt text-blue-500 mr-2"></i>{{ __('Calendar View') }}
        </h2>
        <div class="overflow-x-auto rounded-md">
            <div id="calendar" class="w-full"></div>
        </div>
    </div>

    <!-- Add Schedule Form -->
    <div id="add-form" class="p-6 mb-6 overflow-hidden bg-white rounded-lg shadow-md" style="display: none;">
        <h2 class="mb-4 font-semibold text-xl text-gray-800 flex items-center">
            <i class="fa-solid fa-plus text-blue-500 mr-2"></i>{{ __('Add Availability') }}
        </h2>
        
        <form action="{{ route('doctor.schedule.add', ['id' => $doctor->id]) }}" method="POST" class="space-y-6">
            @csrf
            @php
                $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
                $timeSlots = [
                    '08:00 - 08:30', '09:00 - 09:30', '10:00 - 10:30', '11:00 - 11:30', 
                    '12:00 - 12:30', '13:00 - 13:30', '14:00 - 14:30', '15:00 - 15:30', 
                    '16:00 - 16:30', '17:00 - 17:30', '18:00 - 18:30', '19:00 - 19:30'
                ];
            @endphp
            
            @foreach ($days as $day)
                <div class="p-5 border border-gray-200 rounded-lg">
                    <div class="flex flex-wrap justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-800 mb-2 sm:mb-0">{{ ucfirst($day) }}</h3>
                        <div class="flex space-x-3 items-center">
                            <label class="text-sm text-gray-600">Specific Date:</label>
                            <input type="date" name="specific_date[{{ $day }}]" class="border border-gray-300 rounded-md" min="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-3">
                        @foreach ($timeSlots as $index => $timeSlot)
                            @php
                                $startTime = explode(' - ', $timeSlot)[0];
                                $hour = explode(':', $startTime)[0];
                            @endphp
                            <div class="relative">
                                <input id="{{ $day }}_{{ $hour }}" type="checkbox" name="start_times[{{ $day }}][]" value="{{ $startTime }}" class="sr-only time-slot-checkbox">
                                <label for="{{ $day }}_{{ $hour }}" class="time-slot flex items-center justify-center p-2 border border-gray-300 rounded-md text-sm cursor-pointer hover:bg-blue-50 hover:border-blue-300">
                                    {{ $timeSlot }}
                                </label>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
            
            <div class="flex justify-end">
                <button type="submit" class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 flex items-center">
                    <i class="fas fa-save mr-2"></i> Save All Slots
                </button>
            </div>
        </form>
    </div>
</x-doctor-layout>

<script src="{{ asset('js/fullcalendar/doctor_schedules.js') }}"></script>
<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Tab switching functionality
    document.addEventListener('DOMContentLoaded', function() {
        const listTab = document.getElementById('tab-list');
        const calendarTab = document.getElementById('tab-calendar');
        const addTab = document.getElementById('tab-add');
        const listView = document.getElementById('list-view');
        const calendarView = document.getElementById('calendar-view');
        const addForm = document.getElementById('add-form');
        const addScheduleBtn = document.getElementById('add-schedule-btn');
        
        if (addScheduleBtn) {
            addScheduleBtn.addEventListener('click', function() {
                showTab('add');
            });
        }
        
        function showTab(tab) {
            // Reset all tabs and views
            [listTab, calendarTab, addTab].forEach(t => t.classList.remove('tab-active'));
            [listTab, calendarTab, addTab].forEach(t => t.classList.add('text-gray-500'));
            [listView, calendarView, addForm].forEach(v => v.style.display = 'none');
            
            // Set active tab and view
            if (tab === 'list') {
                listTab.classList.add('tab-active');
                listTab.classList.remove('text-gray-500');
                listView.style.display = 'block';
            } else if (tab === 'calendar') {
                calendarTab.classList.add('tab-active');
                calendarTab.classList.remove('text-gray-500');
                calendarView.style.display = 'block';
                // Trigger window resize to make sure calendar renders correctly
                window.dispatchEvent(new Event('resize'));
            } else if (tab === 'add') {
                addTab.classList.add('tab-active');
                addTab.classList.remove('text-gray-500');
                addForm.style.display = 'block';
            }
        }
        
        listTab.addEventListener('click', () => showTab('list'));
        calendarTab.addEventListener('click', () => showTab('calendar'));
        addTab.addEventListener('click', () => showTab('add'));
    });
    
    // Delete confirmation
    function confirmDelete(itemId) {
        Swal.fire({
            title: 'Delete Schedule?',
            text: 'This action cannot be undone',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it',
            cancelButtonText: 'Cancel',
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            reverseButtons: true,
            borderRadius: '10px',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('deleteForm_' + itemId).submit();
            }
        });
    }
    
    // Time slot selection
    document.querySelectorAll('.time-slot-checkbox').forEach((checkbox) => {
        checkbox.addEventListener('change', (event) => {
            const label = event.target.nextElementSibling;
            if (checkbox.checked) {
                label.classList.add('bg-blue-500', 'text-white', 'border-blue-600');
                label.classList.remove('hover:bg-blue-50', 'hover:border-blue-300');
            } else {
                label.classList.remove('bg-blue-500', 'text-white', 'border-blue-600');
                label.classList.add('hover:bg-blue-50', 'hover:border-blue-300');
            }
        });
    });
</script>
