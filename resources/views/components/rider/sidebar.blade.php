   <!-- Mobile Sidebar -->
        <div id="sidebar" class="sidebar-transition sidebar-mobile-closed md:sidebar-mobile-open fixed md:static top-0 left-0 z-50 w-64 h-full bg-white text-gray-700 shadow-lg md:shadow-sm">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-4">
                <h2 class="text-xl font-bold text-gray-800">PamilihanPasabuy</h2>
                <!-- Close button (mobile only) -->
                <button id="sidebar-close" class="md:hidden p-2 rounded-lg text-gray-500 hover:text-red-500 hover:bg-red-50 transition-colors focus:outline-none focus:ring-2 focus:ring-red-200">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>
            
            <!-- Sidebar Navigation -->
            <nav class="flex-1 overflow-y-auto py-4">
                <ul class="space-y-2 px-4">
                    <!-- Dashboard -->
                    <li>
                        <a href="{{ route('rider.dashboard') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            <span class="ml-3 text-sm">Dashboard</span>
                        </a>
                    </li>

                    <!-- Profile -->
                      <li>
                        <a href="{{ route('rider.profile.show') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-user-icon lucide-user"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                            <span class="ml-3 text-sm">My Profile</span>
                        </a>
                    </li>

                    <!-- Deliveries -->
                    <li>
                        <a href="#" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-truck-icon lucide-truck"><path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/></svg>
                            <span class="ml-3 text-sm">My Deliveries</span>
                        </a>
                    </li>

                    <!-- Ratings -->
                     <li>
                        <a href="{{ route('rider.ratings') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-star-icon lucide-star"><path d="M11.525 2.295a.53.53 0 0 1 .95 0l2.31 4.679a2.123 2.123 0 0 0 1.595 1.16l5.166.756a.53.53 0 0 1 .294.904l-3.736 3.638a2.123 2.123 0 0 0-.611 1.878l.882 5.14a.53.53 0 0 1-.771.56l-4.618-2.428a2.122 2.122 0 0 0-1.973 0L6.396 21.01a.53.53 0 0 1-.77-.56l.881-5.139a2.122 2.122 0 0 0-.611-1.879L2.16 9.795a.53.53 0 0 1 .294-.906l5.165-.755a2.122 2.122 0 0 0 1.597-1.16z"/></svg>
                            <span class="ml-3 text-sm">My Ratings</span>
                        </a>
                    </li>

                    <!-- Earniing and Payouts -->
                    <li>
                        <a href="{{ route('rider.earnings') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-chart-line-icon lucide-chart-line"><path d="M3 3v16a2 2 0 0 0 2 2h16"/><path d="m19 9-5 5-4-4-3 3"/></svg>
                            <span class="ml-3 text-sm">Earning & Payouts</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>