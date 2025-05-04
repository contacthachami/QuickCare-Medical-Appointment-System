<head>
    <title>My Appointments</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<x-doctor-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Appointments') }}
        </h2>
    </x-slot>

    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    @if(!request()->has('travel'))
    <!-- My Appointments Section -->
    <div class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
            <div>
                <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                    <i class="fas fa-calendar-check mr-2 text-blue-600"></i> My Appointments
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your patient appointments and track visits</p>
            </div>
            <div class="flex flex-wrap gap-2">
                <a href="{{ route('doctor.travel-tracking') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white py-2 px-4 rounded flex items-center">
                    <i class="fas fa-route mr-2"></i> Travel Tracking
                </a>
                <button id="csvBtn" class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded flex items-center">
                    <i class="fas fa-file-csv mr-2"></i> Export CSV
                </button>
                <button id="printBtn" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded flex items-center">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>
        </div>

        <!-- Filter and Search Controls -->
        <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fas fa-search text-gray-400"></i>
                </div>
                <input type="text" id="appointmentSearch" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Search appointments...">
            </div>
            
            <div>
                <select id="statusFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Canceled">Canceled</option>
                    <option value="Expired">Expired</option>
                </select>
            </div>
            
            <div>
                <select id="dateFilter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full p-2.5">
                    <option value="">All Dates</option>
                    <option value="today">Today</option>
                    <option value="tomorrow">Tomorrow</option>
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                </select>
            </div>
        </div>
        
        <!-- Appointments Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                <div class="flex items-center">
                    <div class="bg-blue-100 p-3 rounded-full mr-4">
                        <i class="fas fa-calendar-alt text-blue-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-blue-600 font-medium">Total</div>
                        <div class="text-2xl font-bold text-blue-800">{{ count($appointments) }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                <div class="flex items-center">
                    <div class="bg-green-100 p-3 rounded-full mr-4">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-green-600 font-medium">Approved</div>
                        <div class="text-2xl font-bold text-green-800">{{ $appointments->where('status', 'Approved')->count() }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                <div class="flex items-center">
                    <div class="bg-yellow-100 p-3 rounded-full mr-4">
                        <i class="fas fa-clock text-yellow-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-yellow-600 font-medium">Pending</div>
                        <div class="text-2xl font-bold text-yellow-800">{{ $appointments->where('status', 'Pending')->count() }}</div>
                    </div>
                </div>
            </div>
            
            <div class="bg-red-50 p-4 rounded-lg border border-red-200">
                <div class="flex items-center">
                    <div class="bg-red-100 p-3 rounded-full mr-4">
                        <i class="fas fa-times-circle text-red-600 text-xl"></i>
                    </div>
                    <div>
                        <div class="text-sm text-red-600 font-medium">Canceled</div>
                        <div class="text-2xl font-bold text-red-800">{{ $appointments->where('status', 'Canceled')->count() }}</div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table id="appointmentsTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">#</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Patient</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Travel</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(count($appointments) > 0)
                        @foreach($appointments as $item)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $item->id }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ \Carbon\Carbon::parse($item->appointment_date)->format('M d, Y') }}</div>
                                    <div class="text-xs text-gray-500">{{ $item->schedule->start ?? \Carbon\Carbon::parse($item->appointment_date)->format('h:i A') }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 bg-blue-100 rounded-full flex items-center justify-center mr-3">
                                            <i class="fas fa-user text-blue-600"></i>
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $item->patient->user->name }}</div>
                                            <div class="text-xs text-gray-500">{{ $item->patient->user->email ?? 'No email' }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900">{{ Str::limit($item->reason, 40) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($item->status == 'Pending')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 border border-yellow-200">
                                            <i class="fas fa-clock mr-1"></i> Pending
                                        </span>
                                    @elseif($item->status == 'Approved')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 border border-green-200">
                                            <i class="fas fa-check-circle mr-1"></i> Approved
                                        </span>
                                    @elseif($item->status == 'Canceled')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 border border-red-200">
                                            <i class="fas fa-times-circle mr-1"></i> Canceled
                                        </span>
                                    @elseif($item->status == 'Expired')
                                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800 border border-gray-200">
                                            <i class="fas fa-calendar-times mr-1"></i> Expired
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if(!$item->check_in_time)
                                        <div class="flex items-center">
                                            <span class="inline-block h-2 w-2 rounded-full bg-yellow-400 mr-2"></span>
                                            <span class="text-xs font-medium">Not started</span>
                                        </div>
                                    @elseif($item->check_in_time && !$item->check_out_time)
                                        <div class="flex items-center">
                                            <span class="inline-block h-2 w-2 rounded-full bg-blue-500 mr-2 animate-pulse"></span>
                                            <span class="text-xs font-medium text-blue-700">In progress</span>
                                        </div>
                                    @elseif($item->check_in_time && $item->check_out_time)
                                        <div class="flex items-center">
                                            <span class="inline-block h-2 w-2 rounded-full bg-green-500 mr-2"></span>
                                            <span class="text-xs font-medium text-green-700">{{ $item->travel_time_minutes }} minutes</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <a href="{{ route('doctor.CRUD.appointment.details', [$item->id]) }}" class="text-blue-600 hover:text-blue-900 bg-blue-100 hover:bg-blue-200 p-1.5 rounded-lg transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        @if($item->status == 'Pending')
                                            <a href="{{ route('doctor.CRUD.appointment.edit', [$item->id]) }}" class="text-indigo-600 hover:text-indigo-900 bg-indigo-100 hover:bg-indigo-200 p-1.5 rounded-lg transition-colors" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        @endif
                                        @if($item->status == 'Approved' && !$item->check_in_time)
                                            <form action="{{ route('doctor.appointment.check-in', $item->id) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit" class="text-green-600 hover:text-green-900 bg-green-100 hover:bg-green-200 p-1.5 rounded-lg transition-colors" title="Start Travel">
                                                    <i class="fas fa-route"></i>
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="7" class="px-6 py-10 text-center">
                                <div class="flex flex-col items-center justify-center">
                                    <i class="fas fa-calendar-times text-gray-300 text-5xl mb-4"></i>
                                    <h3 class="text-lg font-medium text-gray-700 mb-1">No appointments found</h3>
                                    <p class="text-sm text-gray-500">You don't have any appointments scheduled at the moment.</p>
                                </div>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        <div class="mt-4 flex justify-between items-center text-sm text-gray-600">
            <div>Showing {{ $appointments->firstItem() ?? 0 }} to {{ $appointments->lastItem() ?? 0 }} of {{ $appointments->total() ?? 0 }} entries</div>
            {{ $appointments->links() }}
        </div>
    </div>

    <!-- Calendar View Section -->
    <div class="p-6 mt-6 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <h2 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
            <i class="fas fa-calendar-alt mr-2 text-blue-600"></i> Appointment Calendar
        </h2>
        
        <div id="calendar" class="mt-4"></div>
    </div>
    @endif

    <!-- Calendar Scripts -->
    <script src="{{ asset('js/fullcalendar/doctor_calendar.js') }}"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Get references to filter and search elements
            const searchInput = document.getElementById('appointmentSearch');
            const statusFilter = document.getElementById('statusFilter');
            const dateFilter = document.getElementById('dateFilter');
            const csvBtn = document.getElementById('csvBtn');
            
            // Apply filtering when inputs change
            searchInput.addEventListener('input', filterAppointments);
            statusFilter.addEventListener('change', filterAppointments);
            dateFilter.addEventListener('change', filterAppointments);
            
            // Export CSV button click handler
            csvBtn.addEventListener('click', function() {
                window.open("{{ route('doctor.appointments.export') }}", "_blank");
            });
            
            // Export CSV function
            function exportCSV() {
                window.open("{{ route('doctor.appointments.export') }}", "_blank");
            }
            
            // Print button click handler
            document.getElementById('printBtn').addEventListener('click', function() {
                window.open("{{ route('doctor.appointments.print') }}", "_blank");
            });

            // Filter function
            function filterAppointments() {
                const searchValue = searchInput.value.toLowerCase();
                const statusValue = statusFilter.value;
                const dateValue = dateFilter.value;
                const rows = document.querySelectorAll('#appointmentsTable tbody tr');
                
                rows.forEach(row => {
                    const text = row.textContent.toLowerCase();
                    let showRow = true;
                    
                    // Apply search filter
                    if (searchValue && !text.includes(searchValue)) {
                        showRow = false;
                    }
                    
                    // Apply status filter
                    if (statusValue && showRow) {
                        const statusCell = row.querySelector('td:nth-child(5)');
                        if (!statusCell.textContent.includes(statusValue)) {
                            showRow = false;
                        }
                    }
                    
                    // Apply date filter (this is simplified - would need actual date parsing)
                    if (dateValue && showRow) {
                        const dateCell = row.querySelector('td:nth-child(2)').textContent;
                        const today = new Date().toLocaleDateString();
                        
                        if (dateValue === 'today' && !dateCell.includes(today)) {
                            showRow = false;
                        }
                        // Additional date filtering logic would go here
                    }
                    
                    row.style.display = showRow ? '' : 'none';
                });
            }
        });
    </script>
</x-doctor-layout>
