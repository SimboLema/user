<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ClaimController extends Controller
{

    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'http://192.168.168.200';
    }
    public function claimNotificationSubmission(Request $request)
    {
        Log::info("=== Claim Notification Submission Request Received ===");
        Log::info("Received Claim Notification Request: ", $request->all());

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
                'ClaimNotificationNumber' => $request->claim_notification_number,
                'CoverNoteReferenceNumber' => $request->cover_note_reference_number,
                'ClaimReportDate' => returnTiraDate($request->claim_report_date),
                'ClaimFormDullyFilled' => strtoupper($request->claim_form_dully_filled ?? 'Y'),
                'LossDate' => returnTiraDate($request->loss_date),
                'LossNature' => $request->loss_nature,
                'LossType' => $request->loss_type,
                'LossLocation' => $request->loss_location,
                'OfficerName' => $request->officer_name,
                'OfficerTitle' => $request->officer_title,
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

    public function claimIntimationSubmission(Request $request)
    {
        Log::info("=== Claim Intimation Submission Request Received ===");
        Log::info("Payload:", $request->all());

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
                'ClaimIntimationNumber' => $request->claim_intimation_number,
                'ClaimReferenceNumber' => $request->claim_reference_number,
                'CoverNoteReferenceNumber' => $request->cover_note_reference_number,
                // format to TIRA datetime if you have helper, else send as-is (YYYY-MM-DDTHH:MM:SS)
                'ClaimIntimationDate' => returnTiraDate($request->claim_intimation_date),
                'CurrencyCode' => $request->currency_code,
                'ExchangeRate' => number_format($request->exchange_rate, 2, '.', ''),
                'ClaimEstimatedAmount' => number_format($request->claim_estimated_amount, 2, '.', ''),
                'ClaimReserveAmount' => number_format($request->claim_reserve_amount, 2, '.', ''),
                'ClaimReserveMethod' => $request->claim_reserve_method,
                'LossAssessmentOption' => $request->loss_assessment_option,
                'AssessorName' => $request->assessor_name,
                'AssessorIdNumber' => $request->assessor_id_number,
                'AssessorIdType' => $request->assessor_id_type,
            ];

            // ================= CLAIMANTS =================
            // TIRA expects <Claimants><Claimant>...</Claimant></Claimants>
            $claimants = [];
            foreach ($request->input('claimants', []) as $c) {
                $claimants[] = [
                    'ClaimantName' => $c['claimant_name'] ?? '',
                    // optional fields — format dates if helper exists
                    'ClaimantBirthDate' => returnTiraDate($c['claimant_birth_date']),
                    'ClaimantCategory' => $c['claimant_category'] ?? '',
                    'ClaimantType' => $c['claimant_type'] ?? '',
                    'ClaimantIdNumber' => $c['claimant_id_number'] ?? '',
                    'ClaimantIdType' => $c['claimant_id_type'] ?? '',
                    'Gender' => $c['gender'] ?? '',
                    'CountryCode' => $c['country_code'] ?? '',
                    'Region' => $c['region'] ?? '',
                    'District' => $c['district'] ?? '',
                    'Street' => $c['street'] ?? '',
                    'ClaimantPhoneNumber' => $c['claimant_phone_number'] ?? '',
                    'ClaimantFax' => $c['claimant_fax'] ?? '',
                    'PostalAddress' => $c['postal_address'] ?? '',
                    'EmailAddress' => $c['email_address'] ?? '',
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

    public function claimAssessmentSubmission(Request $request)
    {
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
            foreach ($request->input('claimants', []) as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c['claimant_category'] ?? 2,
                    'ClaimantType' => $c['claimant_type'] ?? 1,
                    'ClaimantIdNumber' => $c['claimant_id_number'] ?? '00000000',
                    'ClaimantIdType' => $c['claimant_id_type'] ?? 1,
                ];
            }
            $details = [
                'ClaimAssessmentNumber' => $request['claim_assessment_number'],
                'ClaimIntimationNumber' => $request['claim_intimation_number'],
                'ClaimReferenceNumber' => $request['claim_reference_number'],
                'CoverNoteReferenceNumber' => $request['cover_note_reference_number'],
                'AssessmentReceivedDate' => returnTiraDate($request['assessment_received_date']),
                'AssessmentReportSummary' => $request['assessment_report_summary'],
                'CurrencyCode' => $request['currency_code'],
                'ExchangeRate' => $request['exchange_rate'],
                'AssessmentAmount' => $request['assessment_amount'],
                'ApprovedClaimAmount' => $request['approved_claim_amount'],
                'ClaimApprovalDate' => returnTiraDate($request['claim_approval_date']),
                'ClaimApprovalAuthority' => $request['claim_approval_authority'],
                'IsReAssessment' => $request['is_re_assessment'],
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
            $response = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-assessment/v1/request', $xmlData);

            return response()->json([
                'success' => true,
                'message' => 'Claim Assessment Request Sent Successfully.',
                'response' => $response,
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


    public function claimDischargeVoucherSubmission(Request $request)
    {
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
            foreach ($request->input('claimants', []) as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c['claimant_category'] ?? 2,
                    'ClaimantType' => $c['claimant_type'] ?? 1,
                    'ClaimantIdNumber' => $c['claimant_id_number'] ?? '00000000',
                    'ClaimantIdType' => $c['claimant_id_type'] ?? 1,
                ];
            }

            // Details
            $details = [
                'DischargeVoucherNumber' => $request['discharge_voucher_number'],
                'ClaimAssessmentNumber' => $request['claim_assessment_number'],
                'ClaimReferenceNumber' => $request['claim_reference_number'],
                'CoverNoteReferenceNumber' => $request['cover_note_reference_number'],
                'DischargeVoucherDate' => returnTiraDate($request['discharge_voucher_date']),
                'CurrencyCode' => $request['currency_code'],
                'ExchangeRate' => $request['exchange_rate'],
                'ClaimOfferCommunicationDate' => returnTiraDate($request['claim_offer_communication_date']),
                'ClaimOfferAmount' => $request['claim_offer_amount'],
                'ClaimantResponseDate' => returnTiraDate($request['claimant_response_date']),
                'AdjustmentDate' => returnTiraDate($request['adjustment_date']),
                'AdjustmentReason' => $request['adjustment_reason'],
                'AdjustmentAmount' => $request['adjustment_amount'],
                'ReconciliationDate' => returnTiraDate($request['reconciliation_date']),
                'ReconciliationSummary' => $request['reconciliation_summary'],
                'ReconciledAmount' => $request['reconciled_amount'],
                'OfferAccepted' => $request['offer_accepted'],
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
            $response = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-dischargevoucher/v1/request', $xmlData);

            return response()->json([
                'success' => true,
                'message' => 'Claim Discharge Voucher Request Sent Successfully.',
                'response' => $response,
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

    public function claimPaymentSubmission(Request $request)
    {
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
            foreach ($request->input('claimants', []) as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c['claimant_category'] ?? 2,
                    'ClaimantType' => $c['claimant_type'] ?? 1,
                    'ClaimantIdNumber' => $c['claimant_id_number'] ?? '00000000',
                    'ClaimantIdType' => $c['claimant_id_type'] ?? 1,
                ];
            }


            // Details
            $details = [
                'ClaimPaymentNumber' => $request->claim_payment_number,
                'ClaimReferenceNumber' => $request->claim_reference_number,
                'ClaimIntimationNumber' => $request->claim_intimation_number,
                'CoverNoteReferenceNumber' => $request->cover_note_reference_number,
                'PaymentDate' => returnTiraDate($request->payment_date) ?? now()->format('Y-m-d\TH:i:s'),
                'PaidAmount' => $request->paid_amount,
                'PaymentMode' => $request->payment_mode,
                'PartiesNotified' => $request->parties_notified,
                'NetPremiumEarned' => $request->net_premium_earned,
                'ClaimResultedLitigation' => $request->claim_resulted_litigation,
                'LitigationReason' => $request->litigation_reason,
                'CurrencyCode' => $request->currency_code,
                'ExchangeRate' => $request->exchange_rate,
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
            $response = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-payment/v1/request', $xmlData);

            return response()->json([
                'success' => true,
                'message' => 'Claim Payment Request Sent Successfully.',
                'response' => $response,
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


    public function claimRejectionSubmission(Request $request)
    {
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
            foreach ($request->input('claimants', []) as $c) {
                $claimantsArray[] = [
                    'ClaimantCategory' => $c['claimant_category'] ?? 2,
                    'ClaimantType' => $c['claimant_type'] ?? 1,
                    'ClaimantIdNumber' => $c['claimant_id_number'] ?? '00000000',
                    'ClaimantIdType' => $c['claimant_id_type'] ?? 1,
                ];
            }

            // ================= DETAILS =================
            $details = [
                'ClaimRejectionNumber' => $request->claim_rejection_number,
                'ClaimReferenceNumber' => $request->claim_reference_number,
                'ClaimIntimationNumber' => $request->claim_intimation_number,
                'CoverNoteReferenceNumber' => $request->cover_note_reference_number,
                'RejectionDate' => returnTiraDate($request->rejection_date) ?? now()->format('Y-m-d\TH:i:s'),
                'RejectionReason' => $request->rejection_reason,
                'ClaimResultedLitigation' => $request->claim_resulted_litigation,
                'ClaimAmount' => $request->claim_amount,
                'CurrencyCode' => $request->currency_code,
                'ExchangeRate' => $request->exchange_rate,
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
            $response = TiraRequest($this->baseUrl . '/eclaim/api/claim/claim-rejection/v1/request', $xmlData);

            return response()->json([
                'success' => true,
                'message' => 'Claim Rejection Request Sent Successfully.',
                'response' => $response,
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
