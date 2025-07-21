<!-- Notification Icon Wrapper -->
<div class="relative">
    <!-- Notification Button -->
    <button id="notification-button" class="p-2 rounded-full border-2 border-gray-200 bg-white hover:bg-gray-50 shadow-sm transition-colors focus:outline-none focus:ring-2 focus:ring-green-200">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="h-6 w-6 text-gray-500 hover:text-gray-600">
            <path d="M10.268 21a2 2 0 0 0 3.464 0"/>
            <path d="M3.262 15.326A1 1 0 0 0 4 17h16a1 1 0 0 0 .74-1.673C19.41 13.956 18 12.499 18 8A6 6 0 0 0 6 8c0 4.499-1.411 5.956-2.738 7.326"/>
        </svg>
        <!-- Badge -->
        <span class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center font-medium">2</span>
    </button>

    <!-- Notification Dropdown -->
    <div id="notification-dropdown" class="hidden fixed inset-0 z-50 bg-white md:absolute md:inset-auto md:mt-2 md:w-80 md:max-w-[90vw] md:right-0 md:rounded-lg md:shadow-lg md:border md:border-gray-200">
        <div class="flex justify-between items-center p-4 border-b border-gray-200 md:rounded-t-lg">
            <h3 class="text-lg font-semibold text-gray-800">Notifications</h3>
            <button id="close-notification-dropdown" class="text-gray-500 hover:text-gray-700 md:hidden">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="max-h-[calc(100vh-160px)] overflow-y-auto">
            <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Order Confirmed</p>
                        <p class="text-sm text-gray-600">Your order #12345 has been confirmed</p>
                        <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                    </div>
                </div>
            </div>
            <div class="p-4 border-b border-gray-100 hover:bg-gray-50">
                <div class="flex items-start space-x-3">
                    <div class="w-2 h-2 bg-green-500 rounded-full mt-2 flex-shrink-0"></div>
                    <div class="flex-1">
                        <p class="text-sm font-medium text-gray-800">Delivery Update</p>
                        <p class="text-sm text-gray-600">Your package is out for delivery</p>
                        <p class="text-xs text-gray-400 mt-1">5 hours ago</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 border-t border-gray-200">
            <button class="w-full text-center text-sm text-green-600 hover:text-green-700 font-medium">View All Notifications</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const notificationButton = document.getElementById('notification-button');
        const notificationDropdown = document.getElementById('notification-dropdown');
        const closeNotificationDropdown = document.getElementById('close-notification-dropdown');

        

        closeNotificationDropdown?.addEventListener('click', function () {
            notificationDropdown?.classList.add('hidden');
        });

</script>
