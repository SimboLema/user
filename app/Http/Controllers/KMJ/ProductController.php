<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\PolicyHolderIdType;
use App\Models\Models\KMJ\PolicyHolderType;
use App\Models\Models\KMJ\Region;
use App\Models\Models\KMJ\Country;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts()
    {
        $countries=Country::all();
         $regions = Region::where('country_id', 219)->get();
        $policyHolderType = PolicyHolderType::all();
        $policyHolderIdType = PolicyHolderIdType::all();
        return view('kmj.product.index', compact('countries','regions', 'policyHolderType', 'policyHolderIdType'));
    }
}
