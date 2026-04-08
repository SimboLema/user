<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Traits\ActivityLoggableTrait;
use App\Models\User;
use App\Models\Setting;
use App\Models\Utility;
use App\Models\TransferAmount;
use App\Models\SmtpSetting;
use App\Models\RecyclingFacility;
use App\Models\Producer;
use App\Models\PaymentStatus;
use App\Models\BeamSetting;
use App\Models\Unit;
use App\Models\PusherSetting;
use Illuminate\Validation\Rule;

use App\Models\EmailTemplate;
use App\Models\NotificationUser;
use App\Models\Color;
use App\Models\Product;
use App\Models\Country;
use App\Models\Region;
use App\Models\District;
use App\Models\Ward;
use App\Models\NotificationTemplate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;
use Auth;

class SettingController extends Controller
{
    use ActivityLoggableTrait;

    public function index()
    {
        if (!\Auth::user()->can('manage setting')) {
            return abort(401, __('Permission denied.'));
        }
        $roles = Role::where('id','!=',1)->get();



        return view('setting.index',compact('roles'));
    }

    public function view()
    {
        if (!\Auth::user()->can('manage setting')) {
            return abort(401, __('Permission denied.'));
        }
        $settings = Setting::first();
        $units = Unit::where('archive',0)->get();

        return view('setting.view', compact('settings','units'));
    }

