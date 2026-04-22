<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FleetCoverNoteController extends Controller
{

    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'http://192.168.168.200';
    }
    public function fleetCoverNotes(Request $request)
    {

        // Extract all variables as before...
        $coverage_id = $request->coverage_id;
        $customer_id = $request->customer_id;
        $cover_note_type_id = $request->cover_note_type_id;
        $salePointCode = $request->sale_point_code;
        $CoverNoteDesc = $request->cover_note_desc;
        $OperativeClause = $request->operative_clause;
        $CoverNoteStartDate = Carbon::parse($request->cover_note_start_date);
        $CoverNoteEndDate = $CoverNoteStartDate->copy()->addMonths($request->cover_note_duration)->subDay();
        $paymentModeId = $request->payment_mode_id;
        $currencyCodeId = $request->currency_code;
        $exchangeRate = (float)$request->exchange_rate;
        $TotalPremiumExcludingTax = (float)$request->total_premium_excluding_tax;
        $TotalPremiumIncludingTax = (float)$request->total_premium_including_tax;
        $CommisionPaid = (float)$request->commission_paid;
        $CommisionRate = (float)$request->commission_rate;
        $OfficerName = $request->officer_name;
        $OfficerTitle = $request->officer_title;
        $ProductCode = $request->product_code;

        // Risk details
        $SumInsured = (float)$request->sum_insured;
        $SumInsuredEquivalent = (float)$request->sum_insured_equivalent;
        $PremiumRate = (float)$request->premium_rate;
        $PremiumBeforeDiscount = (float)$request->premium_before_discount;
        $PremiumAfterDiscount = (float)$request->premium_after_discount;
        $PremiumExcludingTaxEquivalent = (float)$request->premium_excluding_tax_equivalent;
        $PremiumIncludingTax = (float)$request->premium_including_tax;
        $TaxCode = $request->tax_code;
        $IsTaxExempted = $request->is_tax_exempted;
        $TaxExemptionType = $request->tax_exemption_type;
        $TaxExemptionReference = $request->tax_exemption_reference;
        $TaxRate = (float)$request->tax_rate;
        $TaxAmount = (float)$request->tax_amount;

        // Subject matter
        $SubjectMatterReference = $request->subject_matter_reference;
        $SubjectMatterDescription = $request->subject_matter_description;

        // Policy holder details
        $PolicyHolderName = $request->policy_holder_name;
        $PolicyHolderBirthDate = $request->policy_holder_birth_date;
        $PolicyHolderBirtAge = $request->policy_holder_birt_age; // Fix: use this field
        $PolicyHolderType = $request->policy_holder_type;
        $PolicyHolderIdNumber = $request->policy_holder_id_number;
        $PolicyHolderIdType = $request->policy_holder_id_type;
        $CountryCode = $request->country_code;
        $Region = $request->region;
        $District = $request->district;
        $Street = $request->street;
        $PolicyHolderPhoneNumber = $request->policy_holder_phone_number;
        $PostalAddress = $request->postal_address;
        $RiskCode = $request->risk_code;
        $PrevCoverNoteReferenceNumber = $request->prev_cover_note_reference_number;

        // Fleet details per TIRA spec 6.1 (With Fleet)
        $FleetId = $request->fleet_id;
        $FleetType = $request->fleet_type; // 1-New, 2-Addition
        $FleetSize = $request->fleet_size;
        $ComprehensiveInsured = $request->comprehensive_insured;
        $FleetEntry = $request->fleet_entry;

        $PremiumDiscount = (float)$request->premium_discount;
        $DiscountType = $request->discount_type;
        $DiscountRate = (float)$request->discount_rate;
        $DiscountAmount = (float)$request->discount_amount;

        // Addon details
        $AddonReference = $request->addon_reference;
        $AddonDesc = $request->addon_desc;
        $AddonAmount = (float)$request->addon_amount;
        $AddonPremiumRate = (float)$request->addon_premium_rate;
        $PremiumExcludingTax = (float)$request->premium_excluding_tax;

        $Gender = $request->gender;
        $PolicyHolderFax = $request->policy_holder_fax;
        $EmailAddress = $request->email_address;

        // Motor details
        $MotorCategory = $request->motor_category;
        $MotorType = $request->motor_type; // 1 Registered, 2 In transit
        $RegistrationNumber = $request->registration_number;
        $ChassisNumber = $request->chassis_number;
        $Make = $request->make;
        $Model = $request->model;
        $ModelNumber = $request->model_number;
        $BodyType = $request->body_type;
        $Color = $request->color;
        $EngineNumber = $request->engine_number;
        $EngineCapacity = $request->engine_capacity;
        $FuelUsed = $request->fuel_used;
        $NumberOfAxles = $request->number_of_axles;
        $AxleDistance = $request->axle_distance;
        $SittingCapacity = $request->sitting_capacity;
        $YearOfManufacture = $request->year_of_manufacture;
        $TareWeight = $request->tare_weight;
        $GrossWeight = $request->gross_weight;
        $MotorUsage = $request->motor_usage;
        $OwnerName = $request->owner_name;
        $OwnerCategory = $request->owner_category;
        $OwnerAddress = $request->owner_address;

        // Build the data structure per TIRA spec 6.1 (With Fleet)
        $data = [
            'CoverNoteHdr' => [
                'RequestId' => generateRequestID(),
                'CompanyCode' => 'IB10152',
                'SystemCode' => 'TP_KMJ_001',
                'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback",
                'InsurerCompanyCode' => 'ICC125',
                'TranCompanyCode' => 'IB10152',
                'CoverNoteType' => $cover_note_type_id,
            ],
            'CoverNoteDtl' => [
                'FleetHdr' => [
                    'FleetId' => $FleetId,
                    'FleetType' => $FleetType,
                    'FleetSize' => $FleetSize,
                    'ComprehensiveInsured' => $ComprehensiveInsured,
                    'SalePointCode' => $salePointCode,
                    'CoverNoteStartDate' => returnTiraDate($CoverNoteStartDate),
                    'CoverNoteEndDate' => returnTiraDate($CoverNoteEndDate),
                    'PaymentMode' => $paymentModeId,
                    'CurrencyCode' => $currencyCodeId,
                    'ExchangeRate' => $exchangeRate,
                    'TotalPremiumExcludingTax' => $TotalPremiumExcludingTax,
                    'TotalPremiumIncludingTax' => $TotalPremiumIncludingTax,
                    'CommisionPaid' => $CommisionPaid,
                    'CommisionRate' => $CommisionRate,
                    'OfficerName' => $OfficerName,
                    'OfficerTitle' => $OfficerTitle,
                    'ProductCode' => $ProductCode,
                    'PolicyHolders' => [
                        'PolicyHolder' => [
                            'PolicyHolderName' => $PolicyHolderName,
                            'PolicyHolderBirthDate' => $PolicyHolderBirthDate,
                            'PolicyHolderType' => $PolicyHolderType,
                            'PolicyHolderIdNumber' => $PolicyHolderIdNumber,
                            'PolicyHolderIdType' => $PolicyHolderIdType,
                            'Gender' => $Gender,
                            'CountryCode' => $CountryCode,
                            'Region' => $Region,
                            'District' => $District,
                            'Street' => $Street,
                            'PolicyHolderPhoneNumber' => $PolicyHolderPhoneNumber,
                            'PolicyHolderFax' => $PolicyHolderFax,
                            'PostalAddress' => $PostalAddress,
                            'EmailAddress' => $EmailAddress,
                        ],
                    ],
                ],
                'FleetDtl' => [
                    'FleetEntry' => $FleetEntry,
                    'CoverNoteNumber' => otherUniqueID(),
                    'PrevCoverNoteReferenceNumber' => $PrevCoverNoteReferenceNumber,
                    'CoverNoteDesc' => $CoverNoteDesc ?? "Motor cover note - test",
                    'OperativeClause' => $OperativeClause ?? "Fire and Allied Perils",
                    'EndorsementType' => $request->endorsement_type,
                    'EndorsementReason' => $request->endorsement_reason,
                    'EndorsementPremiumEarned' => $request->endorsement_premium_earned,
                    'RisksCovered' => [
                        'RiskCovered' => [
                            [
                                'RiskCode' => $RiskCode,
                                'SumInsured' => $SumInsured,
                                'SumInsuredEquivalent' => $SumInsuredEquivalent,
                                'PremiumRate' => $PremiumRate,
                                'PremiumBeforeDiscount' => $PremiumBeforeDiscount,
                                'PremiumAfterDiscount' => $PremiumAfterDiscount,
                                'PremiumExcludingTaxEquivalent' => $PremiumExcludingTaxEquivalent,
                                'PremiumIncludingTax' => $PremiumIncludingTax,
                                'DiscountsOffered' => [
                                    'DiscountOffered' => [
                                        'DiscountType' => $DiscountType,
                                        'DiscountRate' => $DiscountRate,
                                        'DiscountAmount' => $DiscountAmount ?? $PremiumDiscount,
                                    ]
                                ],
                                'TaxesCharged' => [
                                    'TaxCharged' => [
                                        [
                                            'TaxCode' => $TaxCode,
                                            'IsTaxExempted' => $IsTaxExempted,
                                            'TaxExemptionType' => $TaxExemptionType,
                                            'TaxExemptionReference' => $TaxExemptionReference,
                                            'TaxRate' => $TaxRate,
                                            'TaxAmount' => $TaxAmount,
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'SubjectMattersCovered' => [
                        'SubjectMatter' => [
                            'SubjectMatterReference' => $SubjectMatterReference,
                            'SubjectMatterDesc' => $SubjectMatterDescription,
                        ],
                    ],
                    'CoverNoteAddons' => [
                        'CoverNoteAddon' => [
                            [
                                'AddonReference' => $AddonReference,
                                'AddonDesc' => $AddonDesc,
                                'AddonAmount' => $AddonAmount,
                                'AddonPremiumRate' => $AddonPremiumRate,
                                'PremiumExcludingTax' => $PremiumExcludingTax,
                                'PremiumExcludingTaxEquivalent' => $PremiumExcludingTaxEquivalent,
                                'PremiumIncludingTax' => $PremiumIncludingTax,
                                'TaxesCharged' => [
                                    'TaxCharged' => [
                                        [
                                            'TaxCode' => $TaxCode,
                                            'IsTaxExempted' => $IsTaxExempted,
                                            'TaxExemptionType' => $TaxExemptionType,
                                            'TaxExemptionReference' => $TaxExemptionReference,
                                            'TaxRate' => $TaxRate,
                                            'TaxAmount' => $TaxAmount,
                                        ]
                                    ],
                                ],
                            ],
                        ],
                    ],
                    'MotorDtl' => [
                        'MotorCategory' => $MotorCategory,
                        'MotorType' => $MotorType,
                        'RegistrationNumber' => $RegistrationNumber,
                        'ChassisNumber' => $ChassisNumber,
                        'Make' => $Make,
                        'Model' => $Model,
                        'ModelNumber' => $ModelNumber,
                        'BodyType' => $BodyType,
                        'Color' => $Color,
                        'EngineNumber' => $EngineNumber,
                        'EngineCapacity' => $EngineCapacity,
                        'FuelUsed' => $FuelUsed,
                        'NumberOfAxles' => $NumberOfAxles,
                        'AxleDistance' => $AxleDistance,
                        'SittingCapacity' => $SittingCapacity,
                        'YearOfManufacture' => $YearOfManufacture,
                        'TareWeight' => $TareWeight,
                        'GrossWeight' => $GrossWeight,
                        'MotorUsage' => $MotorUsage,
                        'OwnerName' => $OwnerName,
                        'OwnerCategory' => $OwnerCategory,
                        'OwnerAddress' => $OwnerAddress,
                    ],
                ],
            ],
        ];

        try {
            $gen_data = generateXML('MotorCoverNoteRefReq', $data);

            // return $gen_data;

            Log::channel('tiramisxml')->info($gen_data);
            $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor-fleet/v2/request', $gen_data);

            return response()->json([
                'success' => 'TRA Response',
                'covernote_ref_number' => otherUniqueID(),
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
