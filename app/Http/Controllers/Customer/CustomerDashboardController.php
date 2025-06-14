<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;


class CustomerDashboardController extends Controller
{
    public function dashboard(){

        return view('customer.home.home');
    }
}
