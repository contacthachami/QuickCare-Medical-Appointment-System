<head>
    <title>MA| My Appointments List</title>
</head>
<x-patient-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white">
            {{ __('My Appointments List Page') }}
        </h2>
    </x-slot>
    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>
    <div
        class="p-6 mt-7 overflow-hidden bg-white dark:bg-dark-eval-1 rounded-md shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-center">
            <div class="p-6 bg-white dark:bg-dark-eval-2 rounded-md flex flex-col items-center justify-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    <span class="mr-2"><i class="fa-regular fa-calendar-check" style="color: #74C0FC;"></i></span>
                    Total Appointments
                </h3>
                <p class="text-blue-500 text-3xl dark:text-blue-400">{{ count(Auth::user()->patient->Appointments) }}</p>
            </div>
            <div class="p-6 bg-white dark:bg-dark-eval-2 rounded-md flex flex-col items-center justify-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    <span class="mr-2"><i class="fa-solid fa-spinner" style="color: #74C0FC;"></i></span>
                    Pending Appointments
                </h3>
                <p class="text-blue-500 text-3xl dark:text-blue-400">
                    {{ count(Auth::user()->patient->appointments()->where('status', 'Pending')->get()) }}
                </p>
            </div>
            <div class="p-6 bg-white dark:bg-dark-eval-2 rounded-md flex flex-col items-center justify-center">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">
                    <span class="mr-2"><i class="fa-solid fa-check" style="color: #74C0FC;"></i></span>
                    Completed or Expired Appointments
                </h3>
                <p class="text-blue-500 text-3xl dark:text-blue-400">
                    {{ count(Auth::user()->patient->appointments()->whereIn('status', ['Completed', 'Expired'])->get()) }}
                </p>
            </div>
        </div>
    </div>

    <div class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl text-gray-700 font-medium flex items-center">
                <i class="fas fa-calendar-check text-blue-500 mr-2"></i> 
                My Appointments List
        </h2>
        
            <div class="flex justify-between items-center gap-4">
                <div class="flex">
                    <form id="export-excel-form" method="POST" action="{{ route('patient.appointments.export') }}" class="mr-2">
                    @csrf
                        <button type="submit" id="excel-btn" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-md shadow-sm">
                            <i class="fas fa-file-excel text-green-600 mr-2"></i> 
                            <span class="text-gray-700">Excel</span>
                    </button>
                </form>
                    
                    <button id="print-btn" class="flex items-center px-4 py-2 bg-white border border-gray-200 rounded-md shadow-sm">
                        <i class="fas fa-print text-blue-600 mr-2"></i> 
                        <span class="text-gray-700">Print</span>
                </button>
            </div>
            
            <div class="relative">
                    <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                        <i class="fas fa-search text-gray-400"></i>
                    </div>
                    <input type="text" id="search-input" placeholder="Search appointments..." 
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-1 focus:ring-blue-500 focus:border-blue-500 transition-all duration-300 w-64 dark:bg-gray-700 dark:border-gray-600 dark:text-white">
                </div>
            </div>
        </div>
        
        <div class="overflow-x-auto bg-white border border-gray-200 rounded-md shadow-sm">
            <table id="appointments-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="group px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Numero de rendez-vous <i class="fas fa-sort-up ml-1 text-blue-500"></i>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Reason <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Appointment Date <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Doctor Name <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Starting Hour <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th class="group px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider cursor-pointer">
                            <div class="flex items-center">
                                Status <i class="fas fa-sort ml-1 text-gray-400"></i>
                            </div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Action
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($appointments as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->id }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->reason }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->appointment_date }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-medium">
                                        {{ substr($item->doctor->user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-gray-900">{{ $item->doctor->user->name }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $item->schedule->start }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($item->status == 'Pending')
                                    <span class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                        • Pending
                                    </span>
                                @elseif ($item->status == 'Expired')
                                    <span class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        • Expired
                                    </span>
                                @elseif ($item->status == 'Cancelled')
                                    <span class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                        • Cancelled
                                    </span>
                                @elseif ($item->status == 'Approved')
                                    <span class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                        • Approved
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <a href="{{ route('patiens.appointment.detail', $item->id) }}"
                                    class="text-blue-600 hover:text-blue-900 flex items-center">
                                    <i class="fas fa-eye mr-1"></i> View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div class="flex justify-between items-center mt-4 px-1">
            <div class="text-sm text-gray-500">
                Showing 1 to 2 of 2 entries
            </div>
            <div class="inline-flex shadow-sm">
                <button id="prev-page" class="px-3 py-1 border border-gray-300 bg-white text-gray-500 rounded-l-md hover:bg-gray-50">
                    Previous
                </button>
                <button id="page-1" class="px-3 py-1 border-t border-b border-gray-300 bg-blue-500 text-white">
                    1
                </button>
                <button id="next-page" class="px-3 py-1 border border-gray-300 bg-white text-gray-500 rounded-r-md hover:bg-gray-50">
                    Next
                </button>
            </div>
        </div>
    </div>
</x-patient-layout>

 <script>
// Add event listener to the print button when the DOM is loaded
document.addEventListener('DOMContentLoaded', function() {
    // Set up print button functionality
    const printBtn = document.getElementById('print-btn');
    
    if (printBtn) {
        printBtn.addEventListener('click', function(e) {
            e.preventDefault();
            printAppointmentsTable();
        });
    }

    // Set up search functionality
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            const tableBody = document.querySelector('#appointments-table tbody');
            if (tableBody) {
                const rows = Array.from(tableBody.querySelectorAll('tr'));
                let visibleCount = 0;
                
                // Filter rows based on search term
                rows.forEach(row => {
                    const text = Array.from(row.cells).map(cell => cell.textContent.toLowerCase()).join(' ');
                    const isMatch = text.includes(searchTerm);
                    row.style.display = isMatch ? '' : 'none';
                    if (isMatch) visibleCount++;
                });
                
                // Update showing text
                document.getElementById('showing-start').textContent = visibleCount > 0 ? '1' : '0';
                document.getElementById('showing-end').textContent = visibleCount;
            }
        });
    }
    
    // Set up sorting functionality
    setupTableSorting();
});

