<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Traits\ActivityLoggableTrait;
use App\Models\User;
use App\Models\Utility;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    use ActivityLoggableTrait;

    public function index()
    {

        if (!\Auth::user()->can('manage role')) {
            return abort(401, __('Permission denied.'));
        }
        $roles = Role::where('id', '!=', 1)->get();

        $modules = [
            'Authentication' => ['user', 'role', 'audit trail', 'user status'],
            // 'Company' => [
            //     'collection center',
            //     'collection center user',
            //     'waste picker',
            //     'recycle facility',
            //     'recycle facility user',
            //     'production company',
            //     'producer',
            //     'producer user'
            // ],
            'Operations' => [
                // 'waste collection',
                // 'recycling waste collection'
                'policy',
                'endorsement',
                'mta',
                'claim'
                ],
            'Settings' => [
                'setting',
                'smtp setting',
                'sms setting',
                'pusher setting',
                'notification template',
                'email template',
                // 'database backup',
            ],
            'Configuration' => [
                'country',
                'region',
                'district',
                'ward',
                // 'waste source',
                // 'waste type',
                // 'payment status',
                // 'color',
                // 'product'
            ],
            // 'Policy' => ['policy'],
            // 'Endorsement' => ['endorsement'],
            // 'MTA' => ['mta'],
            // 'Claim' => ['claim'],
            // 'Reports & Analytics' => ['report'],
            // 'Sales / Intermediary' => ['sales'],
            // 'Customer Actions' => ['customer action'],

        ];

        // Fetch permissions from the database (replace with your query as needed)
        $permissions = Permission::all();

        // return response()->json($permissions);

        return view('role.index', compact('roles', 'modules', 'permissions'));
    }

    public function view()
    {
        if (!\Auth::user()->can('manage role')) {
            return abort(401, __('Permission denied.'));
        }


        $data = Role::whereNot('id', 1)->orderBy('id', 'desc')->get();

        return view('role.view', compact('data'));
    }

    public function saveRole(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'name'      => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            // Get input values
            $hidden_id = $request->input('hidden_id');
            $name = $request->input('name');
            $permissions = $request->input('permissions', []); // Array of selected permissions

            $user_id = \Auth::user()->id;

            if (!empty($hidden_id)) {
                // Editing an existing role
                if (!\Auth::user()->can('edit role')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit role"]);
                }

                $roleId = Crypt::decrypt($hidden_id);
                $role = Role::findOrFail($roleId);

                // Check if the role name is changing and if the new name already exists
                if ($role->name !== $name) {
                    $existingRole = Role::where('name', $name)->where('id', '!=', $roleId)->first();
                    if ($existingRole) {
                        return response()->json(['status' => 500, 'message' => "A role `$name` already exists for guard `web`."]);
                    }
                }

                // Update role
                $role->update(['name' => $name, 'updated_at' => now()]);
                $message = 'Role updated successfully';
                $this->saveActivityLog("Role", "Update Role Id " . $role->id);
            } else {
                // Creating a new role
                if (!\Auth::user()->can('create role')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create role"]);
                }

                // Check if the role already exists
                $existingRole = Role::where('name', $name)->first();
                if ($existingRole) {
                    return response()->json(['status' => 500, 'message' => "A role `$name` already exists for guard `web`."]);
                }

                $role = Role::create([
                    'name' => $name,
                    'created_by' => $user_id,
                    'created_at' => now(),
                ]);
                $message = 'Role saved successfully';
                $this->saveActivityLog("Role", "Save Role Id " . $role->id);
            }

            // Assign selected permissions to the role
            $role->syncPermissions($permissions);

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }



    public function editRole($id)
    {
        $role = Role::findById($id);
        $permissions = $role->permissions->pluck('name')->toArray();

        echo json_encode(['data' => $role, 'permissions' => $permissions, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteRole($id)
    {
        if (!\Auth::user()->can('delete role')) {
            return response()->json(['status' => 500, 'message' => "You don't have permission to delete role"]);
        }

        try {
            $role = Role::findOrFail($id); // Use findOrFail to handle not found case

            // Remove all permissions associated with the role
            $role->syncPermissions([]); // This removes all permissions from the role

            // Delete the role
            $role->delete();

            $this->saveActivityLog("Role", "Delete Role Id " . $id);

            return response()->json(['status' => 200, 'message' => "Role deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }
}
