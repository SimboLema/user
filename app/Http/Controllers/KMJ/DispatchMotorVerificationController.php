<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DispatchMotorVerificationController extends Controller
{
    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'https://api.tira.go.tz:8091';
    }
    public function dispatchMotorVerification(Request $request)
    {
        try {
            // ================= HEADER =================
            $VerificationHdr = [
                'RequestId' => generateRequestID(),
                'CompanyCode' => 'IB10152',
                'SystemCode' => 'TP_KMJ_001',
            ];

            // ================= DETAILS =================

            $VerificationDtl = [
                'MotorCategory' => $request->motor_category, // [1 or 2]
                'MotorRegistrationNumber' => $request->motor_registration_number,
                'MotorChassisNumber' => $request->motor_chassis_number, // Optional
            ];


            // ================= PAYLOAD =================
            $payload = [
                'VerificationHdr' => $VerificationHdr,
                'VerificationDtl' => $VerificationDtl,
            ];

            // ================= GENERATE XML =================
            $gen_data = generateXML('MotorVerificationReq', $payload);
            Log::channel('tiramisxml')->info($gen_data);

            // ================= SEND TO TIRA =================
            // $url = $this->baseUrl . '/dispatch/api/motor/verification/v1/request';
            $url = $this->baseUrl . '/dispatch/api/motor/verification/v1/request';

            $res = TiraRequest($url, $gen_data);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
            }

            return response()->json([
                'success' => true,
                'message' => 'MotorVerificationReq Request Sent Successfully.',
                'response' => $res,
                'generated_xml' => $gen_data,
            ]);
        } catch (\Exception $e) {
            Log::channel('tiramisxml')->error("MotorVerificationReq Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the MotorVerificationReq request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function testTime(Request $request)
    {
        try {
            date_default_timezone_set('Africa/Dar_es_Salaam');
            // Tumia DateTime constructor na tarehe ya request
            $coverNoteStart = new DateTime($request->cover_note_start_date);
            $coverNoteStart->setTime((int)date('H'), (int)date('i') + 5, (int)date('s')); // sasa + 5 min

            $coverNoteEnd = new DateTime($request->cover_note_end_date);
            $coverNoteEnd->setTime((int)date('H'), (int)date('i') + 5, (int)date('s')); // sasa + 5 min

            $data = [
                'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
            ];

            return response()->json([
                'data' => $data,
                'server_time' => now()->toDateTimeString(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Kuna tatizo: ' . $e->getMessage(),
            ], 500);
        }
    }
}
