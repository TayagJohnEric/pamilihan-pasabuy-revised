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
                        <a href="{{ route('customer.dashboard') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-house-icon lucide-house"><path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8"/><path d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/></svg>
                            <span class="ml-3 text-sm">Home</span>
                        </a>
                    </li>


                    <!-- Shop  -->
                   <li>
                        <a href="{{ route('products.index') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-store-icon lucide-store"><path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/></svg>
                            <span class="ml-3 text-sm">Shop</span>
                        </a>
                    </li>

                    <!-- My Orders -->
                    <li>
                        <a href="{{ route('customer.orders.index') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors  lucide lucide-list-ordered-icon lucide-list-ordered"><path d="M10 12h11"/><path d="M10 18h11"/><path d="M10 6h11"/><path d="M4 10h2"/><path d="M4 6h1v4"/><path d="M6 18H4c0-1 2-2 2-3s-1-1.5-2-1"/></svg>                   
                            <span class="ml-3 text-sm">My Orders</span>
                        </a>
                    </li>

                    <!-- Shopping Cart -->
                    <li>
                        <a href="{{ route('cart.index') }}" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                           <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors  lucide lucide-shopping-cart-icon lucide-shopping-cart"><circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/></svg>
                            <span class="ml-3 text-sm">Shopping Cart</span>
                        </a>
                    </li>

                    <!-- Notifications -->
                    <li>
                        <a href="#" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                          <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-bell-icon lucide-bell"><path d="M10.268 21a2 2 0 0 0 3.464 0"/><path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/></svg>
                            <span class="ml-3 text-sm">Notifications</span>
                        </a>
                    </li>

                      <!-- My Account Dropdown -->
                    <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors lucide lucide-circle-user-icon lucide-circle-user"><circle cx="12" cy="12" r="10"/><circle cx="12" cy="10" r="3"/><path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662"/></svg>
                                    <span class="ml-3 text-sm">My Account</span>
                                </div>
                                <svg class="dropdown-arrow w-4 h-4 text-green-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-content ml-8 mt-1">
                                <ul class="space-y-1">
                                    <li><a href="{{ route('customer.profile.show') }}" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Profile</a></li>
                                    <li><a href=" {{ route('customer.saved_addresses.index') }}" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Saved Addresses</a></li>
                                    <li><a href="{{ route('customer.password.edit') }}" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Change Password</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>


