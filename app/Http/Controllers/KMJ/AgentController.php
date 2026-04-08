<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Country;
use App\Models\Models\KMJ\Customer;
use App\Models\Models\KMJ\PolicyHolderIdType;
use App\Models\Models\KMJ\PolicyHolderType;
use App\Models\Models\KMJ\Region;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    public function getAgents()
    {
        $regions = Region::where('country_id', 219)->get();
        $policyHolderType = PolicyHolderType::all();
        $policyHolderIdType = PolicyHolderIdType::all();
         $countries  = Country::all();
        return view('kmj.agent.index', compact('regions', 'policyHolderType', 'policyHolderIdType','countries'));
    }

    public function getAgentReport(Request $request)
    {
        $query = Customer::query();

        // Use user input if available
        if ($request->filled('region_id')) {
            $query->where('region_id', $request->region_id);
        }

        if ($request->filled('policy_holder_type_id')) {
            $query->where('policy_holder_type_id', $request->policy_holder_type_id);
        }

        if ($request->filled('policy_holder_id_type_id')) {
            $query->where('policy_holder_id_type_id', $request->policy_holder_id_type_id);
        }

        $customers = $query->orderBy('id', 'asc')->get();

        return view('kmj.agent.report', compact('customers'));
    }
}
