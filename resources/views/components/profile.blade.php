<!-- Profile Section -->
                        <div class="relative">
                            <button id="profile-menu-button" class="flex items-center space-x-2 p-2 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none focus:ring-2 focus:ring-green-200">
                                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center border-2 border-gray-200 shadow-sm">
                                    <span class="text-white text-sm font-medium">JD</span>
                                </div>
                                <span class="hidden md:block text-sm font-medium text-gray-700">{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</span>
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            
                            <!-- Profile Dropdown Menu -->
                            <div id="profile-dropdown" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-50">
                                <div class="py-1">
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">Settings</a>
                                    <a href="#" class="block px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600">Help</a>
                                    <hr class="my-1 border-gray-200">
                                    <a href="" class="block px-4 py-2 text-sm text-red-600 hover:bg-red-50">Sign Out</a>
                                </div>
                            </div>
                        </div>