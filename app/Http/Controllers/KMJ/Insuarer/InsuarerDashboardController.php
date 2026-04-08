<?php

namespace App\Http\Controllers\KMJ\Insuarer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\KMJ\Quotation;

class InsuarerDashboardController extends Controller
{
    public function index(){
       $quotations = Quotation::where('insuarer_id', Auth::guard('insuarer')->id())->count();

        return view('insuarer.dashboard', compact('quotations'));
    }

    public function support(){
        return view ('insuarer.support');
    }
}
