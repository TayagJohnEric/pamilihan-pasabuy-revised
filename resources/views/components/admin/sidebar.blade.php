   <!-- Mobile Sidebar -->
        <div id="sidebar" class="sidebar-transition sidebar-mobile-closed md:sidebar-mobile-open fixed md:static top-0 left-0 z-50 w-64 h-full bg-white text-gray-700 shadow-lg md:shadow-sm">
            <!-- Sidebar Header -->
            <div class="flex items-center justify-between h-16 px-4">
                <h2 class="text-xl font-bold text-gray-800">PamilihanPasabuy</h2>
                 <span class="text-xs text-gray-500 absolute bottom-1">Admin Portal</span>
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

                    <!-- User Management -->
                    <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
                                    </svg>    
                                    <span class="ml-3 text-sm">User Management</span>
                                </div>
                                <svg class="dropdown-arrow w-4 h-4 text-green-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-content ml-8 mt-1">
                                <ul class="space-y-1">
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">All Users</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Rider Application</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Vendor Application</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                    <!-- Platform Operation -->
                    <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    <span class="ml-3 text-sm">Platform Operations</span>
                                </div>
                                <svg class="dropdown-arrow w-4 h-4 text-green-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-content ml-8 mt-1">
                                <ul class="space-y-1">
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Categories</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Vendors</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Products</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Delivery Zones</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Orders</a></li>

                                </ul>
                            </div>
                        </div>
                    </li>

                    <!-- Financial Management -->
                   <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    <span class="ml-3 text-sm">Financial</span>
                                </div>
                                <svg class="dropdown-arrow w-4 h-4 text-green-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-content ml-8 mt-1">
                                <ul class="space-y-1">
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Payment Transaction</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Rider Payouts</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Vendor Payouts</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                      <!-- System and Monitoring -->
                   <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
                                    </svg>
                                    <span class="ml-3 text-sm">System & Monitoring</span>
                                </div>
                                <svg class="dropdown-arrow w-4 h-4 text-green-500 group-hover:text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div class="dropdown-content ml-8 mt-1">
                                <ul class="space-y-1">
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">System Settings</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Ratings & Feedback</a></li>
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Noification Log</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>

                     <!-- My Account -->
                   <li>
                        <div class="dropdown-item">
                            <button class="dropdown-toggle group flex items-center justify-between w-full font-semibold p-3 rounded-lg hover:bg-green-500 hover:text-white transition-all duration-200">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-green-500 group-hover:text-white transition-colors">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 21v-7.5a.75.75 0 0 1 .75-.75h3a.75.75 0 0 1 .75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349M3.75 21V9.349m0 0a3.001 3.001 0 0 0 3.75-.615A2.993 2.993 0 0 0 9.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 0 0 2.25 1.016c.896 0 1.7-.393 2.25-1.015a3.001 3.001 0 0 0 3.75.614m-16.5 0a3.004 3.004 0 0 1-.621-4.72l1.189-1.19A1.5 1.5 0 0 1 5.378 3h13.243a1.5 1.5 0 0 1 1.06.44l1.19 1.189a3 3 0 0 1-.621 4.72M6.75 18h3.75a.75.75 0 0 0 .75-.75V13.5a.75.75 0 0 0-.75-.75H6.75a.75.75 0 0 0-.75.75v3.75c0 .414.336.75.75.75Z" />
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
                                    <li><a href="#" class="block p-2 text-sm text-gray-600 hover:text-green-500 hover:bg-green-50 rounded-md transition-colors">Change Password</a></li>
                                </ul>
                            </div>
                        </div>
                    </li>
                </ul>
            </nav>
        </div>