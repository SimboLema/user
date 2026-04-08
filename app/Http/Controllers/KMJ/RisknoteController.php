<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Quotation;
use Illuminate\Http\Request;

class RisknoteController extends Controller
{
    //

    public function index()
    {

        $quotations = Quotation::where('status', 'success')->orderBy('id', 'desc')->get();
        return view('kmj.risknote.index', compact('quotations'));
    }
}
