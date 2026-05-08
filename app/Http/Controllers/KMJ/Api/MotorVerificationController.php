<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MotorVerificationController extends Controller
{
    public function verify(Request $request)
    {
        // 1. Validate incoming request
        $payload = $request->validate([
            'VerificationHdr.RequestId' => 'required|string',
            'VerificationHdr.CompanyCode' => 'required|string',
            'VerificationHdr.SystemCode' => 'required|string',

            'VerificationDtl.MotorCategory' => 'required|string',
            'VerificationDtl.MotorRegistrationNumber' => 'required|string',
            'VerificationDtl.MotorChassisNumber' => 'nullable|string',
        ]);

        // 2. Call TIRAMIS API
        $response = Http::withOptions([
            'verify' => false,
        ])->post(env('TIRAMIS_MOTOR_URL'), $payload);

        // 3. Return response back to client
        return response()->json([
            'success' => $response->successful(),
            'status' => $response->status(),
            'data' => $response->json(),
        ]);
    }
}