    public function saveSetting(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'system_name'      => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $system_name = $request->input('system_name');
            $currency = $request->input('currency');
            $email_notifications = $request->input('email_notifications');
            $sms_notifications = $request->input('sms_notifications');
            $two_factor_auth = $request->input('two_factor_auth');
            $unit_id = $request->input('unit_id');
            $user_id = \Auth::user()->id;

            // Handle logo upload
            if ($request->hasFile('system_logo')) {
                // Ensure the 'logos' directory exists
                if (!Storage::disk('public')->exists('logos')) {
                    Storage::disk('public')->makeDirectory('logos');
                }

                // Store the logo in the 'logos' directory within the 'public' disk
                $logo = $request->file('system_logo')->store('logos', 'public');
            }

            if ($request->hasFile('system_favicon')) {
                // Ensure the 'logos' directory exists
                if (!Storage::disk('public')->exists('logos')) {
                    Storage::disk('public')->makeDirectory('logos');
                }

                // Store the logo in the 'logos' directory within the 'public' disk
                $favicon = $request->file('system_favicon')->store('logos', 'public');
            }


            // Prepare array with data
            $data = [
                'system_name' => $system_name,
                'two_factor_auth' => $two_factor_auth ?? 0,
                'currency' => $currency,
                'email_notifications' => $email_notifications ?? 0,
                'sms_notifications' => $sms_notifications ?? 0,
                'unit_id' => $unit_id ?? 0,
            ];

            if (isset($logo)) {
                $data['system_logo'] = $logo;
            }

            if (isset($favicon)) {
                $data['system_favicon'] = $favicon;
            }

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit settings"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                Setting::where($condition)->update($data);
                $message = 'Settings updated successfully';
                $this->saveActivityLog("Settings", "Update Settings Id " . $condition['id']);
            } else {
                if (!\Auth::user()->can('create setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create settings"]);
                }

                $data['created_by'] = $user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = Setting::create($data);

                $message = 'Settings saved successfully';
                $this->saveActivityLog("Settings", "Save Settings Id " . $instance->id);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();

            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function smtp()
    {
        if (!\Auth::user()->can('manage smtp setting')) {
            return abort(401, __('Permission denied.'));
        }
        $roles = Role::where('id','!=',1)->get();



        return view('setting.smtp',compact('roles'));
    }

    public function smtp_view()
    {
        if (!\Auth::user()->can('manage smtp setting')) {
            return abort(401, __('Permission denied.'));
        }
        $settings = SmtpSetting::first();

        return view('setting.smtp_view', compact('settings'));
    }

    public function saveSmtpSetting(Request $request)
    {
        try {

            $validator = \Validator::make($request->all(), [
                'sender_name'      => 'required|string',
                'sender_email'      => 'required|email',
                'smtp_driver'      => 'required',
                'smtp_host'      => 'required',
                'smtp_username'      => 'required',
                'smtp_password'      => 'required',
                'smtp_encryption'      => 'required',
                'smtp_port'      => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }

            DB::beginTransaction();

            // Retrieve the data from the request
            $hidden_id = $request->input('hidden_id');
            $sender_name = $request->input('sender_name');
            $sender_email = $request->input('sender_email');
            $smtp_driver = $request->input('smtp_driver');
            $smtp_host = $request->input('smtp_host');
            $smtp_username = $request->input('smtp_username');
            $smtp_password = $request->input('smtp_password');
            $smtp_encryption = $request->input('smtp_encryption');
            $smtp_port = $request->input('smtp_port');
            $user_id = \Auth::user()->id;

            // Prepare array with data
            $data = [
                'sender_name' => $sender_name,
                'sender_email' => $sender_email,
                'smtp_driver' => $smtp_driver,
                'smtp_host' => $smtp_host,
                'smtp_username' => $smtp_username,
                'smtp_password' => $smtp_password,
                'smtp_encryption' => $smtp_encryption,
                'smtp_port' => $smtp_port,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit smtp setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit SMTP settings"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                SmtpSetting::where($condition)->update($data);
                $message = 'SMTP Settings updated successfully';
                $this->saveActivityLog("SMTP Settings", "Update SMTP Settings Id " . $condition['id']);
            } else {
                if (!\Auth::user()->can('create smtp setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create SMTP settings"]);
                }

                $data['created_by'] = $user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = SmtpSetting::create($data);

                $message = 'SMTP Settings saved successfully';
                $this->saveActivityLog("SMTP Settings", "Save SMTP Settings Id " . $instance->id);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    public function sms()
    {
        if (!\Auth::user()->can('manage sms setting')) {
            return abort(401, __('Permission denied.'));
        }
        $roles = Role::where('id','!=',1)->get();



        return view('setting.sms',compact('roles'));
    }

    public function sms_view()
    {
        if (!\Auth::user()->can('manage sms setting')) {
            return abort(401, __('Permission denied.'));
        }
        $settings = BeamSetting::first();

        return view('setting.sms_view', compact('settings'));
    }

    public function saveBeamSetting(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'api_key'      => 'required',
                'secret_key'      => 'required',
                'base_url'      => 'required',
                'source_address'      => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first()
                ]);
            }


            DB::beginTransaction();

            // Retrieve the data from the request
            $hidden_id = $request->input('hidden_id');
            $api_key = $request->input('api_key');
            $secret_key = $request->input('secret_key');
            $base_url = $request->input('base_url');
            $source_address = $request->input('source_address');
            $user_id = \Auth::user()->id;

            // Prepare array with data
            $data = [
                'api_key' => $api_key,
                'secret_key' => $secret_key,
                'base_url' => $base_url,
                'source_address' => $source_address,
            ];

            if (!empty($hidden_id)) {
                // Check for edit permission
                if (!\Auth::user()->can('edit sms setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit SMS settings"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                BeamSetting::where($condition)->update($data);
                $message = 'Beam Settings updated successfully';
                $this->saveActivityLog("Beam Settings", "Update Beam Settings Id " . $condition['id']);
            } else {
                // Check for create permission
                if (!\Auth::user()->can('create sms setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create SMS settings"]);
                }

                $data['created_by'] = $user_id; // Set the creator
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = BeamSetting::create($data);

                $message = 'Beam Settings saved successfully';
                $this->saveActivityLog("Beam Settings", "Save Beam Settings Id " . $instance->id);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    public function notification_template()
    {
        if (!\Auth::user()->can('manage notification template')) {
            return abort(401, __('Permission denied.'));
        }




        return view('setting.notification_template');
    }

    public function notification_template_view()
    {
        if (!\Auth::user()->can('manage notification template')) {
            return abort(401, __('Permission denied.'));
        }
        $notificationTemplates = NotificationTemplate::all();
        $users = User::where('status',"active")->get();

        return view('setting.notification_template_view', compact('notificationTemplates','users'));
    }

    public function saveNotificationTemplate(Request $request)
    {
        try {
            DB::beginTransaction();
            $notificationTemplates = NotificationTemplate::all();

            foreach ($notificationTemplates as $template) {
                $slug = $template->slug;

                // Get the content, status, and selected users for the current template
                $smsBodyKey = 'sms_body_' . $slug;
                $isActiveKey = 'is_active_' . $slug;
                $usersKey = 'users_' . $slug;

                if ($request->has($smsBodyKey)) {
                    $smsBody = $request->input($smsBodyKey);
                    $isActive = $request->input($isActiveKey, 0); // Defaults to inactive if not set
                    $selectedUsers = $request->input($usersKey, []); // Defaults to an empty array if no users selected

                    // Update the template content and status
                    $template->update([
                        'content' => $smsBody,
                        'is_active' => $isActive,
                    ]);

                    NotificationUser::where(['type'=>"notification",'template_id'=>$template->id])->update(['archive'=>1]);
                    foreach($selectedUsers as $selectedUser){
                        NotificationUser::updateOrCreate(
                            [
                                'template_id' => $template->id,
                                'type' => "notification",
                                'user_id' => $selectedUser,

                            ],
                            [
                                'archive' => 0,
                            ]
                        );
                    }
                }
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => "Template Saved successfully"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    function getRecentNotification(){
        $userId= Auth::user()->id;

        $notifications = PusherNotification::where(['user_id'=>$userId,'is_opened'=>0])->get();

        echo json_encode($notifications);
    }


    public function generateUniqueNumber(Request $request){
        $table=$request->table;
        $column=$request->column;
        $prefix="";

        $setting  = CompanySetting::first();
        $number_padding_zero =$setting->number_padding_zero ?? 3;


        if($table == "customers"){
            $prefix=$setting->customer_prefix ?? "" ;
        }
        if($table == "loans"){
            $prefix=$setting->loan_prefix ?? "" ;
        }
        if($table == "accounts"){
            $prefix=$setting->account_prefix ?? "" ;
        }
        if($table == "expenses"){
            $prefix=$setting->expense_prefix ?? "" ;
        }
        if($table == "loan_types"){
            $prefix=$setting->loan_type_prefix ?? "" ;
        }

        $uniqueNumber=Utility::generateFormatNumber($table,$column,null,$number_padding_zero,$prefix);
        return response()->json($uniqueNumber);

    }

    public function downloadDatabaseBackup()
    {
        $databaseName = config('database.connections.mysql.database');
        $filename = $databaseName . '_backup_' . date('Y-m-d_H-i-s') . '.sql';
        $filePath = storage_path('app/' . $filename);

        // Start writing the file
        File::put($filePath, '');

        // Retrieve all tables in the database
        $tables = DB::select("SHOW TABLES");
        $tableKey = 'Tables_in_' . $databaseName;

        foreach ($tables as $table) {
            $tableName = $table->$tableKey;

            // Write the SQL to create the table structure
            $createTable = DB::select("SHOW CREATE TABLE `$tableName`");
            File::append($filePath, "\n\n" . $createTable[0]->{'Create Table'} . ";\n\n");

            // Retrieve all rows from the table
            $rows = DB::table($tableName)->get();
            if ($rows->isEmpty()) continue;

            // Write the SQL insert statements
            foreach ($rows as $row) {
                $columns = implode('`, `', array_keys((array)$row));
                $values = implode("', '", array_map(function ($value) {
                    return addslashes($value);
                }, (array)$row));

                $insertStatement = "INSERT INTO `$tableName` (`$columns`) VALUES ('$values');\n";
                File::append($filePath, $insertStatement);
            }
        }

        // Stream the backup file for download
        return new StreamedResponse(function () use ($filePath) {
            $stream = fopen($filePath, 'r');
            fpassthru($stream);
            fclose($stream);
            File::delete($filePath); // Clean up after download
        }, 200, [
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => 'attachment; filename="' . basename($filename) . '"',
            'Cache-Control' => 'no-store, no-cache',
        ]);
    }


    public function pusher()
    {
        if (!\Auth::user()->can('manage pusher setting')) {
            return abort(401, __('Permission denied.'));
        }


        return view('setting.pusher');
    }

    public function pusher_view()
    {
        if (!\Auth::user()->can('manage pusher setting')) {
            return abort(401, __('Permission denied.'));
        }
        $settings = PusherSetting::first();

        return view('setting.pusher_view', compact('settings'));
    }

    public function savePusherSetting(Request $request)
    {
        try {
            // Validation rules for Pusher settings
            $validator = \Validator::make($request->all(), [
                'api_id'      => 'required|string',
                'key'         => 'required|string',
                'secret'      => 'required|string',
                'cluster'     => 'required|string',
                'channel'     => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status'  => 500,
                    'message' => $validator->errors()->first(),
                ]);
            }

            DB::beginTransaction();

            // Retrieve the data from the request
            $hidden_id  = $request->input('hidden_id');
            $api_id     = $request->input('api_id');
            $key        = $request->input('key');
            $secret     = $request->input('secret');
            $cluster    = $request->input('cluster');
            $channel    = $request->input('channel');
            $user_id    = \Auth::user()->id;

            // Prepare array with data
            $data = [
                'api_id'    => $api_id,
                'key'       => $key,
                'secret'    => $secret,
                'cluster'   => $cluster,
                'channel'   => $channel,
                'archive'   => 0,
            ];

            if (!empty($hidden_id)) {
                // If editing Pusher settings
                if (!\Auth::user()->can('edit pusher setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit Pusher settings"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                PusherSetting::where($condition)->update($data);
                $message = 'Pusher Settings updated successfully';
                $this->saveActivityLog("Pusher Settings", "Update Pusher Settings Id " . $condition['id']);
            } else {
                // If creating new Pusher settings
                if (!\Auth::user()->can('create pusher setting')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create Pusher settings"]);
                }

                $data['created_by'] = $user_id;
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = PusherSetting::create($data);

                $message = 'Pusher Settings saved successfully';
                $this->saveActivityLog("Pusher Settings", "Save Pusher Settings Id " . $instance->id);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function payment_status()
    {
        if (!\Auth::user()->can('manage payment status')) {
            return abort(401, __('Permission denied.'));
        }

        $statuses = PaymentStatus::where('archive', 0)->get(); // Fetch active payment statuses

        return view('setting.payment_status', compact('statuses')); // Update view name accordingly
    }

    public function payment_status_view()
    {
        if (!\Auth::user()->can('manage payment status')) {
            return abort(401, __('Permission denied.'));
        }

        $data = PaymentStatus::where('archive', 0)->get(); // Fetch active payment statuses

        return view('setting.payment_status_view', compact('data')); // Update view name accordingly
    }

    public function savePaymentStatus(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'color' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first(),
                ]);
            }

            DB::beginTransaction();

            $hidden_id = $request->input('hidden_id');
            $name = $request->input('name');
            $color = $request->input('color');
            $user_id = \Auth::user()->id;

            // Prepare array with data
            $data = [
                'name' => $name,
                'color' => $color,
                'archive' => 0,
            ];

            if (!empty($hidden_id)) {
                // Check for edit permission
                if (!\Auth::user()->can('edit payment status')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to edit payment status"]);
                }

                $data['updated_at'] = now();

                $condition = [
                    'id' => Crypt::decrypt($hidden_id),
                ];

                PaymentStatus::where($condition)->update($data);
                $message = 'Payment status updated successfully';
                $this->saveActivityLog("Payment Status", "Update Payment Status Id " . $condition['id']);
            } else {
                // Check for create permission
                if (!\Auth::user()->can('create payment status')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "You don't have permission to create payment status"]);
                }

                $data['created_by'] = $user_id; // Set the creator
                $data['created_at'] = now();
                $data['updated_at'] = now();

                $instance = PaymentStatus::create($data);

                $message = 'Payment status saved successfully';
                $this->saveActivityLog("Payment Status", "Save Payment Status Id " . $instance->id);
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => $message]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editPaymentStatus($id)
    {
        $data = PaymentStatus::where('id', $id)->first();

        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deletePaymentStatus($id)
    {
        if (!\Auth::user()->can('delete payment status')) {
            return response()->json(['status' => 500, 'message' => "You don't have permission to delete payment status"]);
        }

        try {
            $data = PaymentStatus::find($id);
            $data->update(['archive' => 1]);

            $this->saveActivityLog("Payment Status", "Delete Payment Status Id " . $id);

            return response()->json(['status' => 200, 'message' => "Payment status deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    public function email_template()
    {
        if (!\Auth::user()->can('manage email template')) {
            return abort(401, __('Permission denied.'));
        }
        $roles = Role::where('id','!=',1)->get();



        return view('setting.email_template',compact('roles'));
    }

    public function email_template_view()
    {
        if (!\Auth::user()->can('manage email template')) {
            return abort(401, __('Permission denied.'));
        }
        $notificationTemplates =EmailTemplate::all();
        $users = User::where('status',"active")->get();

        return view('setting.email_template_view', compact('notificationTemplates','users'));
    }

    public function saveEmailTemplate(Request $request)
    {
        try {
            DB::beginTransaction();
            $notificationTemplates = EmailTemplate::all();

            // Iterate over each template to update its content and status
            foreach ($notificationTemplates as $template) {
                $slug = $template->slug;

                // Get the content and status inputs for the current template
                $smsBodyKey = 'sms_body_' . $slug;
                $isActiveKey = 'is_active_' . $slug;
                $usersKey = 'users_' . $slug;

                NotificationUser::where(['type'=>"email",'template_id'=>$template->id])->update(['archive'=>1]);

                // Check if form contains inputs for the current template
                if ($request->has($smsBodyKey)) {
                    $smsBody = $request->input($smsBodyKey);
                    $isActive = $request->input($isActiveKey, 0);
                    $selectedUsers = $request->input($usersKey, []);

                    // Update the template content and status
                    $template->update([
                        'content' => $smsBody,
                        'is_active' => $isActive,
                    ]);


                    foreach($selectedUsers as $selectedUser){
                        NotificationUser::updateOrCreate(
                            [
                                'template_id' => $template->id,
                                'type' => "email",
                                'user_id' => $selectedUser,

                            ],
                            [
                                'archive' => 0,
                            ]
                        );
                    }

                }
            }

            DB::commit();

            return response()->json(['status' => 200, 'message' => "Email Template Saved successful"]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function country()
    {
        if (!\Auth::user()->can('manage country')) {
            return abort(401, __('Permission denied.'));
        }

        return view('setting.country');
    }

    public function country_view()
    {
        if (!\Auth::user()->can('manage country')) {
            return abort(401, __('Permission denied.'));
        }

        $data = Country::orderBy('id','desc')->get();
        return view('setting.country_view', compact('data'));
    }

    public function saveCountry(Request $request)
    {
        try {
            $hidden_id = $request->input('hidden_id');

            $uniqueRule = Rule::unique('countries', 'name');
            if (!empty($hidden_id)) {
                $uniqueRule = $uniqueRule->ignore(Crypt::decrypt($hidden_id), 'id');
            }
            $validator = \Validator::make($request->all(), [
                'name' => ['required',$uniqueRule],
                'code' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();


            $data = [
                'name' => $request->name,
                'code' => $request->code,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit country')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit country"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                Country::where('id', $id)->update($data);
                $message = 'Country updated successfully';
                $this->saveActivityLog("Country", "Update Country Id " . $id);
            } else {
                if (!\Auth::user()->can('create country')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create country"]);
                }


                $data['created_at'] = now();
                $data['updated_at'] = now();
                $country = Country::create($data);
                $message = 'Country saved successfully';
                $this->saveActivityLog("Country", "Save Country Id " . $country->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editCountry($id)
    {
        $data = Country::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteCountry($id)
    {
        if (!\Auth::user()->can('delete country')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete country"]);
        }

        try {
            Country::find($id)->delete();
            $this->saveActivityLog("Country", "Delete Country Id " . $id);
            return response()->json(['status' => 200, 'message' => "Country deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function region()
    {
        if (!\Auth::user()->can('manage region')) {
            return abort(401, __('Permission denied.'));
        }

        $countries = Country::get();
        return view('setting.region', compact('countries'));
    }

    public function region_view()
    {
        if (!\Auth::user()->can('manage region')) {
            return abort(401, __('Permission denied.'));
        }

        $data = Region::with('country')->orderBy('id','desc')->get();
        return view('setting.region_view', compact('data'));
    }

    public function saveRegion(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'country_id' => 'required|exists:countries,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');

            $data = [
                'name' => $request->name,
                'country_id' => $request->country_id,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit region')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit region"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                Region::where('id', $id)->update($data);
                $message = 'Region updated successfully';
                $this->saveActivityLog("Region", "Update Region Id " . $id);
            } else {
                if (!\Auth::user()->can('create region')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create region"]);
                }

                $data['created_at'] = now();
                $data['updated_at'] = now();
                $region = Region::create($data);
                $message = 'Region saved successfully';
                $this->saveActivityLog("Region", "Save Region Id " . $region->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editRegion($id)
    {
        $data = Region::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteRegion($id)
    {
        if (!\Auth::user()->can('delete region')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete region"]);
        }

        try {
            Region::find($id)->delete();
            $this->saveActivityLog("Region", "Delete Region Id " . $id);
            return response()->json(['status' => 200, 'message' => "Region deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function district()
    {
        if (!\Auth::user()->can('manage district')) {
            return abort(401, __('Permission denied.'));
        }

        $countries = Country::get();
        return view('setting.district', compact('countries'));
    }

    public function district_view()
    {
        if (!\Auth::user()->can('manage district')) {
            return abort(401, __('Permission denied.'));
        }

        $data = District::with('region')->orderBy('id','desc')->get();
        return view('setting.district_view', compact('data'));
    }

    public function saveDistrict(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'region_id' => 'required|exists:regions,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');

            $data = [
                'name' => $request->name,
                'region_id' => $request->region_id,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit district')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit district"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                District::where('id', $id)->update($data);
                $message = 'District updated successfully';
                $this->saveActivityLog("District", "Update District Id " . $id);
            } else {
                if (!\Auth::user()->can('create district')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create district"]);
                }

                $data['created_at'] = now();
                $data['updated_at'] = now();
                $district = District::create($data);
                $message = 'District saved successfully';
                $this->saveActivityLog("District", "Save District Id " . $district->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editDistrict($id)
    {
        $data = District::with(['country','region'])->find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteDistrict($id)
    {
        if (!\Auth::user()->can('delete district')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete district"]);
        }

        try {
            District::find($id)->delete();
            $this->saveActivityLog("District", "Delete District Id " . $id);
            return response()->json(['status' => 200, 'message' => "District deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function ward()
    {
        if (!\Auth::user()->can('manage ward')) {
            return abort(401, __('Permission denied.'));
        }

        $countries = Country::get();
        return view('setting.ward', compact('countries'));
    }

    public function ward_view()
    {
        if (!\Auth::user()->can('manage ward')) {
            return abort(401, __('Permission denied.'));
        }

        $data = Ward::with('district')->orderBy('id','desc')->get();
        return view('setting.ward_view', compact('data'));
    }

    public function saveWard(Request $request)
    {
        try {
            $validator = \Validator::make($request->all(), [
                'name' => 'required',
                'district_id' => 'required|exists:districts,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();
            $hidden_id = $request->input('hidden_id');

            $data = [
                'name' => $request->name,
                'district_id' => $request->district_id,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit ward')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit ward"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                Ward::where('id', $id)->update($data);
                $message = 'Ward updated successfully';
                $this->saveActivityLog("Ward", "Update Ward Id " . $id);
            } else {
                if (!\Auth::user()->can('create ward')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create ward"]);
                }

                $data['created_at'] = now();
                $data['updated_at'] = now();
                $ward = Ward::create($data);
                $message = 'Ward saved successfully';
                $this->saveActivityLog("Ward", "Save Ward Id " . $ward->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editWard($id)
    {
        $data = Ward::with(['district.region.country'])->find($id);

        echo json_encode([
            'data' => $data,
            'id'   => Crypt::encrypt($id)
        ]);
    }


    public function deleteWard($id)
    {
        if (!\Auth::user()->can('delete ward')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete ward"]);
        }

        try {
            Ward::find($id)->delete();
            $this->saveActivityLog("Ward", "Delete Ward Id " . $id);
            return response()->json(['status' => 200, 'message' => "Ward deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function getPermission(Request $request){
        // Get the currently authenticated user
        $user = \Auth::user();

        // Check if the user is authenticated
        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        // Retrieve the roles assigned to the user
        $roles = $user->getRoleNames(); // This will return an array of roles

        // Get the permissions assigned to the user's role(s)
        $permissions = $user->getPermissionsViaRoles(); // This retrieves all permissions via roles

        return response()->json([
            'roles' => $roles,
            'permissions' => $permissions
        ]);
    }

    public function getCountry()
    {
        $data = Country::with('regions.districts.wards')->get();
        return response()->json($data);
    }

    public function getCountryRegion($id)
    {
        $data = Region::with('districts.wards')->where('country_id',$id)->get();
        echo json_encode($data);
    }

    public function getRegionDistrict($id)
    {
        $data = District::with('wards')->where('region_id',$id)->get();
        echo json_encode($data);
    }

    public function getDistrictWard($id)
    {
        $data = Ward::where('district_id',$id)->get();
        echo json_encode($data);
    }

    public function saveDeviceLocation(Request $request)
    {
        try {

            // Log incoming request
            Log::info('Incoming Device Location Request:', $request->all()); die();


            $device_id = $request->device_id;
            $latitude = $request->latitude;
            $longitude = $request->longitude;
            $speed = $request->speed;
            $direction = $request->direction;
            $altitude = $request->altitude;
            $battery_level = $request->battery_level;
            $signal_strength = $request->signal_strength;
            $status = $request->status;
            $recorded_at = $request->recorded_at;
            $device_type = $request->device_type;
            $device_model = $request->device_model;
            $device_serial_number = $request->device_serial_number;
            $device_firmware_version = $request->device_firmware_version;
            $device_imei = $request->device_imei;
            $created_by = $request->created_by;
            $archive = $request->archive;

            $data = [
                'device_id' => $device_id,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'speed' => $speed,
                'direction' => $direction,
                'altitude' => $altitude,
                'battery_level' => $battery_level,
                'signal_strength' => $signal_strength,
                'status' => $status,
                'recorded_at' => $recorded_at,
                'device_type' => $device_type,
                'device_model' => $device_model,
                'device_serial_number' => $device_serial_number,
                'device_firmware_version' => $device_firmware_version,
                'device_imei' => $device_imei,
                'created_by' => $created_by,
                'archive' => $archive
            ];

            GpsTracker::create($data);

            return response()->json([
                'status' => 200,
                'message' => 'Device location saved successfully.'
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Failed to save device location.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function color()
    {
        if (!\Auth::user()->can('manage color')) {
            return abort(401, __('Permission denied.'));
        }

        return view('setting.color');
    }

    public function color_view()
    {
        if (!\Auth::user()->can('manage color')) {
            return abort(401, __('Permission denied.'));
        }

        $data = Color::orderBy('id','desc')->get();
        return view('setting.color_view', compact('data'));
    }

    public function getColor(Request $request)
    {
        $search_text = $request->search_text;

        $user = \Auth::user();
        $data = Color::query();

        // Search filter on waste picker name, weather, or payment_status
        if (!empty($search_text)) {
            $data->where('name', 'like', "%$search_text%");
        }

        $result = $data->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveColor(Request $request)
    {
        try {
            $hidden_id = $request->input('hidden_id');

            $uniqueRule = Rule::unique('colors', 'name');
            if (!empty($hidden_id)) {
                $uniqueRule = $uniqueRule->ignore(Crypt::decrypt($hidden_id), 'id');
            }
            $validator = \Validator::make($request->all(), [
                'name' => ['required',$uniqueRule],
                'value' => 'required'
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();


            $data = [
                'name' => $request->name,
                'value' => $request->value,
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit color')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit color"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                Color::where('id', $id)->update($data);
                $message = 'Color updated successfully';
                $this->saveActivityLog("Color", "Update color Id " . $id);
            } else {
                if (!\Auth::user()->can('create color')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create color"]);
                }


                $data['created_at'] = now();
                $data['updated_at'] = now();
                $color = Color::create($data);
                $message = 'Color saved successfully';
                $this->saveActivityLog("Color", "Save Color Id " . $color->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editColor($id)
    {
        $data = Color::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteColor($id)
    {
        if (!\Auth::user()->can('delete color')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete color"]);
        }

        try {
            Color::find($id)->delete();
            $this->saveActivityLog("Color", "Delete color Id " . $id);
            return response()->json(['status' => 200, 'message' => "Color deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


    public function product()
    {
        if (!\Auth::user()->can('manage product')) {
            return abort(401, __('Permission denied.'));
        }

        $user = \Auth::user();

        $facilities = RecyclingFacility::where('archive',0);
        $producers = Producer::where('archive',0);
        if ($user && $user->role == 4) {
            $facilityIds = $user->facilities()->pluck('facility_id');
            $facilities->whereIn('id', $facilityIds);

        }

        if ($user && $user->role == 5) {
            $producerIds = $user->producers()->pluck('producer_id');
            $producers->whereIn('id', $producerIds);

        }

        $facilities = $facilities->orderBy('id', 'desc')->get();
        $producers = $producers->orderBy('id', 'desc')->get();

        return view('setting.product',compact('producers','facilities','user'));
    }

    public function product_view()
    {
        if (!\Auth::user()->can('manage product')) {
            return abort(401, __('Permission denied.'));
        }

        $query = Product::query();
        $user = \Auth::user();
        if ($user && $user->role == 4) {
            $facilityIds = $user->facilities()->pluck('facility_id');
            $query->whereIn('facility_id', $facilityIds);

        }

        if ($user && $user->role == 5) {
            $producerIds = $user->producers()->pluck('producer_id');
            $query->whereIn('producer_id', $producerIds);

        }

        $data = $query->orderBy('id', 'desc')->get();

        return view('setting.product_view', compact('data'));
    }

    public function getProduct(Request $request)
    {
        $search_text = $request->search_text;


        $query = Product::query();
        $user = \Auth::user();
        if ($user && $user->role == 4) {
            $facilityIds = $user->facilities()->pluck('facility_id');
            $query->whereIn('facility_id', $facilityIds);

        }

        if ($user && $user->role == 5) {
            $producerIds = $user->producers()->pluck('producer_id');
            $query->whereIn('producer_id', $producerIds);

        }

        // Search filter on waste picker name, weather, or payment_status
        if (!empty($search_text)) {
            $query->where('name', 'like', "%$search_text%");
        }

        $result = $query->orderBy('id', 'desc')->get();

        return response()->json(['status' => 200, 'message' => "Data Exist", 'data' => $result]);
    }

    public function saveProduct(Request $request)
    {
        try {
            $hidden_id = $request->input('hidden_id');

            $uniqueRule = Rule::unique('products', 'name');
            if (!empty($hidden_id)) {
                $uniqueRule = $uniqueRule->ignore(Crypt::decrypt($hidden_id), 'id');
            }
            $validator = \Validator::make($request->all(), [
                'name' => ['required',$uniqueRule],
            ]);

            if ($validator->fails()) {
                return response()->json(['status' => 500, 'message' => $validator->errors()->first()]);
            }

            DB::beginTransaction();

            $setting = Setting::first();

            $data = [
                'name' => $request->name,
                'unit_id'=>$setting->unit_id,
                'facility_id'=>$request->facility_id,
                'producer_id'=>$request->producer_id
            ];

            if (!empty($hidden_id)) {
                if (!\Auth::user()->can('edit product')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to edit product"]);
                }

                $data['updated_at'] = now();
                $id = Crypt::decrypt($hidden_id);
                Product::where('id', $id)->update($data);
                $message = 'Product updated successfully';
                $this->saveActivityLog("Product", "Update product Id " . $id);
            } else {
                if (!\Auth::user()->can('create product')) {
                    DB::rollback();
                    return response()->json(['status' => 500, 'message' => "No permission to create product"]);
                }


                $data['created_at'] = now();
                $data['updated_at'] = now();
                $product = Product::create($data);
                $message = 'Product saved successfully';
                $this->saveActivityLog("Product", "Save product Id " . $product->id);
            }

            DB::commit();
            return response()->json(['status' => 200, 'message' => $message]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }

    public function editProduct($id)
    {
        $data = Product::find($id);
        echo json_encode(['data' => $data, 'id' => Crypt::encrypt($id)]);
    }

    public function deleteProduct($id)
    {
        if (!\Auth::user()->can('delete product')) {
            return response()->json(['status' => 500, 'message' => "No permission to delete product"]);
        }

        try {
            Product::find($id)->delete();
            $this->saveActivityLog("Product", "Delete product Id " . $id);
            return response()->json(['status' => 200, 'message' => "Product deleted successfully"]);
        } catch (\Exception $e) {
            return response()->json(['status' => 500, 'message' => $e->getMessage()]);
        }
    }


}
