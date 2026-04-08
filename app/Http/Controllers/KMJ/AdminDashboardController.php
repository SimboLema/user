<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models\KMJ\Customer;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalCustomers = Customer::count();
        $listCustomers = Customer::orderBy('name','desc')->take(10)->get();
        return view('kmj.index', compact('totalCustomers','listCustomers'));
    }
}
