<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Models\KMJ\Branches;
use App\Models\Models\KMJ\Region;

class BranchController extends Controller
{
    public function getBranches()

    {
        $regions = Region::all();
        $branches = Branches::latest()->paginate(10);
        return view('kmj.branch.index', compact('branches','regions'));
    }
    public function create()
    {
        return view('kmj.branch.create');
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'status' => 'required|boolean',
        ]);

        Branches::create($validated);

        return redirect()->route('kmj.getBranches')
            ->with('success', 'Branch created successfully.');
    }


    public function update(Request $request, Branches $branch)
    {
        $validated = $request->validate([
            'name'   => 'required|string|max:255',
            'region' => 'required|string|max:255',
            'phone'  => 'nullable|string|max:20',
            'status' => 'required|boolean',
        ]);

        $branch->update($validated);

        return redirect()->route('kmj.getBranches')
            ->with('success', 'Branch updated successfully.');
    }

    public function destroy(Branches $branch)
    {
        $branch->delete();

        return redirect()->route('kmj.getBranches')
            ->with('success', 'Branch deleted successfully.');
    }

    public function getBrancheReport(Request $request)
    {
        // Implement your logic to fetch branch report data based on the request parameters

        return view('kmj.branch.report');
    }
}
