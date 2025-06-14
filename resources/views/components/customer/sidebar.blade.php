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
                        <a href="#" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 12 8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25" />
                            </svg>
                            <span class="ml-3 text-sm">Dashboard</span>
                        </a>
                    </li>

                    <!-- My Account Dropdown -->
                    <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>    
                                    <span class="ml-3 text-sm">My Account</span>
                                </div>
                                <svg class="dropdown-arrow w-4 h-4 text-green-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-content ml-8 mt-1">
                                <ul class="space-y-1">
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Profile</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Saved Addresses</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Change Password</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <!-- Shop Dropdown -->
                    <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    <span class="ml-3 text-sm">Shop</span>
                                </div>
                                <svg class="dropdown-arrow w-4 h-4 text-green-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-content ml-8 mt-1">
                                <ul class="space-y-1">
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Browse Products</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Categories</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Vendors</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <!-- My Orders -->
                    <li>
                        <a href="#" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z" />
                            </svg>                      
                            <span class="ml-3 text-sm">My Orders</span>
                        </a>
                    </li>

                    <!-- Shopping Cart -->
                    <li>
                        <a href="#" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 10.5V6a3.75 3.75 0 1 0-7.5 0v4.5m11.356-1.993 1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 0 1-1.12-1.243l1.264-12A1.125 1.125 0 0 1 5.513 7.5h12.974c.576 0 1.059.435 1.119 1.007ZM8.625 10.5a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm7.5 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <span class="ml-3 text-sm">Shopping Cart</span>
                        </a>
                    </li>

                    <!-- Notifications -->
                    <li>
                        <a href="#" class="group flex items-center font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                            </svg>
                            <span class="ml-3 text-sm">Notifications</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>