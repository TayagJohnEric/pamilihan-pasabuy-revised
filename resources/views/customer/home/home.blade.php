@extends('layout.customer')

@section('title', 'Customer Dashboard')

@section('content')

 <!-- Sample Content -->
                <div class="max-w-7xl mx-auto">
                    <div class="bg-white rounded-lg shadow p-6">
                        <h2 class="text-2xl font-bold text-gray-800 mb-4">Welcome to Your Dashboard</h2>
                        <p class="text-gray-600 mb-4">This is your customer dashboard with a responsive sidebar that includes smooth transitions.</p>
                        
                        <!-- Demo Cards -->
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
                            <div class="bg-gradient-to-r from-green-500 to-green-600 text-white p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Total Orders</h3>
                                <p class="text-3xl font-bold">24</p>
                            </div>
                            <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Active Cart Items</h3>
                                <p class="text-3xl font-bold">8</p>
                            </div>
                            <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white p-6 rounded-lg">
                                <h3 class="text-lg font-semibold mb-2">Notifications</h3>
                                <p class="text-3xl font-bold">3</p>
                            </div>
                        </div>
                    </div>
                </div>

@endsection