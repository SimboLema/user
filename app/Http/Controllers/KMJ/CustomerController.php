<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Country;
use App\Models\Models\KMJ\Customer;
use App\Models\Models\KMJ\PolicyHolderIdType;
use App\Models\Models\KMJ\PolicyHolderType;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\Region;
use Exception;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $countries = Country::orderBy('name', 'asc')->get();

        $policyHolderType = PolicyHolderType::all();
        $policyHolderIdType = PolicyHolderIdType::all();
        $customers = Customer::orderBy('id', 'desc')->get();
        return view('kmj.customer.index', compact('countries', 'policyHolderType', 'policyHolderIdType', 'customers'));
    }

    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if ($query == '') {
            $customers = Customer::take(10)
                ->get(['id', 'name', 'phone', 'email_address', 'policy_holder_id_number']);
        } else {
            $customers = Customer::where(function ($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                    ->orWhere('phone', 'like', "%{$query}%")
                    ->orWhere('email_address', 'like', "%{$query}%")
                    ->orWhere('policy_holder_id_number', 'like', "%{$query}%");
            })->take(10)->get(['id', 'name', 'phone', 'email_address', 'policy_holder_id_number']);
        }

        return response()->json($customers);
    }

    public function customerStore(Request $request)
    {
        try {
            $customer = new Customer();
            $customer->name = $request->name;
            $customer->dob = $request->dob;
            $customer->policy_holder_type_id = $request->policy_holder_type_id;
            $customer->policy_holder_id_number = $request->policy_holder_id_number;
            $customer->tin_number = $request->tin_number;
            $customer->policy_holder_id_type_id = $request->policy_holder_id_type_id;
            $customer->gender = $request->gender;
            $customer->district_id = $request->district_id;
            $customer->street = $request->street;
            $customer->phone = $request->phone;
            $customer->fax = $request->fax;
            $customer->postal_address = $request->postal_address;
            $customer->email_address = $request->email_address;
            $customer->save();

            return redirect()
                ->back()
                ->with('success', 'Customer Created Successfully!');
        } catch (Exception $e) {

            return redirect()
                ->route('kmj.customers')
                ->with('error', 'Failed to save customer. Please try again.');
        }
    }

    public function customerShow($id)
    {
        $customer = Customer::find($id);

        return view('kmj.customer.show', compact('customer'));
    }

    // public function customer($id)
    // {
    //     $quotation = Quotation::findOrFail($id);
    //     $customer  = $quotation->customer;
    //     $regions = Region::all();
    //     $policyHolderType = PolicyHolderType::all();
    //     $policyHolderIdType = PolicyHolderIdType::all();

    //     return view('kmj.quotation.tabs.customer', compact(
    //         'quotation',
    //         'customer',
    //         'regions',
    //         'policyHolderType',
    //         'policyHolderIdType'
    //     ));
    // }

    public function updateCustomer(Request $request, $customerId)
    {
        $customer = Customer::findOrFail($customerId);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'dob' => 'nullable|date',
            'gender' => 'nullable|in:M,F',
            'region_id' => 'required|exists:regions,id',
            'district_id' => 'required|exists:districts,id',
            'street' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'fax' => 'nullable|string|max:50',
            'postal_address' => 'required|string|max:255',
            'email_address' => 'nullable|email|max:255',
            'policy_holder_type_id' => 'required|exists:policy_holder_types,id',
            'policy_holder_id_type_id' => 'required|exists:policy_holder_id_types,id',
            'policy_holder_id_number' => 'required|string|max:100',
        ]);

        $customer->update($validated);

        return redirect()->back()->with('success', 'Customer details updated successfully.');
    }
}
