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
use App\Models\Product;
use App\Models\RecyclingMaterial;
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


class OperationController extends Controller
{
    use ActivityLoggableTrait;



    public function waste_collection()
    {
        if (!Auth::user()->can('manage waste collection')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();

        $wastePickers = WastePicker::where('archive', 0)->get();

        // collection center
        $collectionCenters = CollectionCenter::where('archive', 0);
        if ($user && $user->role == 3) {
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $collectionCenters->whereIn('id', $centerIds);
        }
        $collectionCenters = $collectionCenters->orderBy('id', 'desc')->get();

        $wasteTypes = WasteType::where('archive', 0)->where('parent_id',null)->get();
        $colors = Color::get();
        $units = Unit::where('archive', 0)->get();

        return view('waste_collection.index',compact('wastePickers','colors','wasteTypes','wasteTypes','collectionCenters','units'));
    }

    public function waste_collection_view()
    {
        if (!Auth::user()->can('manage waste collection')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();
        $data = WasteCollection::where('archive', 0);

        // Optional role-based filter example
        if ($user && $user->role == 2) {
            $data->where('waste_picker_id', $user->waste_picker->id?? 0);
        }

        if ($user && $user->role == 3) { // Collection Center User
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $data->whereIn('collection_center_id', $centerIds);
        }

        $data = $data->orderBy('id', 'desc')->get();
        return view('waste_collection.view', compact('data'));
    }

    public function waste_collection_details($id)
    {
        if (!Auth::user()->can('show waste collection')) {
            return abort(401, __('Permission denied.'));
        }


        $id = Crypt::decrypt($id);
        $waste = WasteCollection::find($id);
        return view('waste_collection.show', compact('waste'));
    }

    public function getWasteCollection(Request $request)
    {
        if (!\Auth::user()->can('show waste collection')) {
            return response()->json(['status' => 500, 'message' => "No permission to view waste collection"]);
        }

        $collection_center_id = $request->collection_center_id;
        $waste_picker_id = $request->waste_picker_id;
        $waste_type_id = $request->waste_type_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = WasteCollection::with(['wastePicker', 'wasteType', 'unit','color']) // eager load relations
            ->where('archive', 0);

             // Filter by waste type
        if (!empty($waste_picker_id) && $waste_picker_id !== "All") {
            $data->where('waste_picker_id', $waste_picker_id);
        }else{
            // Optional role-based filter example
            if ($user && $user->role == 2) { ## waste picker
                $data->where('waste_picker_id', $user->waste_picker->id ?? 0);
            }

            if ($user && $user->role == 3) {
                $centerIds = $user->collection_centers()->pluck('collection_center_id');
                $data->whereIn('collection_center_id', $centerIds);
            }
        }

        if (!empty($waste_type_id) && $waste_type_id !== "All") {
            $data->where('waste_type_id', $waste_type_id);
        }


        // Search filter on waste picker name, weather, or payment_status
        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->whereHas('wastePicker.user', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%");
                })
                ->orWhere('weather_condition', 'like', "%$search_text%")
                ->orWhere('payment_status', 'like', "%$search_text%");
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }


    public function getWasteCollectionRow($id)
    {
        if (!\Auth::user()->can('show waste collection')) {
            return response()->json(['status' => 500, 'message' => "No permission to view waste collection"]);
        }


        $data = WasteCollection::with([
            'wastePicker',
            'color',
            'wasteType',
            'unit',
            'collectionPayments',
            'paymentStatus'
        ])
        ->where('archive', 0)
        ->where('id', $id)
        ->orderBy('id', 'desc')
        ->first();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $data]);
    }



    public function saveWasteCollection(Request $request)
    {
        $rules = [
            'waste_picker_id' => 'required',
            'collection_center_id' => 'required',
            'waste_type_id' => 'required',
            'quantity' => 'required|numeric',
            'color_id' => 'required',
            'unit_id' => 'required',
            'total_amount' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
        }

        try {
            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $waste_picker_id = $request->waste_picker_id;
            $user_id = Auth::check() ? Auth::user()->id : null;
            $waste_type_id = $request->waste_type_id;
            $collection_center_id = $request->collection_center_id;
            $quantity = $request->quantity;
            $unit_id = $request->unit_id;
            $total_amount = $request->total_amount;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $weather_condition = $request->weather_condition;
            $color_id = $request->color_id;

            $data = [
                'waste_picker_id' => $waste_picker_id,
                'user_id' => $user_id,
                'collection_center_id' => $collection_center_id,
                'waste_type_id' => $waste_type_id,
                'quantity' => $quantity,
                'unit_id' => $unit_id,
                'total_amount' => $total_amount,
                'longitude' => $longitude,
                'weather_condition' => $weather_condition,
                'color_id' => $color_id,
                'archive' => 0,
            ];

            // Handle logo upload
            if ($request->hasFile('image')) {
                // Ensure the 'collections' directory exists
                if (!Storage::disk('public')->exists('collections')) {
                    Storage::disk('public')->makeDirectory('collections');
                }

                // Store the logo in the 'collections' directory within the 'public' disk
                $data['image'] =$request->file('image')->store('collections', 'public');
            }

            if (!empty($hidden_id)) {
                if (!Auth::user()->can('edit waste collection')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit"]);
                }

                $realId = Crypt::decrypt($hidden_id);
                $old = WasteCollection::find($realId);
                $oldQuantity = $old->quantity;

                ## update
                WasteCollection::where('id', $realId)->update($data);
                $waste_collection = WasteCollection::find($realId);

                ## sync qty
                $result = $this->syncCollectionCenterBalance($waste_collection, true, $oldQuantity);

                $this->saveActivityLog("Waste Collection", "Updated Waste Collection Id $realId");
                $message = "Waste collection updated successfully";
            } else {
                if (!Auth::user()->can('create waste collection')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create"]);
                }

                $data['created_by'] = Auth::id();
                $data['created_at'] = now();
                $waste_collection = WasteCollection::create($data);

                ## sync qty
                $result = $this->syncCollectionCenterBalance($waste_collection);


                ## response
                $this->saveActivityLog("Waste Collection", "Created Waste Collection Id $waste_collection->id");
                $message = "Waste collection created successfully";
            }

            // Sync balance and update payment status
            $result2 = $this->checkPaymentStatus($waste_collection);
            if ($result['status'] != 200 || $result2['status'] != 200) {
                DB::rollback();
                return response()->json(['status' => 500, 'message' => $result['message'] ?? $result2['message']]);
            }


            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    public function saveWasteCollectionPayment(Request $request)
    {

        $rules = [
            'collection_id' => 'required',
            'date' => 'required',
            'payment_method' => 'required',
            'amount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
        }


        DB::beginTransaction();

        try {

            $collection = WasteCollection::findOrFail($request->collection_id);
            $totalPaid = $collection->totalPayment();
            $newTotal = $totalPaid + $request->amount;
            $remainTotal = $collection->total_amount -  $totalPaid;

            if ($newTotal > $collection->total_amount) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Payment exceeds the total collection amount. required amount is '.$remainTotal
                ]);
            }


            $payment = new WasteCollectionPayment();
            $payment->collection_id = $request->collection_id;
            $payment->reference_number = $request->reference_number;
            $payment->date = $request->date;
            $payment->amount = $request->amount;
            $payment->payment_method = $request->payment_method;


            if ($request->hasFile('attachment')) {
                // Ensure the 'collections' directory exists
                if (!Storage::disk('public')->exists('payments')) {
                    Storage::disk('public')->makeDirectory('payments');
                }

                // Store the logo in the 'collections' directory within the 'public' disk
                $payment->attachment =$request->file('attachment')->store('payments', 'public');
            }


            $payment->save();

            ## update payment status
            $this->updateWasteCollectionPayment($request->collection_id);


            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Payment recorded successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Payment failed: ' . $e->getMessage()]);
        }
    }


    public function updateWasteCollectionPayment($collection_id){
        // Update collection payment status
        $collection = WasteCollection::findOrFail($collection_id);
        $totalPaid = $collection->totalPayment();

        if ($totalPaid >= $collection->total_amount) {
            $collection->payment_status = 3; // Paid
        } elseif ($totalPaid > 0) {
            $collection->payment_status = 2; // Partial Paid
        }

        $collection->save();

        return true;
    }

    public function syncCollectionCenterBalance(WasteCollection $wasteCollection, $isUpdate = false, $oldQuantity = 0)
    {
        try {
            $setting = Setting::first();
            $targetUnitId = $setting->unit_id;

            // Convert current quantity to the collection center's unit
            $convertedQty = Unit::convertQuantity($wasteCollection->unit_id, $wasteCollection->quantity, $targetUnitId);

            // If update, convert the old quantity too
            $oldConvertedQty = $isUpdate ? Unit::convertQuantity($wasteCollection->unit_id, $oldQuantity, $targetUnitId) : 0;

            $balance = CollectionCenterBalance::firstOrCreate(
                [
                    'collection_center_id' => $wasteCollection->collection_center_id,
                    'waste_type_id' => $wasteCollection->waste_type_id,
                ],
                [
                    'unit_id' => $targetUnitId,
                    'created_by' => auth()->id(),
                    'archive' => 0,
                    'quantity' => 0,
                ]
            );

            if ($isUpdate) {
                $diff = $convertedQty - $oldConvertedQty;
                $balance->quantity += $diff;
            } else {
                $balance->quantity += $convertedQty;
            }

            $balance->save();

            return ['status' => 200, 'message' => 'Balance synced'];
        } catch (\Exception $e) {
            return [
                'status' => 500,
                'message' => $e->getMessage(),
                'error' => $e->getMessage(),
            ];
        }
    }




    public function checkPaymentStatus(WasteCollection $collection)
    {
        try {
            $totalPaid = $collection->totalPayment();
            $status = 1; // unpaid

            if ($totalPaid > 0 && $totalPaid < $collection->total_amount) {
                $status = 2; // partial
            } elseif ($totalPaid >= $collection->total_amount) {
                $status = 3; // paid
            }

            $collection->update(['payment_status' => $status]);

            return ['status' => 200, 'payment_status' => $status];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => 'Status check failed', 'error' => $e->getMessage()];
        }
    }


    public function editWasteCollection($id)
    {
        $data = WasteCollection::find($id);
        return response()->json(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteWasteCollection($id)
    {
        if (!Auth::user()->can('delete waste collection')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete"]);
        }

        try {
            WasteCollection::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Waste Collection", "Archived Waste Collection Id $id");
            return response()->json(['status' => 200, 'message' => "Waste collection archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function recycling_waste_collection()
    {
        if (!Auth::user()->can('manage recycling waste collection')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();
        $collectionCenters = CollectionCenter::where('archive', 0)->get();
        $producers = Producer::where('archive', 0)->get();
        $facilities = RecyclingFacility::where('archive', 0);

        if ($user && $user->role == 4) {
            $facilityIds = $user->facilities()->pluck('facility_id');
            $facilities->whereIn('id', $facilityIds);
        }
        $facilities = $facilities->orderBy('id', 'desc')->get();

        $wasteTypes = WasteType::where('archive', 0)->where('parent_id',null)->get();
        $colors = Color::get();
        $units = Unit::where('archive', 0)->get();

        return view('recycling_waste_collection.index', compact('collectionCenters','producers','colors', 'wasteTypes', 'units', 'facilities'));
    }

    public function recycling_waste_collection_view()
    {
        if (!Auth::user()->can('manage recycling waste collection')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();
        $data = RecyclingWasteCollection::where('archive', 0);

        if ($user && $user->role == 4) {
            $facilityIds = $user->facilities()->pluck('facility_id');
            $data->whereIn('facility_id', $facilityIds);
        }

        $data = $data->orderBy('id', 'desc')->get();
        return view('recycling_waste_collection.view', compact('data'));
    }

    public function recycling_waste_collection_details($id)
    {
        if (!Auth::user()->can('show recycling waste collection')) {
            return abort(401, __('Permission denied.'));
        }


        $id = Crypt::decrypt($id);
        $waste = RecyclingWasteCollection::find($id);
        return view('recycling_waste_collection.show', compact('waste'));
    }

    public function getRecyclingWasteCollection(Request $request)
    {
        if (!\Auth::user()->can('show recycling waste collection')) {
            return response()->json(['status' => 500, 'message' => "No permission to view recycling collection"]);
        }

        $user = \Auth::user();
        $collection_center_id = $request->collection_center_id;
        $facility_id = $request->facility_id;
        $search_text = $request->search_text;

        $data = RecyclingWasteCollection::with(['items', 'collectionPayments', 'collectionCenter','facility','producer'])
            ->where('archive', 0);

        if ($user && $user->role == 3) {
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $data->whereIn('collection_center_id', $centerIds);
        }

        if ($user && $user->role == 4) {
            $facilities = $user->facilities()->pluck('facility_id');
            $data->whereIn('facility_id', $facilities);
        }

        if ($user && $user->role == 5) {
            $producers = $user->producers()->pluck('producer_id');
            $data->whereIn('producer_id', $producers);
        }

        if (!empty($facility_id) && $facility_id !== "All") {
            $data->where('facility_id', $facility_id);
        }

        if (!empty($collection_center_id) && $collection_center_id !== "All") {
            $data->where('collection_center_id', $collection_center_id);
        }

        if (!empty($search_text)) {
            $data->whereHas('collectionCenter', function ($q) use ($search_text) {
                $q->where('name', 'like', "%$search_text%");
            });
        }

        $result = $data->orderBy('id', 'desc')->get();
        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function getRecyclingWasteCollectionRow($id)
    {
        if (!\Auth::user()->can('show recycling waste collection')) {
            return response()->json(['status' => 500, 'message' => "No permission to view recycling collection"]);
        }

        $data = RecyclingWasteCollection::with(['items', 'collectionPayments', 'collectionCenter','facility','producer'])
            ->where('archive', 0);

        $result = $data->where('id',$id)->orderBy('id', 'desc')->first();
        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveRecyclingWasteCollection(Request $request)
    {
        $rules = [
            'facility_id' => 'required',
            'waste_type_id' => 'required',
            'quantity' => 'required',
            'unit_id' => 'required',
            'total_amount' => 'required|numeric',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
        }

        try {
            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $data = [
                'user_id' => Auth::id(),
                'collection_center_id' => $request->collection_center_id,
                'producer_id' => $request->producer_id,
                'facility_id' => $request->facility_id,
                'total_amount' => $request->total_amount,
                'payment_status' => 1, // unpaid
                'collection_time' => $request->collection_time,
                'collection_time_unit' => $request->collection_time_unit,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'weather_condition' => $request->weather_condition,
                'archive' => 0,
            ];

            if ($request->hasFile('image')) {
                if (!Storage::disk('public')->exists('collections')) {
                    Storage::disk('public')->makeDirectory('collections');
                }
                $data['image'] = $request->file('image')->store('collections', 'public');
            }

            if (!empty($hidden_id)) {
                if (!Auth::user()->can('edit recycling waste collection')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit"]);
                }

                $realId = Crypt::decrypt($hidden_id);
                $old = RecyclingWasteCollection::find($realId);
                $oldQty = $old->quantity;

                RecyclingWasteCollection::where('id', $realId)->update($data);
                $instance = RecyclingWasteCollection::find($realId);
                $this->saveActivityLog("Recycling Collection", "Updated Id $realId");
                $message = "Recycling waste collection updated successfully";
            } else {
                if (!Auth::user()->can('create recycling waste collection')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create"]);
                }

                $data['created_by'] = Auth::id();
                $data['created_at'] = now();
                $instance = RecyclingWasteCollection::create($data);
                $this->saveActivityLog("Recycling Collection", "Created Id $instance->id");
                $message = "Recycling waste collection created successfully";
            }

            $result = $this->saveRecyclingWasteCollectionItem($request,$instance);
            $result2 = $this->checkRecyclingWasteCollectionPaymentStatus($instance);

            if ($result['status'] != 200 || $result2['status'] != 200) {
                DB::rollback();
                return response()->json(['status' => 500, 'message' => $result['message'] ?? $result2['message']]);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function saveRecyclingWasteCollectionPayment(Request $request)
    {
        $rules = [
            'collection_id' => 'required',
            'date' => 'required',
            'payment_method' => 'required',
            'amount' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
        }


        DB::beginTransaction();

        try {

            $collection = RecyclingWasteCollection::findOrFail($request->collection_id);
            $totalPaid = $collection->totalPayment();
            $newTotal = $totalPaid + $request->amount;
            $remainTotal = $collection->total_amount -  $totalPaid;

            if ($newTotal > $collection->total_amount) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Payment exceeds the total collection amount. required amount is '.$remainTotal
                ]);
            }


            $payment = new RecyclingWasteCollectionPayment();
            $payment->rec_waste_collection_id = $request->collection_id;
            $payment->reference_number = $request->reference_number;
            $payment->date = $request->date;
            $payment->amount = $request->amount;
            $payment->payment_method = $request->payment_method;


            if ($request->hasFile('attachment')) {
                // Ensure the 'collections' directory exists
                if (!Storage::disk('public')->exists('payments')) {
                    Storage::disk('public')->makeDirectory('payments');
                }

                // Store the logo in the 'collections' directory within the 'public' disk
                $payment->attachment =$request->file('attachment')->store('payments', 'public');
            }


            $payment->save();

            ## update payment status
            $collection = RecyclingWasteCollection::findOrFail($request->collection_id);
            $this->checkRecyclingWasteCollectionPaymentStatus($collection);


            DB::commit();
            return response()->json(['status' => 200, 'message' => 'Payment recorded successfully']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['status' => 500, 'message' => 'Payment failed: ' . $e->getMessage()]);
        }
    }



    public function checkRecyclingWasteCollectionPaymentStatus(RecyclingWasteCollection $collection)
    {
        try {
            $totalPaid = $collection->totalPayment();
            $status = 1; // unpaid

            if ($totalPaid > 0 && $totalPaid < $collection->total_amount) {
                $status = 2; // partial
            } elseif ($totalPaid >= $collection->total_amount) {
                $status = 3; // paid
            }

            $collection->update(['payment_status' => $status]);

            return ['status' => 200, 'payment_status' => $status];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }


    public function saveRecyclingWasteCollectionItem(Request $request, $instance)
    {
        $rules = [
            'waste_type_id' => 'required|array',
            'quantity' => 'required|array',
            'unit_id' => 'required|array',
            'color_id' => 'required|array',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return ['status' => 500, 'message' => $validator->errors()->first()];
        }

        try {
            $item_hidden_id = $request->input('item_hidden_id', []);
            $quantity = $request->input('quantity', []);
            $waste_type_id = $request->input('waste_type_id', []);
            $unit_id = $request->input('unit_id', []);
            $color_id = $request->input('color_id', []);

            foreach ($waste_type_id as $key => $type_id) {
                $oldQty = 0;
                $oldUnitId = null;

                // Fetch old data if updating
                if (!empty($item_hidden_id[$key])) {
                    $existingItem = RecyclingWasteCollectionItem::find($item_hidden_id[$key]);
                    if ($existingItem) {
                        $oldQty = $existingItem->quantity;
                        $oldUnitId = $existingItem->unit_id;
                    }
                }

                $data = [
                    'rec_waste_collection_id' => $instance->id,
                    'waste_type_id' => $type_id ?? null,
                    'quantity' => $quantity[$key] ?? 0,
                    'unit_id' => $unit_id[$key] ?? null,
                    'color_id' => $color_id[$key] ?? null,
                    'created_by' => \Auth::id(),
                    'archive' => 0,
                ];

                $item = RecyclingWasteCollectionItem::updateOrCreate(
                    ['id' => $item_hidden_id[$key] ?? null],
                    $data
                );

                $this->syncRecyclingWasteCollection(
                    (object)[
                        'facility_id' => $instance->facility_id,
                        'waste_type_id' => $type_id,
                        'unit_id' => $unit_id[$key],
                        'quantity' => $quantity[$key] ?? 0,
                        'old_unit_id' => $oldUnitId
                    ],
                    isset($item_hidden_id[$key]),
                    $oldQty
                );
            }

            return ['status' => 200, 'message' => 'Recycling waste collection updated successfully'];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }



        public function syncRecyclingWasteCollection($data, $isEdit = false, $oldQty = 0)
    {
        if (!$data || empty($data->facility_id) || empty($data->waste_type_id)) {
            return;
        }

        $facility = RecyclingFacility::find($data->facility_id);
        $setting = Setting::first();

        $balance = RecyclingFacilityBalance::firstOrCreate(
            [
                'facility_id' => $data->facility_id,
                'waste_type_id' => $data->waste_type_id,
            ],
            [
                'unit_id' => $setting->unit_id,
                'quantity' => 0,
            ]
        );

        // Convert input quantity to balance unit
        $convertedQty = Unit::convertQuantity($data->unit_id, $data->quantity, $setting->unit_id);
        $oldConvertedQty = $isEdit ? Unit::convertQuantity($data->unit_id, $oldQty, $setting->unit_id) : 0;

        if ($isEdit) {
            $diff = $convertedQty - $oldConvertedQty;
            $balance->quantity += $diff;
        } else {
            $balance->quantity += $convertedQty;
        }

        $balance->save();
    }




    public function editRecyclingWasteCollection($id)
    {
        $data = RecyclingWasteCollection::with('items.wasteType')->find($id);
        return response()->json(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteRecyclingWasteCollection($id)
    {
        if (!Auth::user()->can('delete recycling waste collection')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete"]);
        }

        try {
            RecyclingWasteCollection::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Recycling Collection", "Archived Id $id");
            return response()->json(['status' => 200, 'message' => "Recycling waste collection archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    // RECYCLING MATERIAL

    public function recycling_material()
    {
        if (!Auth::user()->can('manage recycling material')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();

        $facilities = RecyclingFacility::where('archive',0);
        $products = [];
        $balance = [];
        if ($user && $user->role == 4) {
            $facilityIds = $user->facilities()->pluck('facility_id');
            $facilities->whereIn('id', $facilityIds);

            $products = Product::whereIn('facility_id', $facilityIds)->get();
            $balance = RecyclingFacilityBalance::where('archive', 0)->whereIn('facility_id', $facilityIds)->get();
        }



        $facilities = $facilities->orderBy('id', 'desc')->get();

        $colors = Color::get();
        $units = Unit::where('archive', 0)->get();


        return view('recycling_material.index', compact('colors', 'products','balance', 'units', 'facilities'));
    }

    public function recycling_material_view()
    {
        if (!Auth::user()->can('manage recycling material')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();
        $data = RecyclingMaterial::where('archive', 0);

        if ($user && $user->role == 4) {
            $facilityIds = $user->facilities()->pluck('facility_id');
            $data->whereIn('facility_id', $facilityIds);
        }

        $data = $data->orderBy('id', 'desc')->get();
        return view('recycling_material.view', compact('data'));
    }

    public function recycling_material_details($id)
    {
        if (!Auth::user()->can('show recycling material')) {
            return abort(401, __('Permission denied.'));
        }

        $id = Crypt::decrypt($id);
        $material = RecyclingMaterial::find($id);
        return view('recycling_material.show', compact('material'));
    }

    public function getRecyclingMaterial(Request $request)
    {
        if (!\Auth::user()->can('show recycling material')) {
            return response()->json(['status' => 500, 'message' => "No permission to view recycling material"]);
        }

        $user = \Auth::user();
        $collection_center_id = $request->collection_center_id;
        $facility_id = $request->facility_id;
        $search_text = $request->search_text;

        $data = RecyclingMaterial::with(['facility','wasteType','unit','outputProduct'])
            ->where('archive', 0);



        if ($user && $user->role == 4) {
            $facilities = $user->facilities()->pluck('facility_id');
            $data->whereIn('facility_id', $facilities);
        }

        if (!empty($facility_id) && $facility_id !== "All") {
            $data->where('facility_id', $facility_id);
        }


        $result = $data->orderBy('id', 'desc')->get();
        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function getRecyclingMaterialRow($id)
    {
        if (!\Auth::user()->can('show recycling material')) {
            return response()->json(['status' => 500, 'message' => "No permission to view recycling material"]);
        }

        $data = RecyclingMaterial::with(['facility','wasteType','unit','outputProduct'])
            ->where('archive', 0);

        $result = $data->where('id', $id)->orderBy('id', 'desc')->first();
        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }


    public function saveRecyclingMaterial(Request $request)
    {
        $rules = [
            'facility_id' => 'required|exists:recycling_facilities,id',
            'input_quantity' => 'required|numeric|min:0',
            'waste_type_id' => 'required|exists:waste_types,id',
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
        }

        try {
            DB::beginTransaction();
            $setting = Setting::first();

            $hidden_id = $request->input('hidden_id');
            $waste_type_id = $request->input('waste_type_id');
            $facility_id = $request->input('facility_id');
            $input_quantity = $request->input('input_quantity');

            $balance = RecyclingFacilityBalance::where('archive', 0)->where('facility_id', $facility_id)->where('waste_type_id',$waste_type_id)->first();
            $unit_id =  $balance->unit_id ?? $setting->unit_id ?? "";
            $oldUnitId = null;
            $oldQty = null;

            if (!$balance) {
                return response()->json([
                    'status' => 500,
                    'message' => 'No available balance for selected waste type at this facility.'
                ]);
            }

            $availableQty = $balance->quantity;

            // Handle edit case: add back the old quantity (it's not yet deducted)
            if (!empty($hidden_id)) {
                $existingItem = RecyclingMaterial::find(Crypt::decrypt($hidden_id));
                if ($existingItem) {
                    $availableQty += $existingItem->input_quantity;
                }
            }

            // Final check: requested input must not exceed available quantity
            if ($input_quantity > $availableQty) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Input quantity exceeds available balance. Available: ' . $availableQty
                ]);
            }


            $data = [
                'facility_id' => $facility_id,
                'process' => $request->process,
                'input_quantity' => $input_quantity,
                'waste_type_id' => $waste_type_id,
                'unit_id' => $unit_id,
                'output_product_id' => $request->output_product_id,
                'output_product_quantity' => $request->output_product_quantity,
                'archive' => 0,
            ];

            if (!empty($hidden_id)) {
                if (!Auth::user()->can('edit recycling material')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit recycled material"]);
                }


                $existingItem = RecyclingMaterial::find($hidden_id);
                if ($existingItem) {
                    $oldQty = $existingItem->input_quantity;
                    $oldUnitId = $existingItem->unit_id;
                }

                $realId = Crypt::decrypt($hidden_id);
                RecyclingMaterial::where('id', $realId)->update($data);
                $instance = RecyclingMaterial::find($realId);





                $this->saveActivityLog("Recycled Material", "Updated Id $realId");
                $message = "Recycled material updated successfully";
            } else {
                if (!Auth::user()->can('create recycling material')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create recycled material"]);
                }

                $data['created_by'] = Auth::id();
                $data['created_at'] = now();
                $instance = RecyclingMaterial::create($data);
                $this->saveActivityLog("Recycled Material", "Created Id $instance->id");
                $message = "Recycled material created successfully";
            }

            $this->syncRecyclingMaterial(
                (object)[
                    'facility_id' => $instance->facility_id,
                    'waste_type_id' => $waste_type_id,
                    'unit_id' => $unit_id,
                    'input_quantity' => $input_quantity ?? 0,
                    'old_unit_id' => $oldUnitId
                ],
                isset($hidden_id),
                $oldQty
            );

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function syncRecyclingMaterial($data, $isEdit = false, $oldQty = 0)
    {
        if (!$data || empty($data->facility_id) || empty($data->waste_type_id)) {
            return;
        }

        $facility = RecyclingFacility::find($data->facility_id);
        $setting = Setting::first();

        // Update Waste Balance
        $balance = RecyclingFacilityBalance::firstOrCreate(
            [
                'facility_id' => $data->facility_id,
                'waste_type_id' => $data->waste_type_id,
            ],
            [
                'unit_id' => $data->unit_id,
                'quantity' => 0,
            ]
        );

        $convertedQty = Unit::convertQuantity($data->unit_id, $data->input_quantity, $data->unit_id);
        $oldConvertedQty = $isEdit ? Unit::convertQuantity($data->unit_id, $oldQty, $data->unit_id) : 0;

        $balance->quantity -= $isEdit ? ($convertedQty - $oldConvertedQty) : $convertedQty;
        $balance->save();

        // Update Output Product Quantity
        if (!empty($data->output_product_id) && !empty($data->output_product_quantity)) {
            $product = Product::find($data->output_product_id);
            if ($product) {
                $product->quantity += $data->output_product_quantity;
                $product->save();
            }
        }
    }

    public function editRecyclingMaterial($id)
    {
        $data = RecyclingMaterial::with(['wasteType', 'unit', 'outputProduct'])->find($id);
        return response()->json(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteRecyclingMaterial($id)
    {
        if (!Auth::user()->can('delete recycling material')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete"]);
        }

        try {
            RecyclingMaterial::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Recycling Material", "Archived Id $id");
            return response()->json(['status' => 200, 'message' => "Recycling material archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }



}
