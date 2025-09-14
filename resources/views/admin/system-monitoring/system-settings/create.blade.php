@extends('layout.admin')


@section('content')
<div class="p-6 max-w-lg mx-auto">
<h1 class="text-2xl font-bold mb-4">Add New Setting</h1>
<form method="POST" action="{{ route('system-settings.store') }}" class="space-y-4">
@csrf


<div>
<label class="block font-medium">Key</label>
<input type="text" name="setting_key" class="w-full border rounded-xl px-4 py-2" required>
</div>


<div>
<label class="block font-medium">Value</label>
<textarea name="setting_value" class="w-full border rounded-xl px-4 py-2"></textarea>
</div>


<div>
<label class="block font-medium">Description</label>
<textarea name="description" class="w-full border rounded-xl px-4 py-2"></textarea>
</div>


<button type="submit" class="bg-green-600 text-white px-6 py-2 rounded-xl hover:bg-green-700">Save</button>
</form>
</div>
@endsection