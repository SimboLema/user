<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class TestEmailController extends Controller
{
    public function send(Request $request)
    {
        // return response()->json($request);
        $request->validate([
            'email' => 'required|email'
        ]);

        try {

            Mail::raw('This is a test email from Laravel SMTP configuration.', function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Test Email - SMTP Working');
            });

            return response()->json([
                'status' => true,
                'message' => 'Email sent successfully'
            ]);
        } catch (\Exception $e) {

            // Log::error('Email Error: ' . $e->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'Email failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
