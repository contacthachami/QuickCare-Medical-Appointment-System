<x-patient-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My reviews') }}
        </h2>
    </x-slot>

    <div class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-800 dark:text-white">Ratings I've Given</h1>
            <div class="flex space-x-2">
                <button id="exportCSV" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">CSV</button>
                <button id="printBtn" class="bg-indigo-500 hover:bg-indigo-600 text-white px-4 py-2 rounded">Print</button>
            </div>
        </div>

        @if ($ratings->count() == 0)
            <div class="text-center py-10">
                <div class="flex justify-center">
                    <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <p class="mt-4 text-lg font-semibold text-gray-600 dark:text-gray-300">No Rating yet.</p>
                <p class="text-gray-500 dark:text-gray-400 mt-2">You haven't rated any doctors yet.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table id="DataTable" class="w-full">
                    <thead>
                        <tr>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                #
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Doctor
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Rating
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Comment
                            </th>
                            <th class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                                Date
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ratings as $index => $rating)
                            <tr class="transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                                <td class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    {{ $rating->doctor->user->name }}
                                </td>
                                <td class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    <div class="flex items-center">
                                        @for ($i = 1; $i <= 5; $i++)
                                            @if ($i <= $rating->rating)
                                                <i class="fas fa-star text-yellow-500"></i>
                                            @else
                                                <i class="fas fa-star text-gray-300"></i>
                                            @endif
                                        @endfor
                                        <span class="ml-2">{{ $rating->rating }}/5</span>
                                    </div>
                                </td>
                                <td class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    {{ $rating->comment ?? 'No comment' }}
                                </td>
                                <td class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                    {{ $rating->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4 text-sm text-gray-600 dark:text-gray-400">
                Showing {{ $ratings->count() }} of {{ $ratings->count() }} entries
            </div>
        @endif
    </div>

    @include('includes.table')

    <script>
        $(document).ready(function() {
            $('#exportCSV').on('click', function() {
                window.location.href = "{{ route('patient.my.ratings') }}?export=csv";
            });

            $('#printBtn').on('click', function() {
                window.print();
            });
        });
    </script>
</x-patient-layout> 