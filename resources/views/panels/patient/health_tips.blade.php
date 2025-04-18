<head>
    <title>QuickCare | Health Articles</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<x-patient-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight dark:text-white flex items-center">
            <span class="mr-2"><i class="fas fa-notes-medical text-blue-500"></i></span>
            {{ __('Health Articles') }}
        </h2>
    </x-slot>
    <x-success-flash></x-success-flash>
    <x-error-flash></x-error-flash>

    <div class="py-8 mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header Section -->
        <div class="mb-10">
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">Health Articles</h1>
                    <p class="text-gray-600 dark:text-gray-400 max-w-3xl">Stay informed with the latest health news, research findings, and expert advice to help you make better healthcare decisions.</p>
                </div>
                <div class="mt-4 md:mt-0">
                    <div class="relative">
                        <input type="text" id="search-articles" placeholder="Search articles..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-full md:w-64">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Categories -->
            <div class="flex overflow-x-auto pb-2 mb-6 gap-2 categories-scrollbar">
                <button class="category-btn active px-4 py-2 rounded-full bg-blue-500 text-white font-medium whitespace-nowrap">All Articles</button>
                <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium whitespace-nowrap">Nutrition</button>
                <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium whitespace-nowrap">Mental Health</button>
                <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium whitespace-nowrap">Fitness</button>
                <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium whitespace-nowrap">Preventive Care</button>
                <button class="category-btn px-4 py-2 rounded-full bg-gray-200 text-gray-700 hover:bg-gray-300 font-medium whitespace-nowrap">Medicine</button>
            </div>
        </div>

        <!-- Featured Article -->
        @if (isset($healthTips) && count($healthTips) > 0)
            <div class="mb-12 rounded-xl overflow-hidden shadow-lg bg-white dark:bg-gray-800">
                <div class="grid md:grid-cols-2 gap-0">
                    <div class="h-64 md:h-full overflow-hidden">
                        <img src="{{ $healthTips[0]['urlToImage'] ?? 'https://via.placeholder.com/800x600?text=Health+Article' }}" 
                            alt="{{ $healthTips[0]['title'] ?? 'Featured Article' }}"
                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                            onerror="this.src='https://via.placeholder.com/800x600?text=Health+Article'">
                    </div>
                    <div class="p-8 flex flex-col justify-between">
                        <div>
                            <div class="flex items-center mb-4">
                                <span class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">Featured</span>
                                <span class="mx-2 text-gray-400">•</span>
                                <span class="text-gray-500 text-sm">{{ isset($healthTips[0]['publishedAt']) ? \Carbon\Carbon::parse($healthTips[0]['publishedAt'])->format('M d, Y') : date('M d, Y') }}</span>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">{{ $healthTips[0]['title'] ?? 'Featured Health Article' }}</h2>
                            <p class="text-gray-700 dark:text-gray-300 mb-6 line-clamp-3">{{ $healthTips[0]['description'] ?? 'No description available' }}</p>
                        </div>
                        <div>
                            <a href="{{ $healthTips[0]['url'] ?? '#' }}" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors duration-200" target="_blank">
                                Read Full Article
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Articles Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 article-grid">
                @foreach ($healthTips as $index => $healthTip)
                    @if ($index > 0) <!-- Skip the first one since it's featured -->
                        <div class="article-card bg-white dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow duration-300"
                             data-categories="all,health">
                            <div class="relative h-48 overflow-hidden">
                                <img src="{{ $healthTip['urlToImage'] ?? 'https://via.placeholder.com/400x300?text=Health+Article' }}" 
                                     alt="{{ $healthTip['title'] }}"
                                     class="w-full h-full object-cover hover:scale-105 transition-transform duration-500"
                                     onerror="this.src='https://via.placeholder.com/400x300?text=Health+Article'">
                                <div class="absolute top-0 right-0 m-2">
                                    <span class="bg-blue-500 text-white text-xs font-semibold px-2.5 py-0.5 rounded-full">Health</span>
                                </div>
                            </div>
                            <div class="p-6">
                                <div class="flex items-center text-sm text-gray-500 mb-3">
                                    <span>{{ isset($healthTip['publishedAt']) ? \Carbon\Carbon::parse($healthTip['publishedAt'])->format('M d, Y') : date('M d, Y') }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ isset($healthTip['author']) ? $healthTip['author'] : 'Health Expert' }}</span>
                                </div>
                                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-3 line-clamp-2">{{ $healthTip['title'] }}</h3>
                                <p class="text-gray-700 dark:text-gray-300 mb-4 line-clamp-3">{{ $healthTip['description'] ?? 'No description available' }}</p>
                                <a href="{{ $healthTip['url'] }}" class="text-blue-600 hover:text-blue-800 font-medium inline-flex items-center" target="_blank">
                                    Read more
                                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>

            <!-- Load More Button -->
            <div class="mt-12 flex justify-center">
                <button id="load-more" class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition-colors duration-200 flex items-center">
                    Load More Articles
                    <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        @else
            <div class="flex flex-col items-center justify-center py-20 bg-white dark:bg-gray-800 rounded-lg shadow-md">
                <svg class="w-16 h-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-xl font-medium text-gray-900 dark:text-white mb-2">No Articles Available</h3>
                <p class="text-gray-600 dark:text-gray-400 text-center max-w-md">We couldn't find any health articles at the moment. Please check back later for updates.</p>
            </div>
        @endif
    </div>
