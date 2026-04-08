<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\PolicyHolderIdType;
use App\Models\Models\KMJ\PolicyHolderType;
use App\Models\Models\KMJ\Region;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index()
    {
        $regions = Region::where('country_id', 219)->get();
        $policyHolderType = PolicyHolderType::all();
        $policyHolderIdType = PolicyHolderIdType::all();
        return view('kmj.transaction.index', compact('regions', 'policyHolderType', 'policyHolderIdType'));
    }

    public function show($client)
    {
        return view('kmj.transaction.show', compact('client'));
    }
}
