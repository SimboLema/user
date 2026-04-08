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
use App\Models\Country;
use App\Models\Region;
use App\Models\User;
use App\Models\BeamSetting;
use App\Models\WastePicker;
use App\Models\District;
use App\Models\Unit;
use App\Models\Producer;
use App\Models\Setting;
use App\Models\ProducerUser;
use App\Models\Ward;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\AuthenticationController;

class CompanyController extends Controller
{
    use ActivityLoggableTrait;

    public function collection_center()
    {
        if (!\Auth::user()->can('manage collection center')) {
            return abort(401, __('Permission denied.'));
        }

        $countries = Country::get();
        $units = Unit::where('archive', 0)->orderBy('id', 'desc')->get();

        return view('collection_center.index',compact('countries','units'));
    }

    public function collection_center_view()
    {
        if (!\Auth::user()->can('manage collection center')) {
            return abort(401, __('Permission denied.'));
        }

        $user = null;
        if(Auth::check()){
            $user = \Auth::user();
        }
        $data = CollectionCenter::where('archive', 0);

        if ($user && $user->role == 2) { // Waste Picker
            $wastePicker = $user->waste_picker;
            if ($wastePicker && $wastePicker->collection_center_id) {
                $data->where('id', $wastePicker->collection_center_id);
            } else {
                $data->whereNull('id'); // No match if not assigned
            }
        }

        if ($user && $user->role == 3) { // Collection Center User
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $data->whereIn('id', $centerIds);
        }


        $data = $data->orderBy('id', 'desc')->get();
        return view('collection_center.view', compact('data'));
    }

