<head>
    <title>Doctor's Dashboard</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/main.css?v=1628755089081">
</head>

<x-doctor-layout>



    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My reviews') }}
        </h2>
    </x-slot>


    <div class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        @if ($ratings->count() == 0)

        <p> <strong>No Rating yet .</p>

        @else

            <div class="mb-6">
                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-2">Overall Rating</h3>
                <div class="flex items-center">
                    <span class="text-3xl font-bold text-gray-800 dark:text-gray-100 mr-4">{{ number_format($ratings->avg('rating'), 1) }}</span>
                    <div class="flex items-center" id="ratingStars">
                        <!-- Stars will be dynamically generated here -->
                    </div>
                    <span class="ml-4 text-sm text-gray-600 dark:text-gray-400">({{ $ratings->count() }} ratings)</span>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
                @for ($i = 5; $i >= 1; $i--)
                    @php
                        $count = $ratings->where('rating', $i)->count();
                        $percentage = $ratings->count() > 0 ? ($count / $ratings->count()) * 100 : 0;
                    @endphp
                    <div class="flex items-center">
                        <span class="mr-2">{{ $i }}</span>
                        <i class="fas fa-star text-yellow-500 mr-2"></i>
                        <div class="w-full bg-gray-200 rounded-full h-2.5">
                            <div class="bg-yellow-500 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                        </div>
                        <span class="ml-2 text-sm text-gray-600">{{ $count }}</span>
                    </div>
                @endfor
            </div>

        @endif

    </div>

    <div class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <div class="overflow-x-auto">
            <table id="DataTable" class="w-full">
                <thead>
                    <tr>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            #</th>

                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Name</th>
                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Rating </th>


                        <th
                            class="px-5 py-3 border-b-2 border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-800 text-left text-xs font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wider">
                            Comment </th>



                    </tr>
                </thead>
                <tbody>

                    @if ($ratings)
                    @foreach ($ratings as $item)
                        <tr class="transition-all duration-300 hover:bg-gray-100 dark:hover:bg-gray-700">
                            <td
                                class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                {{ $item->id }}</td>

                            <td
                                class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                {{ $item->patient->user->name  }}</td>

                            <td
                                class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                <div class="flex items-center">
                                    @for ($i = 1; $i <= 5; $i++)
                                        @if ($i <= $item->rating)
                                            <i class="fas fa-star text-yellow-500"></i>
                                        @else
                                            <i class="fas fa-star text-gray-300"></i>
                                        @endif
                                    @endfor
                                    <span class="ml-2">{{ $item->rating }}/5</span>
                                </div>
                            </td>

                            <td class="py-2 px-5 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                {{ $item->comment}}
                            </td>





                        </tr>

                        @endforeach
                </tbody>
            </table>



        </div>
    </div>

    @else
    <div class="p-6 mt-7 overflow-hidden bg-white rounded-md shadow-md dark:bg-dark-eval-1">
        <h1 class="text-3xl font-bold text-gray-800 dark:text-white">No review Found</h1>
    </div>
    @endif








@include('includes.table')

</x-doctor-layout>

<script>
    const overallRating = {{ $ratings->avg('rating') }};
    const starContainer = document.getElementById('ratingStars');

    // Clear any existing stars
    starContainer.innerHTML = '';

    for (let i = 1; i <= 5; i++) {
        const star = document.createElement('i');
        
        if (i <= Math.floor(overallRating)) {
            star.className = 'fas fa-star text-yellow-500 text-xl mx-0.5';
        } else if (i - 0.5 <= overallRating) {
            star.className = 'fas fa-star-half-alt text-yellow-500 text-xl mx-0.5';
        } else {
            star.className = 'far fa-star text-yellow-500 text-xl mx-0.5';
        }
        
        starContainer.appendChild(star);
    }
</script>
