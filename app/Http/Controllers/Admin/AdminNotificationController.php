<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Notification;

class AdminNotificationController extends Controller
{
    /**
* Display a listing of the notifications.
*/
public function index(Request $request)
{
// Apply optional filters (e.g., type, user, read/unread)
$query = Notification::with('user');


if ($request->filled('type')) {
$query->where('type', $request->type);
}


if ($request->filled('read_status')) {
if ($request->read_status === 'read') {
$query->whereNotNull('read_at');
} elseif ($request->read_status === 'unread') {
$query->whereNull('read_at');
}
}


$notifications = $query->latest()->paginate(15);


return view('admin.system-monitoring.notification.index', compact('notifications'));
}
}
