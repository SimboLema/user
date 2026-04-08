<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\WastePicker;
use App\Models\CollectionCenter;
use App\Models\CollectionCenterUser;
use App\Models\RecyclingFacility;
use App\Models\RecyclingFacilityUser;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define Super Admin role and permissions
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'super_admin'],
            ['guard_name' => 'web']
        );

        $superAdminPermissions = [
            // Dashboard permissions
            ['name' => 'show dashboard', 'guard_name' => 'web'],
            ['name' => 'show report', 'guard_name' => 'web'],

            // setting permissions
            ['name' => 'manage setting', 'guard_name' => 'web'],
            ['name' => 'show setting', 'guard_name' => 'web'],
            ['name' => 'create setting', 'guard_name' => 'web'],
            ['name' => 'edit setting', 'guard_name' => 'web'],
            ['name' => 'delete setting', 'guard_name' => 'web'],



            // smtp setting permissions
            ['name' => 'manage smtp setting', 'guard_name' => 'web'],
            ['name' => 'show smtp setting', 'guard_name' => 'web'],
            ['name' => 'create smtp setting', 'guard_name' => 'web'],
            ['name' => 'edit smtp setting', 'guard_name' => 'web'],
            ['name' => 'delete smtp setting', 'guard_name' => 'web'],

            // pusher setting permissions
            ['name' => 'manage pusher setting', 'guard_name' => 'web'],
            ['name' => 'show pusher setting', 'guard_name' => 'web'],
            ['name' => 'create pusher setting', 'guard_name' => 'web'],
            ['name' => 'edit pusher setting', 'guard_name' => 'web'],
            ['name' => 'delete pusher setting', 'guard_name' => 'web'],

            // sms setting permissions
            ['name' => 'manage sms setting', 'guard_name' => 'web'],
            ['name' => 'show sms setting', 'guard_name' => 'web'],
            ['name' => 'create sms setting', 'guard_name' => 'web'],
            ['name' => 'edit sms setting', 'guard_name' => 'web'],
            ['name' => 'delete sms setting', 'guard_name' => 'web'],

            // notification_template setting permissions
            ['name' => 'manage notification template', 'guard_name' => 'web'],
            ['name' => 'show notification template', 'guard_name' => 'web'],
            ['name' => 'create notification template', 'guard_name' => 'web'],
            ['name' => 'edit notification template', 'guard_name' => 'web'],
            ['name' => 'delete notification template', 'guard_name' => 'web'],

            // email_template setting permissions
            ['name' => 'manage email template', 'guard_name' => 'web'],
            ['name' => 'show email template', 'guard_name' => 'web'],
            ['name' => 'create email template', 'guard_name' => 'web'],
            ['name' => 'edit email template', 'guard_name' => 'web'],
            ['name' => 'delete email template', 'guard_name' => 'web'],

            // User permissions
            ['name' => 'manage user', 'guard_name' => 'web'],
            ['name' => 'show user', 'guard_name' => 'web'],
            ['name' => 'create user', 'guard_name' => 'web'],
            ['name' => 'edit user', 'guard_name' => 'web'],
            ['name' => 'delete user', 'guard_name' => 'web'],

            // User permissions
            ['name' => 'manage payment status', 'guard_name' => 'web'],
            ['name' => 'show payment status', 'guard_name' => 'web'],
            ['name' => 'create payment status', 'guard_name' => 'web'],
            ['name' => 'edit payment status', 'guard_name' => 'web'],
            ['name' => 'delete payment status', 'guard_name' => 'web'],

            // Role permissions
            ['name' => 'manage role', 'guard_name' => 'web'],
            ['name' => 'show role', 'guard_name' => 'web'],
            ['name' => 'create role', 'guard_name' => 'web'],
            ['name' => 'edit role', 'guard_name' => 'web'],
            ['name' => 'delete role', 'guard_name' => 'web'],


            // Country permissions
            ['name' => 'manage country', 'guard_name' => 'web'],
            ['name' => 'show country', 'guard_name' => 'web'],
            ['name' => 'create country', 'guard_name' => 'web'],
            ['name' => 'edit country', 'guard_name' => 'web'],
            ['name' => 'delete country', 'guard_name' => 'web'],

            // Region permissions
            ['name' => 'manage region', 'guard_name' => 'web'],
            ['name' => 'show region', 'guard_name' => 'web'],
            ['name' => 'create region', 'guard_name' => 'web'],
            ['name' => 'edit region', 'guard_name' => 'web'],
            ['name' => 'delete region', 'guard_name' => 'web'],

            // District permissions
            ['name' => 'manage district', 'guard_name' => 'web'],
            ['name' => 'show district', 'guard_name' => 'web'],
            ['name' => 'create district', 'guard_name' => 'web'],
            ['name' => 'edit district', 'guard_name' => 'web'],
            ['name' => 'delete district', 'guard_name' => 'web'],



            // Product permissions
            ['name' => 'manage product', 'guard_name' => 'web'],
            ['name' => 'show product', 'guard_name' => 'web'],
            ['name' => 'create product', 'guard_name' => 'web'],
            ['name' => 'edit product', 'guard_name' => 'web'],
            ['name' => 'delete product', 'guard_name' => 'web'],

            // customer permissions
            ['name' => 'manage user status', 'guard_name' => 'web'],
            ['name' => 'manage audit trail', 'guard_name' => 'web'],
            ['name' => 'show audit trail', 'guard_name' => 'web'],


            /*
    |--------------------------------------------------------------------------
    | POLICY MANAGEMENT
    |--------------------------------------------------------------------------
    */
            // ['name' => 'approve policy', 'guard_name' => 'web'],
            // ['name' => 'approve sales policy', 'guard_name' => 'web'],
            // ['name' => 'cancel policy', 'guard_name' => 'web'],
            ['name' => 'create policy', 'guard_name' => 'web'],
            ['name' => 'edit policy', 'guard_name' => 'web'],
            // ['name' => 'issue policy', 'guard_name' => 'web'],
            ['name' => 'manage policy', 'guard_name' => 'web'],
            ['name' => 'show policy', 'guard_name' => 'web'],

            /*
    |--------------------------------------------------------------------------
    | ENDORSEMENT MANAGEMENT
    |--------------------------------------------------------------------------
    */
            // ['name' => 'approve endorsement', 'guard_name' => 'web'],
            ['name' => 'create endorsement', 'guard_name' => 'web'],
            ['name' => 'edit endorsement', 'guard_name' => 'web'],
            ['name' => 'manage endorsement', 'guard_name' => 'web'],
            ['name' => 'show endorsement', 'guard_name' => 'web'],

            /*
    |--------------------------------------------------------------------------
    | MTA MANAGEMENT
    |--------------------------------------------------------------------------
    */
            // ['name' => 'approve mta', 'guard_name' => 'web'],
            ['name' => 'create mta', 'guard_name' => 'web'],
            ['name' => 'edit mta', 'guard_name' => 'web'],
            ['name' => 'manage mta', 'guard_name' => 'web'],
            ['name' => 'show mta', 'guard_name' => 'web'],

            /*
    |--------------------------------------------------------------------------
    | CLAIM MANAGEMENT
    |--------------------------------------------------------------------------
    */
            // ['name' => 'approve claim', 'guard_name' => 'web'],
            ['name' => 'create claim', 'guard_name' => 'web'],
            ['name' => 'edit claim', 'guard_name' => 'web'],
            ['name' => 'manage claim', 'guard_name' => 'web'],
            ['name' => 'show claim', 'guard_name' => 'web'],

            /*
    |--------------------------------------------------------------------------
    | REPORTS & ANALYTICS
    |--------------------------------------------------------------------------
    */
            // ['name' => 'export report', 'guard_name' => 'web'],
            // ['name' => 'view all reports', 'guard_name' => 'web'],
            // ['name' => 'view client report', 'guard_name' => 'web'],
            // ['name' => 'view financial report', 'guard_name' => 'web'],
            // ['name' => 'view performance report', 'guard_name' => 'web'],
            // ['name' => 'view premium report', 'guard_name' => 'web'],
            // ['name' => 'view product report', 'guard_name' => 'web'],
            // ['name' => 'view report', 'guard_name' => 'web'],
            // ['name' => 'view salesperson report', 'guard_name' => 'web'],
            // ['name' => 'view own performance report', 'guard_name' => 'web'],

            /*
    |--------------------------------------------------------------------------
    | SALES / INTERMEDIARY
    |--------------------------------------------------------------------------
    */
            // ['name' => 'create policy request', 'guard_name' => 'web'],
            // ['name' => 'sell motor insurance', 'guard_name' => 'web'],
            // ['name' => 'track renewal', 'guard_name' => 'web'],
            // ['name' => 'view own clients', 'guard_name' => 'web'],

            /*
    |--------------------------------------------------------------------------
    | CUSTOMER ACTIONS
    |--------------------------------------------------------------------------
    */
            // ['name' => 'download policy document', 'guard_name' => 'web'],
            // ['name' => 'make payment', 'guard_name' => 'web'],
            // ['name' => 'renew policy', 'guard_name' => 'web'],
            // ['name' => 'submit claim', 'guard_name' => 'web'],
            // ['name' => 'track claim status', 'guard_name' => 'web'],
            // ['name' => 'update profile', 'guard_name' => 'web'],
            // ['name' => 'view own policies', 'guard_name' => 'web'],
            // ['name' => 'view payment history', 'guard_name' => 'web'],
            // ['name' => 'view notifications', 'guard_name' => 'web'],





        ];

        // Assign permissions to Super Admin role
        foreach ($superAdminPermissions as $permissionData) {
            $permission = Permission::firstOrCreate($permissionData);
            if (!$superAdminRole->hasPermissionTo($permission)) {
                $superAdminRole->givePermissionTo($permission);
            }
        }

        // Create or update Super Admin user
        $user = User::updateOrCreate(
            ['id' => 1],
            [
                'name' => 'Super Admin',
                'phone' => '1', // Optional
                'title' => 'Super Administrator', // Optional
                'email' => 'superadmin@example.com',
                'password' => Hash::make('1234'), // Use a secure password
                'last_login' => null,
                'is_account_verified' => true,
                'token' => null,
                'token_expired' => null,
                'status' => 'active',
                'role' => $superAdminRole->id,
                'created_by' => 1,

            ]
        );

        $user->assignRole($superAdminRole);






        ## WASTE PICKER
        $wastePickerPermissions = [
            // User permissions
            ['name' => 'show user', 'guard_name' => 'web'],
            ['name' => 'edit user', 'guard_name' => 'web'],


            // waste picker permissions
            ['name' => 'manage waste picker', 'guard_name' => 'web'],
            ['name' => 'show waste picker', 'guard_name' => 'web'],
            ['name' => 'edit waste picker', 'guard_name' => 'web'],

        ];

        $wastePickerRole = Role::firstOrCreate(
            ['name' => 'waste_picker'],
            ['guard_name' => 'web']
        );
        foreach ($wastePickerPermissions as $permissionData) {
            $permission = Permission::firstOrCreate($permissionData);
            if (!$wastePickerRole->hasPermissionTo($permission)) {
                $wastePickerRole->givePermissionTo($permission);
            }
        }





        ## COLLECTION CENTER
        $collectionCenterPermissions = [
            // User permissions
            ['name' => 'show user', 'guard_name' => 'web'],
            ['name' => 'edit user', 'guard_name' => 'web'],

            // collection center permissions
            ['name' => 'manage collection center', 'guard_name' => 'web'],
            ['name' => 'show collection center', 'guard_name' => 'web'],
            ['name' => 'create collection center', 'guard_name' => 'web'],
            ['name' => 'edit collection center', 'guard_name' => 'web'],
            ['name' => 'delete collection center', 'guard_name' => 'web'],

            // collection center permissions
            ['name' => 'manage collection center user', 'guard_name' => 'web'],
            ['name' => 'show collection center user', 'guard_name' => 'web'],
            ['name' => 'create collection center user', 'guard_name' => 'web'],
            ['name' => 'edit collection center user', 'guard_name' => 'web'],
            ['name' => 'delete collection center user', 'guard_name' => 'web'],

            // waste picker permissions
            ['name' => 'manage waste picker', 'guard_name' => 'web'],
            ['name' => 'show waste picker', 'guard_name' => 'web'],
            ['name' => 'create waste picker', 'guard_name' => 'web'],
            ['name' => 'edit waste picker', 'guard_name' => 'web'],
            ['name' => 'delete waste picker', 'guard_name' => 'web'],

            // waste collection permissions
            ['name' => 'manage waste collection', 'guard_name' => 'web'],
            ['name' => 'show waste collection', 'guard_name' => 'web'],
            ['name' => 'create waste collection', 'guard_name' => 'web'],
            ['name' => 'edit waste collection', 'guard_name' => 'web'],
            ['name' => 'delete waste collection', 'guard_name' => 'web'],

            // inventory permissions
            ['name' => 'manage inventory', 'guard_name' => 'web'],
            ['name' => 'show inventory', 'guard_name' => 'web'],

        ];

        $collectionCenterRole = Role::firstOrCreate(
            ['name' => 'collection_center'],
            ['guard_name' => 'web']
        );

        foreach ($collectionCenterPermissions as $permissionData) {
            $permission = Permission::firstOrCreate($permissionData);
            if (!$collectionCenterRole->hasPermissionTo($permission)) {
                $collectionCenterRole->givePermissionTo($permission);
            }
        }




        ## recycling FACILITY
        $facilityPermissions = [
            // User permissions
            ['name' => 'show user', 'guard_name' => 'web'],
            ['name' => 'edit user', 'guard_name' => 'web'],


            // recycling facility permissions
            ['name' => 'manage recycling facility', 'guard_name' => 'web'],
            ['name' => 'show recycling facility', 'guard_name' => 'web'],
            ['name' => 'create recycling facility', 'guard_name' => 'web'],
            ['name' => 'edit recycling facility', 'guard_name' => 'web'],
            ['name' => 'delete recycling facility', 'guard_name' => 'web'],

            // recycling facility permissions
            ['name' => 'manage recycling facility user', 'guard_name' => 'web'],
            ['name' => 'show recycling facility user', 'guard_name' => 'web'],
            ['name' => 'create recycling facility user', 'guard_name' => 'web'],
            ['name' => 'edit recycling facility user', 'guard_name' => 'web'],
            ['name' => 'delete recycling facility user', 'guard_name' => 'web'],

            // recycling waste collection permissions
            ['name' => 'manage recycling waste collection', 'guard_name' => 'web'],
            ['name' => 'show recycling waste collection', 'guard_name' => 'web'],
            ['name' => 'create recycling waste collection', 'guard_name' => 'web'],
            ['name' => 'edit recycling waste collection', 'guard_name' => 'web'],
            ['name' => 'delete recycling waste collection', 'guard_name' => 'web'],

            // Product permissions
            ['name' => 'manage product', 'guard_name' => 'web'],
            ['name' => 'show product', 'guard_name' => 'web'],
            ['name' => 'create product', 'guard_name' => 'web'],
            ['name' => 'edit product', 'guard_name' => 'web'],
            ['name' => 'delete product', 'guard_name' => 'web'],

            // recycling material permissions
            ['name' => 'manage recycling material', 'guard_name' => 'web'],
            ['name' => 'show recycling material', 'guard_name' => 'web'],
            ['name' => 'create recycling material', 'guard_name' => 'web'],
            ['name' => 'edit recycling material', 'guard_name' => 'web'],
            ['name' => 'delete recycling material', 'guard_name' => 'web'],

            // inventory permissions
            ['name' => 'manage inventory', 'guard_name' => 'web'],
            ['name' => 'show inventory', 'guard_name' => 'web'],
        ];

        $facilityRole = Role::firstOrCreate(
            ['name' => 'recycling_facility'],
            ['guard_name' => 'web']
        );

        foreach ($facilityPermissions as $permissionData) {
            $permission = Permission::firstOrCreate($permissionData);
            if (!$facilityRole->hasPermissionTo($permission)) {
                $facilityRole->givePermissionTo($permission);
            }
        }




        ## producer
        $producerPermissions = [
            // User permissions
            ['name' => 'show user', 'guard_name' => 'web'],
            ['name' => 'edit user', 'guard_name' => 'web'],


            // production company permissions
            ['name' => 'manage production company', 'guard_name' => 'web'],
            ['name' => 'show production company', 'guard_name' => 'web'],
            ['name' => 'create production company', 'guard_name' => 'web'],
            ['name' => 'edit production company', 'guard_name' => 'web'],
            ['name' => 'delete production company', 'guard_name' => 'web'],

            // producer user permissions
            ['name' => 'manage producer', 'guard_name' => 'web'],
            ['name' => 'show producer', 'guard_name' => 'web'],
            ['name' => 'create producer', 'guard_name' => 'web'],
            ['name' => 'edit producer', 'guard_name' => 'web'],
            ['name' => 'delete producer user', 'guard_name' => 'web'],

            // Product permissions
            ['name' => 'manage product', 'guard_name' => 'web'],
            ['name' => 'show product', 'guard_name' => 'web'],
            ['name' => 'create product', 'guard_name' => 'web'],
            ['name' => 'edit product', 'guard_name' => 'web'],
            ['name' => 'delete product', 'guard_name' => 'web'],



            // inventory permissions
            ['name' => 'manage inventory', 'guard_name' => 'web'],
            ['name' => 'show inventory', 'guard_name' => 'web'],
        ];

        $producerRole = Role::firstOrCreate(
            ['name' => 'producer'],
            ['guard_name' => 'web']
        );

        foreach ($producerPermissions as $permissionData) {
            $permission = Permission::firstOrCreate($permissionData);
            if (!$producerRole->hasPermissionTo($permission)) {
                $producerRole->givePermissionTo($permission);
            }
        }
    }
}
