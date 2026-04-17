<?php

namespace App\Http\Controllers\KMJ\Insuarer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\KMJ\Quotation;

class InsuarerDashboardController extends Controller
{
    public function index(){
        $insuarerId = Auth::guard('insuarer')->id();

       $quotations = Quotation::where('insuarer_id', Auth::guard('insuarer')->id())->count();

       $baseQuery = Quotation::where('insuarer_id', $insuarerId);
       $approvedQuotations = (clone $baseQuery)
        ->where('status', 'approved')
        ->count();

        return view('insuarer.dashboard', compact('quotations','approvedQuotations'));
    }

    public function support(){
        return view ('insuarer.support');
    }
}