</x-patient-layout>

<style>
    /* Custom Scrollbar for Categories */
    .categories-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
    }
    .categories-scrollbar::-webkit-scrollbar {
        height: 6px;
    }
    .categories-scrollbar::-webkit-scrollbar-track {
        background: transparent;
    }
    .categories-scrollbar::-webkit-scrollbar-thumb {
        background-color: rgba(156, 163, 175, 0.5);
        border-radius: 20px;
    }

    /* Line clamp utilities */
    .line-clamp-2 {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .line-clamp-3 {
        display: -webkit-box;
        -webkit-line-clamp: 3;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    /* Category button styles */
    .category-btn {
        transition: all 0.2s ease;
    }
    .category-btn:hover:not(.active) {
        transform: translateY(-2px);
    }
    .category-btn.active {
        box-shadow: 0 2px 5px rgba(59, 130, 246, 0.5);
    }
    
    /* Card hover effects */
    .article-card {
        transition: transform 0.3s ease;
    }
    .article-card:hover {
        transform: translateY(-5px);
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Assign random categories to articles for demo purposes
        const categories = ['nutrition', 'mental health', 'fitness', 'preventive care', 'medicine', 'all'];
        const articleCards = document.querySelectorAll('.article-card');
        
        articleCards.forEach(card => {
            // Always include 'all' plus 1-2 random categories
            const randomCategories = ['all'];
            
            // Add 1-2 random categories
            const numToAdd = Math.floor(Math.random() * 2) + 1;
            for (let i = 0; i < numToAdd; i++) {
                const randomCategory = categories[Math.floor(Math.random() * (categories.length - 1))]; // Exclude 'all'
                if (!randomCategories.includes(randomCategory)) {
                    randomCategories.push(randomCategory);
                }
            }
            
            // Set the categories attribute
            card.dataset.categories = randomCategories.join(',');
        });
        
        // Category filtering
        const categoryButtons = document.querySelectorAll('.category-btn');
        
        categoryButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Remove active class from all buttons
                categoryButtons.forEach(btn => btn.classList.remove('active', 'bg-blue-500', 'text-white'));
                categoryButtons.forEach(btn => btn.classList.add('bg-gray-200', 'text-gray-700'));
                
                // Add active class to clicked button
                this.classList.add('active', 'bg-blue-500', 'text-white');
                this.classList.remove('bg-gray-200', 'text-gray-700');
                
                const category = this.textContent.trim().toLowerCase();
                
                // Show/hide cards based on category
                articleCards.forEach(card => {
                    if (category === 'all articles' || card.dataset.categories.includes(category)) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });
        
        // Search functionality
        const searchInput = document.getElementById('search-articles');
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            
            articleCards.forEach(card => {
                const title = card.querySelector('h3').textContent.toLowerCase();
                const description = card.querySelector('p').textContent.toLowerCase();
                
                if (title.includes(searchTerm) || description.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
        
        // Simulate "Load More" functionality
        const loadMoreBtn = document.getElementById('load-more');
        let visibleCards = 9; // Initial number of visible cards after featured article
        
        // Hide cards beyond initial visible count
        Array.from(articleCards).forEach((card, index) => {
            if (index >= visibleCards) {
                card.style.display = 'none';
                card.classList.add('hidden-card');
            }
        });
        
        loadMoreBtn.addEventListener('click', function() {
            const hiddenCards = document.querySelectorAll('.hidden-card');
            let count = 0;
            
            hiddenCards.forEach(card => {
                if (count < 6 && card.style.display === 'none') { // Show 6 more cards
                    card.style.display = 'block';
                    card.classList.remove('hidden-card');
                    
                    // Add animation
                    card.classList.add('animate__animated', 'animate__fadeIn');
                    setTimeout(() => {
                        card.classList.remove('animate__animated', 'animate__fadeIn');
                    }, 1000);
                    
                    count++;
                }
            });
            
            // Hide the button if no more cards to show
            if (document.querySelectorAll('.hidden-card').length === 0) {
                this.style.display = 'none';
            }
        });
    });
</script>
