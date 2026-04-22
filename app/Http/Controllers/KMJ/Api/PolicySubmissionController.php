<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\PolicySubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PolicySubmissionController extends Controller
{

    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'http://192.168.168.200';
    }

    public function index()
    {
        $policySubmissions = PolicySubmission::orderBy('id', 'desc')->get();

        return view('kmj.policy-submission.index', compact('policySubmissions'));
    }

    public function savePolicySubmission(Request $request)
    {

        // return $request->all();

        DB::beginTransaction();

        try {

            // 🧱 Create Policy Submission
            $policySubmission = PolicySubmission::create([
                'policy_number' => $request->policy_number,
                'policy_operative_clause' => $request->policy_operative_clause,
                'special_conditions' => $request->special_conditions,
                'exclusions' => $request->exclusions,
                'cover_note_reference_numbers' => $request->cover_note_reference_numbers ?? [],
            ]);

            DB::commit();

            return redirect()
                ->route('policy.submission.index')
                ->with('success', 'Policy Submission saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Failed to save Policy Submission. ' . $e->getMessage());
        }
    }

    public function policySubmission($id)
    {
        $policySubmission = PolicySubmission::find($id);

        try {
            // ================= HEADER =================
            $policyHdr = [
                'RequestId' => generateRequestID(), // Unique ID
                'CompanyCode' => 'IB10152', // Your TIRA company code
                'SystemCode' => 'TP_KMJ_001', // Your TIRA system code
                'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback", // Callback endpoint
                'InsurerCompanyCode' => 'ICC125', // Insurer company code from TIRA
            ];

            // ================= DETAILS =================
            $policyDtl = [
                'PolicyNumber' => $policySubmission->policy_number,
                'PolicyOperativeClause' => $policySubmission->policy_operative_clause,
                'SpecialConditions' => $policySubmission->special_conditions,
                'Exclusions' => $policySubmission->exclusions,
            ];

            // ================= APPLIED COVER NOTES =================
            $appliedCoverNotes = [];
            if (!empty($policySubmission->cover_note_reference_numbers)) {
                foreach ($policySubmission->cover_note_reference_numbers as $coverNoteRef) {
                    $appliedCoverNotes[] = [
                        'CoverNoteReferenceNumber' => $coverNoteRef,
                    ];
                }
            }

            // Attach applied cover notes if exist
            if (!empty($appliedCoverNotes)) {
                $policyDtl['AppliedCoverNotes'] = $appliedCoverNotes;
            }

            // ================= PAYLOAD =================
            $payload = [
                'PolicyHdr' => $policyHdr,
                'PolicyDtl' => $policyDtl,
            ];

            // ================= GENERATE XML =================
            $gen_data = generateXML('PolicyReq', $payload);
            Log::channel('tiramisxml')->info($gen_data);

            // ================= SEND TO TIRA =================
            $url = $this->baseUrl . '/ecovernote/api/policy/v1/request';
            $res = TiraRequest($url, $gen_data);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('policy.submission.index')
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('policy.submission.index')
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->PolicyReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $policySubmission->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ');
                return redirect()
                    ->route('policy.submission.index')
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return redirect()
                    ->route('policy.submission.index')
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }

            // ================= RESPONSE =================
            return response()->json([
                'success' => true,
                'message' => 'Policy Submission Request Sent Successfully.',
                'response' => $res,
                'generated_xml' => $gen_data,
            ]);
        } catch (\Exception $e) {
            Log::channel('tiramisxml')->error("Policy Submission Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the policy submission request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
