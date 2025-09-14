@extends('layout.admin')


@section('content')
<div class="p-6">
<h1 class="text-2xl font-bold mb-4">System Settings</h1>


@if(session('success'))
<div class="mb-4 p-4 bg-green-100 text-green-700 rounded-xl">{{ session('success') }}</div>
@endif


<div class="flex justify-between items-center mb-4">
<form method="GET" action="{{ route('system-settings.index') }}" class="flex">
<input type="text" name="search" value="{{ $search }}" placeholder="Search..." class="px-4 py-2 border rounded-l-xl focus:outline-none">
<button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-r-xl hover:bg-green-700">Search</button>
</form>
<a href="{{ route('system-settings.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-xl hover:bg-green-700">Add Setting</a>
</div>


<div class="overflow-x-auto">
<table class="w-full border border-gray-200 rounded-xl overflow-hidden">
<thead class="bg-gray-100">
<tr>
<th class="p-3 text-left">Key</th>
<th class="p-3 text-left">Value</th>
<th class="p-3 text-left">Description</th>
<th class="p-3 text-center">Actions</th>
</tr>
</thead>
<tbody>
@forelse($settings as $setting)
<tr class="border-t hover:bg-gray-50">
<td class="p-3">{{ $setting->setting_key }}</td>
<td class="p-3">{{ $setting->setting_value }}</td>
<td class="p-3">{{ $setting->description }}</td>
<td class="p-3 text-center flex justify-center gap-2">
<a href="{{ route('system-settings.edit', $setting) }}" class="bg-blue-500 text-white px-3 py-1 rounded-lg hover:bg-blue-600">Edit</a>
<form action="{{ route('system-settings.destroy', $setting) }}" method="POST" onsubmit="return confirm('Are you sure?')">
@csrf
@method('DELETE')
<button type="submit" class="bg-red-500 text-white px-3 py-1 rounded-lg hover:bg-red-600">Delete</button>
</form>
</td>
</tr>
@empty
<tr>
<td colspan="4" class="p-3 text-center text-gray-500">No settings found.</td>
</tr>
@endforelse
</tbody>
</table>
</div>


<div class="mt-4">{{ $settings->links() }}</div>
</div>
@endsection