<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SystemSetting;

class AdminSystemSettingController extends Controller
{
    public function index(Request $request)
{
$search = $request->input('search');
$settings = SystemSetting::when($search, function ($query, $search) {
return $query->where('setting_key', 'like', "%{$search}%")
->orWhere('setting_value', 'like', "%{$search}%")
->orWhere('description', 'like', "%{$search}%");
})->paginate(10);


return view('admin.system-monitoring.system-settings.index', compact('settings', 'search'));
}


public function create()
{
return view('admin.system-monitoring.system-settings.create');
}


public function store(Request $request)
{
$request->validate([
'setting_key' => 'required|unique:system_settings,setting_key',
'setting_value' => 'nullable|string',
'description' => 'nullable|string',
]);


SystemSetting::create($request->all());


return redirect()->route('system-settings.index')->with('success', 'Setting created successfully.');
}


public function edit(SystemSetting $systemSetting)
{
return view('admin.system-monitoring.system-settings.edit', compact('systemSetting'));
}


public function update(Request $request, SystemSetting $systemSetting)
{
$request->validate([
'setting_key' => 'required|unique:system_settings,setting_key,' . $systemSetting->id,
'setting_value' => 'nullable|string',
'description' => 'nullable|string',
]);


$systemSetting->update($request->all());


return redirect()->route('system-settings.index')->with('success', 'Setting updated successfully.');
}


public function destroy(SystemSetting $systemSetting)
{
$systemSetting->delete();


return redirect()->route('system-settings.index')->with('success', 'Setting deleted successfully.');
}
}
