<?php

namespace App\Http\Controllers\Rider;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class RiderDashboardController extends Controller
{
    public function dashboard(){
      
        return view('rider.dashboard.dashboard');
    }
}
