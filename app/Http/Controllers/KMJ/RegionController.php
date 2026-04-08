<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Region;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    public function getDistricts($regionId)
    {
        $districts = Region::find($regionId)->districts()->select('id', 'name')->orderBy('name', 'asc')->get();
        return response()->json($districts);
    }

    public function getRegions($countryId)
    {
        $regions = Region::where('country_id', $countryId)->select('id', 'name')->orderBy('name', 'asc')->get();
        return response()->json($regions);
    }
}
