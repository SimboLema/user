<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Quotation;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RenewalController extends Controller
{
    public function renewals(Request $request)
    {
        $query = Quotation::query()->where('status', 'success');


        // Use user input if available
        if ($request->filled('from_date') || $request->filled('to_date')) {

            if ($request->filled('from_date')) {
                $from = Carbon::parse($request->from_date)->format('Y-m-d');
                $query->whereDate('cover_note_end_date', '>=', $from);
            }

            if ($request->filled('to_date')) {
                $to = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('cover_note_end_date', '<=', $to);
            }
        } else {
            // Default: today → today+30
            $today = Carbon::today();
            $query->whereDate('cover_note_end_date', '>=', $today)
                ->whereDate('cover_note_end_date', '<=', $today->copy()->addDays(30));
        }

        $quotations = $query->orderBy('id', 'asc')->get();

        return view('kmj.renewal.index', compact('quotations'));
    }

    public function renewalReport(Request $request)
    {
        $query = Quotation::query()->where('status', 'success');


        // Use user input if available
        if ($request->filled('from_date') || $request->filled('to_date')) {

            if ($request->filled('from_date')) {
                $from = Carbon::parse($request->from_date)->format('Y-m-d');
                $query->whereDate('cover_note_end_date', '>=', $from);
            }

            if ($request->filled('to_date')) {
                $to = Carbon::parse($request->to_date)->format('Y-m-d');
                $query->whereDate('cover_note_end_date', '<=', $to);
            }
        } else {
            // Default: today → today+30
            $today = Carbon::today();
            $query->whereDate('cover_note_end_date', '>=', $today)
                ->whereDate('cover_note_end_date', '<=', $today->copy()->addDays(30));
        }

        $quotations = $query->orderBy('id', 'asc')->get();
        return view('kmj.renewal.report', compact('quotations'));
    }
}
