<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranches()
    {
        return view('kmj.branch.index');
    }

    public function getBrancheReport(Request $request)
    {
        // Implement your logic to fetch branch report data based on the request parameters

        return view('kmj.branch.report');
    }
}
