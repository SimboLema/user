<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\ClaimAssessment;
use App\Models\Models\KMJ\ClaimAssessmentClaimant;
use App\Models\Models\KMJ\ClaimDischargeVoucher;
use App\Models\Models\KMJ\ClaimDischargeVoucherClaimant;
use App\Models\Models\KMJ\ClaimIntimation;
use App\Models\Models\KMJ\ClaimIntimationClaimant;
use App\Models\Models\KMJ\ClaimNotification;
use App\Models\Models\KMJ\ClaimPayment;
use App\Models\Models\KMJ\ClaimPaymentClaimant;
use App\Models\Models\KMJ\ClaimRejection;
use App\Models\Models\KMJ\ClaimRejectionClaimant;
use App\Models\Models\KMJ\Country;
use App\Models\Models\KMJ\Currency;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClaimController extends Controller
{

    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'https://api.tira.go.tz:8091';
    }
    public function index()
    {
        $claims = ClaimNotification::orderBy('id', 'desc')->get();

        return view('kmj.claim.index', compact('claims'));
    }



    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'quotation_id' => 'required|exists:quotations,id',
                'claim_notification_number' => 'required|string',
                'claim_report_date' => 'required|date',
                'claim_form_dully_filled' => 'required|string|max:1',
                'loss_date' => 'required|date',
                'loss_nature' => 'required|string',
                'loss_type' => 'required|string',
                'loss_location' => 'required|string',
                'officer_name' => 'required|string',
                'officer_title' => 'required|string',
            ]);

            $claim = ClaimNotification::create($data);
            $quotation = Quotation::find($request->quotation_id);

            return redirect()->route('kmj.claims')->with([
                'success' => 'Claim notification saved successfully.',
                'claim' => $claim,
                'quotation' => $quotation
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Claim notification saved successfully.',
                'data' => $claim
            ], 201);
        } catch (\Exception $e) {
            // Optional: log error
            Log::error('Claim Notification Save Error: ' . $e->getMessage());

            // Redirect back with error message
            return redirect()->back()->with('error', 'Failed to save claim notification. ' . $e->getMessage());
        }
    }

    public function quotationCreateNotification($quotationId, Request $request)
    {
        $quotation = Quotation::find($quotationId);
        if (!$quotation) {
            return redirect()->back()->with('error', 'Quotation not found.');
        }

        $coverNoteRef = $request->query('cover_note');

        $claims = ClaimNotification::where('quotation_id', $quotation->id)->orderBy('id', 'desc')->get();


        return view('kmj.claim.create', compact('quotation', 'claims'));
    }


    // Other tabs

    public function notification($id)
    {


        $claim = ClaimNotification::find($id);

        return view('kmj.claim.tabs.notification', compact('claim'));
    }

    public function claimNotificationSubmission($id)
    {
        $claim = ClaimNotification::find($id);

        try {
            // ================= HEADER =================
            $claimNotificationHdr = [
                'RequestId' => generateRequestID(), // Unique ID per request
                'CompanyCode' => 'IB10152', // TIRA assigned system owner code
                'SystemCode' => 'TP_KMJ_001', // TIRA system code
                'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback", // Your callback endpoint
                'InsurerCompanyCode' => 'ICC125', // Insurer company code assigned by TIRA
                'TranCompanyCode' => 'IB10152', // Transaction company code (same as Insurer if direct)
            ];

            // ================= DETAILS =================
            $claimNotificationDtl = [
                'ClaimNotificationNumber' => $claim->claim_notification_number,
                'CoverNoteReferenceNumber' => $claim->quotation->cover_note_reference,
                'ClaimReportDate' => returnTiraDate($claim->claim_report_date),
                'ClaimFormDullyFilled' => strtoupper($claim->claim_form_dully_filled ?? 'Y'),
                'LossDate' => returnTiraDate($claim->loss_date),
                'LossNature' => $claim->loss_nature,
                'LossType' => $claim->loss_type,
                'LossLocation' => $claim->loss_location,
                'OfficerName' => $claim->officer_name,
                'OfficerTitle' => $claim->officer_title,
            ];

            // ================= PAYLOAD =================
            $payload = [
                'ClaimNotificationHdr' => $claimNotificationHdr,
                'ClaimNotificationDtl' => $claimNotificationDtl,
            ];

            // ================= GENERATE XML =================
            $gen_data = generateXML('ClaimNotificationRefReq', $payload);
            Log::channel('tiramisxml')->info($gen_data);

            // ================= SEND TO TIRA =================
            $url = $this->baseUrl . '/eclaim/api/claim/claim-notification/v1/request';
            $res = TiraRequest($url, $gen_data);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('claim.notification', ['id' => $claim->id])
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('claim.notification', ['id' => $claim->id])
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->ClaimNotificationRefReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $claim->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $claim->id);
                return redirect()
                    ->route('claim.notification', ['id' => $claim->id])
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return redirect()
                    ->route('claim.notification', ['id' => $claim->id])
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }

            // ================= RESPONSE =================
            return response()->json([
                'success' => true,
                'message' => 'Claim Notification submitted successfully to TIRA.',
                'response' => $res,
                'generated_xml' => $gen_data,
            ]);
        } catch (\Exception $e) {
            Log::channel('tiramisxml')->error("Claim Notification Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the claim notification request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    //intimation

    public function intimation($id)
    {

        $claim = ClaimNotification::find($id);
        $currencies = Currency::where('id', 1)->get();
        $regions  = Region::where('country_id', 219)->get();

        $claimIntimations = ClaimIntimation::where('claim_notification_id', $claim->id)->get();
        return view('kmj.claim.tabs.intimation', compact('claim', 'currencies', 'regions', 'claimIntimations'));
    }

    public function saveClaimIntimation(Request $request)
    {
        // return $request->all();

        DB::beginTransaction();

        try {

            $claim = ClaimNotification::find($request->claim_notification_id);
            // 1️⃣ Hifadhi Claim Intimation
            $claimIntimation = ClaimIntimation::create([
                'claim_notification_id'   => $request->claim_notification_id,
                'claim_intimation_number' => $request->claim_intimation_number,
                'claim_intimation_date'   => $request->claim_intimation_date,
                'currency_id'             => $request->currency_id,
                'exchange_rate'           => $request->exchange_rate,
                'claim_estimated_amount'  => $request->claim_estimated_amount,
                'claim_reserve_amount'    => $request->claim_reserve_amount,
                'claim_reserve_method'    => $request->claim_reserve_method,
                'loss_assessment_option'  => $request->loss_assessment_option,
                'assessor_name'           => $request->assessor_name,
                'assessor_id_number'      => $request->assessor_id_number,
                'assessor_id_type'        => $request->assessor_id_type,
            ]);

            // 2️⃣ Hifadhi Claimants kama wameletwa
            if ($request->has('claimants') && is_array($request->claimants)) {
                foreach ($request->claimants as $claimant) {
                    ClaimIntimationClaimant::create([
                        'claim_intimation_id'   => $claimIntimation->id,
                        'claimant_name'         => $claimant['claimant_name'] ?? null,
                        'claimant_birth_date'   => $claimant['claimant_birth_date'] ?? null,
                        'claimant_category'     => $claimant['claimant_category'] ?? null,
                        'claimant_type'         => $claimant['claimant_type'] ?? null,
                        'claimant_id_number'    => $claimant['claimant_id_number'] ?? null,
                        'claimant_id_type'      => $claimant['claimant_id_type'] ?? null,
                        'gender'                => $claimant['gender'] ?? null,
                        'district_id'           => $claimant['district_id'] ?? null,
                        'street'                => $claimant['street'] ?? null,
                        'claimant_phone_number' => $claimant['claimant_phone_number'] ?? null,
                        'claimant_fax'          => $claimant['claimant_fax'] ?? null,
                        'postal_address'        => $claimant['postal_address'] ?? null,
                        'email_address'         => $claimant['email_address'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('claim.intimation', ['id' => $claim->id])
                ->with('success', 'Claim Intimation and claimants saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to save Claim Intimation. ' . $e->getMessage());
        }
    }

    public function claimIntimationSubmission($id)
    {
        $intimation = ClaimIntimation::find($id);

        try {
            // ================= HEADER =================
            $hdr = [
                'RequestId' => generateRequestID(), // helper that you already use
                'CompanyCode' => 'IB10152', // replace with your TIRA CompanyCode if different
                'SystemCode' => 'TP_KMJ_001',
                'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback",
                'InsurerCompanyCode' => 'ICC125',
            ];

            // ================= DETAILS =================
            $dtl = [
                'ClaimIntimationNumber' => $intimation->claim_intimation_number,
                'ClaimReferenceNumber' => $intimation->claimNotification->claim_reference_number,
                'CoverNoteReferenceNumber' => $intimation->claimNotification->quotation->cover_note_reference,
                'ClaimIntimationDate' => returnTiraDate($intimation->claim_intimation_date),
                'CurrencyCode' => $intimation->currency->code,
                'ExchangeRate' => $intimation->exchange_rate,
                'ClaimEstimatedAmount' => $intimation->claim_estimated_amount,
                'ClaimReserveAmount' => $intimation->claim_reserve_amount,
                'ClaimReserveMethod' => $intimation->claim_reserve_method,
                'LossAssessmentOption' => $intimation->loss_assessment_option,
                'AssessorName' => $intimation->assessor_name,
                'AssessorIdNumber' => $intimation->assessor_id_number,
                'AssessorIdType' => $intimation->assessor_id_type,
            ];

            // ================= CLAIMANTS =================
            // TIRA expects <Claimants><Claimant>...</Claimant></Claimants>
            $claimants = [];
            foreach ($intimation->claimants as $c) {
                $claimants[] = [
                    'ClaimantName' => $c->claimant_name,
                    'ClaimantBirthDate' => $c->claimant_birth_date,
                    'ClaimantCategory' => $c->claimant_category,
                    'ClaimantType' => $c->claimant_type,
                    'ClaimantIdNumber' => $c->claimant_id_number,
                    'ClaimantIdType' => $c->claimant_id_type,
                    'Gender' => $c->gender,
                    'CountryCode' => $c->district->region->country->code,
                    'Region' => $c->district->region->name,
                    'District' => $c->district->name,
                    'Street' => $c->street,
                    'ClaimantPhoneNumber' => $c->claimant_phone_number,
                    'ClaimantFax' => $c->claimant_fax,
                    'PostalAddress' => $c->postal_address,
                    'EmailAddress' => $c->email_address,
                ];
            }

            if (!empty($claimants)) {
                // Depending on your generateXML implementation, you can either:
                // - set 'Claimants' => ['Claimant' => $claimants] or
                // - set 'Claimants' => $claimants  (generateXML should map array to repeated tags)
                // I'll use 'Claimants' => ['Claimant' => $claimants] to be explicit.
                $dtl['Claimants'] = [
                    'Claimant' => $claimants
                ];
            }

            // ================= PAYLOAD =================
            $payload = [
                'ClaimIntimationHdr' => $hdr,
                'ClaimIntimationDtl' => $dtl,
            ];

            // ================= GENERATE XML =================
            $gen_data = generateXML('ClaimIntimationReq', $payload);
            Log::channel('tiramisxml')->info($gen_data);

            // ================= SEND TO TIRA =================
            $url = $this->baseUrl . '/eclaim/api/claim/claim-intimation/v1/request';
            $res = TiraRequest($url, $gen_data);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('claim.intimation', ['id' => $intimation->claim_notification_id])
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('claim.intimation', ['id' => $intimation->claim_notification_id])
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->ClaimIntimationReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $intimation->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $intimation->claim_notification_id);
                return redirect()
                    ->route('claim.intimation', ['id' => $intimation->claim_notification_id])
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return redirect()
                    ->route('claim.intimation', ['id' => $intimation->claim_notification_id])
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }

            // ================= RESPONSE =================
            return response()->json([
                'success' => true,
                'message' => 'Claim Intimation Request Sent Successfully.',
                'response' => $res,
                'generated_xml' => $gen_data,
            ]);
        } catch (\Exception $e) {
            Log::channel('tiramisxml')->error("Claim Intimation Error: " . $e->getMessage());
            return response()->json([
                'success' => false,
                'error' => 'An error occurred while processing the claim intimation request.',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function assessment($id)
    {

        $claim = ClaimNotification::find($id);
        $currencies = Currency::where('id', 1)->get();

        $claimAssessments = ClaimAssessment::where('claim_notification_id', $claim->id)->get();
        return view('kmj.claim.tabs.assessment', compact('claim', 'claimAssessments', 'currencies'));
    }

    public function saveClaimAssessment(Request $request)
    {
        DB::beginTransaction();

        try {
            // 1️⃣ Pata claim notification
            $claim = ClaimNotification::find($request->claim_notification_id);
            if (!$claim) {
                return redirect()->back()->with('error', 'Claim Notification not found.');
            }

            // 2️⃣ Hifadhi Claim Assessment
            $claimAssessment = ClaimAssessment::create([
                'claim_notification_id'       => $request->claim_notification_id,
                'claim_assessment_number'     => $request->claim_assessment_number,
                'claim_intimation_number'     => $request->claim_intimation_number,
                'assessment_received_date'    => $request->assessment_received_date,
                'assessment_report_summary'   => $request->assessment_report_summary,
                'currency_id'                 => $request->currency_id,
                'exchange_rate'               => $request->exchange_rate,
                'assessment_amount'           => $request->assessment_amount,
                'approved_claim_amount'       => $request->approved_claim_amount,
                'claim_approval_date'         => $request->claim_approval_date,
                'claim_approval_authority'    => $request->claim_approval_authority,
                'is_re_assessment'            => $request->is_re_assessment ?? 'N',

            ]);

            // 3️⃣ Hifadhi Claim Assessment Claimants
            if ($request->has('claimants') && is_array($request->claimants)) {
                foreach ($request->claimants as $claimant) {
                    ClaimAssessmentClaimant::create([
                        'claim_assessment_id' => $claimAssessment->id,
                        'claimant_category'   => $claimant['claimant_category'] ?? null,
                        'claimant_type'       => $claimant['claimant_type'] ?? null,
                        'claimant_id_number'  => $claimant['claimant_id_number'] ?? null,
                        'claimant_id_type'    => $claimant['claimant_id_type'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('claim.assessment', ['id' => $claim->id])
                ->with('success', 'Claim Assessment and claimants saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to save Claim Assessment. ' . $e->getMessage());
        }
    }

    public function claimAssessmentSubmission($id)
    {

        $assessment = ClaimAssessment::find($id);

        try {

            // Header Details
            $header = [
                'RequestId' =>  generateRequestID(),
                'CompanyCode' => 'IB10152',
                'SystemCode' => 'TP_KMJ_001',
                'CallBackUrl' => 'https://suretech.co.tz/api/tiramis/callback',
                'InsurerCompanyCode' => 'ICC125',
            ];

            // Detail Section
            $claimantsArray = [];
            foreach ($assessment->claimants as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c->claimant_category ?? 2,
                    'ClaimantType' => $c->claimant_type ?? 1,
                    'ClaimantIdNumber' => $c->claimant_id_number ?? '',
                    'ClaimantIdType' => $c->claimant_id_type ?? 1,
                ];
            }
            $details = [
                'ClaimAssessmentNumber' => $assessment->claim_assessment_number,
                'ClaimIntimationNumber' => $assessment->claim_intimation_number,
                'ClaimReferenceNumber' => $assessment->claimNotification->claim_reference_number,
                'CoverNoteReferenceNumber' => $assessment->claimNotification->quotation->cover_note_reference,
                'AssessmentReceivedDate' => returnTiraDate($assessment->assessment_received_date),
                'AssessmentReportSummary' => $assessment->assessment_report_summary,
                'CurrencyCode' => $assessment->currency->code,
                'ExchangeRate' => $assessment->exchange_rate,
                'AssessmentAmount' => $assessment->assessment_amount,
                'ApprovedClaimAmount' => $assessment->approved_claim_amount,
                'ClaimApprovalDate' => returnTiraDate($assessment->claim_approval_date),
                'ClaimApprovalAuthority' => $assessment->claim_approval_authority,
                'IsReAssessment' => $assessment->is_re_assessment,
                'Claimants' => [
                    'Claimant' => $claimantsArray
                ],
            ];

            // Full Payload
            $payload = [
                'ClaimAssessmentHdr' => $header,
                'ClaimAssessmentDtl' => $details,
            ];

            // XML Generation
            $xmlData = generateXML('ClaimAssessmentReq', $payload);

            // Send to TIRA Endpoint
            $res = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-assessment/v1/request', $xmlData);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('claim.assessment', ['id' => $assessment->claim_notification_id])
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('claim.assessment', ['id' => $assessment->claim_notification_id])
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->ClaimAssessmentReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $assessment->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $assessment->claim_notification_id);
                return redirect()
                    ->route('claim.assessment', ['id' => $assessment->claim_notification_id])
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return redirect()
                    ->route('claim.assessment', ['id' => $assessment->claim_notification_id])
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Claim Assessment Request Sent Successfully.',
                'response' => $res,
                'generated_xml' => $xmlData
            ]);
        } catch (\Exception $e) {
            Log::error('Claim Assessment Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }






    public function dischargeVoucher($id)
    {

        $claim = ClaimNotification::find($id);
        $currencies = Currency::where('id', 1)->get();

        $claimDischargeVouchers = ClaimDischargeVoucher::where('claim_notification_id', $claim->id)->get();
        return view('kmj.claim.tabs.discharge-voucher', compact('claim', 'claimDischargeVouchers', 'currencies'));
    }

    public function saveDischargeVoucher(Request $request)
    {

        DB::beginTransaction();

        try {
            // 1️⃣ Pata claim notification
            $claim = ClaimNotification::find($request->claim_notification_id);
            if (!$claim) {
                return redirect()->back()->with('error', 'Claim Notification not found.');
            }

            // 2️⃣ Hifadhi Discharge Voucher
            $voucher = ClaimDischargeVoucher::create([
                'claim_notification_id'          => $request->claim_notification_id,
                'discharge_voucher_number'       => $request->discharge_voucher_number,
                'claim_assessment_number'        => $request->claim_assessment_number,
                // 'cover_note_reference_number'    => $request->cover_note_reference_number,
                'discharge_voucher_date'         => $request->discharge_voucher_date,
                'currency_id'                    => $request->currency_id,
                'exchange_rate'                  => $request->exchange_rate,
                'claim_offer_communication_date' => $request->claim_offer_communication_date,
                'claim_offer_amount'             => $request->claim_offer_amount,
                'claimant_response_date'         => $request->claimant_response_date,
                'adjustment_date'                => $request->adjustment_date,
                'adjustment_reason'              => $request->adjustment_reason,
                'adjustment_amount'              => $request->adjustment_amount,
                'reconciliation_date'            => $request->reconciliation_date,
                'reconciliation_summary'         => $request->reconciliation_summary,
                'reconciled_amount'              => $request->reconciled_amount,
                'offer_accepted'                 => $request->offer_accepted ?? 'N',

            ]);

            // 3️⃣ Hifadhi Discharge Voucher Claimants
            if ($request->has('claimants') && is_array($request->claimants)) {
                foreach ($request->claimants as $claimant) {
                    ClaimDischargeVoucherClaimant::create([
                        'discharge_voucher_id' => $voucher->id,
                        'claimant_category'    => $claimant['claimant_category'] ?? null,
                        'claimant_type'        => $claimant['claimant_type'] ?? null,
                        'claimant_id_number'   => $claimant['claimant_id_number'] ?? null,
                        'claimant_id_type'     => $claimant['claimant_id_type'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('claim.discharge.voucher', ['id' => $claim->id])
                ->with('success', 'Discharge Voucher and claimants saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to save Discharge Voucher. ' . $e->getMessage());
        }
    }

    public function claimDischargeVoucherSubmission($id)
    {

        $voucher = ClaimDischargeVoucher::find($id);

        try {
            // Validation

            // Header
            $header = [
                'RequestId' => generateRequestID(),
                'CompanyCode' => 'IB10152',
                'SystemCode' => 'TP_KMJ_001',
                'CallBackUrl' => 'https://suretech.co.tz/api/tiramis/callback',
                'InsurerCompanyCode' => 'ICC125',
            ];

            $claimantsArray = [];
            foreach ($voucher->claimants as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c->claimant_category ?? 2,
                    'ClaimantType' => $c->claimant_type ?? 1,
                    'ClaimantIdNumber' => $c->claimant_id_number ?? '',
                    'ClaimantIdType' => $c->claimant_id_type ?? 1,
                ];
            }

            // Details
            $details = [
                'DischargeVoucherNumber' => $voucher->discharge_voucher_number,
                'ClaimAssessmentNumber' => $voucher->claim_assessment_number,
                'ClaimReferenceNumber' => $voucher->claimNotification->claim_reference_number,
                'CoverNoteReferenceNumber' => $voucher->claimNotification->quotation->cover_note_reference,
                'DischargeVoucherDate' => returnTiraDate($voucher->discharge_voucher_date),
                'CurrencyCode' => $voucher->currency->code,
                'ExchangeRate' => $voucher->exchange_rate,
                'ClaimOfferCommunicationDate' => returnTiraDate($voucher->claim_offer_communication_date),
                'ClaimOfferAmount' => $voucher->claim_offer_amount,
                'ClaimantResponseDate' => returnTiraDate($voucher->claimant_response_date),
                'AdjustmentDate' => returnTiraDate($voucher->adjustment_date),
                'AdjustmentReason' => $voucher->adjustment_reason,
                'AdjustmentAmount' => $voucher->adjustment_amount,
                'ReconciliationDate' => returnTiraDate($voucher->reconciliation_date),
                'ReconciliationSummary' => $voucher->reconciliation_summary,
                'ReconciledAmount' => $voucher->reconciled_amount,
                'OfferAccepted' => $voucher->offer_accepted,
                'Claimants' => [
                    'Claimant' => $claimantsArray
                ],
            ];

            // Payload
            $payload = [
                'DischargeVoucherHdr' => $header,
                'DischargeVoucherDtl' => $details,
            ];

            // Convert to XML
            $xmlData = generateXML('DischargeVoucherReq', $payload);

            // Send request to TIRA endpoint
            $res = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-dischargevoucher/v1/request', $xmlData);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('claim.discharge.voucher', ['id' => $voucher->claim_notification_id])
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('claim.discharge.voucher', ['id' => $voucher->claim_notification_id])
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->DischargeVoucherReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $voucher->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $voucher->claim_notification_id);
                return redirect()
                    ->route('claim.discharge.voucher', ['id' => $voucher->claim_notification_id])
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return redirect()
                    ->route('claim.discharge.voucher', ['id' => $voucher->claim_notification_id])
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Claim Discharge Voucher Request Sent Successfully.',
                'response' => $res,
                'generated_xml' => $xmlData,
            ]);
        } catch (\Exception $e) {
            Log::error('Claim Discharge Voucher Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }

    public function payment($id)
    {

        $claim = ClaimNotification::find($id);
        $currencies = Currency::where('id', 1)->get();

        $claimPayments = ClaimPayment::where('claim_notification_id', $claim->id)->get();
        return view('kmj.claim.tabs.payment', compact('claim', 'claimPayments', 'currencies'));
    }

    public function saveClaimPayment(Request $request)
    {

        DB::beginTransaction();

        try {
            // 1️⃣ Pata claim notification
            $claim = ClaimNotification::find($request->claim_notification_id);
            if (!$claim) {
                return redirect()->back()->with('error', 'Claim Notification not found.');
            }

            // 2️⃣ Hifadhi Claim Payment
            $payment = ClaimPayment::create([
                'claim_notification_id'     => $request->claim_notification_id,
                'claim_payment_number'      => $request->claim_payment_number,
                'claim_intimation_number'   => $request->claim_intimation_number,
                'payment_date'              => $request->payment_date,
                'paid_amount'               => $request->paid_amount,
                'payment_mode'              => $request->payment_mode,
                'parties_notified'          => $request->parties_notified,
                'net_premium_earned'        => $request->net_premium_earned,
                'claim_resulted_litigation' => $request->claim_resulted_litigation ?? 'N',
                'litigation_reason'         => $request->litigation_reason,
                'currency_id'               => $request->currency_id,
                'exchange_rate'             => $request->exchange_rate,
            ]);

            // 3️⃣ Hifadhi Claim Payment Claimants
            if ($request->has('claimants') && is_array($request->claimants)) {
                foreach ($request->claimants as $claimant) {
                    ClaimPaymentClaimant::create([
                        'claim_payment_id'     => $payment->id,
                        'claimant_category'    => $claimant['claimant_category'] ?? null,
                        'claimant_type'        => $claimant['claimant_type'] ?? null,
                        'claimant_id_number'   => $claimant['claimant_id_number'] ?? null,
                        'claimant_id_type'     => $claimant['claimant_id_type'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('claim.payment', ['id' => $claim->id])
                ->with('success', 'Claim Payment and claimants saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()
                ->back()
                ->with('error', 'Failed to save Claim Payment. ' . $e->getMessage());
        }
    }

    public function claimPaymentSubmission($id)
    {
        $payment = ClaimPayment::find($id);

        try {
            // Header
            $header = [
                'RequestId' => generateRequestID(),
                'CompanyCode' => 'IB10152',
                'SystemCode' => 'TP_KMJ_001',
                'CallBackUrl' => 'https://suretech.co.tz/api/tiramis/callback',
                'InsurerCompanyCode' => 'ICC125',
            ];

            $claimantsArray = [];
            foreach ($payment->claimants as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c->claimant_category ?? 2,
                    'ClaimantType' => $c->claimant_type ?? 1,
                    'ClaimantIdNumber' => $c->claimant_id_number ?? '',
                    'ClaimantIdType' => $c->claimant_id_type ?? 1,
                ];
            }


            // Details
            $details = [
                'ClaimPaymentNumber' => $payment->claim_payment_number,
                'ClaimReferenceNumber' => $payment->claimNotification->claim_reference_number,
                'ClaimIntimationNumber' => $payment->claim_intimation_number,
                'CoverNoteReferenceNumber' => $payment->claimNotification->quotation->cover_note_reference,
                'PaymentDate' => returnTiraDate($payment->payment_date) ?? now()->format('Y-m-d\TH:i:s'),
                'PaidAmount' => $payment->paid_amount,
                'PaymentMode' => $payment->payment_mode,
                'PartiesNotified' => $payment->parties_notified,
                'NetPremiumEarned' => $payment->net_premium_earned,
                'ClaimResultedLitigation' => $payment->claim_resulted_litigation,
                'LitigationReason' => $payment->litigation_reason,
                'CurrencyCode' => $payment->currency->code,
                'ExchangeRate' => $payment->exchange_rate,
                'Claimants' => [
                    'Claimant' => $claimantsArray
                ]
            ];

            // Payload
            $payload = [
                'ClaimPaymentHdr' => $header,
                'ClaimPaymentDtl' => $details,
            ];

            // Generate XML
            $xmlData = generateXML('ClaimPaymentReq', $payload);

            // Send request
            $res = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-payment/v1/request', $xmlData);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('claim.payment', ['id' => $payment->claim_notification_id])
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('claim.payment', ['id' => $payment->claim_notification_id])
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->ClaimPaymentReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $payment->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $payment->claim_notification_id);
                return redirect()
                    ->route('claim.payment', ['id' => $payment->claim_notification_id])
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return redirect()
                    ->route('claim.payment', ['id' => $payment->claim_notification_id])
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Claim Payment Request Sent Successfully.',
                'response' => $res,
                'generated_xml' => $xmlData,
            ]);
        } catch (\Exception $e) {
            Log::error('Claim Payment Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }



    public function rejection($id)
    {

        $claim = ClaimNotification::find($id);
        $currencies = Currency::where('id', 1)->get();

        $claimRejections = ClaimRejection::where('claim_notification_id', $claim->id)->get();
        return view('kmj.claim.tabs.rejection', compact('claim', 'claimRejections', 'currencies'));
    }

    public function saveClaimRejection(Request $request)
    {

        DB::beginTransaction();

        try {
            // 1️⃣ Validate Claim Notification existence
            $claim = ClaimNotification::find($request->claim_notification_id);
            if (!$claim) {
                return redirect()->back()->with('error', 'Claim Notification not found.');
            }

            // 2️⃣ Save Claim Rejection
            $rejection = ClaimRejection::create([
                'claim_notification_id'    => $request->claim_notification_id,
                'claim_rejection_number'   => $request->claim_rejection_number,
                'claim_intimation_number'  => $request->claim_intimation_number,
                'rejection_date'           => $request->rejection_date,
                'rejection_reason'         => $request->rejection_reason,
                'claim_resulted_litigation' => $request->claim_resulted_litigation ?? 'N',
                'claim_amount'             => $request->claim_amount,
                'currency_id'              => $request->currency_id,
                'exchange_rate'            => $request->exchange_rate,
                'status'                   => 'pending',
            ]);

            // 3️⃣ Save Claimants
            if ($request->has('claimants') && is_array($request->claimants)) {
                foreach ($request->claimants as $claimant) {
                    ClaimRejectionClaimant::create([
                        'claim_rejection_id'   => $rejection->id,
                        'claimant_category'    => $claimant['claimant_category'] ?? null,
                        'claimant_type'        => $claimant['claimant_type'] ?? null,
                        'claimant_id_number'   => $claimant['claimant_id_number'] ?? null,
                        'claimant_id_type'     => $claimant['claimant_id_type'] ?? null,
                    ]);
                }
            }

            DB::commit();

            return redirect()
                ->route('claim.rejection', ['id' => $claim->id])
                ->with('success', 'Claim Rejection and claimants saved successfully.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed to save Claim Rejection. ' . $e->getMessage());
        }
    }

    public function claimRejectionSubmission($id)
    {

        $rejection = ClaimRejection::find($id);

        try {
            // ================= HEADER =================
            $header = [
                'RequestId' => generateRequestID(),
                'CompanyCode' => 'IB10152',
                'SystemCode' => 'TP_KMJ_001',
                'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback",
                'InsurerCompanyCode' => 'ICC125',
            ];

            // ================= CLAIMANTS LOOP =================
            $claimantsArray = [];
            foreach ($rejection->claimants as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c->claimant_category ?? 2,
                    'ClaimantType' => $c->claimant_type ?? 1,
                    'ClaimantIdNumber' => $c->claimant_id_number ?? '',
                    'ClaimantIdType' => $c->claimant_id_type ?? 1,
                ];
            }

            // ================= DETAILS =================
            $details = [
                'ClaimRejectionNumber' => $rejection->claim_rejection_number,
                'ClaimReferenceNumber' => $rejection->claimNotification->claim_reference_number,
                'ClaimIntimationNumber' => $rejection->claim_intimation_number,
                'CoverNoteReferenceNumber' => $rejection->claimNotification->quotation->cover_note_reference,
                'RejectionDate' => returnTiraDate($rejection->rejection_date) ?? now()->format('Y-m-d\TH:i:s'),
                'RejectionReason' => $rejection->rejection_reason,
                'ClaimResultedLitigation' => $rejection->claim_resulted_litigation,
                'ClaimAmount' => $rejection->claim_amount,
                'CurrencyCode' => $rejection->currency->code,
                'ExchangeRate' => $rejection->exchange_rate,
                'Claimants' => [
                    'Claimant' => $claimantsArray
                ]
            ];

            // ================= PAYLOAD =================
            $payload = [
                'ClaimRejectionHdr' => $header,
                'ClaimRejectionDtl' => $details,
            ];

            // ================= GENERATE XML =================
            $xmlData = generateXML('ClaimRejectionReq', $payload);

            // ================= SEND REQUEST =================
            $res = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-rejection/v1/request', $xmlData);

            $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

            if (empty($xmlString)) {
                Log::error('Empty XML response from TIRA');
                return redirect()
                    ->route('claim.rejection', ['id' => $rejection->claim_notification_id])
                    ->with('error', 'Empty response from TIRA.');
                return response()->json(['error' => 'Empty response from TIRA'], 500);
            }

            $xml = simplexml_load_string($xmlString);
            if ($xml === false) {
                Log::error('Invalid XML response from TIRA');
                return redirect()
                    ->route('claim.rejection', ['id' => $rejection->claim_notification_id])
                    ->with('error', 'Invalid XML response received from TIRA.');
                return response()->json(['error' => 'Invalid XML response'], 500);
            }

            $ack = $xml->ClaimRejectionReqAck ?? null;

            if ($ack) {
                $acknowledgementId = (string)$ack->AcknowledgementId;
                $requestId = (string)$ack->RequestId;
                $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                // Update quotation
                $rejection->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $rejection->claim_notification_id);
                return redirect()
                    ->route('claim.rejection', ['id' => $rejection->claim_notification_id])
                    ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
                return redirect()
                    ->route('claim.rejection', ['id' => $rejection->claim_notification_id])
                    ->with('warning', 'Acknowledgment not found in TIRA response.');
            }

            return response()->json([
                'success' => true,
                'message' => 'Claim Rejection Request Sent Successfully.',
                'response' => $res,
                'generated_xml' => $xmlData,
            ]);
        } catch (\Exception $e) {
            Log::error('Claim Rejection Submission Error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }
}