// Setup table sorting
function setupTableSorting() {
    const headerCells = document.querySelectorAll('#appointments-table th.group');
    
    headerCells.forEach((headerCell, columnIndex) => {
        headerCell.addEventListener('click', function() {
            // Reset all sort icons
            document.querySelectorAll('th.group i').forEach(icon => {
                if (icon.parentElement.parentElement !== headerCell) {
                    icon.className = 'fas fa-sort ml-1 text-gray-400';
                }
            });
            
            // Get the icon element in this header
            const sortIcon = headerCell.querySelector('i');
            
            // Toggle sort direction
            if (sortIcon.classList.contains('fa-sort') || sortIcon.classList.contains('fa-sort-down')) {
                sortIcon.className = 'fas fa-sort-up ml-1 text-blue-500';
                sortTable(columnIndex, false); // Sort ascending
            } else {
                sortIcon.className = 'fas fa-sort-down ml-1 text-blue-500';
                sortTable(columnIndex, true); // Sort descending
            }
        });
    });
}

// Sort table function
function sortTable(columnIndex, isDescending) {
    const tableBody = document.querySelector('#appointments-table tbody');
    const rows = Array.from(tableBody.querySelectorAll('tr'));
    
    // Sort rows
    rows.sort((rowA, rowB) => {
        const cellA = rowA.cells[columnIndex].textContent.trim();
        const cellB = rowB.cells[columnIndex].textContent.trim();
        
        // Simple comparison for strings and numbers
        if (isDescending) {
            return cellB.localeCompare(cellA, undefined, { numeric: true });
        } else {
            return cellA.localeCompare(cellB, undefined, { numeric: true });
        }
    });
    
    // Re-append rows in new order
    rows.forEach(row => tableBody.appendChild(row));
}

// Define the print function globally so it's accessible from anywhere
function printAppointmentsTable() {
        // Find the table
        const table = document.getElementById('appointments-table');
        if (!table) {
            alert('Could not find the appointments table.');
            return;
        }
        
        const tableBody = table.querySelector('tbody');
        if (!tableBody) {
            alert('Could not find the table body.');
            return;
        }
        
        const rows = Array.from(tableBody.querySelectorAll('tr:not([style*="display: none"])'));
        if (rows.length === 0) {
            alert('No appointments to print. Please adjust your search filter if you applied one.');
            return;
        }
        
        try {
        // Create a form to POST data to a print page
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("patient.appointments.print") }}';
        form.target = '_blank'; // Open in a new tab/window
        
        // Add CSRF token
        const csrfToken = document.createElement('input');
        csrfToken.type = 'hidden';
        csrfToken.name = '_token';
        csrfToken.value = '{{ csrf_token() }}';
        form.appendChild(csrfToken);
        
        // Add to document and submit
        document.body.appendChild(form);
        form.submit();
        
        // Remove form after submission
        document.body.removeChild(form);
                
            } catch (error) {
        console.error('Error creating print request:', error);
        alert('There was an error preparing the print view: ' + error.message);
        
        // Fallback to basic window.print() if the form submission fails
        window.print();
    }
}
</script>

<style>
/* Base button and input styles */
#excel-btn, #print-btn {
    background-color: white;
    border: 1px solid #e5e7eb;
    color: #4b5563;
    transition: all 0.15s ease;
    box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
}

#excel-btn:hover, #print-btn:hover {
    background-color: #f9fafb;
}

#excel-btn i {
    color: #059669;
}

#print-btn i {
    color: #3b82f6;
}

/* Table styles */
#appointments-table th {
    font-weight: 500;
    color: #6b7280;
}

#appointments-table th:hover {
    background-color: #f9fafb;
}

/* Search input */
#search-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 1px rgba(59, 130, 246, 0.2);
}
</style>
