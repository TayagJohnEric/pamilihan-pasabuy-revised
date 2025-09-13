@extends('layout.admin')


@section('title', 'Payments Management')


@section('content')
<div class="max-w-[90rem] mx-auto">
<div class="bg-white rounded-lg shadow p-6">
<h2 class="text-2xl font-bold text-gray-800 mb-4">Payments</h2>
<p class="text-gray-600 mb-4">Manage and filter all payments.</p>


<!-- Filters -->
<form method="GET" action="{{ route('admin.payments.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
<div>
<label class="block text-gray-700 mb-1">Payment Method</label>
<select name="payment_method_used" class="w-full border-gray-300 rounded-md shadow-sm">
<option value="">All</option>
<option value="online_payment" {{ request('payment_method_used') === 'online_payment' ? 'selected' : '' }}>Online Payment</option>
<option value="cod" {{ request('payment_method_used') === 'cod' ? 'selected' : '' }}>Cash on Delivery</option>
</select>
</div>
<div>
<label class="block text-gray-700 mb-1">Status</label>
<select name="status" class="w-full border-gray-300 rounded-md shadow-sm">
<option value="">All</option>
<option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
<option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
<option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>Failed</option>
<option value="refunded" {{ request('status') === 'refunded' ? 'selected' : '' }}>Refunded</option>
</select>
</div>
<div class="flex items-end">
<button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg shadow hover:bg-blue-700">Filter</button>
<a href="{{ route('admin.payments.index') }}" class="ml-2 px-4 py-2 border rounded-lg text-gray-600 hover:bg-gray-100">Reset</a>
</div>
</form>


<!-- Payments Table -->
<div class="overflow-x-auto">
<table class="min-w-full divide-y divide-gray-200">
<thead class="bg-gray-50">
<tr>
<th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Order ID</th>
<th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Amount Paid</th>
<th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Method</th>
<th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Status</th>
<th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Processed At</th>
<th class="px-4 py-2 text-left text-sm font-semibold text-gray-600">Actions</th>
</tr>
</thead>
<tbody class="bg-white divide-y divide-gray-200">
@forelse($payments as $payment)
<tr>
<td class="px-4 py-2 text-gray-700">{{ $payment->order_id }}</td>
<td class="px-4 py-2 text-gray-700">₱{{ number_format($payment->amount_paid, 2) }}</td>
<td class="px-4 py-2 text-gray-700 capitalize">{{ str_replace('_', ' ', $payment->payment_method_used) }}</td>
<td class="px-4 py-2">
<span class="px-2 py-1 text-xs font-semibold rounded-full
@if($payment->status === 'completed') bg-green-100 text-green-700
@elseif($payment->status === 'pending') bg-yellow-100 text-yellow-700
@elseif($payment->status === 'failed') bg-red-100 text-red-700
@elseif($payment->status === 'refunded') bg-gray-100 text-gray-700
@endif">
{{ ucfirst($payment->status) }}
</span>
</td>
<td class="px-4 py-2 text-gray-700">{{ $payment->payment_processed_at ? $payment->payment_processed_at->format('Y-m-d H:i') : 'N/A' }}</td>
<td class="px-4 py-2">
<a href="#" class="text-blue-600 hover:underline">View</a>
</td>
</tr>
@empty
<tr>
<td colspan="6" class="px-4 py-4 text-center text-gray-500">No payments found.</td>
</tr>
@endforelse
</tbody>
</table>
</div>


<!-- Pagination -->
<div class="mt-4">
{{ $payments->links() }}
</div>
</div>
</div>
@endsection