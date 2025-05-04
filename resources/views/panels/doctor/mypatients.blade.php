<head>
    <title>My Patients - QuickCare</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<x-doctor-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl flex items-center text-gray-800">
                <i class="fas fa-user-group text-blue-600 mr-2"></i> My Patients
        </h2>
            <div class="text-right">
                <span class="text-gray-600">Total patients:</span>
                <span class="font-medium">{{ $patients->count() }}</span>
            </div>
        </div>
    </x-slot>

    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    <div class="p-6 mt-4 bg-white rounded-lg shadow-sm">
        <!-- Top Controls -->
        <div class="flex flex-col md:flex-row justify-between mb-6 space-y-4 md:space-y-0">
            <!-- Export Buttons -->
            <div class="flex space-x-3">
                <a href="{{ route('doctor.patients.export') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                    <i class="fas fa-file-csv mr-2"></i> CSV
                </a>
                <button id="printButton" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700 flex items-center">
                    <i class="fas fa-print mr-2"></i> Print
                </button>
            </div>

            <!-- Search Box -->
            <div class="relative">
                <label for="search" class="sr-only">Search</label>
                <div class="flex items-center">
                    <span class="text-gray-600 mr-2">Search:</span>
                    <input type="text" id="search" name="search" class="block w-64 px-3 py-2 border border-gray-300 rounded-md leading-5 bg-white placeholder-gray-500 focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Search...">
                </div>
            </div>
        </div>

        <!-- Patients Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            # <i class="fas fa-sort text-gray-400 ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            CIN <i class="fas fa-sort text-gray-400 ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            NAME <i class="fas fa-sort text-gray-400 ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            BIRTHDAY <i class="fas fa-sort text-gray-400 ml-1"></i>
                        </th>
                        <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                            ACTIONS
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse ($patients as $item)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                {{ $loop->iteration }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ $item->cin ?? 'N/A' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-100 rounded-full flex items-center justify-center text-blue-600 text-xs font-medium">
                                        {{ substr($item->user->name, 0, 2) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900">{{ $item->user->name }}</div>
                                        <div class="text-xs text-gray-500">{{ $item->user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-700">
                                {{ \Carbon\Carbon::parse($item->birth_date)->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right space-x-1">
                                <a href="{{ route('doctor.CRUD.patient.view', [$item->id]) }}" class="text-blue-600 hover:text-blue-900" title="View Patient">
                                    <i class="fa-regular fa-eye"></i>
                                </a>
                                <a href="{{ route('doctor.CRUD.patient.book', [$item->id]) }}" class="text-green-600 hover:text-green-900 ml-3" title="Book Appointment">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center">
                                <div class="text-center">
                                    <div class="text-blue-500 mb-3">
                                        <i class="fas fa-user-injured fa-3x"></i>
                                    </div>
                                    <h3 class="text-lg font-medium text-gray-900">No patients found</h3>
                                    <p class="mt-1 text-sm text-gray-500">You don't have any patients assigned yet.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-5 flex items-center justify-between">
            <div class="text-sm text-gray-500">
                Showing 1 to {{ min(2, $patients->count()) }} of {{ $patients->count() }} entries
            </div>
            <div class="flex-1 flex justify-end">
                <nav class="relative z-0 inline-flex shadow-sm -space-x-px" aria-label="Pagination">
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <i class="fas fa-chevron-left h-5 w-5"></i>
                    </a>
                    <a href="#" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-blue-500 text-sm font-medium text-white hover:bg-blue-600">
                        1
                    </a>
                    <a href="#" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <i class="fas fa-chevron-right h-5 w-5"></i>
                    </a>
                </nav>
            </div>
        </div>
    </div>

    <!-- Print Template - Hidden by default -->
    <div id="printTemplate" style="display: none;">
        <div style="font-family: Arial, sans-serif; max-width: 800px; margin: 0 auto; padding: 20px;">
            <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #4F81BD; padding-bottom: 10px;">
                <h1 style="font-size: 24px; font-weight: bold; color: #4F81BD; margin: 0;">QuickCare - My Patients</h1>
                <p style="font-size: 14px; color: #777; margin: 5px 0;">Generated on {{ date('F d, Y') }}</p>
            </div>
            
            <div style="text-align: left; margin: 15px 0; font-size: 14px;">
                <p><strong>Doctor:</strong> {{ Auth::user()->name }}</p>
                <p><strong>Specialty:</strong> {{ Auth::user()->doctor->speciality ? Auth::user()->doctor->speciality->name : 'General' }}</p>
                <p><strong>Total Patients:</strong> {{ $patients->count() }}</p>
            </div>
            
            <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <thead>
                    <tr>
                        <th style="background-color: #4F81BD; color: white; font-weight: bold; text-align: left; padding: 10px;">#</th>
                        <th style="background-color: #4F81BD; color: white; font-weight: bold; text-align: left; padding: 10px;">CIN</th>
                        <th style="background-color: #4F81BD; color: white; font-weight: bold; text-align: left; padding: 10px;">NAME</th>
                        <th style="background-color: #4F81BD; color: white; font-weight: bold; text-align: left; padding: 10px;">BIRTHDAY</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($patients as $item)
                    <tr style="{{ $loop->iteration % 2 == 0 ? 'background-color: #f9f9f9;' : '' }}">
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $loop->iteration }}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $item->cin ?? 'N/A' }}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $item->user->name }}</td>
                        <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ \Carbon\Carbon::parse($item->birth_date)->format('M d, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            
            <div style="margin-top: 30px; font-size: 12px; text-align: center; color: #777;">
                <p>This is a confidential medical document.</p>
                <p>QuickCare &copy; {{ date('Y') }} - All rights reserved</p>
            </div>
        </div>
    </div>
</x-doctor-layout>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('search');
        const rows = document.querySelectorAll('tbody tr');
        
        searchInput.addEventListener('keyup', function(e) {
            const searchString = e.target.value.toLowerCase();
            
            rows.forEach(row => {
                const textContent = row.textContent.toLowerCase();
                if(textContent.includes(searchString)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Print functionality
        document.getElementById('printButton').addEventListener('click', function() {
            const printContents = document.getElementById('printTemplate').innerHTML;
            const originalContents = document.body.innerHTML;
            
            // Create a new window
            const printWindow = window.open('', '_blank');
            
            // Write the print content to the new window
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>QuickCare - My Patients</title>
                    <meta charset="utf-8">
                </head>
                <body>
                    ${printContents}
                    <script>
                        window.onload = function() {
                            window.print();
                            window.onafterprint = function() {
                                window.close();
                            }
                        }
                    <\/script>
                </body>
                </html>
            `);
            
            printWindow.document.close();
        });
    });
</script>

