@extends('layout.customer')
@section('title', 'Fresh Market Products - Browse Local Vendors')
@section('content')

<style>
    /* Custom carousel styles */
    .featured-carousel {
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .featured-carousel::-webkit-scrollbar {
        display: none;
    }

    .category-carousel {
        scroll-behavior: smooth;
        scrollbar-width: none;
        -ms-overflow-style: none;
    }
    .category-carousel::-webkit-scrollbar {
        display: none;
    }

    /* Custom animations */
    @keyframes slideIn {
        from {
            transform: translateY(20px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }

    .animate-slide-in {
        animation: slideIn 0.6s ease-out forwards;
    }

    /* Enhanced hover effects */
    .product-card {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: block;
        text-decoration: none;
        color: inherit;
    }

    .product-card:hover {
        transform: translateY(-4px);
        text-decoration: none;
        color: inherit;
    }

    /* Improved focus styles for accessibility */
    .focus-ring:focus {
        outline: 2px solid #10b981;
        outline-offset: 2px;
    }

    /* Search input with icon styles */
    .search-input-wrapper {
        position: relative;
    }

    .search-input-wrapper input {
        padding-right: 3.5rem;
    }

    .search-input-wrapper .search-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #6b7280;
        pointer-events: none;
    }
</style>

<div class="max-w-[90rem] mx-auto px-3 sm:px-6 lg:px-8">
    <!-- Hero Section with Background Image -->
<div class="relative rounded-xl shadow-md mb-8 p-6 sm:p-8 text-white animate-slide-in bg-cover bg-center" 
     style="background-image: url('{{ asset('images/bg-banner.png') }}');">
    <div class="absolute inset-0 bg-emerald-700/60 rounded-xl"></div> <!-- Optional overlay for better text readability -->

    <div class="relative max-w-4xl">
        <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold mb-1 leading-tight">
            Fresh from <span class="text-emerald-200">Local Vendors</span>
        </h1>
        <p class="text-md sm:text-lg mb-5 text-emerald-100 max-w-2xl">
            Discover premium quality products from trusted vendors in your community
        </p>

        <!-- Search Bar -->
        <form method="GET" action="{{ route('products.search') }}" class="max-w-3xl">
            <div class="search-input-wrapper">
                <label for="product-search" class="sr-only">Search products</label>
                <input id="product-search" type="text" name="q" value="{{ request('search') }}" placeholder="Search products, vendors, or categories..." class="w-full px-5 py-4 rounded-xl text-gray-900 border-0 focus:ring-4 focus:ring-emerald-300/50 shadow-lg placeholder-gray-500 text-lg" aria-label="Search products, vendors, or categories">
                <button type="submit" class="search-icon" aria-label="Search products">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>





<!-- Categories -->
    @if($categories->count() > 0)
        <div class="animate-slide-in mb-7">
             <h3 class="text-lg font-semibold text-gray-800 mb-4">Browse by Category:</h3>
            <div class="relative">
                <div id="category-carousel" class="category-carousel flex gap-3 overflow-x-auto pb-2">
                    @foreach($categories->take(12) as $category)
                        <a href="{{ route('products.category', $category->id) }}" class="flex-none px-4 py-2 bg-white border border-gray-200 rounded-xl shadow-sm text-gray-600 rounded-full hover:bg-emerald-100 hover:text-emerald-700 transition-all duration-200 text-sm font-medium whitespace-nowrap">
                            {{ $category->category_name }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
    


   
    <!-- All Products -->
    <div class="animate-slide-in">
        <div class="sm:max-w-[90rem] sm:mx-auto">
            <div class="p-1">
                <h2 class="text-lg font-semibold mb-4 text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-6 h-6 text-emerald-600 mr-2 lucide lucide-shopping-cart-icon lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                    @if(request()->hasAny(['search', 'category', 'min_price', 'max_price']))
                        Filtered Products
                    @else
                        All Products
                    @endif
                </h2>
            </div>

            @if($products->count() > 0)
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-4 sm:gap-6 mb-10">
                    @foreach($products as $product)
                        <a href="{{ route('products.show', $product->id) }}" class="product-card bg-white border border-gray-200 rounded-xl overflow-hidden hover:shadow-2xl transition-all duration-300 hover:border-emerald-300">
                            <div class="aspect-square relative overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                                @if($product->image_url)
                                    <img src="{{ asset('storage/' . $product->image_url) }}" alt="{{ $product->product_name }}" class="w-full h-full object-cover hover:scale-110 transition-transform duration-500" loading="lazy">
                                @else
                                    <div class="w-full h-full flex items-center justify-center text-gray-400">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                @endif

                                @if($product->is_budget_based)
                                    <span class="absolute top-2 left-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white text-xs font-semibold px-2 py-1 rounded-full shadow-lg">Budget</span>
                                @endif
                            </div>

                            <div class="p-3 sm:p-4">
                                <div class="mb-2">
                                    @if($product->category)
                                        <span class="inline-block bg-emerald-100 text-emerald-700 text-xs font-medium px-2 py-1 rounded-full">{{ $product->category->category_name }}</span>
                                    @endif
                                </div>
                                <h3 class="font-semibold text-gray-900 mb-1 line-clamp-2 text-sm sm:text-base">{{ $product->product_name }}</h3>
                                <p class="text-xs sm:text-sm text-gray-600 mb-3">by <span class="text-emerald-600 font-medium">{{ $product->vendor->vendor_name }}</span></p>
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <span class="text-lg font-bold text-emerald-600">₱{{ number_format($product->price, 2) }}</span>
                                        <span class="text-xs text-gray-500">/ {{ $product->unit }}</span>
                                    </div>
                                </div>
                                  @if($product->vendor->average_rating)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <svg class="w-4 h-4 text-yellow-400 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                        {{ number_format($product->vendor->average_rating, 1) }}
                                    </div>
                                @endif
                            </div>
                        </a>
                    @endforeach
                </div>

              
            @else
                <div class="text-center py-16">
                    <div class="bg-gradient-to-br from-gray-100 to-gray-200 rounded-full w-32 h-32 flex items-center justify-center mx-auto mb-6">
                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-700 mb-3">No products found</h3>
                    <p class="text-gray-500 mb-6 max-w-md mx-auto">We couldn't find any products matching your search criteria. Try adjusting your filters or search terms.</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="{{ route('products.index') }}" class="inline-block px-6 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white rounded-lg hover:from-emerald-700 hover:to-teal-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl">View All Products</a>
                        <button onclick="document.querySelector('form').reset()" class="inline-block px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors font-semibold">Clear Filters</button>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>


<script>
    // Enhanced carousel functionality
    function scrollCarousel(direction, carouselId) {
        const carousel = document.getElementById(carouselId);
        let cardWidth;
        
        // Set card width based on carousel type
        switch(carouselId) {
            case 'featured-carousel':
                cardWidth = 288 + 16; // featured product card width + gap
                break;
            case 'vendor-carousel':
                cardWidth = window.innerWidth < 640 ? 192 + 16 : 256 + 16; // vendor card width + gap (responsive)
                break;
            case 'category-carousel':
                cardWidth = 120 + 12; // category card width + gap
                break;
            default:
                cardWidth = 200 + 16;
        }
        
        const scrollAmount = direction === 'left' ? -cardWidth * 2 : cardWidth * 2;
        
        carousel.scrollBy({
            left: scrollAmount,
            behavior: 'smooth'
        });
    }
    
    // Auto-scroll carousel (optional)
    let autoScrollInterval;
    
    function startAutoScroll() {
        autoScrollInterval = setInterval(() => {
            const carousel = document.getElementById('featured-carousel');
            if (carousel && carousel.scrollLeft >= carousel.scrollWidth - carousel.clientWidth) {
                carousel.scrollTo({ left: 0, behavior: 'smooth' });
            } else if (carousel) {
                scrollCarousel('right', 'featured-carousel');
            }
        }, 5000);
    }
    
    function stopAutoScroll() {
        clearInterval(autoScrollInterval);
    }
    
    // Start auto-scroll when page loads
    document.addEventListener('DOMContentLoaded', function() {
        const featuredCarousel = document.getElementById('featured-carousel');
        const vendorCarousel = document.getElementById('vendor-carousel');
        
        if (featuredCarousel) {
            startAutoScroll();
            
            // Pause auto-scroll on hover
            featuredCarousel.addEventListener('mouseenter', stopAutoScroll);
            featuredCarousel.addEventListener('mouseleave', startAutoScroll);
        }
        
        // Add touch scrolling support for vendor carousel on mobile
        if (vendorCarousel) {
            let isDown = false;
            let startX;
            let scrollLeft;
            
            vendorCarousel.addEventListener('mousedown', (e) => {
                isDown = true;
                vendorCarousel.classList.add('cursor-grabbing');
                startX = e.pageX - vendorCarousel.offsetLeft;
                scrollLeft = vendorCarousel.scrollLeft;
            });
            
            vendorCarousel.addEventListener('mouseleave', () => {
                isDown = false;
                vendorCarousel.classList.remove('cursor-grabbing');
            });
            
            vendorCarousel.addEventListener('mouseup', () => {
                isDown = false;
                vendorCarousel.classList.remove('cursor-grabbing');
            });
            
            vendorCarousel.addEventListener('mousemove', (e) => {
                if (!isDown) return;
                e.preventDefault();
                const x = e.pageX - vendorCarousel.offsetLeft;
                const walk = (x - startX) * 2;
                vendorCarousel.scrollLeft = scrollLeft - walk;
            });
        }
    });
    
    // Enhanced search functionality with debouncing
    const searchInput = document.getElementById('product-search');
    if (searchInput) {
        let searchTimeout;
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                // Optional: implement live search here
            }, 300);
        });
        
        // Submit form when search icon is clicked
        const searchIcon = document.querySelector('.search-icon');
        if (searchIcon) {
            searchIcon.addEventListener('click', function(e) {
                e.preventDefault();
                searchInput.closest('form').submit();
            });
        }
    }
</script>

@endsection