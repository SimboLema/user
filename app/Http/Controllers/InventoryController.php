<?php

namespace App\Http\Controllers;

use App\Models\CollectionCenter;
use App\Models\CollectionCenterUser;
use App\Models\RecyclingFacility;
use App\Models\RecyclingFacilityUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use App\Traits\ActivityLoggableTrait;
use App\Models\EmailTemplate;
use App\Models\NotificationUser;
use App\Models\Producer;
use App\Models\Color;
use App\Models\Country;
use App\Models\Region;
use App\Models\RecyclingWasteCollection;
use App\Models\RecyclingWasteCollectionItem;
use App\Models\RecyclingWasteCollectionPayment;
use App\Models\RecyclingFacilityWasteType;
use App\Models\RecyclingFacilityProcessing;
use App\Models\RecyclingFacilityBalance;
use App\Models\User;
use App\Models\BeamSetting;
use App\Models\WastePicker;
use App\Models\District;
use App\Models\Ward;
use App\Models\WasteType;
use App\Models\Setting;
use App\Models\Unit;
use App\Models\WasteCollection;
use App\Models\CollectionCenterBalance;
use App\Models\WasteCollectionPayment;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
class InventoryController extends Controller
{
    use ActivityLoggableTrait;

    public function inventory_balance()
    {
        if (!Auth::user()->can('show inventory')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();

        return view('inventory.index',compact('user'));
    }

    public function inventory_balance_view()
    {
        if (!Auth::user()->can('show inventory')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();
        $balances = [] ;


        if ($user && ($user->role == 3 )) { // Collection Center User
            $ids = $user->collection_centers()->pluck('collection_center_id');
            $balances = CollectionCenterBalance::whereIn('collection_center_id',$ids)->get();
        }

        if ($user && $user->role == 4) { // recycling facility
            $ids = $user->facilities()->pluck('facility_id');
            $balances = RecyclingFacilityBalance::whereIn('facility_id',$ids)->get();
        }

        if ($user && $user->role == 5) { // producer
            $ids = $user->producers()->pluck('producer_id');
            $balances = ProducerBalance::whereIn('producer_id',$ids)->get();
        }

        return view('inventory.view', compact('balances'));
    }

    public function getInventoryBalance()
    {
        if (!Auth::user()->can('show inventory')) {
            return response()->json([
                'status' => 500,
                'message' => 'you dont have permission to view inventory',
            ], 200);
        }

        $user = \Auth::user();
        $balances = [] ;


        if ($user && ($user->role == 3 )) { // Collection Center User
            $ids = $user->collection_centers()->pluck('collection_center_id');
            $balances = CollectionCenterBalance::with(['collectionCenter','wasteType','unit','createdBy'])->whereIn('collection_center_id',$ids)->get();
        }

        if ($user && $user->role == 4) { // recycling facility
            $ids = $user->facilities()->pluck('facility_id');
            $balances = RecyclingFacilityBalance::with(['facility','wasteType','unit','createdBy'])->whereIn('facility_id',$ids)->get();
        }

        if ($user && $user->role == 5) { // producer
            $ids = $user->producers()->pluck('producer_id');
            $balances = ProducerBalance::with(['producer','wasteType','unit','createdBy'])->whereIn('producer_id',$ids)->get();
        }

        return response()->json([
            'status' => 500,
            'message' => 'data exist',
            'data' => $balances
        ], 200);
    }

    public function inventory_adjustment()
    {
        if (!Auth::user()->can('show inventory')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();

        return view('inventory_adjustment.index',compact('user'));
    }

    public function inventory_adjustment_view()
    {
        if (!Auth::user()->can('show inventory')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();
        $balances = [] ;


        if ($user && ($user->role == 3)) { // Collection Center User
            $ids = $user->collection_centers()->pluck('collection_center_id');
            $balances = CollectionCenterBalance::whereIn('collection_center_id',$ids)->get();
        }

        if ($user && $user->role == 4) { // recycling facility
            $ids = $user->facilities()->pluck('facility_id');
            $balances = RecyclingFacilityBalance::whereIn('facility_id',$ids)->get();
        }

        if ($user && $user->role == 5) { // producer
            $ids = $user->producers()->pluck('producer_id');
            $balances = ProducerBalance::whereIn('producer_id',$ids)->get();
        }

        return view('inventory_adjustment.view', compact('balances'));
    }

    public function saveInventoryAdjustment(Request $request)
    {
        try {
            $user = \Auth::user();

            foreach ($request->items as $item) {
                $id = $item['id'];
                $quantity = $item['quantity'];

                if ($user->role == 3) {
                    // Collection Center
                    CollectionCenterBalance::where('id', $id)->update(['quantity' => $quantity]);
                } elseif ($user->role == 4) {
                    // Recycling Facility
                    RecyclingFacilityBalance::where('id', $id)->update(['quantity' => $quantity]);
                } elseif ($user->role == 5) {
                    // Producer
                    ProducerBalance::where('id', $id)->update(['quantity' => $quantity]);
                }

                $this->saveActivityLog("Inventory Adjustment", "Change balance of id ".$id." role".$user->role." change qty to ".$quantity);
            }

            return response()->json(['status' => 200, 'message' => 'Inventory updated successfully.']);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


}
