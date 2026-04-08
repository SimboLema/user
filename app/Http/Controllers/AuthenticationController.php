<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Utility;
use App\Models\UserRefer;
use App\Models\BeamSetting;
use App\Models\Country;
use App\Models\Unit;
use App\Models\Setting;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Mail\CustomEmail;
use Illuminate\Support\Facades\Hash;



class AuthenticationController extends Controller
{

    public function indexPage()
    {
        return view('kmj.home');
    }


    public function login()
    {
        if (auth()->check()) {
            return redirect()->route('kmj.index');
            // return redirect()->route('dashboard');

        }

        return view('auth.login'); // Show login view if user is not authenticated
    }

    public function register(Request $request)
    {
        if (auth()->check()) {
            return redirect()->route('kmj.index');
            // return redirect()->route('dashboard');

        }

        $role = $request->role;
        $countries = Country::get();
        $units = Unit::where('archive', 0)->orderBy('id', 'desc')->get();

        return view('auth.register', compact('role', 'countries', 'units')); // Show login view if user is not authenticated
    }

    public function page_not_found()
    {
        return view('errors.404');
    }

    public function page_not_authorized()
    {
        return view('errors.401');
    }

    public function token_login(Request $request)
    {
        $email = $request->email;

        return view('auth.token_login', compact('email'));
    }

