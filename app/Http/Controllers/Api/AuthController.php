<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\NextSmsSetting;
use App\Models\BeamSetting;
use App\Models\Setting;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use App\Traits\ActivityLoggableTrait;
use App\Models\AuditTrail;
use App\Models\Country;
use App\Models\Utility;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{

    use ActivityLoggableTrait;

    public function login(Request $request)
    {
        try {
            // Validate the request
            $validator = Validator::make($request->all(), [
                'email' => 'required|string', // Accepts either an email or phone number
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                $errors = $validator->errors()->all();
                $errorMessage = implode(', ', $errors);
                $output = [
                    'status' => 500,
                    'message' => 'Validation failed: ' . $errorMessage,
                ];
                return response()->json($output, 200);
            }

            // Retrieve the input values
            $loginInput = $request->input('email');
            $password = $request->input('password');

            if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
                // Input is an email
                $user = User::where('email', $loginInput)->first();

            } else if (preg_match('/^\+?[0-9]{9,13}$/', $loginInput)) {
                $phone = BeamSetting::formatPhoneNumber($loginInput);
                $user = User::where('phone', $phone)->first();

            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Invalid email or phone number format',
                ], 200);
            }



            // If user exists, attempt to authenticate using Auth::attempt
            if ($user && $user->status == "active" && (Auth::attempt(['email' => $user->email, 'password' => $password]) ||
                Auth::attempt(['phone' => $user->phone, 'password' => $password]))) {



                // Create a new token for the user
                $token = $user->createToken('API Token')->plainTextToken;

                $output = [
                    'status' => 200,
                    'message' => "Login successful",
                    'token' => $token,
                    'token_type' => 'Bearer',
                    'users' => $user,
                ];
                return response()->json($output, 200);
            }
            else if($user && $user->status == "inactive"){
                return response()->json([
                    'status' => 500,
                    'message' => 'Login Failed, Your Account is inactive, contact admin for activation',
                ], 200);
            }
            else {
                // If authentication fails
                $output = [
                    'status' => 500,
                    'message' => 'Invalid credentials, Please try again with valid Credentials',
                ];
                return response()->json($output, 200);
            }

        } catch (Exception $e) {
            $output = [
                'status' => 500,
                'message' => $e->getMessage(),
            ];
            return response()->json($output, 200);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Get the authenticated user
            $user = $request->user();

            // Revoke the user's token
            if ($user) {
                $user->currentAccessToken()->delete(); // Deletes the current access token
            }

            $output = [
                'status' => 200,
                'message' => 'Logout successful.',
            ];
            return response()->json($output, 200);
        } catch (Exception $e) {
            $output = [
                'status' => 500,
                'message' => 'Logout failed: ' . $e->getMessage(),
            ];
            return response()->json($output, 200);
        }
    }
}
