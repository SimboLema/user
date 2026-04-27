<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Currency;
use App\Models\Models\KMJ\ParticipantType;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\Reinsurance;
use App\Models\Models\KMJ\ReinsuranceCategory;
use App\Models\Models\KMJ\ReinsuranceForm;
use App\Models\Models\KMJ\ReinsuranceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReinsuranceController extends Controller
{

    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'https://api.tira.go.tz';
    }

    // Show all reinsurances
    public function index()
    {
        // Pata reinsurances zote kutoka DB
        $reinsurances = Reinsurance::orderByDesc('id')->get();

        return view('kmj.reinsurance.index', compact('reinsurances'));
    }

    // Show single reinsurance details
    public function show($id)
    {
        $reinsurance = Reinsurance::with('participants', 'currency', 'reinsuranceCategory')->findOrFail($id);

        return view('kmj.reinsurance.show', compact('reinsurance'));
    }

    public function store(Request $request)
    {
        try {
            // Chukua data zote
            $data = $request->all();

            // 🧾 Onyesha data zote kutoka kwa request (kwa debug)
            Log::info('Full Request Data:', $data);

            // -------- SAVE MAIN REINSURANCE -----------
            $reinsurance = Reinsurance::create([
                'quotation_id'              => $data['quotation_id'] ?? null,
                'currency_id'               => $data['currency_id'] ?? null,
                'reinsurance_category_id'   => $data['reinsurance_category_id'] ?? null,
                'exchange_rate'             => $data['exchange_rate'] ?? 1.00,
                'authorizing_officer_name'  => $data['authorizing_officer_name'] ?? null,
                'authorizing_officer_title' => $data['authorizing_officer_title'] ?? null,
                // 'status'                    => $data['status'] ?? 'pending',
                // 'acknowledgement_id'        => $data['acknowledgement_id'] ?? null,
                // 'request_id'                => $data['request_id'] ?? null,
                // 'acknowledgement_status_code' => $data['acknowledgement_status_code'] ?? null,
                // 'acknowledgement_status_desc' => $data['acknowledgement_status_desc'] ?? null,
                // 'response_id'               => $data['response_id'] ?? null,
                // 'response_status_code'      => $data['response_status_code'] ?? null,
                // 'response_status_desc'      => $data['response_status_desc'] ?? null,
            ]);

            Log::info('Saved Reinsurance Record:', $reinsurance->toArray());

            // -------- SAVE PARTICIPANTS -----------
            if (!empty($data['reinsurance_details']) && is_array($data['reinsurance_details'])) {

                foreach ($data['reinsurance_details'] as $key => $detail) {
                    // Onyesha kila participant data kabla ya kuhifadhi
                    Log::info("Participant #{$key} Data:", $detail);

                    $participant = $reinsurance->participants()->create([
                        'participant_code'        => $detail['participant_code'] ?? null,
                        'participant_type_id'     => $detail['participant_type_id'] ?? null,
                        'reinsurance_form_id'     => $detail['reinsurance_form_id'] ?? null,
                        'reinsurance_type_id'     => $detail['reinsurance_type_id'] ?? null,
                        'rebroker_code'           => $detail['rebroker_code'] ?? null,
                        'brokerage_commission'    => $detail['brokerage_commission'] ?? 0,
                        'reinsurance_commission'  => $detail['reinsurance_commission'] ?? 0,
                        'premium_share'           => $detail['premium_share'] ?? 0,
                        'participation_date'      => $detail['participation_date'] ?? now(),
                    ]);

                    Log::info("Saved Participant #{$key} Record:", $participant->toArray());
                }
            }

            // Load participants for response
            $reinsurance->load('participants');

            // -------- RETURN FULL RESPONSE -----------
            return response()->json([
                'success' => true,
                'message' => 'Reinsurance and participants saved successfully.',
                'data' => [
                    'main_data' => $reinsurance,
                    'participants' => $reinsurance->participants
                ]
            ], 201);
        } catch (\Exception $e) {
            // Onyesha kosa kwenye log
            Log::error('Reinsurance Save Error:', ['error' => $e->getMessage(), 'line' => $e->getLine()]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line' => $e->getLine()
            ], 500);
        }
    }

    public function reinsuranceSubmission($id)
    {
        $reinsurance = Reinsurance::find($id);
        $quotation = Quotation::find($reinsurance->quotation_id);

        // Build ReinsuranceReq per TIRA spec 7.1

        $reinsuranceHdr = [
            'RequestId' => generateRequestID(),
            'CompanyCode' => 'IB10152',
            'SystemCode' => 'TP_KMJ_001',
            'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback",
            'InsurerCompanyCode' => 'ICC125',
            'CoverNoteReferenceNumber' => $quotation->cover_note_reference,
            'PremiumIncludingTax' => $quotation->total_premium_including_tax,
            'CurrencyCode' => $reinsurance->currency->code,
            'ExchangeRate' => $reinsurance->exchange_rate,
            'AuthorizingOfficerName' => $reinsurance->authorizing_officer_name,
            'AuthorizingOfficerTitle' => $reinsurance->authorizing_officer_title,
            'ReinsuranceCategory' => $reinsurance->reinsurance_category_id,
        ];

        // Expect an array of participants in reinsurance_details
        $details = [];
        foreach ($reinsurance->participants as $participant) {



            $details[] = [
                'ParticipantCode' => $participant->participant_code ?? '', #given by TIRA
                'ParticipantType' => $participant->participant_type_id,
                'ReinsuranceForm' => $participant->reinsurance_form_id,
                'ReinsuranceType' => $participant->reinsurance_type_id,
                'ReBrokerCode' =>  $participant->rebroker_code ?? '', #given by TIRA
                'BrokerageCommission' => $participant->brokerage_commission ?? '',
                'ReinsuranceCommission' => $participant->reinsurance_commission ?? '',
                'PremiumShare' => $participant->premium_share  ?? '',
                'ParticipationDate' => $participant->participation_date ? returnTiraDate($participant->participation_date) : '',
            ];
        }

        $payload = [
            'ReinsuranceHdr' => $reinsuranceHdr,
        ];

        // Repeat ReinsuranceDtl tags
        if (!empty($details)) {
            $payload['ReinsuranceDtl'] = $details;
        }

        try {
            $gen_data = generateXML('ReinsuranceReq', $payload);

            // return $gen_data;

            Log::channel('tiramisxml')->info($gen_data);
            $res = TiraRequest($this->baseUrl . '/ecovernote/api/reinsurance/v1/request', $gen_data);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('kmj.reinsurance.index')
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('kmj.reinsurance.index')
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->ReinsuranceReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $reinsurance->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);


                Log::info('TIRA response saved for Quotation ID: ' . $quotation->id);
                Log::info('TIRA Reinsurance Request Data', [
                    'generated_xml' => $gen_data,
                    'timestamp' => now(),
                ]);


                return redirect()
                    ->route('kmj.reinsurance.index')
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return response()->json([
                    'success' => 'TRA Response',
                    'response' => $res,
                    'gen_data' => $gen_data,
                ]);
                return redirect()
                    ->route('kmj.reinsurance.index',)
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }


            return response()->json([
                'success' => 'TRA Response',
                'response' => $res,
                'gen_data' => $gen_data,
            ]);
        } catch (\Exception $e) {
            Log::channel('tiramisxml')->info($e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ]);
        }
    }
}