    public function validateAccount(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first(),
                ], 200);
            }

            $password = $request->input('password');
            $loginInput = $request->input('email');

            // Determine whether the input is an email or phone
            if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', $loginInput)->first();
            } elseif (preg_match('/^\+?[0-9]{9,13}$/', $loginInput)) {
                $phone = BeamSetting::formatPhoneNumber($loginInput);
                $user = User::where('phone', $phone)->first();
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Invalid email or phone number format',
                ], 200);
            }


            if ($user && $user->is_account_verified == 0) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Email or phone number is not verified.',
                ], 200);
            }


            // Check account status
            if ($user && $user->status == "inactive") {
                return response()->json([
                    'status' => 500,
                    'message' => 'Account is inactive. Please contact admin.',
                ], 200);
            }

            // If user exists, attempt to authenticate using Auth::attempt
            if ($user && (Auth::attempt(['email' => $user->email, 'password' => $password]) ||
                Auth::attempt(['phone' => $user->phone, 'password' => $password]))) {

                $settings = Setting::first();

                // Handle two-factor authentication (if enabled)
                if ($settings && $settings->two_factor_auth == 1) {
                    if (!$user || !Hash::check($request->password, $user->password)) {
                        return response()->json([
                            'status' => 500,
                            'message' => 'Invalid credentials.',
                        ], 200);
                    }

                    $token = rand(100000, 999999);
                    $user->token = $token;
                    $user->token_expired = now()->addHour();
                    $user->save();

                    // Send SMS with OTP
                    $this->send_sms($user->phone, $token);

                    return response()->json([
                        'status' => 200,
                        'redirect' => "/token_login?email=" . $loginInput,
                        'message' => "Verify Your Account with the OTP sent to your phone number."
                    ], 200);
                }

                $user = Auth::user();
                $user->last_login = now();
                $user->save();

                return response()->json([
                    'status' => 200,
                    'redirect' => "/dashboard",
                    'message' => 'Login successful!',
                ], 200);
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Invalid credentials.',
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 200);
        }
    }


    public function verifyToken(Request $request)
    {
        try {
            // Validate the incoming request for the token and email
            $validator = Validator::make($request->all(), [
                'token' => 'required|numeric|digits:6', // Ensure the token is 6 digits
                'email' => 'required', // Validate the email
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first(),
                ], 200); // Response header status is 200
            }


            $loginInput = $request->input('email');

            // Determine whether the input is an email or phone
            if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', $loginInput)->first();
            } elseif (preg_match('/^\+?[0-9]{9,13}$/', $loginInput)) {
                $phone = BeamSetting::formatPhoneNumber($loginInput);
                $user = User::where('phone', $phone)->first();
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Invalid email or phone number format',
                ], 200);
            }


            // Check if user exists
            if (!$user) {
                return response()->json([
                    'status' => 500,
                    'message' => 'User not found.',
                ], 200);
            }

            // Check if the token is valid and not expired
            if ($user->token !== $request->token || $user->token_expired < now()) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Token is invalid or expired .',
                ], 200);
            }


            // Log in the user manually without password verification
            Auth::login($user);

            // Clear the token and update last login
            $user->last_login = now();
            $user->token = null;
            $user->token_expired = null;
            $user->is_account_verified = 1;
            $user->save();

            // Redirect to the dashboard
            return response()->json([
                'status' => 200,
                'message' => 'Authentication successful, redirecting to dashboard.',
                // 'redirect' => route('dashboard'),
                'redirect' => route('kmj.index'),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 200);
        }
    }

    public function resendToken(Request $request)
    {
        try {
            // Validate incoming request for email
            $validator = Validator::make($request->all(), [
                'email' => 'required',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first(),
                ], 200); // Response header status is 200
            }

            // Retrieve the user based on the email
            $loginInput = $request->input('email');

            // Determine whether the input is an email or phone
            if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
                $user = User::where('email', $loginInput)->first();
            } elseif (preg_match('/^\+?[0-9]{9,13}$/', $loginInput)) {
                $phone = BeamSetting::formatPhoneNumber($loginInput);
                $user = User::where('phone', $phone)->first();
            } else {
                return response()->json([
                    'status' => 500,
                    'message' => 'Invalid email or phone number format',
                ], 200);
            }

            // Check if user exists
            if (!$user) {
                return response()->json([
                    'status' => 500,
                    'message' => 'User not found.',
                ], 200);
            }

            // Generate a new token
            $token = random_int(100000, 999999); // Generate a random 6-digit token
            $user->token = $token;
            $user->token_expired = now()->addHour(); // Set token to expire in 1 hour
            $user->save();

            // send sms
            $this->send_sms($user->phone, $token);

            return response()->json([
                'status' => 200,
                'message' => 'A new token has been sent to your phone number.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 200);
        }
    }

    public function send_sms($phone, $token)
    {
        // Create the message containing the token
        $message = "Your authentication token is: $token. Please use this token to complete your login.";

        BeamSetting::send_sms($message, [$phone]);
    }

    public function forgot_password()
    {

        return view('auth.forgot_password');
    }

    public function sendPasswordResetLink(Request $request)
    {
        $request->validate(['email' => 'required']);

        // Retrieve the user based on the email
        $loginInput = $request->input('email');

        // Determine whether the input is an email or phone
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } elseif (preg_match('/^\+?[0-9]{9,13}$/', $loginInput)) {
            $phone = BeamSetting::formatPhoneNumber($loginInput);
            $user = User::where('phone', $phone)->first();
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Invalid email or phone number format',
            ], 200);
        }

        // Generate a new token
        $token = random_int(100000, 999999); // Generate a random 6-digit token
        $user->token = $token;
        $user->token_expired = now()->addHour(); // Set token to expire in 1 hour
        $user->save();

        // send sms
        $this->send_sms($user->phone, $token);

        // Send email with the reset link
        //Mail::to($user->email)->send(new CustomEmail($token, $user));

        return response()->json([
            'status' => 200,
            'redirect' => "/reset_password?email=" . $loginInput,
            'message' => 'Password reset Token sent to your email and phone number.',
        ], 200);
    }

    public function reset_password(Request $request)
    {
        return view('auth.reset_password', ['token' => $request->token, 'email' => $request->email]);
    }

    public function resetPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'token' => 'required',
            'password' => 'required|confirmed|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 500,
                'message' => $validator->errors()->first(),
            ], 200); // Response header status is 200
        }

        // Retrieve the user based on the email
        $loginInput = $request->input('email');

        // Determine whether the input is an email or phone
        if (filter_var($loginInput, FILTER_VALIDATE_EMAIL)) {
            $user = User::where('email', $loginInput)->first();
        } elseif (preg_match('/^\+?[0-9]{9,13}$/', $loginInput)) {
            $phone = BeamSetting::formatPhoneNumber($loginInput);
            $user = User::where('phone', $phone)->first();
        } else {
            return response()->json([
                'status' => 500,
                'message' => 'Invalid email or phone number format',
            ], 200);
        }

        // Check if the token is valid and not expired
        if ($user->token !== $request->token || $user->token_expired < now()) {
            return response()->json([
                'status' => 500,
                'message' => 'Token is invalid or expired .' . $user->token,
            ], 200);
        }

        // Reset the password
        $user->password = Hash::make($request->password);
        $user->is_account_verified = 1;
        $user->token = null;
        $user->token_expired = null;
        $user->save();

        return response()->json([
            'status' => 200,
            'redirect' => "/login",
            'message' => 'Password has been reset successfully.',
        ], 200);
    }

    public function logout(Request $request)
    {
        // Log out the user
        Auth::logout();

        // Invalidate the session
        $request->session()->invalidate();

        // Regenerate the session token
        $request->session()->regenerateToken();

        // Redirect to the login page
        return redirect()->route('login')->with('message', 'Logout successful!');
    }

    public function submitRegister(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'phone' => 'required|string|min:10|regex:/^\+?[0-9]{10,13}$/',
                'password' => 'required|string|min:6',
                're_password' => 'required|string|same:password',
                'referral_code' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 500,
                    'message' => $validator->errors()->first(),
                ], 200);
            }

            $phone = BeamSetting::formatPhoneNumber($request->input('phone'));
            $password = $request->input('password');
            $referral_code = $request->input('referral_code');

            // Check if user already exists
            if (User::where('phone', $phone)->where('is_account_verified', 1)->exists()) {
                return response()->json([
                    'status' => 500,
                    'message' => 'Phone number is already registered.',
                ], 200);
            }

            // Create new user
            $new_referral_code = Utility::generateUniqueNumber('users', 'referral_code');
            $user = User::updateOrCreate(
                [
                    'phone' => $phone,
                ],
                [

                    'password' => Hash::make($password),
                    'referral_code' => $new_referral_code,
                    'status' => 'active',
                    'is_account_verified' => 0,
                    'role' => 2,
                ]
            );

            // Optionally send verification SMS
            $token = rand(100000, 999999);
            $user->token = $token;
            $user->token_expired = now()->addHour();
            $user->save();

            // add refer
            if ($referral_code && !empty($referral_code)) {
                $initialUser = User::where('referral_code', $referral_code)->first();

                UserRefer::create(
                    [
                        'user_id' => $initialUser->id,
                        'refer_user_id' => $user->id,
                        'refer_level_id' => 1,
                        'date' => now(),
                        'archive' => 0,
                    ]
                );
            }

            $this->send_sms($user->phone, $token);

            return response()->json([
                'status' => 200,
                'redirect' => "/token_login?email=" . $phone,
                'message' => 'Registration successful! Verify your account with the OTP sent to your phone.',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 200);
        }
    }
}
