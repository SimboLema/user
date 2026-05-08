<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\QuotationEndorsement;
use App\Models\Models\KMJ\QuotationFleetDetail;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendTiraController extends Controller
{
    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'https://api.tira.go.tz:8091';
    }


    public function sendToTira($id)
    {
        try {
            $quotation = Quotation::find($id);
            $requestId =  generateRequestID();
            $quotation->update([

                'request_id' => $requestId,

            ]);

            date_default_timezone_set('Africa/Dar_es_Salaam');

            $coverNoteStart = new DateTime($quotation->cover_note_start_date);
            $coverNoteStart->setTime((int)date('H'), (int)date('i') + 5, (int)date('s')); // sasa + 5 min

            $coverNoteEnd = new DateTime($quotation->cover_note_end_date);
            $coverNoteEnd->setTime((int)date('H'), (int)date('i') + 5, (int)date('s')); // sasa + 5 min

            if ($quotation->coverage->product->insurance_id === 2 && empty($quotation->fleet_id)) {

                try {

                    $data = [
                        'CoverNoteHdr' => [
                            'RequestId' => $requestId,
                            'CompanyCode' => 'IB10152',
                            'SystemCode' => 'TP_KMJ_001',
                            'CallBackUrl' => "https://102.218.224.4/api/tiramis/callback",
                            'InsurerCompanyCode' => $quotation->insuarer->Insuarer_code,
                            'TranCompanyCode' => 'IB10152',
                            'CoverNoteType' => $quotation->cover_note_type_id,
                        ],
                        'CoverNoteDtl' => [
                            'CoverNoteNumber' => otherUniqueID(),
                            'PrevCoverNoteReferenceNumber' => $quotation->prev_cover_note_reference_number,
                            'SalePointCode' => $quotation->sale_point_code,
                            'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                            'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
                            'CoverNoteDesc' => $quotation->cover_note_desc ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'OperativeClause' => $quotation->operative_clause ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'PaymentMode' => $quotation->payment_mode_id,
                            'CurrencyCode' => $quotation->currency->code,
                            'ExchangeRate' => $quotation->exchange_rate,
                            'TotalPremiumExcludingTax' => $quotation->total_premium_excluding_tax,
                            'TotalPremiumIncludingTax' => $quotation->total_premium_including_tax,
                            // Keep tag names exactly as in sample (CommisionPaid/CommisionRate)
                            'CommisionPaid' => $quotation->commission_paid,
                            'CommisionRate' => $quotation->commission_rate,
                            'OfficerName' => optional($quotation->createdBy)->name ?? 'Jackson Makusu',
                            'OfficerTitle' => optional($quotation->createdBy)->title ?? 'Sales Officer',
                            'ProductCode' => $quotation->coverage->product->code,
                            'EndorsementType' => $quotation->endorsement_type_id,
                            'EndorsementReason' => $quotation->endorsement_reason ?? '',
                            'EndorsementPremiumEarned' => $quotation->endorsement_premium_earned ?? '',
                            'RisksCovered' => [
                                'RiskCovered' => [
                                    [
                                        'RiskCode' => $quotation->coverage->risk_code,
                                        'SumInsured' => $quotation->sum_insured,
                                        'SumInsuredEquivalent' => $quotation->sum_insured,
                                        'PremiumRate' => $quotation->premium_rate,
                                        'PremiumBeforeDiscount' => $quotation->premium_before_discount,
                                        'PremiumAfterDiscount' => $quotation->premium_after_discount,
                                        'PremiumExcludingTaxEquivalent' => $quotation->premium_excluding_tax_equivalent,
                                        'PremiumIncludingTax' => $quotation->premium_including_tax,
                                        // DiscountsOffered tag must be present; populate if provided else keep empty
                                        'DiscountsOffered' => ($quotation->discount_type !== null && $quotation->discount_rate !== null && $quotation->discount_amount !== null)
                                            ? [
                                                'DiscountOffered' => [
                                                    'DiscountType' => $quotation->discount_type,
                                                    'DiscountRate' => $quotation->discount_rate,
                                                    'DiscountAmount' => $quotation->discount_amount,
                                                ]
                                            ]
                                            : '',
                                        'TaxesCharged' => [
                                            'TaxCharged' => [
                                                'TaxCode' => $quotation->tax_code,
                                                'IsTaxExempted' => $quotation->is_tax_exempted,
                                                'TaxExemptionType' => $quotation->tax_exemption_type ?? '',
                                                'TaxExemptionReference' => $quotation->tax_exemption_reference ?? '',
                                                'TaxRate' => $quotation->tax_rate,
                                                'TaxAmount' => $quotation->tax_amount,
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'SubjectMattersCovered' => [
                                'SubjectMatter' => [
                                    'SubjectMatterReference' => $quotation->subject_matter_reference,
                                    'SubjectMatterDesc' => $quotation->subject_matter_description,
                                ],
                            ],
                            'CoverNoteAddons' => [],
                            'PolicyHolders' => [
                                'PolicyHolder' => [
                                    'PolicyHolderName' => $quotation->customer->name,
                                    'PolicyHolderBirthDate' => Carbon::parse($quotation->customer->dob)->format('Y-m-d'),
                                    'PolicyHolderType' => $quotation->customer->policy_holder_type_id,
                                    'PolicyHolderIdNumber' => $quotation->customer->policy_holder_id_number,
                                    'PolicyHolderIdType' => $quotation->customer->policy_holder_id_type_id,
                                    'Gender' => $quotation->customer->gender,
                                    'CountryCode' => $quotation->customer->district->region->country->code,
                                    'Region' => $quotation->customer->district->region->name,
                                    'District' => $quotation->customer->district->name,
                                    'Street' => $quotation->customer->street ?? '',
                                    'PolicyHolderPhoneNumber' => $quotation->customer->phone,
                                    'PolicyHolderFax' => $quotation->customer->fax ?? '',
                                    'PostalAddress' => $quotation->customer->postal_address,
                                    'EmailAddress' => $quotation->customer->email_address ?? '',
                                ],
                            ],
                            'MotorDtl' => [
                                'MotorCategory' => $quotation->motor_category_id,
                                'MotorType' => $quotation->motor_type_id,
                                'RegistrationNumber' => $quotation->registration_number,
                                'ChassisNumber' => $quotation->chassis_number,
                                'Make' => $quotation->make,
                                'Model' => $quotation->model,
                                'ModelNumber' => $quotation->model_number,
                                'BodyType' => $quotation->body_type,
                                'Color' => $quotation->color,
                                'EngineNumber' => $quotation->engine_number,
                                'EngineCapacity' => $quotation->engine_capacity,
                                'FuelUsed' => $quotation->fuel_used,
                                'NumberOfAxles' => $quotation->number_of_axles,
                                'AxleDistance' => $quotation->axle_distance ?? '',
                                'SittingCapacity' => $quotation->sitting_capacity ?? '',
                                'YearOfManufacture' => $quotation->year_of_manufacture,
                                'TareWeight' => $quotation->tare_weight,
                                'GrossWeight' => $quotation->gross_weight,
                                'MotorUsage' => $quotation->motor_usage_id,
                                'OwnerName' => $quotation->customer->name,
                                'OwnerCategory' => $quotation->owner_category_id,
                                'OwnerAddress' => $quotation->customer->email_address ?? '',
                            ],
                        ],
                    ];

                    // Keep empty elements as per sample spec (no cleanup)
                    $gen_data = generateXML('MotorCoverNoteRefReq', $data);

                    // return $gen_data;

                    Log::channel('tiramisxml')->info($gen_data);

                    $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor/v2/request', $gen_data);

                    $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

                    if (empty($xmlString)) {
                        Log::error('Empty XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                            ->with('error', 'Empty response from TIRA.');
                        return response()->json(['error' => 'Empty response from TIRA'], 500);
                    }

                    $xml = simplexml_load_string($xmlString);
                    if ($xml === false) {
                        Log::error('Invalid XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                            ->with('error', 'Invalid XML response received from TIRA.');
                        return response()->json(['error' => 'Invalid XML response'], 500);
                    }

                    // Extract data kutoka kwenye XML
                    $ack = $xml->MotorCoverNoteRefReqAck ?? null;
                    // $msgSignature = (string) ($xml->MsgSignature ?? '');

                    if ($ack) {
                        $acknowledgementId = (string)$ack->AcknowledgementId;
                        $requestId = (string)$ack->RequestId;
                        $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                        $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                        // Update quotation
                        if ($ackStatusCode === 'TIRA001') {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            // 📧 SEND EMAIL
                            try {

                                $emails = [
                                    "first@example.com",
                                    "second@example.com"
                                ];

                                Mail::raw(
                                    "TIRA SUCCESS\n\nQuotation ID: {$quotation->id}\nStatus: {$ackStatusDesc}\nRequest ID: {$requestId}",
                                    function ($message) use ($emails) {
                                        $message->to($emails)
                                            ->subject("TIRA Successful Submission");
                                    }
                                );
                            } catch (\Exception $mailError) {
                                Log::error("Mail failed: " . $mailError->getMessage());
                            }


                            return redirect()
                                ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                                ->with('tira_success', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        } else {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // request_id DO NOT update here
                            ]);

                            return redirect()
                                ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                                ->with('tira_failed', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        }

                        Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
                        // return redirect()
                        //     ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                        //     ->with('tira_success', true)
                        //     ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                    } else {
                        Log::warning('TIRA acknowledgment not found in XML response');
                        return redirect()
                            ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('warning', 'Acknowledgment not found in TIRA response.');
                    }



                    return response()->json([
                        'acknowledgement_status_code' => $ackStatusCode,
                        'acknowledgement_status_desc' => $ackStatusDesc,
                        'request_id' => $requestId,
                        'gen_data' => $gen_data,
                        'quotation' => $quotation,
                        'res' => $res
                    ]);
                } catch (\Throwable $e) {
                    Log::channel('tiramisxml')->info($e->getMessage());
                    return redirect()
                        ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                        ->with('tira_failed', true)
                        ->with('warning', 'An error occurred while processing your request.');

                    return response()->json([
                        'error' => 'An error occurred while processing your request.',
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else if (!empty($quotation->fleet_id)) {

                try {


                    $fleetArray = [];

                    $totalPremiumIncludingTax = 0;
                    $totalPremiumExcludingTaxEquivalent = 0;

                    $useFilteredTotals = false;

                    foreach ($quotation->quotationFleetDetails as $fleet) {

                        // Loop quotation addons
                        $coverNoteAddons = [];
                        foreach ($fleet->quotationAddons as $addon) {
                            $taxRate = $addon->tax_rate ?? 0;
                            $taxAmount = $addon->tax_amount ?? 0;

                            $coverNoteAddons[] = [
                                'AddonReference' => $addon->addon_reference,
                                'AddonDesc' => $addon->addon_desc,
                                'AddonAmount' => $addon->addon_amount,
                                'AddonPremiumRate' => $addon->addon_rate,
                                'PremiumExcludingTax' => $addon->premium_excluding_tax,
                                'PremiumExcludingTaxEquivalent' => $addon->premium_excluding_tax_equivalent,
                                'PremiumIncludingTax' => $addon->premium_including_tax,
                                'TaxesCharged' => [
                                    'TaxCharged' => [
                                        [
                                            'TaxCode' => $addon->tax_code,
                                            'IsTaxExempted' => $addon->is_tax_exempted,
                                            'TaxExemptionType' => $addon->tax_exemption_type,
                                            'TaxExemptionReference' => $addon->tax_exemption_reference,
                                            'TaxRate' => $taxRate,
                                            'TaxAmount' => $taxAmount,
                                        ]
                                    ],
                                ],
                            ];
                        }



                        if ($quotation->fleet_type == 2) {

                            if (empty($fleet->cover_note_reference_number) && empty($fleet->sticker_number)) {

                                $totalPremiumIncludingTax += $fleet->premium_including_tax;
                                $totalPremiumExcludingTaxEquivalent += $fleet->premium_excluding_tax_equivalent;
                                $useFilteredTotals = true;

                                $fleet->cover_note_number = otherUniqueID();
                                $fleet->save();

                                $fleetArray[] = [
                                    'FleetEntry' => $fleet->fleet_entry,
                                    'CoverNoteNumber' => otherUniqueID(),
                                    'PrevCoverNoteReferenceNumber' => $fleet->prev_cover_note_reference_number,
                                    'CoverNoteDesc' => $fleet->cover_note_desc ?? "Motor cover note - test",
                                    'OperativeClause' => $fleet->operative_clause ?? "Fire and Allied Perils",
                                    'EndorsementType' => $fleet->endorsement_type,
                                    'EndorsementReason' => $fleet->endorsement_reason,
                                    'EndorsementPremiumEarned' =>  $fleet->endorsement_premium_earned,
                                    'RisksCovered' => [
                                        'RiskCovered' => [
                                            [
                                                'RiskCode' => $fleet->quotation->coverage->risk_code,
                                                'SumInsured' => $fleet->sum_insured,
                                                'SumInsuredEquivalent' => $fleet->sum_insured,
                                                'PremiumRate' => $fleet->premium_rate,
                                                'PremiumBeforeDiscount' => $fleet->premium_before_discount,
                                                'PremiumAfterDiscount' => $fleet->premium_after_discount,
                                                'PremiumExcludingTaxEquivalent' => $fleet->premium_excluding_tax_equivalent,
                                                'PremiumIncludingTax' => $fleet->premium_including_tax,
                                                'DiscountsOffered' => [
                                                    //     'DiscountOffered' => []
                                                ],
                                                // 'DiscountsOffered' => [
                                                //     'DiscountOffered' => [
                                                //         'DiscountType' => $fleet->discount_type,
                                                //         'DiscountRate' => $fleet->discount_rate,
                                                //         'DiscountAmount' => $fleet->discount_amount,
                                                //     ]
                                                // ],
                                                'TaxesCharged' => [
                                                    'TaxCharged' => [
                                                        [
                                                            'TaxCode' => $fleet->tax_code,
                                                            'IsTaxExempted' => $fleet->is_tax_exempted,
                                                            'TaxExemptionType' => $fleet->tax_exemption_type,
                                                            'TaxExemptionReference' => $fleet->tax_exemption_reference,
                                                            'TaxRate' => $fleet->tax_rate,
                                                            'TaxAmount' => $fleet->tax_amount,
                                                        ]
                                                    ],
                                                ],
                                            ],
                                        ],
                                    ],
                                    'SubjectMattersCovered' => [
                                        'SubjectMatter' => [
                                            'SubjectMatterReference' =>  $fleet->subject_matter_reference,
                                            'SubjectMatterDesc' =>  $fleet->subject_matter_desc,
                                        ],
                                    ],
                                    'CoverNoteAddons' => [
                                        // 'CoverNoteAddon' => $coverNoteAddons,
                                    ],
                                    'MotorDtl' => [
                                        'MotorCategory' => $fleet->motor_category,
                                        'MotorType' => $fleet->motor_type,
                                        'RegistrationNumber' => $fleet->registration_number,
                                        'ChassisNumber' => $fleet->chassis_number,
                                        'Make' => $fleet->make,
                                        'Model' => $fleet->model,
                                        'ModelNumber' => $fleet->model_number,
                                        'BodyType' => $fleet->body_type,
                                        'Color' => $fleet->color,
                                        'EngineNumber' => $fleet->engine_number,
                                        'EngineCapacity' => $fleet->engine_capacity,
                                        'FuelUsed' => $fleet->fuel_used,
                                        'NumberOfAxles' => $fleet->number_of_axles,
                                        'AxleDistance' => $fleet->axle_distance,
                                        'SittingCapacity' => $fleet->sitting_capacity,
                                        'YearOfManufacture' => $fleet->year_of_manufacture,
                                        'TareWeight' => $fleet->tare_weight,
                                        'GrossWeight' => $fleet->gross_weight,
                                        'MotorUsage' => $fleet->motor_usage,
                                        'OwnerName' => $fleet->quotation->customer->name ?? $fleet->owner_name,
                                        'OwnerCategory' => $fleet->owner_category,
                                        'OwnerAddress' => $fleet->$quotation->customer->email_address ?? $fleet->owner_address,
                                    ],

                                ];
                            }
                        } else {

                            $fleet->cover_note_number = otherUniqueID();
                            $fleet->save();

                            $fleetArray[] = [
                                'FleetEntry' => $fleet->fleet_entry,
                                'CoverNoteNumber' => otherUniqueID(),
                                'PrevCoverNoteReferenceNumber' => $fleet->prev_cover_note_reference_number,
                                'CoverNoteDesc' => $fleet->cover_note_desc ?? "Motor cover note - test",
                                'OperativeClause' => $fleet->operative_clause ?? "Fire and Allied Perils",
                                'EndorsementType' => $fleet->endorsement_type,
                                'EndorsementReason' => $fleet->endorsement_reason,
                                'EndorsementPremiumEarned' =>  $fleet->endorsement_premium_earned,
                                'RisksCovered' => [
                                    'RiskCovered' => [
                                        [
                                            'RiskCode' => $fleet->quotation->coverage->risk_code,
                                            'SumInsured' => $fleet->sum_insured,
                                            'SumInsuredEquivalent' => $fleet->sum_insured,
                                            'PremiumRate' => $fleet->premium_rate,
                                            'PremiumBeforeDiscount' => $fleet->premium_before_discount,
                                            'PremiumAfterDiscount' => $fleet->premium_after_discount,
                                            'PremiumExcludingTaxEquivalent' => $fleet->premium_excluding_tax_equivalent,
                                            'PremiumIncludingTax' => $fleet->premium_including_tax,
                                            'DiscountsOffered' => [
                                                //     'DiscountOffered' => []
                                            ],
                                            // 'DiscountsOffered' => [
                                            //     'DiscountOffered' => [
                                            //         'DiscountType' => $fleet->discount_type,
                                            //         'DiscountRate' => $fleet->discount_rate,
                                            //         'DiscountAmount' => $fleet->discount_amount,
                                            //     ]
                                            // ],
                                            'TaxesCharged' => [
                                                'TaxCharged' => [
                                                    [
                                                        'TaxCode' => $fleet->tax_code,
                                                        'IsTaxExempted' => $fleet->is_tax_exempted,
                                                        'TaxExemptionType' => $fleet->tax_exemption_type,
                                                        'TaxExemptionReference' => $fleet->tax_exemption_reference,
                                                        'TaxRate' => $fleet->tax_rate,
                                                        'TaxAmount' => $fleet->tax_amount,
                                                    ]
                                                ],
                                            ],
                                        ],
                                    ],
                                ],
                                'SubjectMattersCovered' => [
                                    'SubjectMatter' => [
                                        'SubjectMatterReference' =>  $fleet->subject_matter_reference,
                                        'SubjectMatterDesc' =>  $fleet->subject_matter_desc,
                                    ],
                                ],
                                'CoverNoteAddons' => [
                                    // 'CoverNoteAddon' => $coverNoteAddons,
                                ],
                                'MotorDtl' => [
                                    'MotorCategory' => $fleet->motor_category,
                                    'MotorType' => $fleet->motor_type,
                                    'RegistrationNumber' => $fleet->registration_number,
                                    'ChassisNumber' => $fleet->chassis_number,
                                    'Make' => $fleet->make,
                                    'Model' => $fleet->model,
                                    'ModelNumber' => $fleet->model_number,
                                    'BodyType' => $fleet->body_type,
                                    'Color' => $fleet->color,
                                    'EngineNumber' => $fleet->engine_number,
                                    'EngineCapacity' => $fleet->engine_capacity,
                                    'FuelUsed' => $fleet->fuel_used,
                                    'NumberOfAxles' => $fleet->number_of_axles,
                                    'AxleDistance' => $fleet->axle_distance,
                                    'SittingCapacity' => $fleet->sitting_capacity,
                                    'YearOfManufacture' => $fleet->year_of_manufacture,
                                    'TareWeight' => $fleet->tare_weight,
                                    'GrossWeight' => $fleet->gross_weight,
                                    'MotorUsage' => $fleet->motor_usage,
                                    'OwnerName' => $fleet->quotation->customer->name ?? $fleet->owner_name,
                                    'OwnerCategory' => $fleet->owner_category,
                                    'OwnerAddress' => $fleet->$quotation->customer->email_address ?? $fleet->owner_address,
                                ],
                            ];
                        }
                    }





                    // ONA MATOKEO

                    $data = [
                        'CoverNoteHdr' => [
                            'RequestId' => generateRequestID(),
                            'CompanyCode' => 'IB10152',
                            'SystemCode' => 'TP_KMJ_001',
                            'CallBackUrl' => "https://102.218.224.4/api/tiramis/callback",
                            'InsurerCompanyCode' => 'ICC125',
                            'TranCompanyCode' => 'IB10152',
                            'CoverNoteType' => $quotation->cover_note_type_id,
                        ],
                        'CoverNoteDtl' => [
                            'FleetHdr' => [
                                'FleetId' => $quotation->fleet_id,
                                'FleetType' => $quotation->fleet_type,
                                'FleetSize' => $quotation->fleet_size,
                                'ComprehensiveInsured' => $quotation->comprehensive_insured,
                                'SalePointCode' => $quotation->sale_point_code,
                                'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                                'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
                                'PaymentMode' => $quotation->payment_mode_id,
                                'CurrencyCode' => $quotation->currency->code,
                                'ExchangeRate' => $quotation->exchange_rate,
                                'TotalPremiumExcludingTax' => $useFilteredTotals
                                    ? $totalPremiumExcludingTaxEquivalent
                                    : $quotation->total_premium_excluding_tax,
                                'TotalPremiumIncludingTax' => $useFilteredTotals
                                    ? $totalPremiumIncludingTax
                                    : $quotation->total_premium_including_tax,
                                'CommisionPaid' => $quotation->commission_paid,
                                'CommisionRate' => $quotation->commission_rate,
                                'OfficerName' => optional($quotation->createdBy)->name ?? 'Jackson Makusu',
                                'OfficerTitle' => optional($quotation->createdBy)->title ?? 'Sales Officer',
                                'ProductCode' => $quotation->coverage->product->code,
                                'PolicyHolders' => [
                                    'PolicyHolder' => [
                                        'PolicyHolderName' => $quotation->customer->name,
                                        'PolicyHolderBirthDate' => Carbon::parse($quotation->customer->dob)->format('Y-m-d'),
                                        'PolicyHolderType' =>  $quotation->customer->policy_holder_type_id,
                                        'PolicyHolderIdNumber' => $quotation->customer->policy_holder_id_number,
                                        'PolicyHolderIdType' => $quotation->customer->policy_holder_id_type_id,
                                        'Gender' => $quotation->customer->gender,
                                        'CountryCode' => $quotation->customer->district->region->country->code,
                                        'Region' => $quotation->customer->district->region->name,
                                        'District' => $quotation->customer->district->name,
                                        'Street' => $quotation->customer->street ?? '',
                                        'PolicyHolderPhoneNumber' => $quotation->customer->phone,
                                        'PolicyHolderFax' => $quotation->customer->fax ?? '',
                                        'PostalAddress' => $quotation->customer->postal_address,
                                        'EmailAddress' => $quotation->customer->email_address ?? '',
                                    ],
                                ],
                            ],
                            'FleetDtl' => $fleetArray,

                        ],
                    ];

                    $gen_data = generateXML('MotorCoverNoteRefReq', $data);
                    // return response()->json([
                    //     'gen_data' => $gen_data,
                    // ]);

                    Log::channel('tiramisxml')->info($gen_data);
                    $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor-fleet/v2/request', $gen_data);


                    $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

                    if (empty($xmlString)) {
                        Log::error('Empty XML response from TIRA');
                    }

                    $xml = simplexml_load_string($xmlString);
                    if ($xml === false) {
                        Log::error('Invalid XML response from TIRA');
                    }

                    // Extract data kutoka kwenye XML
                    $ack = $xml->MotorCoverNoteRefReqAck ?? null;
                    // $msgSignature = (string) ($xml->MsgSignature ?? '');

                    if ($ack) {
                        $acknowledgementId = (string)$ack->AcknowledgementId;
                        $requestId = (string)$ack->RequestId;
                        $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                        $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;


                        if ($ackStatusCode === 'TIRA001') {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            // 📧 SEND EMAIL
                            try {

                                $emails = [
                                    "first@example.com",
                                    "second@example.com"
                                ];

                                Mail::raw(
                                    "TIRA SUCCESS\n\nQuotation ID: {$quotation->id}\nStatus: {$ackStatusDesc}\nRequest ID: {$requestId}",
                                    function ($message) use ($emails) {
                                        $message->to($emails)
                                            ->subject("TIRA Successful Submission");
                                    }
                                );
                            } catch (\Exception $mailError) {
                                Log::error("Mail failed: " . $mailError->getMessage());
                            }


                            return redirect()
                                ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                                ->with('tira_success', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        } else {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // request_id DO NOT update here
                            ]);

                            return redirect()
                                ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                                ->with('tira_failed', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        }


                        Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
                    } else {
                        Log::warning('TIRA acknowledgment not found in XML response');
                        return redirect()
                            ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('warning', 'Acknowledgment not found in TIRA response.');
                    }


                    // return redirect()
                    //     ->back()
                    //     ->with('success', 'Acknowledgment found in TIRA response.');


                    return response()->json([
                        'acknowledgement_status_code' => $ackStatusCode,
                        'acknowledgement_status_desc' => $ackStatusDesc,
                        'request_id' => $requestId,
                        'gen_data' => $gen_data,
                        'quotation' => $quotation,
                        'res' => $res
                    ]);
                } catch (\Exception $e) {
                    Log::channel('tiramisxml')->info($e->getMessage());
                    return redirect()
                        ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                        ->with('tira_failed', true)
                        ->with('warning', 'An error occurred while processing your request.');

                    return response()->json([
                        'error' => 'An error occurred while processing your request.',
                        'message' => $e->getMessage()
                    ]);
                }
            } else {

                try {

                    $data = [
                        'CoverNoteHdr' => [
                            'RequestId' => generateRequestID(),
                            'CompanyCode' => 'IB10152',
                            'SystemCode' => 'TP_KMJ_001',
                            'CallBackUrl' => "https://102.218.224.4/api/tiramis/callback",
                            'InsurerCompanyCode' => 'ICC125',
                            'TranCompanyCode' => 'IB10152',
                            'CoverNoteType' => $quotation->cover_note_type_id,
                        ],
                        'CoverNoteDtl' => [
                            'CoverNoteNumber' => otherUniqueID(),
                            'PrevCoverNoteReferenceNumber' => null,
                            'SalePointCode' => $quotation->sale_point_code,
                            'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                            'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
                            'CoverNoteDesc' => $quotation->cover_note_desc ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'OperativeClause' => $quotation->operative_clause ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'PaymentMode' => $quotation->payment_mode_id,
                            'CurrencyCode' => $quotation->currency->code,
                            'ExchangeRate' => $quotation->exchange_rate,
                            'TotalPremiumExcludingTax' => $quotation->total_premium_excluding_tax,
                            'TotalPremiumIncludingTax' => $quotation->total_premium_including_tax,
                            'CommisionPaid' => $quotation->commission_paid,
                            'CommisionRate' => $quotation->commission_rate,
                            'OfficerName' => optional($quotation->createdBy)->name ?? 'Jackson Makusu',
                            'OfficerTitle' => optional($quotation->createdBy)->title ?? 'Sales Officer',
                            'ProductCode' => $quotation->coverage->product->code,
                            'EndorsementType' => null,
                            'EndorsementReason' => null,
                            'EndorsementPremiumEarned' => null,
                            'RisksCovered' => [
                                'RiskCovered' => [
                                    [
                                        'RiskCode' => $quotation->coverage->risk_code,
                                        'SumInsured' => $quotation->sum_insured,
                                        'SumInsuredEquivalent' => $quotation->sum_insured,
                                        'PremiumRate' => $quotation->premium_rate,
                                        'PremiumBeforeDiscount' => $quotation->premium_before_discount,
                                        'PremiumAfterDiscount' => $quotation->premium_after_discount,
                                        'PremiumExcludingTaxEquivalent' => $quotation->premium_excluding_tax_equivalent,
                                        'PremiumIncludingTax' => $quotation->premium_including_tax,
                                        'DiscountsOffered' => [],
                                        'TaxesCharged' => [
                                            'TaxCharged' => [
                                                'TaxCode' => $quotation->tax_code,
                                                'IsTaxExempted' => $quotation->is_tax_exempted,
                                                'TaxExemptionType' => null,
                                                'TaxExemptionReference' => null,
                                                'TaxRate' => $quotation->tax_rate,
                                                'TaxAmount' => $quotation->tax_amount,
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'SubjectMattersCovered' => [
                                'SubjectMatter' => [
                                    'SubjectMatterReference' => $quotation->subject_matter_reference,
                                    'SubjectMatterDesc' => $quotation->subject_matter_description,
                                ],
                            ],
                            'CoverNoteAddons' => [],
                            'PolicyHolders' => [
                                'PolicyHolder' => [
                                    'PolicyHolderName' => $quotation->customer->name,
                                    'PolicyHolderBirthDate' => Carbon::parse($quotation->customer->dob)->format('Y-m-d'),
                                    'PolicyHolderType' => $quotation->customer->policy_holder_type_id,
                                    'PolicyHolderIdNumber' => $quotation->customer->policy_holder_id_number,
                                    'PolicyHolderIdType' => $quotation->customer->policy_holder_id_type_id,
                                    'Gender' => $quotation->customer->gender,
                                    'CountryCode' => $quotation->customer->district->region->country->code,
                                    'Region' => $quotation->customer->district->region->name,
                                    'District' => $quotation->customer->district->name,
                                    'Street' => $quotation->customer->street,
                                    'PolicyHolderPhoneNumber' => $quotation->customer->phone,
                                    'PolicyHolderFax' => $quotation->customer->fax ?? null,
                                    'PostalAddress' => $quotation->customer->postal_address,
                                    'EmailAddress' => $quotation->customer->email_address,
                                ],
                            ],
                        ],
                    ];

                    $gen_data = generateXML('CoverNoteRefReq', $data);

                    Log::channel('tiramisxml')->info($gen_data);
                    $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/other/v2/request', $gen_data);


                    $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

                    if (empty($xmlString)) {
                        Log::error('Empty XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('error', 'Empty response from TIRA.');
                        return response()->json(['error' => 'Empty response from TIRA'], 500);
                    }

                    $xml = simplexml_load_string($xmlString);
                    if ($xml === false) {
                        Log::error('Invalid XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('error', 'Invalid XML response received from TIRA.');
                        return response()->json(['error' => 'Invalid XML response'], 500);
                    }

                    $ack = $xml->CoverNoteRefReqAck ?? null;

                    if ($ack) {
                        $acknowledgementId = (string)$ack->AcknowledgementId;
                        $requestId = (string)$ack->RequestId;
                        $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                        $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                        if ($ackStatusCode === 'TIRA001') {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            // 📧 SEND EMAIL
                            try {

                                $emails = [
                                    "first@example.com",
                                    "second@example.com"
                                ];

                                Mail::raw(
                                    "TIRA SUCCESS\n\nQuotation ID: {$quotation->id}\nStatus: {$ackStatusDesc}\nRequest ID: {$requestId}",
                                    function ($message) use ($emails) {
                                        $message->to($emails)
                                            ->subject("TIRA Successful Submission");
                                    }
                                );
                            } catch (\Exception $mailError) {
                                Log::error("Mail failed: " . $mailError->getMessage());
                            }


                            return redirect()
                                ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                                ->with('tira_success', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        } else {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // request_id DO NOT update here
                            ]);

                            return redirect()
                                ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                                ->with('tira_failed', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        }


                        Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
                        // return redirect()
                        //     ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                        //     ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                    } else {
                        Log::warning('TIRA acknowledgment not found in XML response');
                        return redirect()
                            ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('warning', 'Acknowledgment not found in TIRA response.');
                    }

                    return response()->json([
                        'acknowledgement_status_code' => $ackStatusCode,
                        'acknowledgement_status_desc' => $ackStatusDesc,
                        'request_id' => $requestId,
                        'gen_data' => $gen_data,
                        'res' => $res,
                    ]);
                } catch (\Throwable $e) {
                    Log::channel('tiramisxml')->info($e->getMessage());
                    return redirect()
                        ->route('kmj.quotation.covernote', ['id' => $quotation->id])
                        ->with('tira_failed', true)
                        ->with('error', 'An error occurred while processing your request.');

                    return response()->json([
                        'error' => 'An error occurred while processing your request.',
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            return response()->json($quotation);

            return redirect()->back()->with('success', 'Quotation sent to TIRA successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('tira_failed', true)->with('error', 'Failed to send quotation to TIRA: ' . $e->getMessage());
        }
    }

    public function sendToTiraEndorsements($id)
    {

        try {
            date_default_timezone_set('Africa/Dar_es_Salaam');

            $QuotationEndorsement = QuotationEndorsement::find($id);

            $quotation = Quotation::find($QuotationEndorsement->quotation_id);

            $coverNoteStart = new DateTime($quotation->cover_note_start_date);
            $coverNoteStart->setTime((int)date('H'), (int)date('i') + 5, (int)date('s')); // sasa + 5 min

            $coverNoteEnd = new DateTime($quotation->cover_note_end_date);
            $coverNoteEnd->setTime((int)date('H'), (int)date('i') + 5, (int)date('s')); // sasa + 5 min

            if ($quotation->coverage->product->insurance_id === 2 && (empty($quotation->fleet_id))) {


                try {

                    $data = [
                        'CoverNoteHdr' => [
                            'RequestId' => generateRequestID(),
                            'CompanyCode' => 'IB10152',
                            'SystemCode' => 'TP_KMJ_001',
                            'CallBackUrl' => "https://102.218.224.4/api/tiramis/callback",
                            'InsurerCompanyCode' => 'ICC125',
                            'TranCompanyCode' => 'IB10152',
                            'CoverNoteType' => $quotation->cover_note_type_id,
                        ],
                        'CoverNoteDtl' => [
                            'CoverNoteNumber' => otherUniqueID(),
                            'PrevCoverNoteReferenceNumber' => $quotation->prev_cover_note_reference_number,
                            'SalePointCode' => $quotation->sale_point_code,
                            'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                            'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
                            'CoverNoteDesc' => $quotation->cover_note_desc ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'OperativeClause' => $quotation->operative_clause ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'PaymentMode' => $quotation->payment_mode_id,
                            'CurrencyCode' => $quotation->currency->code,
                            'ExchangeRate' => $quotation->exchange_rate,
                            'TotalPremiumExcludingTax' => $quotation->total_premium_excluding_tax,
                            'TotalPremiumIncludingTax' => $quotation->total_premium_including_tax,
                            // Keep tag names exactly as in sample (CommisionPaid/CommisionRate)
                            'CommisionPaid' => $quotation->commission_paid,
                            'CommisionRate' => $quotation->commission_rate,
                            'OfficerName' => optional($quotation->createdBy)->name ?? 'Jackson Makusu',
                            'OfficerTitle' => optional($quotation->createdBy)->title ?? 'Sales Officer',
                            'ProductCode' => $quotation->coverage->product->code,
                            'EndorsementType' => $quotation->endorsement_type_id,
                            'EndorsementReason' => $quotation->endorsement_reason ?? '',
                            'EndorsementPremiumEarned' => $quotation->endorsement_premium_earned ?? '',
                            'RisksCovered' => [
                                'RiskCovered' => [
                                    [
                                        'RiskCode' => $quotation->coverage->risk_code,
                                        'SumInsured' => $quotation->sum_insured,
                                        'SumInsuredEquivalent' => $quotation->sum_insured,
                                        'PremiumRate' => $quotation->premium_rate,
                                        'PremiumBeforeDiscount' => $quotation->premium_before_discount,
                                        'PremiumAfterDiscount' => $quotation->premium_after_discount,
                                        'PremiumExcludingTaxEquivalent' => $quotation->premium_excluding_tax_equivalent,
                                        'PremiumIncludingTax' => $quotation->premium_including_tax,
                                        // DiscountsOffered tag must be present; populate if provided else keep empty
                                        'DiscountsOffered' => ($quotation->discount_type !== null && $quotation->discount_rate !== null && $quotation->discount_amount !== null)
                                            ? [
                                                'DiscountOffered' => [
                                                    'DiscountType' => $quotation->discount_type,
                                                    'DiscountRate' => $quotation->discount_rate,
                                                    'DiscountAmount' => $quotation->discount_amount,
                                                ]
                                            ]
                                            : '',
                                        'TaxesCharged' => [
                                            'TaxCharged' => [
                                                'TaxCode' => $quotation->tax_code,
                                                'IsTaxExempted' => $quotation->is_tax_exempted,
                                                'TaxExemptionType' => $quotation->tax_exemption_type ?? '',
                                                'TaxExemptionReference' => $quotation->tax_exemption_reference ?? '',
                                                'TaxRate' => $quotation->tax_rate,
                                                'TaxAmount' => $quotation->tax_amount,
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'SubjectMattersCovered' => [
                                'SubjectMatter' => [
                                    'SubjectMatterReference' => $quotation->subject_matter_reference,
                                    'SubjectMatterDesc' => $quotation->subject_matter_description,
                                ],
                            ],
                            'CoverNoteAddons' => [],
                            'PolicyHolders' => [
                                'PolicyHolder' => [
                                    'PolicyHolderName' => $quotation->customer->name,
                                    'PolicyHolderBirthDate' => Carbon::parse($quotation->customer->dob)->format('Y-m-d'),
                                    'PolicyHolderType' => $quotation->customer->policy_holder_type_id,
                                    'PolicyHolderIdNumber' => $quotation->customer->policy_holder_id_number,
                                    'PolicyHolderIdType' => $quotation->customer->policy_holder_id_type_id,
                                    'Gender' => $quotation->customer->gender,
                                    'CountryCode' => $quotation->customer->district->region->country->code,
                                    'Region' => $quotation->customer->district->region->name,
                                    'District' => $quotation->customer->district->name,
                                    'Street' => $quotation->customer->street ?? '',
                                    'PolicyHolderPhoneNumber' => $quotation->customer->phone,
                                    'PolicyHolderFax' => $quotation->customer->fax ?? '',
                                    'PostalAddress' => $quotation->customer->postal_address,
                                    'EmailAddress' => $quotation->customer->email_address ?? '',
                                ],
                            ],
                            'MotorDtl' => [
                                'MotorCategory' => $quotation->motor_category_id,
                                'MotorType' => $quotation->motor_type_id,
                                'RegistrationNumber' => $quotation->registration_number,
                                'ChassisNumber' => $quotation->chassis_number,
                                'Make' => $quotation->make,
                                'Model' => $quotation->model,
                                'ModelNumber' => $quotation->model_number,
                                'BodyType' => $quotation->body_type,
                                'Color' => $quotation->color,
                                'EngineNumber' => $quotation->engine_number,
                                'EngineCapacity' => $quotation->engine_capacity,
                                'FuelUsed' => $quotation->fuel_used,
                                'NumberOfAxles' => $quotation->number_of_axles,
                                'AxleDistance' => $quotation->axle_distance ?? '',
                                'SittingCapacity' => $quotation->sitting_capacity ?? '',
                                'YearOfManufacture' => $quotation->year_of_manufacture,
                                'TareWeight' => $quotation->tare_weight,
                                'GrossWeight' => $quotation->gross_weight,
                                'MotorUsage' => $quotation->motor_usage_id,
                                'OwnerName' => $quotation->customer->name,
                                'OwnerCategory' => $quotation->owner_category_id,
                                'OwnerAddress' => $quotation->customer->email_address ?? '',
                            ],
                        ],
                    ];

                    // Keep empty elements as per sample spec (no cleanup)
                    $gen_data = generateXML('MotorCoverNoteRefReq', $data);

                    // return $gen_data;

                    Log::channel('tiramisxml')->info($gen_data);
                    $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor/v2/request', $gen_data);

                    $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

                    if (empty($xmlString)) {
                        Log::error('Empty XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('error', 'Empty response from TIRA.');
                        return response()->json(['error' => 'Empty response from TIRA'], 500);
                    }

                    $xml = simplexml_load_string($xmlString);
                    if ($xml === false) {
                        Log::error('Invalid XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('error', 'Invalid XML response received from TIRA.');
                        return response()->json(['error' => 'Invalid XML response'], 500);
                    }

                    // Extract data kutoka kwenye XML
                    $ack = $xml->MotorCoverNoteRefReqAck ?? null;
                    // $msgSignature = (string) ($xml->MsgSignature ?? '');

                    if ($ack) {
                        $acknowledgementId = (string)$ack->AcknowledgementId;
                        $requestId = (string)$ack->RequestId;
                        $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                        $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;

                        if ($ackStatusCode === 'TIRA001') {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            // 📧 SEND EMAIL
                            try {

                                $emails = [
                                    "first@example.com",
                                    "second@example.com"
                                ];

                                Mail::raw(
                                    "TIRA SUCCESS\n\nQuotation ID: {$quotation->id}\nStatus: {$ackStatusDesc}\nRequest ID: {$requestId}",
                                    function ($message) use ($emails) {
                                        $message->to($emails)
                                            ->subject("TIRA Successful Submission");
                                    }
                                );
                            } catch (\Exception $mailError) {
                                Log::error("Mail failed: " . $mailError->getMessage());
                            }


                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_success', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        } else {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // request_id DO NOT update here
                            ]);

                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_failed', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        }





                        Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
                        // return redirect()
                        //     ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                        //     ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                    } else {
                        Log::warning('TIRA acknowledgment not found in XML response');
                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('warning', 'Acknowledgment not found in TIRA response.');
                    }



                    return response()->json([
                        'acknowledgement_status_code' => $ackStatusCode,
                        'acknowledgement_status_desc' => $ackStatusDesc,
                        'request_id' => $requestId,
                        'gen_data' => $gen_data,
                        'quotation' => $quotation,
                        'res' => $res
                    ]);
                } catch (\Throwable $e) {
                    Log::channel('tiramisxml')->info($e->getMessage());
                    return redirect()
                        ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                        ->with('tira_failed', true)
                        ->with('error', 'An error occurred while processing your request.');

                    return response()->json([
                        'error' => 'An error occurred while processing your request.',
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            } else if (!empty($quotation->fleet_id) && empty($QuotationEndorsement->quotation_fleet_detail_id)) {
                try {

                    $fleetArray = [];

                    foreach ($quotation->quotationFleetDetails as $fleet) {

                        // Loop quotation addons
                        $coverNoteAddons = [];
                        foreach ($fleet->quotationAddons as $addon) {
                            $taxRate = $addon->tax_rate ?? 0;
                            $taxAmount = $addon->tax_amount ?? 0;

                            $coverNoteAddons[] = [
                                'AddonReference' => $addon->addon_reference,
                                'AddonDesc' => $addon->addon_desc,
                                'AddonAmount' => $addon->addon_amount,
                                'AddonPremiumRate' => $addon->addon_rate,
                                'PremiumExcludingTax' => $addon->premium_excluding_tax,
                                'PremiumExcludingTaxEquivalent' => $addon->premium_excluding_tax_equivalent,
                                'PremiumIncludingTax' => $addon->premium_including_tax,
                                'TaxesCharged' => [
                                    'TaxCharged' => [
                                        [
                                            'TaxCode' => $addon->tax_code,
                                            'IsTaxExempted' => $addon->is_tax_exempted,
                                            'TaxExemptionType' => $addon->tax_exemption_type,
                                            'TaxExemptionReference' => $addon->tax_exemption_reference,
                                            'TaxRate' => $taxRate,
                                            'TaxAmount' => $taxAmount,
                                        ]
                                    ],
                                ],
                            ];
                        }


                        $fleet->cover_note_number = otherUniqueID();
                        $fleet->save();

                        $fleetArray[] = [
                            // 'FleetDtl' => [
                            'FleetEntry' => $fleet->fleet_entry,
                            'CoverNoteNumber' => otherUniqueID(),
                            'PrevCoverNoteReferenceNumber' => $fleet->prev_cover_note_reference_number,
                            'CoverNoteDesc' => $fleet->cover_note_desc ?? "Motor cover note - test",
                            'OperativeClause' => $fleet->operative_clause ?? "Fire and Allied Perils",
                            'EndorsementType' => $fleet->endorsement_type,
                            'EndorsementReason' => $fleet->endorsement_reason,
                            'EndorsementPremiumEarned' =>  $fleet->endorsement_premium_earned,
                            'RisksCovered' => [
                                'RiskCovered' => [
                                    [
                                        'RiskCode' => $fleet->quotation->coverage->risk_code,
                                        'SumInsured' => $fleet->sum_insured,
                                        'SumInsuredEquivalent' => $fleet->sum_insured,
                                        'PremiumRate' => $fleet->premium_rate,
                                        'PremiumBeforeDiscount' => $fleet->premium_before_discount,
                                        'PremiumAfterDiscount' => $fleet->premium_after_discount,
                                        'PremiumExcludingTaxEquivalent' => $fleet->premium_excluding_tax_equivalent,
                                        'PremiumIncludingTax' => $fleet->premium_including_tax,
                                        'DiscountsOffered' => [
                                            //     'DiscountOffered' => []
                                        ],
                                        // 'DiscountsOffered' => [
                                        //     'DiscountOffered' => [
                                        //         'DiscountType' => $fleet->discount_type,
                                        //         'DiscountRate' => $fleet->discount_rate,
                                        //         'DiscountAmount' => $fleet->discount_amount,
                                        //     ]
                                        // ],
                                        'TaxesCharged' => [
                                            'TaxCharged' => [
                                                [
                                                    'TaxCode' => $fleet->tax_code,
                                                    'IsTaxExempted' => $fleet->is_tax_exempted,
                                                    'TaxExemptionType' => $fleet->tax_exemption_type,
                                                    'TaxExemptionReference' => $fleet->tax_exemption_reference,
                                                    'TaxRate' => $fleet->tax_rate,
                                                    'TaxAmount' => $fleet->tax_amount,
                                                ]
                                            ],
                                        ],
                                    ],
                                ],
                            ],
                            'SubjectMattersCovered' => [
                                'SubjectMatter' => [
                                    'SubjectMatterReference' =>  $fleet->subject_matter_reference,
                                    'SubjectMatterDesc' =>  $fleet->subject_matter_desc,
                                ],
                            ],
                            'CoverNoteAddons' => [
                                // 'CoverNoteAddon' => $coverNoteAddons,
                            ],
                            'MotorDtl' => [
                                'MotorCategory' => $fleet->motor_category,
                                'MotorType' => $fleet->motor_type,
                                'RegistrationNumber' => $fleet->registration_number,
                                'ChassisNumber' => $fleet->chassis_number,
                                'Make' => $fleet->make,
                                'Model' => $fleet->model,
                                'ModelNumber' => $fleet->model_number,
                                'BodyType' => $fleet->body_type,
                                'Color' => $fleet->color,
                                'EngineNumber' => $fleet->engine_number,
                                'EngineCapacity' => $fleet->engine_capacity,
                                'FuelUsed' => $fleet->fuel_used,
                                'NumberOfAxles' => $fleet->number_of_axles,
                                'AxleDistance' => $fleet->axle_distance,
                                'SittingCapacity' => $fleet->sitting_capacity,
                                'YearOfManufacture' => $fleet->year_of_manufacture,
                                'TareWeight' => $fleet->tare_weight,
                                'GrossWeight' => $fleet->gross_weight,
                                'MotorUsage' => $fleet->motor_usage,
                                'OwnerName' => $fleet->quotation->customer->name ?? $fleet->owner_name,
                                'OwnerCategory' => $fleet->owner_category,
                                'OwnerAddress' => $fleet->$quotation->customer->email_address ?? $fleet->owner_address,
                            ],
                            // ],
                        ];
                    }





                    // ONA MATOKEO

                    $data = [
                        'CoverNoteHdr' => [
                            'RequestId' => generateRequestID(),
                            'CompanyCode' => 'IB10152',
                            'SystemCode' => 'TP_KMJ_001',
                            'CallBackUrl' => "https://102.218.224.4/api/tiramis/callback",
                            'InsurerCompanyCode' => 'ICC125',
                            'TranCompanyCode' => 'IB10152',
                            'CoverNoteType' => $quotation->cover_note_type_id,
                        ],
                        'CoverNoteDtl' => [
                            'FleetHdr' => [
                                'FleetId' => $quotation->fleet_id,
                                'FleetType' => $quotation->fleet_type,
                                'FleetSize' => $quotation->fleet_size,
                                'ComprehensiveInsured' => $quotation->comprehensive_insured,
                                'SalePointCode' => $quotation->sale_point_code,
                                'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                                'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
                                'PaymentMode' => $quotation->payment_mode_id,
                                'CurrencyCode' => $quotation->currency->code,
                                'ExchangeRate' => $quotation->exchange_rate,
                                'TotalPremiumExcludingTax' => $quotation->total_premium_excluding_tax,
                                'TotalPremiumIncludingTax' => $quotation->total_premium_including_tax,
                                'CommisionPaid' => $quotation->commission_paid,
                                'CommisionRate' => $quotation->commission_rate,
                                'OfficerName' => optional($quotation->createdBy)->name ?? 'Jackson Makusu',
                                'OfficerTitle' => optional($quotation->createdBy)->title ?? 'Sales Officer',
                                'ProductCode' => $quotation->coverage->product->code,
                                'PolicyHolders' => [
                                    'PolicyHolder' => [
                                        'PolicyHolderName' => $quotation->customer->name,
                                        'PolicyHolderBirthDate' => Carbon::parse($quotation->customer->dob)->format('Y-m-d'),
                                        'PolicyHolderType' =>  $quotation->customer->policy_holder_type_id,
                                        'PolicyHolderIdNumber' => $quotation->customer->policy_holder_id_number,
                                        'PolicyHolderIdType' => $quotation->customer->policy_holder_id_type_id,
                                        'Gender' => $quotation->customer->gender,
                                        'CountryCode' => $quotation->customer->district->region->country->code,
                                        'Region' => $quotation->customer->district->region->name,
                                        'District' => $quotation->customer->district->name,
                                        'Street' => $quotation->customer->street ?? '',
                                        'PolicyHolderPhoneNumber' => $quotation->customer->phone,
                                        'PolicyHolderFax' => $quotation->customer->fax ?? '',
                                        'PostalAddress' => $quotation->customer->postal_address,
                                        'EmailAddress' => $quotation->customer->email_address ?? '',
                                    ],
                                ],
                            ],
                            'FleetDtl' => $fleetArray,

                        ],
                    ];

                    $gen_data = generateXML('MotorCoverNoteRefReq', $data);
                    // return response()->json([
                    //     'gen_data' => $gen_data,
                    // ]);

                    Log::channel('tiramisxml')->info($gen_data);
                    $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor-fleet/v2/request', $gen_data);


                    $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

                    if (empty($xmlString)) {
                        Log::error('Empty XML response from TIRA');
                    }

                    $xml = simplexml_load_string($xmlString);
                    if ($xml === false) {
                        Log::error('Invalid XML response from TIRA');
                    }

                    // Extract data kutoka kwenye XML
                    $ack = $xml->MotorCoverNoteRefReqAck ?? null;
                    // $msgSignature = (string) ($xml->MsgSignature ?? '');

                    if ($ack) {
                        $acknowledgementId = (string)$ack->AcknowledgementId;
                        $requestId = (string)$ack->RequestId;
                        $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                        $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;


                        if ($ackStatusCode === 'TIRA001') {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            // 📧 SEND EMAIL
                            try {

                                $emails = [
                                    "first@example.com",
                                    "second@example.com"
                                ];

                                Mail::raw(
                                    "TIRA SUCCESS\n\nQuotation ID: {$quotation->id}\nStatus: {$ackStatusDesc}\nRequest ID: {$requestId}",
                                    function ($message) use ($emails) {
                                        $message->to($emails)
                                            ->subject("TIRA Successful Submission");
                                    }
                                );
                            } catch (\Exception $mailError) {
                                Log::error("Mail failed: " . $mailError->getMessage());
                            }


                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_success', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        } else {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // request_id DO NOT update here
                            ]);

                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_failed', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        }

                        Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
                    } else {
                        Log::warning('TIRA acknowledgment not found in XML response');
                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('warning', 'Acknowledgment not found in TIRA response.');
                    }

                    // return redirect()
                    //     ->back()
                    //     ->with('success', 'Acknowledgment found in TIRA response.');



                    return response()->json([
                        'acknowledgement_status_code' => $ackStatusCode,
                        'acknowledgement_status_desc' => $ackStatusDesc,
                        'request_id' => $requestId,
                        'gen_data' => $gen_data,
                        'quotation' => $quotation,
                        'res' => $res
                    ]);
                } catch (\Exception $e) {
                    Log::channel('tiramisxml')->info($e->getMessage());
                    return redirect()
                        ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                        ->with('tira_failed', true)
                        ->with('error', 'An error occurred while processing your request.');

                    return response()->json([
                        'error' => 'An error occurred while processing your request.',
                        'message' => $e->getMessage()
                    ]);
                }
            } else if (!empty($QuotationEndorsement->quotation_fleet_detail_id)) {
                // Do somsing for fleet detail endorsement
                try {
                    $fleet = QuotationFleetDetail::find($QuotationEndorsement->quotation_fleet_detail_id);

                    $fleetArray = [];


                    // Loop quotation addons
                    $coverNoteAddons = [];
                    foreach ($fleet->quotationAddons as $addon) {
                        $taxRate = $addon->tax_rate ?? 0;
                        $taxAmount = $addon->tax_amount ?? 0;

                        $coverNoteAddons[] = [
                            'AddonReference' => $addon->addon_reference,
                            'AddonDesc' => $addon->addon_desc,
                            'AddonAmount' => $addon->addon_amount,
                            'AddonPremiumRate' => $addon->addon_rate,
                            'PremiumExcludingTax' => $addon->premium_excluding_tax,
                            'PremiumExcludingTaxEquivalent' => $addon->premium_excluding_tax_equivalent,
                            'PremiumIncludingTax' => $addon->premium_including_tax,
                            'TaxesCharged' => [
                                'TaxCharged' => [
                                    [
                                        'TaxCode' => $addon->tax_code,
                                        'IsTaxExempted' => $addon->is_tax_exempted,
                                        'TaxExemptionType' => $addon->tax_exemption_type,
                                        'TaxExemptionReference' => $addon->tax_exemption_reference,
                                        'TaxRate' => $taxRate,
                                        'TaxAmount' => $taxAmount,
                                    ]
                                ],
                            ],
                        ];
                    }

                    $fleet->cover_note_number = otherUniqueID();
                    $fleet->save();

                    $fleetArray[] = [
                        // 'FleetDtl' => [
                        'FleetEntry' => $fleet->fleet_entry,
                        'CoverNoteNumber' => otherUniqueID(),
                        'PrevCoverNoteReferenceNumber' => $fleet->prev_cover_note_reference_number,
                        'CoverNoteDesc' => $fleet->cover_note_desc ?? "Motor cover note - test",
                        'OperativeClause' => $fleet->operative_clause ?? "Fire and Allied Perils",
                        'EndorsementType' => $fleet->endorsement_type,
                        'EndorsementReason' => $fleet->endorsement_reason,
                        'EndorsementPremiumEarned' =>  $fleet->endorsement_premium_earned,
                        'RisksCovered' => [
                            'RiskCovered' => [
                                [
                                    'RiskCode' => $fleet->quotation->coverage->risk_code,
                                    'SumInsured' => $fleet->sum_insured,
                                    'SumInsuredEquivalent' => $fleet->sum_insured,
                                    'PremiumRate' => $fleet->premium_rate,
                                    'PremiumBeforeDiscount' => $fleet->premium_before_discount,
                                    'PremiumAfterDiscount' => $fleet->premium_after_discount,
                                    'PremiumExcludingTaxEquivalent' => $fleet->premium_excluding_tax_equivalent,
                                    'PremiumIncludingTax' => $fleet->premium_including_tax,
                                    'DiscountsOffered' => [
                                        //     'DiscountOffered' => []
                                    ],
                                    // 'DiscountsOffered' => [
                                    //     'DiscountOffered' => [
                                    //         'DiscountType' => $fleet->discount_type,
                                    //         'DiscountRate' => $fleet->discount_rate,
                                    //         'DiscountAmount' => $fleet->discount_amount,
                                    //     ]
                                    // ],
                                    'TaxesCharged' => [
                                        'TaxCharged' => [
                                            [
                                                'TaxCode' => $fleet->tax_code,
                                                'IsTaxExempted' => $fleet->is_tax_exempted,
                                                'TaxExemptionType' => $fleet->tax_exemption_type,
                                                'TaxExemptionReference' => $fleet->tax_exemption_reference,
                                                'TaxRate' => $fleet->tax_rate,
                                                'TaxAmount' => $fleet->tax_amount,
                                            ]
                                        ],
                                    ],
                                ],
                            ],
                        ],
                        'SubjectMattersCovered' => [
                            'SubjectMatter' => [
                                'SubjectMatterReference' =>  $fleet->subject_matter_reference,
                                'SubjectMatterDesc' =>  $fleet->subject_matter_desc,
                            ],
                        ],
                        'CoverNoteAddons' => [
                            // 'CoverNoteAddon' => $coverNoteAddons,
                        ],
                        'MotorDtl' => [
                            'MotorCategory' => $fleet->motor_category,
                            'MotorType' => $fleet->motor_type,
                            'RegistrationNumber' => $fleet->registration_number,
                            'ChassisNumber' => $fleet->chassis_number,
                            'Make' => $fleet->make,
                            'Model' => $fleet->model,
                            'ModelNumber' => $fleet->model_number,
                            'BodyType' => $fleet->body_type,
                            'Color' => $fleet->color,
                            'EngineNumber' => $fleet->engine_number,
                            'EngineCapacity' => $fleet->engine_capacity,
                            'FuelUsed' => $fleet->fuel_used,
                            'NumberOfAxles' => $fleet->number_of_axles,
                            'AxleDistance' => $fleet->axle_distance,
                            'SittingCapacity' => $fleet->sitting_capacity,
                            'YearOfManufacture' => $fleet->year_of_manufacture,
                            'TareWeight' => $fleet->tare_weight,
                            'GrossWeight' => $fleet->gross_weight,
                            'MotorUsage' => $fleet->motor_usage,
                            'OwnerName' => $fleet->quotation->customer->name ?? $fleet->owner_name,
                            'OwnerCategory' => $fleet->owner_category,
                            'OwnerAddress' => $fleet->$quotation->customer->email_address ?? $fleet->owner_address,
                        ],
                        // ],
                    ];






                    // ONA MATOKEO

                    $data = [
                        'CoverNoteHdr' => [
                            'RequestId' => generateRequestID(),
                            'CompanyCode' => 'IB10152',
                            'SystemCode' => 'TP_KMJ_001',
                            'CallBackUrl' => "https://102.218.224.4/api/tiramis/callback",
                            'InsurerCompanyCode' => 'ICC125',
                            'TranCompanyCode' => 'IB10152',
                            'CoverNoteType' => 3,
                        ],
                        'CoverNoteDtl' => [
                            'FleetHdr' => [
                                'FleetId' => $quotation->fleet_id,
                                'FleetType' => 2,
                                // 'FleetSize' => $quotation->fleet_size,
                                // 'ComprehensiveInsured' => $quotation->comprehensive_insured,
                                'FleetSize' => 1,
                                'ComprehensiveInsured' => 1,
                                'SalePointCode' => $quotation->sale_point_code,
                                'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                                'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
                                'PaymentMode' => $quotation->payment_mode_id,
                                'CurrencyCode' => $quotation->currency->code,
                                'ExchangeRate' => $quotation->exchange_rate,

                                'TotalPremiumExcludingTax' => $fleet->premium_excluding_tax_equivalent,
                                'TotalPremiumIncludingTax' => $fleet->premium_including_tax,

                                // 'TotalPremiumExcludingTax' => $quotation->total_premium_excluding_tax,
                                // 'TotalPremiumIncludingTax' => $quotation->total_premium_including_tax,
                                'CommisionPaid' => $quotation->commission_paid,
                                'CommisionRate' => $quotation->commission_rate,
                                'OfficerName' => optional($quotation->createdBy)->name ?? 'Jackson Makusu',
                                'OfficerTitle' => optional($quotation->createdBy)->title ?? 'Sales Officer',
                                'ProductCode' => $quotation->coverage->product->code,
                                'PolicyHolders' => [
                                    'PolicyHolder' => [
                                        'PolicyHolderName' => $quotation->customer->name,
                                        'PolicyHolderBirthDate' => Carbon::parse($quotation->customer->dob)->format('Y-m-d'),
                                        'PolicyHolderType' =>  $quotation->customer->policy_holder_type_id,
                                        'PolicyHolderIdNumber' => $quotation->customer->policy_holder_id_number,
                                        'PolicyHolderIdType' => $quotation->customer->policy_holder_id_type_id,
                                        'Gender' => $quotation->customer->gender,
                                        'CountryCode' => $quotation->customer->district->region->country->code,
                                        'Region' => $quotation->customer->district->region->name,
                                        'District' => $quotation->customer->district->name,
                                        'Street' => $quotation->customer->street ?? '',
                                        'PolicyHolderPhoneNumber' => $quotation->customer->phone,
                                        'PolicyHolderFax' => $quotation->customer->fax ?? '',
                                        'PostalAddress' => $quotation->customer->postal_address,
                                        'EmailAddress' => $quotation->customer->email_address ?? '',
                                    ],
                                ],
                            ],
                            'FleetDtl' => $fleetArray,

                        ],
                    ];

                    $gen_data = generateXML('MotorCoverNoteRefReq', $data);


                    Log::channel('tiramisxml')->info($gen_data);
                    $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor-fleet/v2/request', $gen_data);


                    $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

                    if (empty($xmlString)) {
                        Log::error('Empty XML response from TIRA');
                    }

                    $xml = simplexml_load_string($xmlString);
                    if ($xml === false) {
                        Log::error('Invalid XML response from TIRA');
                    }

                    // Extract data kutoka kwenye XML
                    $ack = $xml->MotorCoverNoteRefReqAck ?? null;
                    // $msgSignature = (string) ($xml->MsgSignature ?? '');

                    if ($ack) {
                        $acknowledgementId = (string)$ack->AcknowledgementId;
                        $requestId = (string)$ack->RequestId;
                        $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                        $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;


                        if ($ackStatusCode === 'TIRA001') {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            // 📧 SEND EMAIL
                            try {

                                $emails = [
                                    "first@example.com",
                                    "second@example.com"
                                ];

                                Mail::raw(
                                    "TIRA SUCCESS\n\nQuotation ID: {$quotation->id}\nStatus: {$ackStatusDesc}\nRequest ID: {$requestId}",
                                    function ($message) use ($emails) {
                                        $message->to($emails)
                                            ->subject("TIRA Successful Submission");
                                    }
                                );
                            } catch (\Exception $mailError) {
                                Log::error("Mail failed: " . $mailError->getMessage());
                            }


                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_success', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        } else {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // request_id DO NOT update here
                            ]);

                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_failed', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        }

                        Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
                    } else {
                        Log::warning('TIRA acknowledgment not found in XML response');

                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('warning', 'Acknowledgment not found in TIRA response.');
                    }

                    return redirect()
                        ->back()
                        ->with('success', 'Acknowledgment found in TIRA response.');



                    return response()->json([
                        'acknowledgement_status_code' => $ackStatusCode,
                        'acknowledgement_status_desc' => $ackStatusDesc,
                        'request_id' => $requestId,
                        'gen_data' => $gen_data,
                        'quotation' => $quotation,
                        'res' => $res
                    ]);
                } catch (\Exception $e) {
                    Log::channel('tiramisxml')->info($e->getMessage());
                    return redirect()
                        ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                        ->with('tira_failed', true)
                        ->with('error', 'An error occurred while processing your request.');

                    return response()->json([
                        'error' => 'An error occurred while processing your request.',
                        'message' => $e->getMessage()
                    ]);
                }
            } else {

                try {

                    $data = [
                        'CoverNoteHdr' => [
                            'RequestId' => generateRequestID(),
                            'CompanyCode' => 'IB10152',
                            'SystemCode' => 'TP_KMJ_001',
                            'CallBackUrl' => "https://102.218.224.4/api/tiramis/callback",
                            'InsurerCompanyCode' => 'ICC125',
                            'TranCompanyCode' => 'IB10152',
                            'CoverNoteType' => $quotation->cover_note_type_id,
                        ],
                        'CoverNoteDtl' => [
                            'CoverNoteNumber' => otherUniqueID(),
                            'PrevCoverNoteReferenceNumber' => $quotation->prev_cover_note_reference_number,
                            'SalePointCode' => $quotation->sale_point_code,
                            'CoverNoteStartDate' => returnTiraDate($coverNoteStart->format('Y-m-d H:i:s')),
                            'CoverNoteEndDate' => returnTiraDate($coverNoteEnd->format('Y-m-d H:i:s')),
                            'CoverNoteDesc' => $quotation->cover_note_desc ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'OperativeClause' => $quotation->operative_clause ?? "To cover the liability that will arise as a result of professional activities of the insured",
                            'PaymentMode' => $quotation->payment_mode_id,
                            'CurrencyCode' => $quotation->currency->code,
                            'ExchangeRate' => $quotation->exchange_rate,
                            'TotalPremiumExcludingTax' => $quotation->total_premium_excluding_tax,
                            'TotalPremiumIncludingTax' => $quotation->total_premium_including_tax,
                            'CommisionPaid' => $quotation->commission_paid,
                            'CommisionRate' => $quotation->commission_rate,
                            'OfficerName' => optional($quotation->createdBy)->name ?? 'Jackson Makusu',
                            'OfficerTitle' => optional($quotation->createdBy)->title ?? 'Sales Officer',
                            'ProductCode' => $quotation->coverage->product->code,
                            'EndorsementType' => $quotation->endorsement_type_id,
                            'EndorsementReason' => $quotation->endorsement_reason ?? '',
                            'EndorsementPremiumEarned' => $quotation->endorsement_premium_earned ?? '',
                            'RisksCovered' => [
                                'RiskCovered' => [
                                    [
                                        'RiskCode' => $quotation->coverage->risk_code,
                                        'SumInsured' => $quotation->sum_insured,
                                        'SumInsuredEquivalent' => $quotation->sum_insured,
                                        'PremiumRate' => $quotation->premium_rate,
                                        'PremiumBeforeDiscount' => $quotation->premium_before_discount,
                                        'PremiumAfterDiscount' => $quotation->premium_after_discount,
                                        'PremiumExcludingTaxEquivalent' => $quotation->premium_excluding_tax_equivalent,
                                        'PremiumIncludingTax' => $quotation->premium_including_tax,
                                        'DiscountsOffered' => [],
                                        'TaxesCharged' => [
                                            'TaxCharged' => [
                                                'TaxCode' => $quotation->tax_code,
                                                'IsTaxExempted' => $quotation->is_tax_exempted,
                                                'TaxExemptionType' => $quotation->tax_exemption_type ?? '',
                                                'TaxExemptionReference' =>  $quotation->tax_exemption_reference ?? '',
                                                'TaxRate' => $quotation->tax_rate,
                                                'TaxAmount' => $quotation->tax_amount,
                                            ],
                                        ],
                                    ],
                                ]
                            ],
                            'SubjectMattersCovered' => [
                                'SubjectMatter' => [
                                    'SubjectMatterReference' => $quotation->subject_matter_reference,
                                    'SubjectMatterDesc' => $quotation->subject_matter_description,
                                ],
                            ],
                            'CoverNoteAddons' => [],
                            'PolicyHolders' => [
                                'PolicyHolder' => [
                                    'PolicyHolderName' => $quotation->customer->name,
                                    'PolicyHolderBirthDate' => Carbon::parse($quotation->customer->dob)->format('Y-m-d'),
                                    'PolicyHolderType' => $quotation->customer->policy_holder_type_id,
                                    'PolicyHolderIdNumber' => $quotation->customer->policy_holder_id_number,
                                    'PolicyHolderIdType' => $quotation->customer->policy_holder_id_type_id,
                                    'Gender' => $quotation->customer->gender,
                                    'CountryCode' => $quotation->customer->district->region->country->code,
                                    'Region' => $quotation->customer->district->region->name,
                                    'District' => $quotation->customer->district->name,
                                    'Street' => $quotation->customer->street,
                                    'PolicyHolderPhoneNumber' => $quotation->customer->phone,
                                    'PolicyHolderFax' => $quotation->customer->fax ?? '',
                                    'PostalAddress' => $quotation->customer->postal_address,
                                    'EmailAddress' => $quotation->customer->email_address,
                                ],
                            ],
                        ],
                    ];

                    $gen_data = generateXML('CoverNoteRefReq', $data);

                    Log::channel('tiramisxml')->info($gen_data);
                    $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/other/v2/request', $gen_data);


                    $xmlString = is_array($res) ? ($res['response'] ?? '') : $res;

                    if (empty($xmlString)) {
                        Log::error('Empty XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('error', 'Empty response from TIRA.');
                        return response()->json(['error' => 'Empty response from TIRA'], 500);
                    }

                    $xml = simplexml_load_string($xmlString);
                    if ($xml === false) {
                        Log::error('Invalid XML response from TIRA');
                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('error', 'Invalid XML response received from TIRA.');
                        return response()->json(['error' => 'Invalid XML response'], 500);
                    }

                    $ack = $xml->CoverNoteRefReqAck ?? null;

                    if ($ack) {
                        $acknowledgementId = (string)$ack->AcknowledgementId;
                        $requestId = (string)$ack->RequestId;
                        $ackStatusCode = (string)$ack->AcknowledgementStatusCode;
                        $ackStatusDesc = (string)$ack->AcknowledgementStatusDesc;


                        if ($ackStatusCode === 'TIRA001') {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            // 📧 SEND EMAIL
                            try {

                                $emails = [
                                    "first@example.com",
                                    "second@example.com"
                                ];

                                Mail::raw(
                                    "TIRA SUCCESS\n\nQuotation ID: {$quotation->id}\nStatus: {$ackStatusDesc}\nRequest ID: {$requestId}",
                                    function ($message) use ($emails) {
                                        $message->to($emails)
                                            ->subject("TIRA Successful Submission");
                                    }
                                );
                            } catch (\Exception $mailError) {
                                Log::error("Mail failed: " . $mailError->getMessage());
                            }


                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'request_id' => $requestId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_success', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        } else {
                            $quotation->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // request_id DO NOT update here
                            ]);

                            // Update QuotationEndorsement
                            $QuotationEndorsement->update([
                                'acknowledgement_id' => $acknowledgementId,
                                'acknowledgement_status_code' => $ackStatusCode,
                                'acknowledgement_status_desc' => $ackStatusDesc,
                                // 'msg_signature' => $msgSignature,
                            ]);

                            return redirect()
                                ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                                ->with('tira_failed', true)
                                ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                        }

                        Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
                        // return redirect()
                        //     ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                        //     ->with('success', 'TIRA response received successfully! ' . $ackStatusDesc);
                    } else {
                        Log::warning('TIRA acknowledgment not found in XML response');
                        return redirect()
                            ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                            ->with('tira_failed', true)
                            ->with('warning', 'Acknowledgment not found in TIRA response.');
                    }

                    return response()->json([
                        'acknowledgement_status_code' => $ackStatusCode,
                        'acknowledgement_status_desc' => $ackStatusDesc,
                        'request_id' => $requestId,
                        'gen_data' => $gen_data,
                        'res' => $res,
                    ]);
                } catch (\Throwable $e) {
                    Log::channel('tiramisxml')->info($e->getMessage());
                    return redirect()
                        ->route('kmj.quotation.endorsement', ['id' => $quotation->id])
                        ->with('tira_failed', true)
                        ->with('error', 'An error occurred while processing your request.');
                    return response()->json([
                        'error' => 'An error occurred while processing your request.',
                        'message' => $e->getMessage(),
                        'trace' => $e->getTraceAsString()
                    ]);
                }
            }

            return response()->json($quotation);

            return redirect()->back()->with('success', 'Quotation sent to TIRA successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('tira_failed', true)->with('error', 'Failed to send quotation to TIRA: ' . $e->getMessage());
        }
    }
}
