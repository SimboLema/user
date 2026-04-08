<?php

namespace App\Http\Controllers\KMJ\Insuarer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Models\KMJ\Quotation;

class InsuarerQuotationController extends Controller
{
    public function index(Request $request)
    {
    $insuarerId = Auth::guard('insuarer')->id();
    $search = $request->input('search');

    $quotations = Quotation::where('insuarer_id', $insuarerId)
        ->when($search, function ($query, $search) {
            return $query->where(function ($q) use ($search) {
                // Search by Quotation ID (handling the offset you used in view)
                $q->where('id', 'like', "%{$search}%")
                  // Search by Customer Name (assuming relationship exists)
                  ->orWhereHas('customer', function ($customerQuery) use ($search) {
                      $customerQuery->where('name', 'like', "%{$search}%");
                  })
                  // Search by Risk Name
                  ->orWhereHas('coverage', function ($coverageQuery) use ($search) {
                      $coverageQuery->where('risk_name', 'like', "%{$search}%");
                  });
            });
        })
        ->latest()
        ->paginate(15);

    return view('insuarer.quotation.index', compact('quotations', 'search'));
    }

    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:approved,cancelled', // insurer can ONLY approve or cancel
    ]);

    $insuarerId = Auth::guard('insuarer')->id();

    $quotation = Quotation::where('id', $id)
                          ->where('insuarer_id', $insuarerId)
                          ->firstOrFail();

    $quotation->status = $request->status;
    $quotation->save();

    return redirect()->back()->with('success', 'Quotation status updated successfully.');
}

    public function show($id)
{
    $insuarerId = Auth::guard('insuarer')->id();

    $quotation = Quotation::where('id', $id)
                          ->where('insuarer_id', $insuarerId)
                          ->firstOrFail();

    return view('insuarer.quotation.show', compact('quotation'));
}
}
