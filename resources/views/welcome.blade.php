<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PamilihanPasabuy - Your San Fernando Market, Delivered Fresh</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        'market-primary': '#059669',
                        'market-secondary': '#10b981',
                        'market-accent': '#f59e0b',
                        'market-dark': '#065f46'
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out',
                        'fade-in-down': 'fadeInDown 0.8s ease-out',
                        'fade-in-left': 'fadeInLeft 0.8s ease-out',
                        'fade-in-right': 'fadeInRight 0.8s ease-out',
                        'scale-in': 'scaleIn 0.6s ease-out',
                        'bounce-gentle': 'bounceGentle 2s ease-in-out infinite',
                        'float': 'float 3s ease-in-out infinite',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite'
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeInDown: {
                            '0%': { opacity: '0', transform: 'translateY(-30px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' }
                        },
                        fadeInLeft: {
                            '0%': { opacity: '0', transform: 'translateX(-30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        fadeInRight: {
                            '0%': { opacity: '0', transform: 'translateX(30px)' },
                            '100%': { opacity: '1', transform: 'translateX(0)' }
                        },
                        scaleIn: {
                            '0%': { opacity: '0', transform: 'scale(0.9)' },
                            '100%': { opacity: '1', transform: 'scale(1)' }
                        },
                        bounceGentle: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' }
                        },
                        float: {
                            '0%, 100%': { transform: 'translateY(0px)' },
                            '50%': { transform: 'translateY(-20px)' }
                        }
                    }
                }
            }
        }
    </script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .animate-on-scroll {
            opacity: 0;
            transform: translateY(30px);
            transition: all 0.6s ease-out;
        }
        .animate-on-scroll.animated {
            opacity: 1;
            transform: translateY(0);
        }
        .glass-effect {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.9);
        }
        .gradient-text {
            background: linear-gradient(135deg, #059669, #10b981);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stats-counter {
            font-variant-numeric: tabular-nums;
        }
        
        /* Fix for scrolling issues */
        body {
            overflow-x: hidden;
            padding-top: 0;
        }
        
        /* Ensure sections are properly spaced and visible */
        section {
            position: relative;
            z-index: 1;
        }
        
        /* Fix header positioning */
        header {
            z-index: 1000;
        }
        
        /* Ensure content doesn't get hidden behind fixed header */
        #home {
            padding-top: 80px;
        }
        
        /* Fix any potential z-index conflicts */
        .relative {
            position: relative;
        }
        
        .absolute {
            position: absolute;
        }
        
        /* Ensure proper stacking context */
        .z-10 {
            z-index: 10;
        }
        
        .z-50 {
            z-index: 50;
        }
        
        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
        
        /* Ensure all sections are visible */
        section {
            min-height: auto;
            width: 100%;
        }
        
        /* Fix for mobile responsiveness */
        @media (max-width: 768px) {
            #home {
                padding-top: 60px;
            }
        }
        
        /* Ensure proper spacing between sections */
        section:not(:first-child) {
            margin-top: 0;
        }
        
        /* Fix for any potential overflow issues */
        .container {
            max-width: 100%;
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        @media (min-width: 640px) {
            .container {
                max-width: 640px;
            }
        }
        
        @media (min-width: 768px) {
            .container {
                max-width: 768px;
            }
        }
        
        @media (min-width: 1024px) {
            .container {
                max-width: 1024px;
            }
        }
        
        @media (min-width: 1280px) {
            .container {
                max-width: 1280px;
            }
        }

        .header-white .nav-link,
        .header-white .fa-shopping-basket,
        .header-white .text-gray-800,
        .header-white .text-gray-700,
        .header-white .text-gray-500,
        .header-white .font-medium,
        .header-white .font-bold,
        .header-white .fa-bars,
        .header-white .fa-shopping-cart,
        .header-white .fa-play {
            color: #fff !important;
        }
        .header-white .bg-green-600 {
            background-color: #fff !important;
            color: #059669 !important;
        }
        .header-white .border-white {
            border-color: #fff !important;
        }
        .header-white .bg-white {
            background-color: transparent !important;
            color: #fff !important;
        }
        
        /* Hero Entrance Animations */
        .hero-title.animate-in {
            animation: heroTitleIn 1.2s ease-out forwards;
        }
        
        .hero-subtitle.animate-in {
            animation: heroSubtitleIn 1.2s ease-out 0.3s forwards;
        }
        
        .hero-buttons.animate-in {
            animation: heroButtonsIn 1.2s ease-out 0.6s forwards;
        }
        
        .hero-trust.animate-in {
            animation: heroTrustIn 1.2s ease-out 0.9s forwards;
        }
        
        .hero-scroll.animate-in {
            animation: heroScrollIn 1.2s ease-out 1.2s forwards;
        }
        
        @keyframes heroTitleIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.95);
            }
            50% {
                opacity: 0.8;
                transform: translateY(-5px) scale(1.02);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes heroSubtitleIn {
            0% {
                opacity: 0;
                transform: translateY(30px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes heroButtonsIn {
            0% {
                opacity: 0;
                transform: translateY(30px) scale(0.9);
            }
            50% {
                opacity: 0.8;
                transform: translateY(-3px) scale(1.05);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        @keyframes heroTrustIn {
            0% {
                opacity: 0;
                transform: translateY(20px);
            }
            100% {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes heroScrollIn {
            0% {
                opacity: 0;
                transform: translateY(20px) scale(0.8);
            }
            100% {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }
        
        /* Add a subtle background animation */
        #home::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(135deg, rgba(5, 150, 105, 0.1), rgba(16, 185, 129, 0.1));
            opacity: 0;
            animation: backgroundFadeIn 2s ease-out 0.5s forwards;
            z-index: 1;
        }
        
        @keyframes backgroundFadeIn {
            0% {
                opacity: 0;
            }
            100% {
                opacity: 1;
            }
        }
    </style>
</head>
<body class="font-sans bg-white">
    

   <!-- Header for top of hero (white content) -->
<header id="header-top" class="fixed top-0 w-full z-50 transition-all duration-300 animate-fade-in-down">
    <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform">
                <i class="fas fa-shopping-basket text-white text-lg"></i>
            </div>
            <div>
                <span class="text-2xl font-bold text-white">PamilihanPasabuy</span>
                <div class="text-xs text-white leading-none">San Fernando Market</div>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
            <a href="#home" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">Home</a>
            <a href="#how-it-works" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">How It Works</a>
            <a href="#features" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">Features</a>
            <a href="#testimonials" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">Reviews</a>
            <a href="#about" class="nav-link text-white hover:text-green-600 transition-colors font-medium relative">About</a>
        </div>

        <!-- Action Buttons -->
        <div class="hidden lg:flex items-center space-x-3">
            <button class="px-3 py-2.5 text-white hover:bg-white hover:text-green-600 border border-white rounded-xl transition-colors font-medium">Log In</button>
            <button class="px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Sign Up Free
            </button>
        </div>

        <!-- Mobile Menu Button -->
        <button class="lg:hidden text-white p-2" onclick="toggleMobileMenu()">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </nav>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden lg:hidden glass-effect border-t">
        <div class="container mx-auto px-4 py-6 space-y-4">
            <a href="#home" class="block text-gray-700 hover:text-green-600 py-2 font-medium">Home</a>
            <a href="#how-it-works" class="block text-gray-700 hover:text-green-600 py-2 font-medium">How It Works</a>
            <a href="#features" class="block text-gray-700 hover:text-green-600 py-2 font-medium">Features</a>
            <a href="#testimonials" class="block text-gray-700 hover:text-green-600 py-2 font-medium">Reviews</a>
            <a href="#about" class="block text-gray-700 hover:text-green-600 py-2 font-medium">About</a>
            <div class="pt-4 border-t space-y-3">
                <button class="w-full px-4 py-3 text-green-600 border-2 border-green-600 rounded-xl font-semibold">Join as Vendor</button>
                <button class="w-full px-4 py-3 text-green-600 border-2 border-green-600 rounded-xl font-semibold">Become Rider</button>
                <button class="w-full px-4 py-3 text-green-600 border border-green-600 rounded-xl font-medium">Log In</button>
                <button class="w-full px-4 py-3 bg-green-600 text-white rounded-xl font-semibold">Sign Up Free</button>
            </div>
        </div>
    </div>
</header>

<!-- Header for scrolling (original style) -->
<header id="header-scroll" class="fixed top-0 w-full z-50 transition-all duration-300 animate-fade-in-down" style="display:none;">
    <nav class="container mx-auto px-4 py-4 flex items-center justify-between">
        <!-- Logo -->
        <div class="flex items-center space-x-3">
            <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center shadow-lg transform hover:scale-110 transition-transform">
                <i class="fas fa-shopping-basket text-white text-lg"></i>
            </div>
            <div>
                <span class="text-2xl font-bold text-gray-800">PamilihanPasabuy</span>
                <div class="text-xs text-gray-500 leading-none">San Fernando Market</div>
            </div>
        </div>

        <!-- Desktop Navigation -->
        <div class="hidden lg:flex items-center space-x-8">
            <a href="#home" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">Home</a>
            <a href="#how-it-works" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">How It Works</a>
            <a href="#features" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">Features</a>
            <a href="#testimonials" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">Reviews</a>
            <a href="#about" class="nav-link text-gray-700 hover:text-green-600 transition-colors font-medium relative">About</a>
        </div>

        <!-- Action Buttons -->
        <div class="hidden lg:flex items-center space-x-3">
            <button class="px-3 py-2.5 text-gray-600 hover:text-green-600 border border-white rounded-xl transition-colors font-medium">Log In</button>
            <button class="px-6 py-2.5 bg-green-600 text-white rounded-xl hover:bg-green-700 transition-all duration-300 font-semibold shadow-lg hover:shadow-xl transform hover:scale-105">
                Sign Up Free
            </button>
        </div>

        <!-- Mobile Menu Button -->
        <button class="lg:hidden text-gray-700 p-2" onclick="toggleMobileMenu()">
            <i class="fas fa-bars text-xl"></i>
        </button>
    </nav>
</header>

<!-- Hero Section -->
<section id="home" 
         class="relative min-h-screen flex items-center justify-center bg-cover bg-center bg-no-repeat" 
         style="background-image: url('{{ asset('images/hero-bg.png') }}');">

    <div class="absolute inset-0 bg-black/60 z-0"></div>

    <div class="relative z-10 w-full px-4 py-20">
        <div class="max-w-5xl mx-auto text-center text-white">
            <h1 class="text-5xl lg:text-7xl font-bold mb-6 leading-tight drop-shadow-lg hero-title opacity-0 transform translate-y-8">
                Your 
                <span class="gradient-text">San Fernando Market</span>, 
                <br>Delivered 
                <span class="text-green-300">Fresh</span> 
                to Your Door
            </h1>
            <p class="text-xl lg:text-2xl text-gray-100 mb-8 leading-relaxed drop-shadow hero-subtitle opacity-0 transform translate-y-8">
                Experience the convenience of online grocery shopping while supporting 
                local businesses. Fresh produce, authentic flavors, delivered with care.
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-8 hero-buttons opacity-0 transform translate-y-8">
                <button class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 transform hover:scale-105 shadow-xl hover:shadow-2xl flex items-center justify-center">
                    <i class="fas fa-shopping-cart mr-3"></i>
                    Start Shopping Now
                </button>
                <button class="border-2 border-white text-white hover:bg-white hover:text-green-700 px-8 py-4 rounded-xl font-bold text-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-info-circle mr-3"></i>
                    Learn More
                </button>

            </div>
            <!-- Trust Indicators -->
            <div class="flex items-center justify-center space-x-6 text-sm text-white/80 hero-trust opacity-0 transform translate-y-8">
                <div class="flex items-center">
                    <i class="fas fa-shield-alt text-green-300 mr-2"></i>
                    100% Secure
                </div>
                <div class="flex items-center">
                    <i class="fas fa-truck text-green-300 mr-2"></i>
                    Same Day Delivery
                </div>
                <div class="flex items-center">
                    <i class="fas fa-leaf text-green-300 mr-2"></i>
                    Fresh Guarantee
                </div>
            </div>
        </div>
    </div>

    <!-- Scroll Indicator -->
    <div class="absolute bottom-8 left-1/2 transform -translate-x-1/2 animate-bounce z-10 hero-scroll opacity-0">
        <div class="w-6 h-10 border-2 border-white rounded-full flex justify-center">
            <div class="w-1 h-3 bg-white rounded-full mt-2 animate-pulse"></div>
        </div>
    </div>
</section>



    <!-- Stats Section -->
    <section class="py-16 bg-white animate-on-scroll">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-green-600 mb-2 stats-counter" data-count="10000">0</div>
                    <div class="text-gray-600 font-medium">Happy Customers</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-green-600 mb-2 stats-counter" data-count="500">0</div>
                    <div class="text-gray-600 font-medium">Local Vendors</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-green-600 mb-2 stats-counter" data-count="50000">0</div>
                    <div class="text-gray-600 font-medium">Orders Delivered</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl lg:text-5xl font-bold text-green-600 mb-2 stats-counter" data-count="98">0</div>
                    <div class="text-gray-600 font-medium">% Satisfaction</div>
                </div>
            </div>
        </div>
    </section>

    <!-- User Options Section -->
    <section class="py-20 bg-gradient-to-br from-gray-50 to-green-50 animate-on-scroll">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-users mr-2"></i>
                    Join Our Growing Community
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Choose Your Journey</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Whether you're shopping for fresh groceries, growing your business, or earning flexible income - we have the perfect path for you.</p>
            </div>

            <div class="grid lg:grid-cols-3 gap-8 max-w-7xl mx-auto">
                <!-- Customer Card -->
                <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-shopping-bag text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Shop as a Customer</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Browse thousands of fresh products from trusted local vendors. Get everything delivered fast and fresh to your doorstep.</p>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                500+ Fresh products daily
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Same-day delivery available
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                100% freshness guarantee
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Secure payment options
                            </li>
                        </ul>
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Start Shopping
                        </button>
                    </div>
                </div>

                <!-- Vendor Card -->
                <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border-2 border-green-200 relative overflow-hidden">
                    <div class="absolute top-0 right-0 bg-green-500 text-white px-4 py-2 text-sm font-semibold rounded-bl-2xl">
                        POPULAR
                    </div>
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-store text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Sell as a Vendor</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Transform your market stall into a digital storefront. Reach thousands of customers and grow your business exponentially.</p>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Easy online store setup
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Reach 10,000+ customers
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Real-time order management
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Weekly payouts
                            </li>
                        </ul>
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Become a Vendor
                        </button>
                    </div>
                </div>

                <!-- Rider Card -->
                <div class="group bg-white rounded-3xl p-8 shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-gray-100">
                    <div class="text-center">
                        <div class="w-24 h-24 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-6 group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-motorcycle text-white text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold text-gray-900 mb-4">Deliver as a Rider</h3>
                        <p class="text-gray-600 mb-6 leading-relaxed">Earn flexible income by connecting your community with fresh groceries. Be your own boss with competitive rates.</p>
                        <ul class="text-left space-y-3 mb-8">
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Flexible working hours
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Competitive delivery rates
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Weekly earnings
                            </li>
                            <li class="flex items-center text-gray-600">
                                <i class="fas fa-check text-green-500 mr-3"></i>
                                Fuel bonus incentives
                            </li>
                        </ul>
                        <button class="w-full bg-green-600 hover:bg-green-700 text-white py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            Become a Rider
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- How It Works Section -->
<section id="how-it-works" class="py-16 md:py-20 bg-white animate-on-scroll">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-12 md:mb-16">
            <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4 hover:bg-green-200 transition-colors duration-200">
                <i class="fas fa-cogs mr-2 text-green-600" aria-hidden="true"></i>
                Simple Process
            </div>
            <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 md:mb-6 leading-tight">
                Fresh Groceries in 3 Easy Steps
            </h2>
            <p class="text-lg md:text-xl text-gray-600 max-w-3xl mx-auto leading-relaxed px-4">
                From browsing to delivery, we've made grocery shopping as simple as 1-2-3. Experience the easiest way to get fresh market products.
            </p>
        </div>

        <div class="max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 md:gap-12">
                <!-- Step 1 -->
                <div class="text-center group relative">       
                    <div class="bg-white rounded-2xl p-6 md:p-8  hover:shadow-xl transition-all duration-300 border border-gray-50 hover:border-green-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-search text-green-600 text-3xl sm:text-4xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 md:mb-4">Browse & Select</h3>
                        <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm sm:text-base">
                            Explore our extensive catalog of fresh produce, meats, seafood, and local specialties from verified San Fernando Market vendors.
                        </p>
                        
                        <!-- Feature Tags -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex flex-wrap items-center justify-center gap-2 text-xs sm:text-sm text-gray-500">
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-apple-alt mr-1 text-green-500" aria-hidden="true"></i> 
                                    Fruits
                                </span>
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-carrot mr-1 text-orange-500" aria-hidden="true"></i> 
                                    Vegetables
                                </span>
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-fish mr-1 text-blue-500" aria-hidden="true"></i> 
                                    Seafood
                                </span>
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-drumstick-bite mr-1 text-red-500" aria-hidden="true"></i> 
                                    Meats
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Connection Line -->
                <div class="flex justify-center md:hidden">
                    <div class="w-1 h-8 bg-gradient-to-b from-green-400 to-green-600 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                </div>

                <!-- Step 2 -->
                <div class="text-center group relative">           
                    <div class="bg-white rounded-2xl p-6 md:p-8  hover:shadow-xl transition-all duration-300 border border-gray-50 hover:border-green-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-hands text-green-600 text-3xl sm:text-4xl" aria-hidden="true"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 md:mb-4">We Pick & Pack</h3>
                        <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm sm:text-base">
                            Our experienced vendors personally select and prepare your order, ensuring you get the freshest, highest-quality products available.
                        </p>
                        
                        <!-- Feature Tags -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex flex-wrap items-center justify-center gap-2 text-xs sm:text-sm text-gray-500">
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-eye mr-1 text-blue-500" aria-hidden="true"></i> 
                                    Quality Check
                                </span>
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-box mr-1 text-amber-500" aria-hidden="true"></i> 
                                    Careful Packing
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Connection Line -->
                <div class="flex justify-center md:hidden">
                    <div class="w-1 h-8 bg-gradient-to-b from-green-400 to-green-600 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-b from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                </div>

                <!-- Step 3 -->
                <div class="text-center group relative">      
                    <div class="bg-white rounded-2xl p-6 md:p-8  hover:shadow-xl transition-all duration-300 border border-gray-50 hover:border-green-200 hover:-translate-y-2">
                        <div class="relative mb-6">
                            <div class="w-20 h-20 sm:w-24 sm:h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto shadow-md group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-truck-fast text-green-600 text-3xl sm:text-4xl" aria-hidden="true"></i>
                            </div>                          
                        </div>
                        
                        <h3 class="text-xl sm:text-2xl font-bold text-gray-900 mb-3 md:mb-4">Fast, Local Delivery</h3>
                        <p class="text-gray-600 leading-relaxed mb-4 md:mb-6 text-sm sm:text-base">
                            Our local riders deliver your fresh groceries straight to your doorstep, maintaining the cold chain for optimal freshness.
                        </p>
                        
                        <!-- Feature Tags -->
                        <div class="bg-gray-50 rounded-xl p-4 border border-gray-100">
                            <div class="flex flex-wrap items-center justify-center gap-2 text-xs sm:text-sm text-gray-500">
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-clock mr-1 text-indigo-500" aria-hidden="true"></i> 
                                    Same Day
                                </span>
                                <span class="flex items-center bg-white px-3 py-1 rounded-full border">
                                    <i class="fas fa-thermometer-half mr-1 text-cyan-500" aria-hidden="true"></i> 
                                    Cold Chain
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Desktop Process Flow -->
            <div class="hidden md:flex justify-center mt-12 lg:mt-16">
                <div class="flex items-center space-x-4 lg:space-x-8">
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 text-white text-xl rounded-full font-bold shadow-lg">
                        1
                    </div>
                    <div class="h-1 w-12 lg:w-20 bg-gradient-to-r from-green-400 to-green-500 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 text-white text-xl rounded-full font-bold shadow-lg">
                        2
                    </div>
                    <div class="h-1 w-12 lg:w-20 bg-gradient-to-r from-green-400 to-green-500 rounded-full relative overflow-hidden">
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white to-transparent opacity-30 animate-pulse"></div>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 text-white text-xl rounded-full font-bold shadow-lg">
                        3
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gradient-to-br from-green-50 to-blue-50 animate-on-scroll">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-star mr-2"></i>
                    Why Choose Us
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Experience the Difference</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">We're more than just a delivery app - we're your bridge to authentic, fresh, and affordable market goods from the heart of San Fernando.</p>
            </div>

            <div class="max-w-7xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Features List -->
                    <div class="space-y-8">
                        <div class="flex items-start space-x-4 group">
                            <div class="flex-shrink-0 w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-600 transition-colors duration-300">
                                <i class="fas fa-heart text-green-600 group-hover:text-white text-2xl transition-colors duration-300"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Support Local Businesses</h3>
                                <p class="text-gray-600 leading-relaxed">Every purchase directly supports San Fernando Market vendors and their families, helping preserve our local economy and traditional market culture.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 group">
                            <div class="flex-shrink-0 w-16 h-16 bg-yellow-100 rounded-2xl flex items-center justify-center group-hover:bg-yellow-500 transition-colors duration-300">
                                <i class="fas fa-leaf text-yellow-600 group-hover:text-white text-2xl transition-colors duration-300"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Unbeatable Freshness</h3>
                                <p class="text-gray-600 leading-relaxed">Get the same quality you'd hand-pick yourself. Our vendors select only the freshest produce, meat, and seafood for your orders.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 group">
                            <div class="flex-shrink-0 w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-colors duration-300">
                                <i class="fas fa-clock text-blue-600 group-hover:text-white text-2xl transition-colors duration-300"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Ultimate Convenience</h3>
                                <p class="text-gray-600 leading-relaxed">Skip the crowds and parking hassles. Shop from home and have everything delivered fresh to your door within hours.</p>
                            </div>
                        </div>

                        <div class="flex items-start space-x-4 group">
                            <div class="flex-shrink-0 w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center group-hover:bg-purple-600 transition-colors duration-300">
                                <i class="fas fa-shield-alt text-purple-600 group-hover:text-white text-2xl transition-colors duration-300"></i>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 mb-3">Trusted & Secure</h3>
                                <p class="text-gray-600 leading-relaxed">Shop with confidence using our secure payment system and reliable delivery network. Your satisfaction is guaranteed.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Visual Element -->
                    <div class="relative">
                        <div class="relative z-10">
                            <div class="bg-gradient-to-br from-green-400 to-blue-500 rounded-3xl p-8 shadow-2xl">
                                <div class="bg-white rounded-2xl p-8">
                                    <div class="text-center mb-6">
                                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <i class="fas fa-shopping-basket text-green-600 text-3xl"></i>
                                        </div>
                                        <h4 class="text-2xl font-bold text-gray-900 mb-2">Market Fresh Daily</h4>
                                        <p class="text-gray-600">Connecting you to authentic market experience</p>
                                    </div>
                                    <div class="grid grid-cols-2 gap-4">
                                        <div class="bg-green-50 rounded-xl p-4 text-center">
                                            <div class="text-2xl font-bold text-green-600">500+</div>
                                            <div class="text-sm text-gray-600">Products</div>
                                        </div>
                                        <div class="bg-blue-50 rounded-xl p-4 text-center">
                                            <div class="text-2xl font-bold text-blue-600">2hrs</div>
                                            <div class="text-sm text-gray-600">Avg Delivery</div>
                                        </div>
                                        <div class="bg-yellow-50 rounded-xl p-4 text-center">
                                            <div class="text-2xl font-bold text-yellow-600">98%</div>
                                            <div class="text-sm text-gray-600">Fresh Rating</div>
                                        </div>
                                        <div class="bg-purple-50 rounded-xl p-4 text-center">
                                            <div class="text-2xl font-bold text-purple-600">24/7</div>
                                            <div class="text-sm text-gray-600">Support</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Floating decorations -->
                        <div class="absolute -top-6 -right-6 w-24 h-24 bg-yellow-400 rounded-full opacity-20 animate-float"></div>
                        <div class="absolute -bottom-6 -left-6 w-32 h-32 bg-green-400 rounded-full opacity-15 animate-float" style="animation-delay: -1s;"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 bg-white animate-on-scroll">
        <div class="container mx-auto px-4">
            <div class="text-center mb-16">
                <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-quote-left mr-2"></i>
                    Customer Stories
                </div>
                <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">What Our Community Says</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">Real experiences from real customers who've made PamilihanPasabuy part of their daily routine.</p>
            </div>

            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-3 gap-8">
                    <!-- Testimonial 1 -->
                    <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-green-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                M
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Maria Santos</div>
                                <div class="text-gray-600 text-sm">Busy Mom, San Fernando</div>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 leading-relaxed italic">"PamilihanPasabuy has been a lifesaver! I can get all my fresh groceries delivered while managing my kids. The produce is always fresh, and I love supporting our local vendors."</p>
                    </div>

                    <!-- Testimonial 2 -->
                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-blue-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                J
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Juan Dela Cruz</div>
                                <div class="text-gray-600 text-sm">Vendor, San Fernando Market</div>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 leading-relaxed italic">"As a vendor, this platform doubled my sales! I can now reach customers beyond the physical market. The system is easy to use and payments are always on time."</p>
                    </div>

                    <!-- Testimonial 3 -->
                    <div class="bg-gradient-to-br from-yellow-50 to-yellow-100 rounded-3xl p-8 shadow-lg hover:shadow-xl transition-shadow duration-300">
                        <div class="flex items-center mb-6">
                            <div class="w-16 h-16 bg-yellow-600 rounded-full flex items-center justify-center text-white font-bold text-xl mr-4">
                                A
                            </div>
                            <div>
                                <div class="font-bold text-gray-900">Anna Reyes</div>
                                <div class="text-gray-600 text-sm">Delivery Rider</div>
                            </div>
                        </div>
                        <div class="flex text-yellow-400 mb-4">
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p class="text-gray-700 leading-relaxed italic">"Working as a rider gives me flexible income while helping my community. The app is user-friendly and the support team is always there when I need help."</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-20 bg-gradient-to-br from-gray-50 to-green-50 animate-on-scroll">
        <div class="container mx-auto px-4">
            <div class="max-w-6xl mx-auto">
                <div class="grid lg:grid-cols-2 gap-16 items-center">
                    <!-- Content -->
                    <div>
                        <div class="inline-flex items-center px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold mb-6">
                            <i class="fas fa-info-circle mr-2"></i>
                            Our Story
                        </div>
                        <h2 class="text-4xl lg:text-5xl font-bold text-gray-900 mb-6">Connecting Community Through Fresh Food</h2>
                        <p class="text-xl text-gray-600 mb-8 leading-relaxed">
                            Born from a deep love for San Fernando's rich market tradition, PamilihanPasabuy bridges the gap between time-honored vendors and modern convenience. We believe that fresh, quality food should be accessible to everyone, while preserving the livelihoods of our local market heroes.
                        </p>
                        <div class="space-y-4 mb-8">
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Supporting 500+ local vendors and their families</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Serving 10,000+ happy customers daily</span>
                            </div>
                            <div class="flex items-center space-x-3">
                                <div class="w-6 h-6 bg-green-600 rounded-full flex items-center justify-center">
                                    <i class="fas fa-check text-white text-sm"></i>
                                </div>
                                <span class="text-gray-700 font-medium">Committed to sustainable, eco-friendly practices</span>
                            </div>
                        </div>
                        <button class="bg-green-600 hover:bg-green-700 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 transform hover:scale-105 shadow-lg">
                            <i class="fas fa-users mr-2"></i>
                            Join Our Mission
                        </button>
                    </div>

                    <!-- Visual -->
                    <div class="relative">
                        <div class="bg-gradient-to-br from-green-600 to-green-700 rounded-3xl p-8 shadow-2xl">
                            <div class="text-center text-white">
                                <i class="fas fa-handshake text-6xl mb-6 opacity-80"></i>
                                <h3 class="text-2xl font-bold mb-4">Building Stronger Communities</h3>
                                <p class="text-green-100 mb-6">Every order creates a ripple effect of positive impact throughout San Fernando</p>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                        <div class="text-2xl font-bold">₱2M+</div>
                                        <div class="text-sm text-green-100">Vendor Earnings</div>
                                    </div>
                                    <div class="bg-white bg-opacity-20 rounded-xl p-4">
                                        <div class="text-2xl font-bold">50K+</div>
                                        <div class="text-sm text-green-100">Orders Delivered</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Decorative elements -->
                        <div class="absolute -top-4 -right-4 w-20 h-20 bg-yellow-400 rounded-full opacity-30 animate-pulse-slow"></div>
                        <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-blue-400 rounded-full opacity-20 animate-bounce-gentle"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Final CTA Section -->
    <section class="py-20 bg-gradient-to-r from-green-600 to-green-700 animate-on-scroll">
        <div class="container mx-auto px-4 text-center">
            <div class="max-w-4xl mx-auto">
                <h2 class="text-4xl lg:text-6xl font-bold text-white mb-6">Ready to Get Started?</h2>
                <p class="text-2xl text-green-100 mb-8 leading-relaxed">Join thousands of satisfied customers who've made the switch to convenient, fresh grocery delivery.</p>
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-8">
                    <button class="bg-white text-green-600 hover:bg-gray-100 px-10 py-5 rounded-xl font-bold text-xl transition-all duration-300 transform hover:scale-105 shadow-xl">
                        <i class="fas fa-user-plus mr-3"></i>
                        Create Your Free Account
                    </button>
                    <button class="border-2 border-white text-white hover:bg-white hover:text-green-600 px-10 py-5 rounded-xl font-bold text-xl transition-all duration-300">
                        <i class="fas fa-phone mr-3"></i>
                        Contact Us
                    </button>
                </div>
                <div class="flex items-center justify-center space-x-8 text-green-100">
                    <div class="flex items-center">
                        <i class="fas fa-mobile-alt mr-2"></i>
                        <span>Mobile Friendly</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-lock mr-2"></i>
                        <span>100% Secure</span>
                    </div>
                    <div class="flex items-center">
                        <i class="fas fa-clock mr-2"></i>
                        <span>24/7 Support</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-16">
        <div class="container mx-auto px-4">
            <div class="grid lg:grid-cols-4 md:grid-cols-2 gap-8 mb-12">
                <!-- Company Info -->
                <div>
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center">
                            <i class="fas fa-shopping-basket text-white text-lg"></i>
                        </div>
                        <div>
                            <span class="text-2xl font-bold">PamilihanPasabuy</span>
                            <div class="text-xs text-gray-400">San Fernando Market</div>
                        </div>
                    </div>
                    <p class="text-gray-400 mb-6 leading-relaxed">Connecting San Fernando's rich market tradition with modern convenience. Fresh groceries delivered with care.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-green-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-green-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-green-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="#" class="w-10 h-10 bg-gray-800 hover:bg-green-600 rounded-full flex items-center justify-center transition-colors">
                            <i class="fab fa-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div>
                    <h3 class="text-xl font-bold mb-6">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="#home" class="text-gray-400 hover:text-green-400 transition-colors">Home</a></li>
                        <li><a href="#how-it-works" class="text-gray-400 hover:text-green-400 transition-colors">How It Works</a></li>
                        <li><a href="#features" class="text-gray-400 hover:text-green-400 transition-colors">Features</a></li>
                        <li><a href="#about" class="text-gray-400 hover:text-green-400 transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Contact</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Help Center</a></li>
                    </ul>
                </div>

                <!-- For Partners -->
                <div>
                    <h3 class="text-xl font-bold mb-6">For Partners</h3>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Become a Vendor</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Become a Rider</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Vendor Portal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Rider Portal</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Partner Resources</a></li>
                        <li><a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Commission Structure</a></li>
                    </ul>
                </div>

                <!-- Contact Info -->
                <div>
                    <h3 class="text-xl font-bold mb-6">Contact Info</h3>
                    <div class="space-y-4">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-map-marker-alt text-green-400 mt-1"></i>
                            <div class="text-gray-400">
                                <div>New San Fernando Public Market</div>
                                <div>San Fernando, Pampanga</div>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-phone text-green-400"></i>
                            <span class="text-gray-400">+63 917 123 4567</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-envelope text-green-400"></i>
                            <span class="text-gray-400">hello@pamilihan.ph</span>
                        </div>
                        <div class="flex items-center space-x-3">
                            <i class="fas fa-clock text-green-400"></i>
                            <span class="text-gray-400">24/7 Customer Support</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="border-t border-gray-800 pt-8">
                <div class="flex flex-col lg:flex-row justify-between items-center space-y-4 lg:space-y-0">
                    <div class="text-gray-400 text-center lg:text-left">
                        <p>&copy; 2024 PamilihanPasabuy. All rights reserved.</p>
                    </div>
                    <div class="flex flex-wrap justify-center lg:justify-end space-x-6 text-sm">
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Privacy Policy</a>
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Terms of Service</a>
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Cookie Policy</a>
                        <a href="#" class="text-gray-400 hover:text-green-400 transition-colors">Legal</a>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile Menu Toggle
        function toggleMobileMenu() {
            const mobileMenu = document.getElementById('mobile-menu');
            mobileMenu.classList.toggle('hidden');
        }

        // Header Background Change on Scroll
        const headerTop = document.getElementById('header-top');
        const headerScroll = document.getElementById('header-scroll');
        const hero = document.getElementById('home');

        function toggleHeaders() {
            if (window.scrollY < hero.offsetHeight - headerTop.offsetHeight) {
                headerTop.style.display = '';
                headerScroll.style.display = 'none';
            } else {
                headerTop.style.display = 'none';
                headerScroll.style.display = '';
            }
        }

        window.addEventListener('scroll', toggleHeaders);
        window.addEventListener('resize', toggleHeaders); // Also update on resize
        toggleHeaders(); // Run on load

        // Smooth Scrolling for Navigation Links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                const targetElement = document.getElementById(targetId);
                if (targetElement) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
                // Close mobile menu if open
                const mobileMenu = document.getElementById('mobile-menu');
                mobileMenu.classList.add('hidden');
            });
        });

        // Animation on Scroll
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animated');
                }
            });
        }, observerOptions);

        document.querySelectorAll('.animate-on-scroll').forEach(el => {
            observer.observe(el);
        });

        // Stats Counter Animation
        function animateCounters() {
            const counters = document.querySelectorAll('.stats-counter');
            const speed = 200;

            counters.forEach(counter => {
                const updateCount = () => {
                    const target = +counter.getAttribute('data-count');
                    const count = +counter.innerText;
                    const inc = target / speed;

                    if (count < target) {
                        counter.innerText = Math.ceil(count + inc);
                        setTimeout(updateCount, 1);
                    } else {
                        counter.innerText = target;
                    }
                };
                updateCount();
            });
        }

        // Initialize animations when page loads
        document.addEventListener('DOMContentLoaded', function() {
            // Hero entrance animations
            function triggerHeroAnimations() {
                const heroTitle = document.querySelector('.hero-title');
                const heroSubtitle = document.querySelector('.hero-subtitle');
                const heroButtons = document.querySelector('.hero-buttons');
                const heroTrust = document.querySelector('.hero-trust');
                const heroScroll = document.querySelector('.hero-scroll');
                
                // Trigger animations in sequence
                setTimeout(() => heroTitle.classList.add('animate-in'), 100);
                setTimeout(() => heroSubtitle.classList.add('animate-in'), 400);
                setTimeout(() => heroButtons.classList.add('animate-in'), 700);
                setTimeout(() => heroTrust.classList.add('animate-in'), 1000);
                setTimeout(() => heroScroll.classList.add('animate-in'), 1300);
            }
            
            // Trigger hero animations after a short delay
            setTimeout(triggerHeroAnimations, 200);
            
            // Animate counters when stats section comes into view
            const statsSection = document.querySelector('.animate-on-scroll');
            if (statsSection) {
                const statsObserver = new IntersectionObserver(function(entries) {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            animateCounters();
                            statsObserver.unobserve(entry.target);
                        }
                    });
                }, { threshold: 0.5 });

                statsObserver.observe(statsSection);
            }

            // Add active state to navigation links
            const navLinks = document.querySelectorAll('.nav-link');
            const sections = document.querySelectorAll('section[id]');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    const sectionHeight = section.clientHeight;
                    if (scrollY >= (sectionTop - 200)) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('text-green-600');
                    if (link.getAttribute('href') === `#${current}`) {
                        link.classList.add('text-green-600');
                    }
                });
            });

            // Add hover effects to buttons
            const buttons = document.querySelectorAll('button');
            buttons.forEach(button => {
                button.addEventListener('mouseenter', function() {
                    this.style.transform = 'translateY(-2px)';
                });
                button.addEventListener('mouseleave', function() {
                    this.style.transform = 'translateY(0)';
                });
            });
        });

        // Add smooth reveal animations for sections
        function revealOnScroll() {
            const reveals = document.querySelectorAll('.animate-on-scroll');
            reveals.forEach(element => {
                const windowHeight = window.innerHeight;
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    element.classList.add('animated');
                }
            });
        }

        window.addEventListener('scroll', revealOnScroll);
        revealOnScroll(); // Call once on load

        // Add parallax effect to hero section
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallax = document.querySelector('.absolute.top-10.left-10');
            const parallax2 = document.querySelector('.absolute.bottom-10.right-10');
            
            if (parallax) {
                parallax.style.transform = `translateY(${scrolled * 0.5}px)`;
            }
            if (parallax2) {
                parallax2.style.transform = `translateY(${scrolled * 0.3}px)`;
            }
        });

        

        // Add form validation for any future forms
        function validateForm(form) {
            const inputs = form.querySelectorAll('input[required], textarea[required]');
            let isValid = true;
            
            inputs.forEach(input => {
                if (!input.value.trim()) {
                    input.classList.add('border-red-500');
                    isValid = false;
                } else {
                    input.classList.remove('border-red-500');
                }
            });
            
            return isValid;
        }

        // Add lazy loading for images (if any are added later)
        if ('IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const img = entry.target;
                        img.src = img.dataset.src;
                        img.classList.remove('lazy');
                        imageObserver.unobserve(img);
                    }
                });
            });

            document.querySelectorAll('img[data-src]').forEach(img => {
                imageObserver.observe(img);
            });
        }
    </script>
</body>
</html>