    public function getCollectionCenter(Request $request)
    {
        if (!\Auth::user()->can('show collection center')) {
            return response()->json(['status' => 500, 'message' => "No permission to view collection center"]);
        }

        $collection_center_id = $request->collection_center_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = CollectionCenter::with('collectionCenterUsers.user')->where('archive', 0);

        // If user is role 2, restrict to their own records
        if ($user && $user->role == 3) {
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $data->whereIn('id', $centerIds);
        }

        // Filter by collection center
        if (!empty($collection_center_id) && $collection_center_id !== "All") {
            $data->where('id', $collection_center_id);
        }

        // Search filter in both WastePicker and User table
        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->where('name', 'like', "%$search_text%")
                ->orWhereHas('district', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%");
                })
                ->orWhereHas('ward', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%");
                });
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveCollectionCenter(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'district_id' => 'required',
                'ward_id' => 'required',
                'district_color' => 'nullable',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $phone = $request->input('phone');
            $phone = BeamSetting::formatPhoneNumber($phone);
            $setting = Setting::first();

            $data = [
                'name' => $request->name,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'district_color' => $request->district_color,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'unit_id' => $setting->unit_id ?? 1,
                'archive' => 0,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit collection center')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit collection center"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                CollectionCenter::where('id', $id)->update($data);
                $cc = CollectionCenter::find($id);
                $message = 'Collection Center updated successfully';
                $this->saveActivityLog("Collection Center", "Update Collection Center Id " . $id);
            } else {
                if ( Auth::check() && !\Auth::user()->can('create collection center')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create collection center"]);
                }

                $validator = \Validator::make($request->all(), [
                    'name' => 'required|unique:collection_centers,name',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
                }

                $data['created_by'] = Auth::check() ? Auth::user()->id : "";
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $cc = CollectionCenter::create($data);

                ## save collection center user
                if($phone){
                    $newRequest = new \Illuminate\Http\Request(array_merge($request->all(), [
                        'name' => $request->full_name,
                        'hidden_id' => "",
                        'collection_center_id' => $cc->id,
                    ]));

                    $response = $this->saveCollectionCenterUser($newRequest);
                    $responseData = $response->getData(true);

                    if ($responseData['status'] != 200) {
                        DB::rollback();
                        return response()->json([
                            'status' => 500,
                            'message' =>  $responseData['message']
                        ]);
                    }
                }



                $message = 'Collection Center saved successfully';
                $this->saveActivityLog("Collection Center", "Save Collection Center Id " . $cc->id);
            }



            DB::commit();
            return response()->json(['status' => 200, 'message' => $message,'collection_center_id'=>$cc->id,'redirect' => "/token_login?email=" . $phone]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editCollectionCenter($id)
    {
        $data = CollectionCenter::with(['district.region.country','ward'])->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteCollectionCenter($id)
    {
        if (!\Auth::user()->can('delete collection center')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete collection center"]);
        }

        try {
            CollectionCenter::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Collection Center", "Archive Collection Center Id " . $id);
            return response()->json(['status' => 200, 'message' => "Collection Center archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    public function collection_center_user()
    {
        if (!\Auth::user()->can('manage collection center user')) {
            return abort(401, __('Permission denied.'));
        }

        $user = null;
        if(Auth::check()){
            $user = \Auth::user();
        }
        $collection_centers = CollectionCenter::where('archive', 0);

        if ($user && $user->role == 2) { // Waste Picker
            $wastePicker = $user->waste_picker;
            if ($wastePicker && $wastePicker->collection_center_id) {
                $collection_centers->where('id', $wastePicker->collection_center_id);
            } else {
                $collection_centers->whereNull('id'); // No match if not assigned
            }
        }

        if ($user && $user->role == 3) { // Collection Center User
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $collection_centers->whereIn('id', $centerIds);
        }

        $collection_centers = $collection_centers->orderBy('id', 'desc')->get();


        return view('collection_center.user',compact('collection_centers'));
    }

    public function collection_center_user_view()
    {
        if (!\Auth::user()->can('manage collection center user')) {
            return abort(401, __('Permission denied.'));
        }

        $user = null;
        if(Auth::check()){
            $user = \Auth::user();
        }
        $data = CollectionCenterUser::where('archive', 0);

        if ($user && $user->role == 2) { // Waste Picker
            $wastePicker = $user->waste_picker;
            if ($wastePicker && $wastePicker->collection_center_id) {
                $data->where('collection_center_id', $wastePicker->collection_center_id);
            } else {
                $data->whereNull('collection_center_id'); // No match if not assigned
            }
        }

        if ($user && $user->role == 3) { // Collection Center User
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $data->whereIn('collection_center_id', $centerIds);
        }


        $data = $data->orderBy('id', 'desc')->get();

        return view('collection_center.user_view', compact('data'));
    }

    public function getCollectionCenterUser(Request $request)
    {
        if (!\Auth::user()->can('show collection center user')) {
            return response()->json(['status' => 500, 'message' => "No permission to view collectors"]);
        }

        $collection_center_id = $request->collection_center_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = CollectionCenterUser::with('user') // Eager load related user
            ->where('archive', 0);

        // If user is role 2, restrict to their own records
        if ($user && $user->role == 3) {
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $data->whereIn('collection_center_id', $centerIds);
        }

        // Filter by collection center
        if (!empty($collection_center_id) && $collection_center_id !== "All") {
            $data->where('collection_center_id', $collection_center_id);
        }

        // Search filter in both WastePicker and User table
        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->where('id_type', 'like', "%$search_text%")
                ->orWhere('id_number', 'like', "%$search_text%")
                ->orWhereHas('user', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%")
                        ->orWhere('email', 'like', "%$search_text%")
                        ->orWhere('phone', 'like', "%$search_text%");
                });
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveCollectionCenterUser(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'name'      => 'required|string|max:255',
                'phone'     => 'required|min:10',
                'gender' => 'required',
                'collection_center_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $name = $request->input('name');
            $phone = $request->input('phone');
            $email = $request->input('email');
            $gender = $request->input('gender');
            $password = Hash::make($request->input('password'));
            $user_id = Auth::check() ?  \Auth::user()->id : "";
            $phone = BeamSetting::formatPhoneNumber($phone);
            $role_id = 3;

            // Prepare array with data
            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?? "",
                'gender' => $gender,
                'role' => $role_id,
            ];

            // Handle logo upload
            if ($request->hasFile('avatar')) {
                // Ensure the 'collections' directory exists
                if (!Storage::disk('public')->exists('users')) {
                    Storage::disk('public')->makeDirectory('users');
                }

                // Store the logo in the 'collections' directory within the 'public' disk
                $data['avatar'] =$request->file('avatar')->store('users', 'public');
            }

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit collection center user')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit Collector"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                User::where($condition)->update($data);

                $instance = User::find($condition['id']);
                $role = Role::find($role_id);

                if ($instance && $role) {
                    $instance->roles()->sync([$role_id]);
                }

                $message = 'Collector updated successfully';
                $this->saveActivityLog("Collector", "Update User Id " . $condition['id']);
            } else {
                if (Auth::check() && !\Auth::user()->can('create collection center user')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create collector"]);
                }

                $validator = \Validator::make($request->all(), [
                    'password'  => 'required|string|min:6',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status'  => 500,
                        'message' => $validator->errors()->first()
                    ]);
                }

                if (User::where('phone', $phone)->where('is_account_verified',1)->exists()) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Phone number is already registered.',
                    ], 200);
                }

                // Add additional fields for new records
                $data['is_account_verified'] = Auth::check() ? 1 : 1;
                $data['password'] = $password;
                $data['created_by'] = $user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = User::create($data);

                // Find the role by ID and assign it to the user
                $role = Role::find($role_id);
                if ($role) {
                    $instance->assignRole($role);  // Assign the role to the user
                }

                // Optionally send verification SMS
                $token = rand(100000, 999999);
                $instance->token = $token;
                $instance->token_expired = now()->addHour();
                $instance->save();

                $AuthenticationController = new AuthenticationController();
                $AuthenticationController->send_sms($instance->phone, $token);

                $message = 'Collector account created successfully';
                $this->saveActivityLog("Collection Center User", "Save Collection Center User id " . $instance->id);
            }

            $result = $this->saveCollector($request,$instance);
            if ($result['status'] != 200) {
                DB::rollback();
                return response()->json(['status' => 500, 'message' => $result['message']]);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message,'redirect' => "/token_login?email=" . $phone]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 500, 'message' =>$e->getMessage()]);
        }
    }

    public function saveCollector(Request $request,User $user)
    {
        try {
            $hidden_id = $request->input('hidden_id');
            $collection_center_id = $request->input('collection_center_id');
            $id_number = $request->input('id_number');
            $id_type = $request->input('id_type');

            $condition = [
                'user_id' => $user->id,

            ];
            $data = [
                'collection_center_id' => $collection_center_id,
                'id_type' => $id_type,
                'id_number' => $id_number,
                'created_by' => Auth::check() ? Auth::user()->id : "",
                'archive' => 0,
            ];

            CollectionCenterUser::updateOrCreate(
                $condition,$data
            );

            return ['status' => 200];

        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function editCollectionCenterUser($id)
    {
        $data = CollectionCenterUser::with('user')->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($data->user_id)]);
    }

    public function deleteCollectionCenterUser($id)
    {
        if (!\Auth::user()->can('delete collection center user')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete collection center user"]);
        }

        try {
            $collectionUser = CollectionCenterUser::find($id);
            User::where('id',$collectionUser->user_id)->delete();
            $collectionUser->delete();

            $this->saveActivityLog("Collection Center User", "Archive Collection Center User Id " . $id);
            return response()->json(['status' => 200, 'message' => "Collection Center User archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }



    public function recycling_facility()
    {
        if (!\Auth::user()->can('manage recycling facility')) {
            return abort(401, __('Permission denied.'));
        }
        $countries = Country::get();
        $units = Unit::where('archive', 0)->orderBy('id', 'desc')->get();
        return view('recycling_facility.index',compact('countries','units'));
    }

    public function recycling_facility_view()
    {
        if (!\Auth::user()->can('manage recycling facility')) {
            return abort(401, __('Permission denied.'));
        }


        $user = null;
        if(Auth::check()){
            $user = \Auth::user();
        }
        $data = RecyclingFacility::where('archive', 0);

        if ($user && $user->role == 4) { // Collection Center User
            $centerIds = $user->facilities()->pluck('facility_id');
            $data->whereIn('id', $centerIds);
        }


        $data = $data->orderBy('id', 'desc')->get();

        return view('recycling_facility.view', compact('data'));
    }

    public function getRecyclingFacility(Request $request)
    {


        $facility_id = $request->facility_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = RecyclingFacility::with('facilityUsers.user')->where('archive', 0);

        // If user is role 4, restrict to their own records
        if ($user && $user->role == 4) {
            $centerIds = $user->facilities()->pluck('facility_id');
            $data->whereIn('id', $centerIds);
        }

        // Filter by collection center
        if (!empty($facility_id) && $facility_id !== "All") {
            $data->where('id', $facility_id);
        }

        // Search filter in both WastePicker and User table
        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->where('name', 'like', "%$search_text%")
                ->orWhereHas('district', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%");
                })
                ->orWhereHas('ward', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%");
                });
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveRecyclingFacility(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'district_id' => 'required',
                'ward_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $phone = $request->input('phone');
            $phone = BeamSetting::formatPhoneNumber($phone);
            $setting = Setting::first();

            $data = [
                'name' => $request->name,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'unit_id' => $setting->unit_id ?? 1,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'archive' => 0,
            ];

            // Handle logo upload
            if ($request->hasFile('avatar')) {
                // Ensure the 'collections' directory exists
                if (!Storage::disk('public')->exists('users')) {
                    Storage::disk('public')->makeDirectory('users');
                }

                // Store the logo in the 'collections' directory within the 'public' disk
                $data['avatar'] =$request->file('avatar')->store('users', 'public');
            }

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit recycling facility')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit recycling facility"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                RecyclingFacility::where('id', $id)->update($data);
                $facility = RecyclingFacility::find($id);
                $message = 'Recycling Facility updated successfully';
                $this->saveActivityLog("Recycling Facility", "Update Recycling Facility Id " . $id);
            } else {
                if (Auth::check() && !\Auth::user()->can('create recycling facility')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create recycling facility"]);
                }

                $validator = \Validator::make($request->all(), [
                    'name' => 'required|unique:recycling_facilities,name',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
                }

                $data['created_by'] = Auth::check() ? Auth::user()->id : "";
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $facility = RecyclingFacility::create($data);

                ## save facility user
                if($phone){
                    $newRequest = new \Illuminate\Http\Request(array_merge($request->all(), [
                        'name' => $request->full_name,
                        'hidden_id' => "",
                        'facility_id' => $facility->id,
                    ]));

                    $response = $this->saveRecyclingFacilityUser($newRequest);
                    $responseData = $response->getData(true);

                    if ($responseData['status'] != 200) {
                        DB::rollback();
                        return response()->json([
                            'status' => 500,
                            'message' =>  $responseData['message']
                        ]);
                    }
                }


                $message = 'Recycling Facility saved successfully';
                $this->saveActivityLog("Recycling Facility", "Save Recycling Facility Id " . $facility->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message,'facility_id'=>$facility->id,'redirect' => "/token_login?email=" . $phone]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editRecyclingFacility($id)
    {
        $data = RecyclingFacility::with(['district.region.country','ward'])->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteRecyclingFacility($id)
    {
        if (!\Auth::user()->can('delete recycling facility')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete recycling facility"]);
        }

        try {
            RecyclingFacility::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Recycling Facility", "Archive Recycling Facility Id " . $id);
            return response()->json(['status' => 200, 'message' => "Recycling Facility archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function recycling_facility_user()
    {
        if (!\Auth::user()->can('manage recycling facility user')) {
            return abort(401, __('Permission denied.'));
        }

        $user = Auth::check() ? \Auth::user() : null;
        $recycling_facilities = RecyclingFacility::where('archive', 0);

        if ($user && $user->role == 3) { // Recycling Facility User
            $facilityIds = $user->facilities()->pluck('recycling_facility_id');
            $recycling_facilities->whereIn('id', $facilityIds);
        }

        $recycling_facilities = $recycling_facilities->orderBy('id', 'desc')->get();

        return view('recycling_facility.user', compact('recycling_facilities'));
    }

    public function recycling_facility_user_view()
    {
        if (!\Auth::user()->can('manage recycling facility user')) {
            return abort(401, __('Permission denied.'));
        }

        $user = Auth::check() ? \Auth::user() : null;
        $data = RecyclingFacilityUser::where('archive', 0);

        if ($user && $user->role == 3) {
            $facilityIds = $user->facilities()->pluck('recycling_facility_id');
            $data->whereIn('recycling_facility_id', $facilityIds);
        }

        $data = $data->orderBy('id', 'desc')->get();

        return view('recycling_facility.user_view', compact('data'));
    }

    public function getRecyclingFacilityUser(Request $request)
    {
        if (!\Auth::user()->can('show recycling facility user')) {
            return response()->json(['status' => 500, 'message' => "No permission to view recycling facility agent"]);
        }

        $facility_id = $request->facility_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = RecyclingFacilityUser::with('user')->where('archive', 0);

        // If user is role 4, restrict to their own records
        if ($user && $user->role == 4) {
            $centerIds = $user->facilities()->pluck('facility_id');
            $data->whereIn('facility_id', $centerIds);
        }

        // Filter by collection center
        if (!empty($facility_id) && $facility_id !== "All") {
            $data->where('facility_id', $facility_id);
        }

        // Search filter in both WastePicker and User table
        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->where('id_type', 'like', "%$search_text%")
                ->where('id_number', 'like', "%$search_text%")
                ->orWhereHas('user', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%")
                    ->orWhere('phone', 'like', "%$search_text%")
                    ->orWhere('email', 'like', "%$search_text%");
                });
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveRecyclingFacilityUser(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|min:10',
                'gender' => 'required',
                'facility_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $name = $request->input('name');
            $phone = BeamSetting::formatPhoneNumber($request->input('phone'));
            $email = $request->input('email');
            $gender = $request->input('gender');
            $password = Hash::make($request->input('password'));
            $user_id = Auth::check() ? \Auth::user()->id : "";
            $role_id = 4;

            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?? "",
                'gender' => $gender,
                'role' => $role_id,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit recycling facility user')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit user"]);
                }

                $data['updated_at'] = now();
                $condition = ['id' => Crypt::decrypt($hidden_id)];

                User::where($condition)->update($data);
                $instance = User::find($condition['id']);
                $role = Role::find($role_id);
                if ($instance && $role) {
                    $instance->roles()->sync([$role_id]);
                }

                $message = 'User updated successfully';
                $this->saveActivityLog("Recycling Facility User", "Update User Id " . $condition['id']);
            } else {
                if (Auth::check() && !\Auth::user()->can('create recycling facility user')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create user"]);
                }

                $validator = \Validator::make($request->all(), [
                    'password' => 'required|string|min:6',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
                }

                if (User::where('phone', $phone)->where('is_account_verified',1)->exists()) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Phone number is already registered.',
                    ], 200);
                }

                $data['is_account_verified'] = Auth::check() ? 1 : 1;
                $data['password'] = $password;
                $data['created_by'] = $user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = User::create($data);
                $role = Role::find($role_id);
                if ($role) {
                    $instance->assignRole($role);
                }

                // Optionally send verification SMS
                $token = rand(100000, 999999);
                $instance->token = $token;
                $instance->token_expired = now()->addHour();
                $instance->save();

                $AuthenticationController = new AuthenticationController();
                $AuthenticationController->send_sms($instance->phone, $token);

                $message = 'User saved successfully';
                $this->saveActivityLog("Recycling Facility User", "Save User Id " . $instance->id);
            }

            $result = $this->saveFacilityUser($request, $instance);
            if ($result['status'] != 200) {
                DB::rollback();
                return response()->json(['status' => 500, 'message' => $result['message']]);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message,'redirect' => "/token_login?email=" . $phone]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function saveFacilityUser(Request $request, User $user)
    {
        try {
            $hidden_id = $request->input('hidden_id');
            $facility_id = $request->input('facility_id');
            $id_number = $request->input('id_number');
            $id_type = $request->input('id_type');

            $condition = ['user_id' => $user->id];
            $data = [
                'facility_id' => $facility_id,
                'id_type' => $id_type,
                'id_number' => $id_number,
                'created_by' => Auth::check() ? Auth::user()->id : "",
                'archive' => 0,
            ];

            RecyclingFacilityUser::updateOrCreate($condition, $data);

            return ['status' => 200, 'message' => 'Saved'];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function editRecyclingFacilityUser($id)
    {
        $data = RecyclingFacilityUser::with('user')->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($data->user_id)]);
    }

    public function deleteRecyclingFacilityUser($id)
    {
        if (!\Auth::user()->can('delete recycling facility user')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete user"]);
        }

        try {
            $facilityUser = RecyclingFacilityUser::find($id);
            User::where('id', $facilityUser->user_id)->delete();
            $facilityUser->delete();

            $this->saveActivityLog("Recycling Facility User", "delete User Id " . $id);
            return response()->json(['status' => 200, 'message' => "User deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function waste_picker()
    {
        if (!\Auth::user()->can('manage waste picker')) {
            return abort(401, __('Permission denied.'));
        }

        $user = Auth::check() ? \Auth::user() : null;
        $collection_centers = CollectionCenter::where('archive', 0);

        if ($user && $user->role == 3) {
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $collection_centers->whereIn('id', $centerIds);
        }

        $collection_centers = $collection_centers->orderBy('id', 'desc')->get();

        return view('waste_picker.index', compact('collection_centers'));
    }

    public function waste_picker_view(Request $request)
    {
        if (!\Auth::user()->can('manage waste picker')) {
            return abort(401, __('Permission denied.'));
        }

        $user = Auth::check() ? \Auth::user() : null;
        $data = WastePicker::where('archive', 0);

        if ($user && $user->role == 2) {
            $data->whereIn('user_id', $user->id);
        }

        if ($user && $user->role == 3) {
            $centerIds = $user->collection_centers()->pluck('collection_center_id');
            $data->whereIn('collection_center_id', $centerIds);
        }

        $data = $data->orderBy('id', 'desc')->get();

        return view('waste_picker.view', compact('data'));
    }

    public function getWastePicker(Request $request)
    {
        if (!\Auth::user()->can('show waste picker')) {
            return response()->json(['status' => 500, 'message' => "No permission to view waste picker"]);
        }

        $collection_center_id = $request->collection_center_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = WastePicker::with('user') // Eager load related user
            ->where('archive', 0);

        // If user is role 2, restrict to their own records
        if ($user && $user->role == 2) {
            $data->where('user_id', $user->id);
        }

        // Filter by collection center
        if (!empty($collection_center_id) && $collection_center_id !== "All") {
            $data->where('collection_center_id', $collection_center_id);
        }

        // Search filter in both WastePicker and User table
        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->where('id_type', 'like', "%$search_text%")
                ->orWhere('id_number', 'like', "%$search_text%")
                ->orWhereHas('user', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%")
                        ->orWhere('email', 'like', "%$search_text%")
                        ->orWhere('phone', 'like', "%$search_text%");
                });
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }



    public function saveWastePicker(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|min:10',
                'gender' => 'required',
                'collection_center_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $name = $request->input('name');
            $phone = BeamSetting::formatPhoneNumber($request->input('phone'));
            $email = $request->input('email');
            $gender = $request->input('gender');
            $password = Hash::make($request->input('password'));
            $user_id =  Auth::check() ? \Auth::user()->id : "";
            $role_id = 2;

            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?? "",
                'gender' => $gender,
                'role' => $role_id,
            ];

            // Handle logo upload
            if ($request->hasFile('avatar')) {
                // Ensure the 'collections' directory exists
                if (!Storage::disk('public')->exists('users')) {
                    Storage::disk('public')->makeDirectory('users');
                }

                // Store the logo in the 'collections' directory within the 'public' disk
                $data['avatar'] =$request->file('avatar')->store('users', 'public');
            }

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit waste picker')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit waste picker"]);
                }

                $data['updated_at'] = now();
                $condition = ['id' => Crypt::decrypt($hidden_id)];

                User::where($condition)->update($data);
                $instance = User::find($condition['id']);
                $role = Role::find($role_id);
                if ($instance && $role) {
                    $instance->roles()->sync([$role_id]);
                }

                $message = 'Waste Picker updated successfully';
                $this->saveActivityLog("Waste Picker", "Update User Id " . $condition['id']);
            } else {
                if ( Auth::check()  && !\Auth::user()->can('create waste picker')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create waste picker"]);
                }

                if (User::where('phone', $phone)->where('is_account_verified',1)->exists()) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Phone number is already registered.',
                    ], 200);
                }

                $data['is_account_verified'] = Auth::check() ? 1 : 1;
                $data['password'] = $password ?? 123456;
                $data['created_by'] = $user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = User::create($data);
                $role = Role::find($role_id);
                if ($role) {
                    $instance->assignRole($role);
                }

                $message = 'Waste Picker saved successfully';
                $this->saveActivityLog("Waste Picker", "Save User Id " . $instance->id);
            }

            $this->savePicker($request, $instance);

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message,'redirect' => "/token_login?email=" . $phone]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function savePicker(Request $request, User $user)
    {
        try {
            $hidden_id = $request->input('hidden_id');
            $collection_center_id = $request->input('collection_center_id');
            $id_number = $request->input('id_number');
            $id_type = $request->input('id_type');
            $gps_tracker_id = $request->input('gps_tracker_id');

            $condition = ['user_id' => $user->id];
            $data = [
                'collection_center_id' => $collection_center_id,
                'id_type' => $id_type,
                'id_number' => $id_number,
                'gps_tracker_id' => $gps_tracker_id,
                'created_by' =>  Auth::check() ? Auth::user()->id : "",
                'archive' => 0,
            ];

            WastePicker::updateOrCreate($condition, $data);

            return ['status' => 200, 'message' => 'Saved'];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function editWastePicker($id)
    {
        $data = WastePicker::with('user')->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($data->user_id)]);
    }

    public function deleteWastePicker($id)
    {
        if (!\Auth::user()->can('delete waste picker')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete waste picker"]);
        }

        try {
            $picker = WastePicker::find($id);
            User::where('id', $picker->user_id)->delete();
            $picker->delete();

            $this->saveActivityLog("Waste Picker", "Delete Waste Picker User Id " . $id);
            return response()->json(['status' => 200, 'message' => "Waste Picker deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function producer()
    {
        if (!\Auth::user()->can('manage production company')) {
            return abort(401, __('Permission denied.'));
        }

        $countries = Country::get();
        $units = Unit::where('archive', 0)->orderBy('id', 'desc')->get();

        return view('producer.index',compact('countries','units'));
    }

    public function producer_view()
    {
        if (!\Auth::user()->can('manage production company')) {
            return abort(401, __('Permission denied.'));
        }

        $user = null;
        if(Auth::check()){
            $user = \Auth::user();
        }
        $data = Producer::where('archive', 0);

        if ($user && $user->role == 5) { // Producer User
            $centerIds = $user->producers()->pluck('producer_id');
            $data->whereIn('id', $centerIds);
        }

        $data = $data->orderBy('id', 'desc')->get();
        return view('producer.view', compact('data'));
    }

    public function getProducer(Request $request)
    {


        $producer_id = $request->producer_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = Producer::with('producerUsers.user')->where('archive', 0);

        if ($user && $user->role == 5) {
            $centerIds = $user->producers()->pluck('producer_id');
            $data->whereIn('id', $centerIds);
        }

        if (!empty($producer_id) && $producer_id !== "All") {
            $data->where('id', $producer_id);
        }

        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->where('name', 'like', "%$search_text%")
                ->orWhereHas('district', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%");
                })
                ->orWhereHas('ward', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%");
                });
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveProducer(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'district_id' => 'required',
                'ward_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');
            $latitude = $request->input('latitude');
            $longitude = $request->input('longitude');
            $phone = $request->input('phone');
            $phone = BeamSetting::formatPhoneNumber($phone);
            $setting = Setting::first();

            $data = [
                'name' => $request->name,
                'district_id' => $request->district_id,
                'ward_id' => $request->ward_id,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'unit_id' => $setting->unit_id ?? 1,
                'archive' => 0,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit production company')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit production company"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                Producer::where('id', $id)->update($data);
                $cc = Producer::find($id);
                $message = 'Production company updated successfully';
                $this->saveActivityLog("Production company", "Update Producer Id " . $id);
            } else {
                if ( Auth::check() && !\Auth::user()->can('create production company')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create Production company"]);
                }

                $validator = \Validator::make($request->all(), [
                    'name' => 'required|unique:producers,name',
                ]);

                if ($validator->fails()) {
                    return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
                }

                $data['created_by'] = Auth::check() ? Auth::user()->id : "";
                $data['created_at'] = now();
                $data['updated_at'] = now();
                $cc = Producer::create($data);

                ## save facility user
                if($phone){
                    $newRequest = new \Illuminate\Http\Request(array_merge($request->all(), [
                        'name' => $request->full_name,
                        'hidden_id' => "",
                        'producer_id' => $cc->id,
                    ]));

                    $response = $this->saveProducerUser($newRequest);
                    $responseData = $response->getData(true);

                    if ($responseData['status'] != 200) {
                        DB::rollback();
                        return response()->json([
                            'status' => 500,
                            'message' =>  $responseData['message']
                        ]);
                    }
                }


                $message = 'Production company saved successfully';
                $this->saveActivityLog("Production company", "Save Producer Id " . $cc->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message, 'producer_id' => $cc->id,'redirect' => "/token_login?email=" . $phone,]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editProducer($id)
    {
        $data = Producer::with(['district.region.country','ward'])->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteProducer($id)
    {
        if (!\Auth::user()->can('delete production company')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete producer company"]);
        }

        try {
            Producer::where('id', $id)->update(['archive' => 1, 'updated_at' => now()]);
            $this->saveActivityLog("Producer", "Archive Producer Id " . $id);
            return response()->json(['status' => 200, 'message' => "Production Company deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function producer_user()
    {
        if (!\Auth::user()->can('manage producer')) {
            return abort(401, __('Permission denied.'));
        }

        $user = null;
        if(Auth::check()){
            $user = \Auth::user();
        }
        $producers = Producer::where('archive', 0);

        if ($user && $user->role == 5) { // Producer User
            $producerIds = $user->producers()->pluck('producer_id');
            $producers->whereIn('id', $producerIds);
        }

        $producers = $producers->orderBy('id', 'desc')->get();

        return view('producer.user',compact('producers'));
    }

    public function producer_user_view()
    {
        if (!\Auth::user()->can('manage producer')) {
            return abort(401, __('Permission denied.'));
        }

        $user = null;
        if(Auth::check()){
            $user = \Auth::user();
        }
        $data = ProducerUser::where('archive', 0);

        if ($user && $user->role == 5) { // Producer User
            $producerIds = $user->producers()->pluck('producer_id');
            $data->whereIn('producer_id', $producerIds);
        }

        $data = $data->orderBy('id', 'desc')->get();

        return view('producer.user_view', compact('data'));
    }

    public function getProducerUser(Request $request)
    {
        if (!\Auth::user()->can('show producer')) {
            return response()->json(['status' => 500, 'message' => "No permission to view producers"]);
        }

        $producer_id = $request->producer_id;
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = ProducerUser::with('user')
            ->where('archive', 0);

        if ($user && $user->role == 5) {
            $producerIds = $user->producers()->pluck('producer_id');
            $data->whereIn('producer_id', $producerIds);
        }

        if (!empty($producer_id) && $producer_id !== "All") {
            $data->where('producer_id', $producer_id);
        }

        if (!empty($search_text)) {
            $data->where(function ($q) use ($search_text) {
                $q->where('id_type', 'like', "%$search_text%")
                ->orWhere('id_number', 'like', "%$search_text%")
                ->orWhereHas('user', function ($uq) use ($search_text) {
                    $uq->where('name', 'like', "%$search_text%")
                        ->orWhere('email', 'like', "%$search_text%")
                        ->orWhere('phone', 'like', "%$search_text%");
                });
            });
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveProducerUser(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|min:10',
                'gender' => 'required',
                'producer_id' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $name = $request->input('name');
            $phone = $request->input('phone');
            $email = $request->input('email');
            $gender = $request->input('gender');
            $password = Hash::make($request->input('password'));
            $user_id = Auth::check() ? \Auth::user()->id : "";
            $phone = BeamSetting::formatPhoneNumber($phone);
            $role_id = 5;

            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?? "",
                'gender' => $gender,
                'role' => $role_id,
            ];

            // Handle logo upload
            if ($request->hasFile('avatar')) {
                // Ensure the 'collections' directory exists
                if (!Storage::disk('public')->exists('users')) {
                    Storage::disk('public')->makeDirectory('users');
                }

                // Store the logo in the 'collections' directory within the 'public' disk
                $data['avatar'] =$request->file('avatar')->store('users', 'public');
            }

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit producer')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit producer"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                User::where($condition)->update($data);

                $instance = User::find($condition['id']);
                $role = Role::find($role_id);

                if ($instance && $role) {
                    $instance->roles()->sync([$role_id]);
                }

                $message = 'Producer updated successfully';
                $this->saveActivityLog("Producer", "Update User Id " . $condition['id']);
            } else {
                if (Auth::check() && !\Auth::user()->can('create producer')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create producer"]);
                }

                $validator = \Validator::make($request->all(), [
                    'password' => 'required|string|min:6',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status' => 500,
                        'message' => $validator->errors()->first()
                    ]);
                }

                if (User::where('phone', $phone)->where('is_account_verified',1)->exists()) {
                    return response()->json([
                        'status' => 500,
                        'message' => 'Phone number is already registered.',
                    ], 200);
                }

                $data['is_account_verified'] = Auth::check() ? 1 : 1;
                $data['password'] = $password;
                $data['created_by'] = $user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = User::create($data);

                $role = Role::find($role_id);
                if ($role) {
                    $instance->assignRole($role);
                }

                // Optionally send verification SMS
                $token = rand(100000, 999999);
                $instance->token = $token;
                $instance->token_expired = now()->addHour();
                $instance->save();

                $AuthenticationController = new AuthenticationController();
                $AuthenticationController->send_sms($instance->phone, $token);



                $message = 'Producer account created successfully';
                $this->saveActivityLog("Producer User", "Save Producer User id " . $instance->id);
            }


            $result = $this->saveProducerPeople($request, $instance);
            if ($result['status'] != 200) {
                DB::rollback();
                return response()->json(['status' => 500, 'message' => $result['message']]);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message,'redirect' => "/token_login?email=" . $phone,]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function saveProducerPeople(Request $request, User $user)
    {
        try {
            $hidden_id = $request->input('hidden_id');
            $producer_id = $request->input('producer_id');
            $id_number = $request->input('id_number');
            $id_type = $request->input('id_type');

            $condition = [
                'user_id' => $user->id,
            ];
            $data = [
                'producer_id' => $producer_id,
                'id_type' => $id_type,
                'id_number' => $id_number,
                'created_by' => Auth::check() ? Auth::user()->id : "",
                'archive' => 0,
            ];

            ProducerUser::updateOrCreate($condition, $data);

            return ['status' => 200];
        } catch (\Exception $e) {
            return ['status' => 500, 'message' => $e->getMessage()];
        }
    }

    public function editProducerUser($id)
    {
        $data = ProducerUser::with('user')->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($data->user_id)]);
    }

    public function deleteProducerUser($id)
    {
        if (!\Auth::user()->can('delete producer')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete producer"]);
        }

        try {
            $producerUser = ProducerUser::find($id);
            User::where('id', $producerUser->user_id)->delete();
            $producerUser->delete();

            $this->saveActivityLog("Producer User", "Archive Producer User Id " . $id);
            return response()->json(['status' => 200, 'message' => "Producer archived successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


}
