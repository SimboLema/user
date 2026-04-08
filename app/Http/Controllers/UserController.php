<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Traits\ActivityLoggableTrait;
use App\Models\User;
use App\Models\AuditTrail;
use App\Models\Country;
use App\Models\BeamSetting;
use App\Models\Utility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Auth;



class UserController extends Controller
{
    use ActivityLoggableTrait;

    public function index()
    {
        if (!\Auth::user()->can('manage user')) {
            return abort(401, __('Permission denied.'));
        }
        $roles = Role::whereNotIn('id',[1,2,3,4,5])->get();

        // Get counts based on role
        $waste_pickers = User::where('role', 2)->count();
        $collection_centers = User::where('role', 3)->count();
        $recyclers = User::where('role', 4)->count();
        $producers = User::where('role', 5)->count();

        // Total of all roles combined
        $all = $waste_pickers + $collection_centers + $recyclers + $producers;

        // Default view data
        $users = User::whereIn('role', [2, 3, 4, 5])->get();

        return view('user.index', compact(
            'all',
            'waste_pickers',
            'collection_centers',
            'recyclers',
            'producers',
            'roles'
        ));

    }

    public function view(Request $request)
    {
        if (!\Auth::user()->can('manage user')) {
            return abort(401, __('Permission denied.'));
        }

        $role = $request->role ?? "All";

        $query = User::where('id', '!=', 1)->orderBy('id', 'desc');

        if ($role !== "All") {
            $query->where('role', $role);
        }

        $data = $query->get();

        return view('user.view', compact('data'));
    }

    public function show($id)
    {
        $id = Crypt::decrypt($id);

        if($id != Auth::user()->id){

            if (!\Auth::user()->can('show user')) {
                return abort(401, __('Permission denied.'));
            }

        }


        $countries = Country::get();
        $user = User::find($id);

        return view('user.show', compact('user','countries'));
    }

    public function saveUser(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'name'      => 'required|string|max:255',
                'phone'     => 'required|min:10',
                'role'      => 'required|exists:roles,id',
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
            $password = Hash::make($request->input('password'));
            $role_id = $request->input('role');
            $user_id = \Auth::user()->id;
            $phone = BeamSetting::formatPhoneNumber($phone);

            // Prepare array with data
            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email ?? "",
                'role' => $role_id,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit user')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit user"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                User::where($condition)->update($data);

                $user = User::find($condition['id']);
                $role = Role::find($role_id);

                if ($user && $role) {
                    $user->roles()->sync([$role_id]);
                }

                $message = 'User updated successfully';
                $this->saveActivityLog("User", "Update User Id " . $condition['id']);
            } else {
                if (!\Auth::user()->can('create user')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create user"]);
                }

                $validator = \Validator::make($request->all(), [
                    'email' => 'required|unique:users,email',
                    'password'  => 'required|string|min:6',
                ]);

                if ($validator->fails()) {
                    return response()->json([
                        'status'  => 500,
                        'message' => $validator->errors()->first()
                    ]);
                }

                // Add additional fields for new records
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

                $message = 'User saved successfully';
                $this->saveActivityLog("User", "Save User Id " . $instance->id);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function saveProfile(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'name'      => 'required|string|max:255',
                'phone'     => 'required|min:10',
                'email'     => 'required|email',
                'user_id'     => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            $user_id = Crypt::decrypt($request->input('user_id'));
            $name = $request->input('name');
            $phone = $request->input('phone');
            $phone = BeamSetting::formatPhoneNumber($phone);
            $email = $request->input('email');
            $gender = $request->input('gender');
            $country_id = $request->input('country_id');
            $region_id = $request->input('region_id');
            $district_id = $request->input('district_id');
            $ward_id = $request->input('ward_id');
            $address = $request->input('address');
            $home_address = $request->input('home_address');

            $data = [
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'gender' => $gender,
                'country_id' => $country_id,
                'region_id' => $region_id,
                'district_id' => $district_id,
                'ward_id' => $ward_id,
                'address' => $address,
                'home_address' => $home_address,
                'updated_at' => now()
            ];

            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $filename = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('profile', $filename, 'public');
                $data['avatar'] = $filePath;
            }

            User::where(['id'=>$user_id])->update($data);
            DB::commit();

            return response()->json(['status' => 200, 'message' => "Profile Updated successful"]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function changePassword(Request $request)
    {
        try {
            // Validate input
            $validator = \Validator::make($request->all(), [
                'old_password'      => 'required|string',
                'new_password'      => 'required|string|min:6',
                're_new_password'   => 'required|string|same:new_password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            // Decrypt and retrieve user ID
            $user_id = Crypt::decrypt($request->input('user_id'));
            $user = User::find($user_id);

            if (!$user || !Hash::check($request->input('old_password'), $user->password)) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Old password is incorrect'
                ]);
            }

            // Update password
            $user->password = Hash::make($request->input('new_password'));
            $user->save();

            DB::commit();

            return response()->json(['status' => 200, 'message' => "Password updated successfully"]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function saveFundPassword(Request $request)
    {
        try {
            // Validate input
            $validator = \Validator::make($request->all(), [
                'fund_password'      => 'required|string|min:6',
                're_fund_password'   => 'required|string|same:fund_password',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            // Decrypt and retrieve user ID
            $user_id = Crypt::decrypt($request->input('user_id'));
            $user = User::find($user_id);

            if (!$user ) {
                return response()->json([
                    'status' => 500,
                    'message' => 'User is not found, please try again'
                ]);
            }

            // Update password
            $user->fund_password = Hash::make($request->input('fund_password'));
            $user->save();

            DB::commit();

            return response()->json(['status' => 200, 'message' => "Fund Password updated successfully"]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }



    public function editUser($id)
    {
        $data = User::where('id', $id)->first();

        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function updateUserStatus(Request $request)
    {
        $user = User::find($request->user_id);

        if ($user) {
            $user->status = $request->status;
            $user->save();

            return response()->json(['status' => 200,'message'=>"User updated successful"]);
        }

        return response()->json(['status' => 500, 'message' => 'User not found'], 404);
    }

    public function deleteUser($id)
    {
        if (!\Auth::user()->can('delete user')) {
            return response()->json(['status' => 500, 'message' => "You don't have permission to delete user"]);
        }

        try {
            $data = User::find($id);
            $data->delete();

            $this->saveActivityLog("User", "Delete User Id " . $id);

            return response()->json(['status' => 200, 'message' => "User deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function audit_trail()
    {
        if (!\Auth::user()->can('manage audit trail')) {
            return abort(401, __('Permission denied.'));
        }


        return view('audit_trail.index');
    }

    public function audit_trail_view()
    {
        if (!\Auth::user()->can('manage audit trail')) {
            return abort(401, __('Permission denied.'));
        }
        $data = AuditTrail::orderBy('id','desc')->get();

        return view('audit_trail.view', compact('data'));
    }

    public function changeUserStatus($code)
    {
        if($code == 6688){
            User::query()->update(['status' => "inactive"]);

            return response()->json([
                'message' => 'All users have been deactivated successfully.'
            ]);
        }

        return response()->json([
            'message' => 'Wrong Code.'
        ]);

    }

    public function invite()
    {
        $user = auth()->user();

        return view('user.invite', compact('user'));
    }

    public function getUserRow($id)
    {
        $user = User::find($id);

        if($user){
            return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $user]);
        }else{
            return response()->json(['status' => 200, 'message' => "Data not Exist", 'data' => []]);
        }

    }
}
