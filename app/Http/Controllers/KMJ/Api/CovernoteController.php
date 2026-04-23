<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\CoverNoteVerification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CovernoteController extends Controller
{

    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'http://192.168.168.200:8091';
    }

    public function index()
    {
        $coverNotes = CoverNoteVerification::orderBy('id', 'desc')->get();

        return view('kmj.cover-note-verification.index', compact('coverNotes'));
    }

    public function saveCoverNoteVerification(Request $request)
    {

        DB::beginTransaction();
        try {
            CoverNoteVerification::create([
                'cover_note_reference_number' => $request->cover_note_reference_number,
                'sticker_number' => $request->sticker_number,
                'motor_registration_number' => $request->motor_registration_number,
                'motor_chassis_number' => $request->motor_chassis_number,
            ]);

            DB::commit();

            return redirect()
                ->route('covernote.verification.index')
                ->with('success', 'Cover Note Verification saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to save Cover Note Verification. ' . $e->getMessage());
        }
    }

    public function coverNoteVerificationSubmission($id)
    {
        $coverNote = CoverNoteVerification::find($id);
        try {
            // ================= HEADER =================
            $header = [
                'RequestId' => generateRequestID(),
                'CompanyCode' => 'IB10152', // TIRA assigned code
                'SystemCode' => 'TP_KMJ_001',
            ];

            // ================= DETAILS =================
            $details = [
                'CoverNoteReferenceNumber' => $coverNote->cover_note_reference_number,
                'StickerNumber' => $coverNote->sticker_number ?? '',
                'MotorRegistrationNumber' => $coverNote->motor_registration_number ?? '',
                'MotorChassisNumber' => $coverNote->motor_chassis_number ?? '',
            ];

            // ================= PAYLOAD =================
            $payload = [
                'VerificationHdr' => $header,
                'VerificationDtl' => $details,
            ];

            // ================= GENERATE XML =================
            $xmlData = generateXML('CoverNoteVerificationReq', $payload);

            // ================= SEND REQUEST =================
            $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/verification/v1/request', $xmlData);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            // Convert XML object to JSON
            $jsonResponse = json_encode($xml);
            $decoded = json_decode($jsonResponse, true);

            $resHeader = $decoded['CoverNoteVerificationRes']['CoverNoteHdr'] ?? [];

            $responseId         = $resHeader['ResponseId'] ?? null;
            $requestId          = $resHeader['RequestId'] ?? null;
            $responseStatusCode = $resHeader['ResponseStatusCode'] ?? null;
            $responseStatusDesc = $resHeader['ResponseStatusDesc'] ?? null;

            $coverNote->update([
                'status' => $responseStatusCode == 'TIRA001' ? 'success' : 'pending',
                'response_id'          => $responseId,
                'request_id'           => $requestId,
                'response_status_code' => $responseStatusCode,
                'response_status_desc' => $responseStatusDesc,
                'tira_response'        => $decoded, // Full JSON response saved here
            ]);




            return response()->json([
                'success' => true,
                'message' => 'Cover Note Verification Request Sent Successfully.',
                'response' => $res,
                'response_decoded'       => $decoded,
                'generated_xml' => $xmlData,
            ]);
        } catch (\Exception $e) {
            Log::error('Cover Note Verification Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
}
