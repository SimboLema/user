<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\ClaimAssessment;
use App\Models\Models\KMJ\ClaimDischargeVoucher;
use App\Models\Models\KMJ\ClaimIntimation;
use App\Models\Models\KMJ\ClaimNotification;
use App\Models\Models\KMJ\ClaimPayment;
use App\Models\Models\KMJ\ClaimRejection;
use App\Models\Models\KMJ\PolicySubmission;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\QuotationEndorsement;
use App\Models\Models\KMJ\QuotationFleetDetail;
use App\Models\Models\KMJ\Reinsurance;
use App\Models\Models\KMJ\TiraCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TiraCallbackController extends Controller
{
    public function tiraCallbackHandler(Request $request)
    {
        Log::info("=== TIRA Callback Received ===");

        try {
            $xmlString = $request->getContent();
            Log::info("Raw XML Content:\n" . $xmlString);

            $callback = new TiraCallback();
            $callback->raw_data = $xmlString;
            $callback->save();
            Log::info("Callback saved successfully with ID: " . $callback->id);

            // Parse XML
            $xml = simplexml_load_string($xmlString);
            if (!$xml) {
                Log::error("Invalid XML received in callback");
                return response()->json(['status' => 'error', 'message' => 'Invalid XML'], 400);
            }


            // CoverNoteRefRes
            if (isset($xml->CoverNoteRefRes)) {
                $res = $xml->CoverNoteRefRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $coverNoteRef = $res->CoverNoteReferenceNumber ?? null;
                // $stickerNumber = (string) ($res->StickerNumber ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);
                $msgSignature = (string) ($xml->MsgSignature ?? null);

                $quotation = Quotation::where('request_id', $requestId)->first();
                $quotationEndorsement = QuotationEndorsement::where('request_id', $requestId)->first();


                if ($quotation) {
                    // Update quotation na data mpya
                    $updateData = [
                        'response_id' => $responseId,
                        //                        'cover_note_reference' => $coverNoteRef,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    // ✅ condition 1: weka prev_cover_note_reference_number kama sio cover_note_type_id == 3
                    if ($quotation->cover_note_type_id == 1 || $statusCode === 'TIRA001') {
                        $updateData['prev_cover_note_reference_number'] = $coverNoteRef;
                    }

                    // ✅ condition 2: update status TU kama endorsement_type_id == 4 AU statusCode == 'TIRA001'
                    if ($statusCode === 'TIRA214') {
                        $updateData['status'] = 'cancelled';
                    } elseif ($statusCode === 'TIRA001' || $statusCode === 'TIRA233') {
                        $updateData['status'] = 'success';
                        $updateData['cover_note_reference'] = $coverNoteRef ?? $quotation->cover_note_reference;
                        $updateData['prev_cover_note_reference_number'] = $coverNoteRef ?? $quotation->prev_cover_note_reference_number;
                    } else {
                        $updateData['status'] = 'pending';
                    }

                    $quotation->update($updateData);

                    if ($quotation->cover_note_type_id == 3) {
                        $quotationEndorsement->status =
                            $statusCode === 'TIRA001' ? 'success' : ($statusCode === 'TIRA214' ? 'cancelled' : $quotationEndorsement->status);

                        $quotationEndorsement->cover_note_reference = $coverNoteRef ?? $quotation->cover_note_reference;
                        $quotationEndorsement->response_id = $responseId;
                        $quotationEndorsement->response_status_code = $statusCode;
                        $quotationEndorsement->response_status_desc = $statusDesc;
                        $quotationEndorsement->save();
                    }


                    Log::info("CoverNoteRefRes updated successfully with ID: " . $quotation->id);
                } else {
                    Log::warning("No CoverNoteRefRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('CoverNoteRefResAck', $ackData);

                Log::info("GEN DATA: " .$gen_data);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }

            // MotorCoverNoteRefRes
            if (isset($xml->MotorCoverNoteRefRes)) {
                $res = $xml->MotorCoverNoteRefRes;

                // 🟢 Check kama ni fleet response au la
                if (isset($res->FleetResHdr)) {
                    Log::info('Processing Fleet MotorCoverNoteRefRes...');

                    $fleetHdr = $res->FleetResHdr;
                    $responseId = (string)($fleetHdr->ResponseId ?? null);
                    $requestId = (string)($fleetHdr->RequestId ?? null);
                    $fleetId = (string)($fleetHdr->FleetId ?? null);
                    $fleetStatusCode = (string)($fleetHdr->FleetStatusCode ?? null);
                    $fleetStatusDesc = (string)($fleetHdr->FleetStatusDesc ?? null);


                    $quotation = Quotation::where('request_id', $requestId)->first();
                    $quotationEndorsement = QuotationEndorsement::where('request_id', $requestId)->first();


                    if ($quotationEndorsement) {

                        $fleetDtl1 = $res->FleetResDtl;
                        $fleetEntry1 = $fleetDtl1->FleetEntry ?? null;
                        $coverNoteNumber1 = $fleetDtl1->CoverNoteNumber ?? null;
                        $responseStatusCode = (string)($fleetDtl1->ResponseStatusCode ?? $fleetStatusCode);
                        $responseStatusDesc = (string)($fleetDtl1->ResponseStatusDesc ?? $fleetStatusDesc);

                        $CoverNoteReferenceNumber = $fleetDtl1->CoverNoteReferenceNumber ?? null;
                        $StickerNumber = $fleetDtl1->StickerNumber ?? null;


                        $quotationFleetDetails = null;
                        if ($quotationEndorsement->quotation_fleet_detail_id) {
                            $quotationFleetDetails = QuotationFleetDetail::find($quotationEndorsement->quotation_fleet_detail_id);
                        }

                        $quotationEndorsement->status =
                            $responseStatusCode === 'TIRA001' ? 'success' : ($responseStatusCode === 'TIRA214' ? 'cancelled' : $quotationEndorsement->status);
                        // $quotationEndorsement->sticker_number = $stickerNumber;
                        // $quotationEndorsement->cover_note_reference = $coverNoteRef;
                        $quotationEndorsement->response_id = $responseId;
                        $quotationEndorsement->response_status_code = $responseStatusCode;
                        $quotationEndorsement->response_status_desc = $responseStatusDesc;
                        $quotationEndorsement->save();

                        if ($quotationFleetDetails) {
                            $quotationFleetDetails->update([
                                'response_id' => $responseId,
                                'response_status_code' => $responseStatusCode,
                                'response_status_desc' => $responseStatusDesc,
                                'status' => $responseStatusCode === 'TIRA001' ? 'success' : ($responseStatusCode === 'TIRA214' ? 'cancelled' : 'pending'),
                                'cover_note_reference_number' => $CoverNoteReferenceNumber ?? $quotationFleetDetails->cover_note_reference_number,
                                'prev_cover_note_reference_number' => $CoverNoteReferenceNumber ?? $quotationFleetDetails->prev_cover_note_reference_number,
                                'sticker_number' => $StickerNumber ?? $quotationFleetDetails->sticker_number,
                            ]);
                        }
                    }

                    if ($quotation) {
                        // Update main quotation fleet header info
                        $quotation->update([
                            'response_id' => $responseId,
                            'response_status_code' => $fleetStatusCode,
                            'response_status_desc' => $fleetStatusDesc,
                            'status' => $fleetStatusCode === 'TIRA001' ? 'success' : 'pending',
                        ]);

                        // 🟢 Now loop through all <FleetResDtl> elements
                        foreach ($res->FleetResDtl as $fleetDtl) {
                            $fleetEntry = (string)($fleetDtl->FleetEntry ?? '');
                            $coverNoteNumber = $fleetDtl->CoverNoteNumber ?? null;
                            $coverNoteRef = $fleetDtl->CoverNoteReferenceNumber ?? null;
                            $stickerNumber = $fleetDtl->StickerNumber ?? null;
                            $statusCode = (string)($fleetDtl->ResponseStatusCode ?? '');
                            $statusDesc = (string)($fleetDtl->ResponseStatusDesc ?? '');

                            // Tafuta fleet detail record
                            $fleetDetail = $quotation->quotationFleetDetails()
                                ->where('fleet_entry', $fleetEntry)
                                ->first();

                            if ($fleetDetail) {

                                if ($quotation->cover_note_type_id == 1 || $statusCode === 'TIRA001') {
                                    $fleetDetail['prev_cover_note_reference_number'] = $coverNoteRef;
                                }

                                $updateData = [
                                    'request_id' => $requestId,
                                    'response_id' => $responseId,
                                    'response_status_code' => $statusCode,
                                    'response_status_desc' => $statusDesc,
                                ];

                                // $fleetDetail->update([
                                //     'cover_note_number' => $coverNoteNumber,
                                //     'cover_note_reference_number' => $coverNoteRef,
                                //     'sticker_number' => $stickerNumber,
                                //     'response_status_code' => $statusCode,
                                //     'response_status_desc' => $statusDesc,
                                //     'status' => $statusCode === 'TIRA001' ? 'success' : 'failure',
                                // ]);

                                if ($statusCode === 'TIRA214') {
                                    $updateData['status'] = 'cancelled';
                                } elseif ($statusCode === 'TIRA001' || $statusCode === 'TIRA233') {
                                    $updateData['status'] = 'success';
                                    $updateData['sticker_number'] = $stickerNumber ?? $fleetDetail->sticker_number;
                                    $updateData['cover_note_reference_number'] = $coverNoteRef ?? $fleetDetail->cover_note_reference_number;
                                    $updateData['prev_cover_note_reference_number'] = $coverNoteRef ?? $fleetDetail->prev_cover_note_reference_number;
                                    $updateData['cover_note_number'] = $coverNoteNumber ?? $fleetDetail->cover_note_number;
                                } else {
                                    $updateData['status'] = 'pending';
                                }
                                $fleetDetail->update($updateData);



                                Log::info("Updated Fleet Entry #{$fleetEntry} successfully for Quotation ID {$quotation->id}");
                            } else {
                                Log::warning("Fleet entry {$fleetEntry} not found for Quotation ID {$quotation->id}");
                            }
                        }
                    } else {
                        Log::warning("No quotation found for Fleet RequestId: " . $requestId);
                    }


                    // 🟢 Acknowledgement data
                    $ackData = [
                        'AcknowledgementId' => $requestId ?? '',
                        'ResponseId' => $responseId ?? '',
                        'AcknowledgementStatusCode' => $fleetStatusCode ?? '',
                        'AcknowledgementStatusDesc' => $fleetStatusDesc ?? '',
                    ];

                    $gen_data = generateXML('MotorCoverNoteRefResAck', $ackData);
                    $gen_data = TiraRequestCallBack($gen_data)['xml'];

                    return response($gen_data, 200)->header('Content-Type', 'application/xml');
                }

                // 🟡 Else — Normal (non-fleet) CoverNote response
                else {

                    $responseId = (string)($res->ResponseId ?? null);
                    $requestId = (string)($res->RequestId ?? null);
                    $coverNoteRef = $res->CoverNoteReferenceNumber ?? null;
                    $stickerNumber = $res->StickerNumber ?? null;
                    $statusCode = (string)($res->ResponseStatusCode ?? null);
                    $statusDesc = (string)($res->ResponseStatusDesc ?? null);
                    // $msgSignature = (string) ($xml->MsgSignature ?? null);

                    $quotation = Quotation::where('request_id', $requestId)->first();
                    $quotationEndorsement = QuotationEndorsement::where('request_id', $requestId)->first();


                    if ($quotation) {
                        // Update quotation na data mpya

                        $updateData = [
                            'response_id' => $responseId,
                            'response_status_code' => $statusCode,
                            'response_status_desc' => $statusDesc,
                        ];

                        // ✅ condition 1: weka prev_cover_note_reference_number kama sio cover_note_type_id == 3
                        if ($quotation->cover_note_type_id == 1 || $statusCode === 'TIRA001') {
                            $updateData['prev_cover_note_reference_number'] = $coverNoteRef;
                        }

                        // ✅ condition 2: update status TU kama endorsement_type_id == 4 AU statusCode == 'TIRA001'
                        if ($statusCode === 'TIRA214') {
                            $updateData['status'] = 'cancelled';
                        } elseif ($statusCode === 'TIRA001' || $statusCode === 'TIRA233') {
                            $updateData['status'] = 'success';
                            $updateData['sticker_number'] = $stickerNumber ?? $quotation->sticker_number;
                            $updateData['cover_note_reference'] = $coverNoteRef ?? $quotation->cover_note_reference;
                            $updateData['prev_cover_note_reference_number'] = $coverNoteRef ?? $quotation->prev_cover_note_reference_number;
                        } else {
                            $updateData['status'] = 'pending';
                        }
                        $quotation->update($updateData);

                        if ($quotation->cover_note_type_id == 3) {
                            $quotationEndorsement->status =
                                $statusCode === 'TIRA001' ? 'success' : ($statusCode === 'TIRA214' ? 'cancelled' : $quotationEndorsement->status);
                            $quotationEndorsement->sticker_number = $stickerNumber ?? $quotation->sticker_number;
                            $quotationEndorsement->cover_note_reference = $coverNoteRef ?? $quotation->cover_note_reference;
                            $quotationEndorsement->response_id = $responseId;
                            $quotationEndorsement->response_status_code = $statusCode;
                            $quotationEndorsement->response_status_desc = $statusDesc;
                            $quotationEndorsement->save();
                        }


                        Log::info("MotorCoverNoteRefRes updated successfully with ID: " . $quotation->id);
                    } else {
                        Log::warning("No MotorCoverNoteRefRes found with request_id: " . $requestId);
                    }

                    $ackData = [
                        'AcknowledgementId' => $requestId ?? '',
                        'ResponseId' => $responseId ?? '',
                        'AcknowledgementStatusCode' => $statusCode ?? '',
                        'AcknowledgementStatusDesc' => $statusDesc ?? '',
                    ];

                    // ✅ Generate XML ukitumia helper yako generateXML()
                    $gen_data = generateXML('MotorCoverNoteRefResAck', $ackData);

                    $gen_data = TiraRequestCallBack($gen_data)['xml'];

                    // ✅ Jibu XML (Response)
                    return response($gen_data, 200)
                        ->header('Content-Type', 'application/xml');
                }
            }

            // ReinsuranceRes
            if (isset($xml->ReinsuranceRes)) {
                $res = $xml->ReinsuranceRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);
                // $msgSignature = (string) ($xml->MsgSignature ?? null);

                $reinsurance = Reinsurance::where('request_id', $requestId)->first();


                if ($reinsurance) {
                    // Update quotation na data mpya

                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $updateData['status'] = 'success';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $reinsurance->update($updateData);

                    Log::info("ReinsuranceRes updated successfully with ID: " . $reinsurance->id);
                } else {
                    Log::warning("No ReinsuranceRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('ReinsuranceResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }

            // ClaimNotificationRefRes
            if (isset($xml->ClaimNotificationRefRes)) {
                $res = $xml->ClaimNotificationRefRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);
                $claimReferenceNumber = (string) ($res->ClaimReferenceNumber ?? null);

                $claim = ClaimNotification::where('request_id', $requestId)->first();


                if ($claim) {
                    // Update quotation na data mpya

                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                        'claim_reference_number' => $claimReferenceNumber,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $updateData['claim_notification_status'] = 'success';
                        $updateData['status'] = 'in progress';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $claim->update($updateData);

                    Log::info("ClaimNotificationRefRes updated successfully with ID: " . $claim->id);
                } else {
                    Log::warning("No ClaimNotificationRefRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('ClaimNotificationRefResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }

            // ClaimIntimationRes
            if (isset($xml->ClaimIntimationRes)) {
                $res = $xml->ClaimIntimationRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);

                $claimInt = ClaimIntimation::where('request_id', $requestId)->first();



                if ($claimInt) {
                    // Update quotation na data mpya
                    $claim = ClaimNotification::where('id', $claimInt->claim_notification_id)->first();


                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $claim->claim_notification_status = 'intimation';
                        $claim->save();

                        $updateData['status'] = 'success';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $claimInt->update($updateData);

                    Log::info("ClaimIntimationRes updated successfully with ID: " . $claimInt->id);
                } else {
                    Log::warning("No ClaimIntimationRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('ClaimIntimationResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }

            // ClaimAssessmentRes
            if (isset($xml->ClaimAssessmentRes)) {
                $res = $xml->ClaimAssessmentRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);

                $claimAss = ClaimAssessment::where('request_id', $requestId)->first();



                if ($claimAss) {
                    // Update quotation na data mpya
                    $claim = ClaimNotification::where('id', $claimAss->claim_notification_id)->first();


                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $claim->claim_notification_status = 'assessment';
                        $claim->save();

                        $updateData['status'] = 'success';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $claimAss->update($updateData);

                    Log::info("ClaimAssessmentRes updated successfully with ID: " . $claimAss->id);
                } else {
                    Log::warning("No ClaimAssessmentRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('ClaimAssessmentResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }

            // DischargeVoucherRes
            if (isset($xml->DischargeVoucherRes)) {
                $res = $xml->DischargeVoucherRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);

                $claimDischargeV = ClaimDischargeVoucher::where('request_id', $requestId)->first();



                if ($claimDischargeV) {
                    // Update quotation na data mpya
                    $claim = ClaimNotification::where('id', $claimDischargeV->claim_notification_id)->first();


                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $claim->claim_notification_status = 'discharge voucher';
                        $claim->save();

                        $updateData['status'] = 'success';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $claimDischargeV->update($updateData);

                    Log::info("DischargeVoucherRes updated successfully with ID: " . $claimDischargeV->id);
                } else {
                    Log::warning("No DischargeVoucherRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('DischargeVoucherResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }

            // ClaimPaymentRes
            if (isset($xml->ClaimPaymentRes)) {
                $res = $xml->ClaimPaymentRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);

                $claimPayment = ClaimPayment::where('request_id', $requestId)->first();



                if ($claimPayment) {
                    // Update quotation na data mpya
                    $claim = ClaimNotification::where('id', $claimPayment->claim_notification_id)->first();


                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $claim->claim_notification_status = 'payment';
                        $claim->save();

                        $updateData['status'] = 'success';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $claimPayment->update($updateData);

                    Log::info("ClaimPaymentRes updated successfully with ID: " . $claimPayment->id);
                } else {
                    Log::warning("No ClaimPaymentRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('ClaimPaymentResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }

            // ClaimRejectionRes
            if (isset($xml->ClaimRejectionRes)) {
                $res = $xml->ClaimRejectionRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);

                $claimRejection = ClaimRejection::where('request_id', $requestId)->first();



                if ($claimRejection) {
                    // Update quotation na data mpya
                    $claim = ClaimNotification::where('id', $claimRejection->claim_notification_id)->first();


                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $claim->claim_notification_status = 'rejection';
                        $claim->save();

                        $updateData['status'] = 'success';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $claimRejection->update($updateData);

                    Log::info("ClaimRejectionRes updated successfully with ID: " . $claimRejection->id);
                } else {
                    Log::warning("No ClaimRejectionRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('ClaimRejectionResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }


            // PolicyRes
            if (isset($xml->PolicyRes)) {
                $res = $xml->PolicyRes;

                $responseId = (string)($res->ResponseId ?? null);
                $requestId = (string)($res->RequestId ?? null);
                $statusCode = (string)($res->ResponseStatusCode ?? null);
                $statusDesc = (string)($res->ResponseStatusDesc ?? null);

                $policySubmission = PolicySubmission::where('request_id', $requestId)->first();



                if ($policySubmission) {

                    $updateData = [
                        'response_id' => $responseId,
                        'response_status_code' => $statusCode,
                        'response_status_desc' => $statusDesc,
                    ];

                    if ($statusCode === 'TIRA001') {
                        $updateData['status'] = 'success';
                    } else {
                        $updateData['status'] = 'pending';
                    }
                    $policySubmission->update($updateData);

                    Log::info("PolicyRes updated successfully with ID: " . $policySubmission->id);
                } else {
                    Log::warning("No PolicyRes found with request_id: " . $requestId);
                }

                $ackData = [
                    'AcknowledgementId' => $requestId ?? '',
                    'ResponseId' => $responseId ?? '',
                    'AcknowledgementStatusCode' => $statusCode ?? '',
                    'AcknowledgementStatusDesc' => $statusDesc ?? '',
                ];

                $gen_data = generateXML('PolicyResAck', $ackData);

                $gen_data = TiraRequestCallBack($gen_data)['xml'];

                // ✅ Jibu XML (Response)
                return response($gen_data, 200)
                    ->header('Content-Type', 'application/xml');
            }


            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("Error occurred: " . $e->getMessage());
            Log::error("Stack trace:\n" . $e->getTraceAsString());

            return response()->json(['status' => 'error', 'message' => 'Callback failed'], 500);
        }
    }
}
