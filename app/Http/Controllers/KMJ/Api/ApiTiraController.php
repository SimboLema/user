<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Coverage;
use App\Models\Models\KMJ\CoverNoteDuration;
use App\Models\Models\KMJ\CoverNoteType;
use App\Models\Models\KMJ\Currency;
use App\Models\Models\KMJ\Customer;
use App\Models\Models\KMJ\EndorsementType;
use App\Models\Models\KMJ\MotorCategory;
use App\Models\Models\KMJ\MotorType;
use App\Models\Models\KMJ\MotorUsage;
use App\Models\Models\KMJ\OwnerCategory;
use App\Models\Models\KMJ\ParticipantType;
use App\Models\Models\KMJ\PaymentMode;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\ReinsuranceCategory;
use App\Models\Models\KMJ\ReinsuranceForm;
use App\Models\Models\KMJ\ReinsuranceType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiTiraController extends Controller
{
    // this return total premium excluding tax
    // for including tax just add 18% tax
    private string $baseUrl;
    public function __construct()
    {
        $this->baseUrl = 'https://api.tira.go.tz:8091';
    }

    public function motorPremiumCalculation(Request $request)
    {


        try {
            $duration = $request->duration;
            $coverage_id = $request->coverage_id;
            $sitting_capacities = $request->sitting_capacities;
            $sum_insured = $request->sum_insured;
            $motor_usage = $request->motor_usage;


            $coverage_details = Coverage::where('id', $coverage_id)->first();
            $coverage_parameter = json_decode($coverage_details->parameters, true);

            // return $coverage_details;



            $coverage_type = $coverage_details->coverage_type;
            $coverage_code = $coverage_details->risk_code;
            $premium_cal = $premium_exc = 0;
            $is_per_set = $coverage_parameter['isperset'];
            $plus_tpp = $coverage_parameter['plustpp'];
            $premium_rate = $coverage_details->rate;
            $additional_amount = $coverage_parameter['additionalamount'];
            $tpp_amount = $coverage_parameter['tppamount'];
            $per_set_amount = $coverage_parameter['persetamount'];
            $minimum_amount = $coverage_details->minimum_amount;



            // return response()->json($is_per_set);



            // 3 and 2 wheelers check if it's private(1) or commercial(2)
            if ($coverage_code == "SP014002000001" || $coverage_code == "SP014002000002" || $coverage_code == "SP014002000005" || $coverage_code == "SP014002000007") {
                if ($motor_usage == 2) {
                    $minimum_amount = $minimum_amount + 15000;
                }
            } elseif ($coverage_code == "SP014002000003" || $coverage_code == "SP014002000004" || $coverage_code == "SP014002000006") {
                if ($motor_usage == 2) {
                    $minimum_amount = $minimum_amount + 45000;
                }
            }



            if ($is_per_set == "Yes") {
                if ($plus_tpp == "Yes") {
                    if ($premium_rate == 0) {
                        $premium_cal = $additional_amount + $tpp_amount + ($sitting_capacities * $per_set_amount);
                        if ($premium_cal < $minimum_amount) {
                            $premium_cal = $minimum_amount;
                        }
                    } else {
                        $premium_cal = ($sum_insured * $premium_rate) / 100 + $additional_amount + $tpp_amount + ($sitting_capacities * $per_set_amount);
                        if ($premium_cal < $minimum_amount) {
                            $premium_cal = $minimum_amount;
                        }
                    }
                } else if ($plus_tpp == "No") {
                    if ($premium_rate == 0) {
                        $premium_cal = $additional_amount + ($sitting_capacities * $per_set_amount);
                        if ($premium_cal < $minimum_amount) {
                            $premium_cal = $minimum_amount;
                        }
                    } else {
                        $premium_cal = ($sum_insured * $premium_rate) / 100 + $additional_amount + ($sitting_capacities * $per_set_amount);
                        if ($premium_cal < $minimum_amount) {
                            $premium_cal = $minimum_amount;
                        }
                    }
                }
            } else if ($is_per_set == "No") {
                if ($plus_tpp == "Yes") {
                    if ($premium_rate == 0) {
                        $premium_cal = $additional_amount + $tpp_amount;
                        if ($premium_cal < $minimum_amount) {
                            $premium_cal = $minimum_amount;
                        }
                    } else {
                        $premium_cal = ($sum_insured * $premium_rate) / 100 + $additional_amount + $tpp_amount;
                        if ($premium_cal < $minimum_amount) {
                            $premium_cal = $minimum_amount;
                        }
                    }
                } else if ($plus_tpp == "No") {
                    if ($premium_rate == 0) {
                        $premium_cal = $minimum_amount + $additional_amount;
                    } else {
                        $premium_cal = ($sum_insured * $premium_rate) / 100 + $additional_amount;
                        if ($premium_cal < $minimum_amount) {
                            $premium_cal = $minimum_amount;
                        }
                    }
                }
            }

            if ($coverage_type == "Comprehensive") {
                if ($duration < 12) {
                    if ($duration == 1) {
                        $premium_exc = ($premium_cal * 0.2);
                    } elseif ($duration == 2) {
                        $premium_exc = ($premium_cal * 0.3);
                    } elseif ($duration == 3) {
                        $premium_exc = ($premium_cal * 0.4);
                    } elseif ($duration == 4) {
                        $premium_exc = ($premium_cal * 0.5);
                    } elseif ($duration == 5) {
                        $premium_exc = ($premium_cal * 0.6);
                    } elseif ($duration == 6) {
                        $premium_exc = ($premium_cal * 0.7);
                    } elseif ($duration == 7) {
                        $premium_exc = ($premium_cal * 0.8);
                    } elseif ($duration == 8) {
                        $premium_exc = ($premium_cal * 0.9);
                    } elseif ($duration == 9) {
                        $premium_exc = ($premium_cal * 0.85);
                    }
                } else {
                    $premium_exc = $premium_cal;
                }
            } else {
                $premium_exc = $premium_cal;
            }

            return array("premium" => $premium_exc, "error_code" => 0, "rate" => $premium_rate);
        } catch (\Exception $exception) {
            return array("error_code" => 1, "premium" => $exception->getMessage());
        }
    }


    public function motorCoverNotes(Request $request)
    {
        $coverage_id = $request->coverage_id;
        $customer_id = $request->customer_id;

        $coverNoteType = CoverNoteType::find($request->cover_note_type_id);

        $salePointCode = $request->sale_point_code;
        $CoverNoteDesc = $request->cover_note_desc;
        $OperativeClause = $request->operative_clause;
        $CoverNoteStartDate = Carbon::parse($request->cover_note_start_date);
        $CoverNoteEndDate = $CoverNoteStartDate->copy()->addMonths($request->cover_note_duration)->subDay();
        $paymentModeId = $request->payment_mode_id;
        $currencyCodeId = $request->currency_code;
        $exchangeRate = $request->exchange_rate;
        $TotalPremiumExcludingTax = $request->total_premium_excluding_tax;
        $TotalPremiumIncludingTax = $request->total_premium_including_tax;
        $CommisionPaid = $request->commission_paid;
        $CommisionRate = $request->commission_rate;
        $OfficerName = $request->officer_name;
        $OfficerTitle = $request->officer_title;
        $ProductCode = $request->product_code;

        // RiskCovered
        $SumInsured = $request->sum_insured;
        $SumInsuredEquivalent = $request->sum_insured_equivalent;
        $PremiumRate = $request->premium_rate;
        $PremiumBeforeDiscount = $request->premium_before_discount;
        $PremiumAfterDiscount = $request->premium_after_discount;
        $PremiumExcludingTaxEquivalent = $request->premium_excluding_tax_equivalent;
        $PremiumIncludingTax = $request->premium_including_tax;
        $TaxCode = $request->tax_code;
        $IsTaxExempted = $request->is_tax_exempted;
        $TaxExemptionType = $request->tax_exemption_type;
        $TaxExemptionReference = $request->tax_exemption_reference;
        $TaxRate = $request->tax_rate;
        $TaxAmount = $request->tax_amount;
        // Discounts (optional but tag must be present even if empty)
        $DiscountType = $request->discount_type;
        $DiscountRate = $request->discount_rate;
        $DiscountAmount = $request->discount_amount;

        // SubjectMattersCovered
        $SubjectMatterReference = $request->subject_matter_reference;
        $SubjectMatterDescription = $request->subject_matter_description;

        //PolicyHolders
        $PolicyHolderName = $request->policy_holder_name;
        $PolicyHolderBirthDate = $request->policy_holder_birth_date;
        $PolicyHolderType = $request->policy_holder_type;
        $PolicyHolderIdNumber = $request->policy_holder_id_number;
        $PolicyHolderIdType = $request->policy_holder_id_type;
        $Gender = $request->gender;
        $CountryCode = $request->country_code;
        $Region = $request->region;
        $District = $request->district;
        $Street = $request->street;
        $PolicyHolderPhoneNumber = $request->policy_holder_phone_number;
        $PostalAddress = $request->postal_address;
        $RiskCode = $request->risk_code;

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


        $data = [
            'CoverNoteHdr' => [
                'RequestId' => generateRequestID(),
                'CompanyCode' => 'IB10152',
                'SystemCode' => 'TP_KMJ_001',
                'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback",
                'InsurerCompanyCode' => 'ICC125',
                'TranCompanyCode' => 'IB10152',
                'CoverNoteType' => $coverNoteType->id,
            ],
            'CoverNoteDtl' => [
                'CoverNoteNumber' => otherUniqueID(),
                'PrevCoverNoteReferenceNumber' => $request->prev_cover_note_reference_number ?? '',
                'SalePointCode' => $salePointCode,
                'CoverNoteStartDate' => returnTiraDate($CoverNoteStartDate),
                'CoverNoteEndDate' => returnTiraDate($CoverNoteEndDate),
                'CoverNoteDesc' => $CoverNoteDesc ?? "To cover the liability that will arise as a result of professional activities of the insured",
                'OperativeClause' => $OperativeClause ?? "To cover the liability that will arise as a result of professional activities of the insured",
                'PaymentMode' => $paymentModeId,
                'CurrencyCode' => $currencyCodeId,
                'ExchangeRate' => $exchangeRate,
                'TotalPremiumExcludingTax' => $TotalPremiumExcludingTax,
                'TotalPremiumIncludingTax' => $TotalPremiumIncludingTax,
                // Keep tag names exactly as in sample (CommisionPaid/CommisionRate)
                'CommisionPaid' => $CommisionPaid,
                'CommisionRate' => $CommisionRate,
                'OfficerName' => $OfficerName,
                'OfficerTitle' => $OfficerTitle,
                'ProductCode' => $ProductCode,
                'EndorsementType' => $request->endorsement_type ?? '',
                'EndorsementReason' => $request->endorsement_reason ?? '',
                'EndorsementPremiumEarned' => $request->endorsement_premium_earned ?? '',
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
                            // DiscountsOffered tag must be present; populate if provided else keep empty
                            'DiscountsOffered' => ($DiscountType !== null && $DiscountRate !== null && $DiscountAmount !== null)
                                ? [
                                    'DiscountOffered' => [
                                        'DiscountType' => $DiscountType,
                                        'DiscountRate' => $DiscountRate,
                                        'DiscountAmount' => $DiscountAmount,
                                    ]
                                ]
                                : '',
                            'TaxesCharged' => [
                                'TaxCharged' => [
                                    'TaxCode' => $TaxCode,
                                    'IsTaxExempted' => $IsTaxExempted,
                                    'TaxExemptionType' => $TaxExemptionType ?? '',
                                    'TaxExemptionReference' => $TaxExemptionReference ?? '',
                                    'TaxRate' => $TaxRate,
                                    'TaxAmount' => $TaxAmount,
                                ],
                            ],
                        ],
                    ]
                ],
                'SubjectMattersCovered' => [
                    'SubjectMatter' => [
                        'SubjectMatterReference' => $SubjectMatterReference,
                        'SubjectMatterDesc' => $SubjectMatterDescription,
                    ],
                ],
                'CoverNoteAddons' => [],
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
                        'Street' => $Street ?? '',
                        'PolicyHolderPhoneNumber' => $PolicyHolderPhoneNumber,
                        'PolicyHolderFax' => $request->policy_holder_fax ?? '',
                        'PostalAddress' => $PostalAddress,
                        'EmailAddress' => $request->email_address ?? '',
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
                    'AxleDistance' => $AxleDistance ?? '',
                    'SittingCapacity' => $SittingCapacity ?? '',
                    'YearOfManufacture' => $YearOfManufacture,
                    'TareWeight' => $TareWeight,
                    'GrossWeight' => $GrossWeight,
                    'MotorUsage' => $MotorUsage,
                    'OwnerName' => $OwnerName,
                    'OwnerCategory' => $OwnerCategory,
                    'OwnerAddress' => $OwnerAddress ?? '',
                ],
            ],
        ];


        try {
            // Keep empty elements as per sample spec (no cleanup)
            $gen_data = generateXML('MotorCoverNoteRefReq', $data);

            // return $gen_data;

            Log::channel('tiramisxml')->info($gen_data);
            $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor/v2/request', $gen_data);

            return response()->json([
                'gen_data' => $gen_data,
                'response' => $res
            ]);
        } catch (\Exception $e) {
            Log::channel('tiramisxml')->info($e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function motorCoverNotes1(Request $request)
    {
        try {
            $coverage = Coverage::find($request->coverage_id);
            if (!$coverage) {
                return response()->json(['error' => 'Coverage not found'], 404);
            }
            $customer = Customer::find(1);

            $coverNoteType = CoverNoteType::find($request->cover_note_type_id);
            if (!$coverNoteType) {
                return response()->json(['error' => 'Cover note type not found'], 404);
            }


            $salePointCode = "SP235";
            $CoverNoteDesc = $request->cover_note_desc;
            $OperativeClause = $request->operative_clause;
            // cover_note_start_date must start 2moro date future
            $CoverNoteStartDate = Carbon::parse($request->cover_note_start_date);
            $coverNoteDuration = CoverNoteDuration::find($request->cover_note_duration_id);
            $CoverNoteEndDate = $CoverNoteStartDate->copy()->addMonths($coverNoteDuration->months ?? 12)->subDay();
            $paymentMode = PaymentMode::find($request->payment_mode_id);
            if (!$paymentMode) {
                return response()->json(['error' => 'Payment mode not found'], 404);
            }
            $currency = Currency::find($request->currency_id);
            if (!$currency) {
                return response()->json(['error' => 'Currency not found'], 404);
            }
            $exchangeRate = $request->exchange_rate;
            // $TotalPremiumExcludingTax = $request->total_premium_excluding_tax;
            // $TotalPremiumIncludingTax = $request->total_premium_including_tax;
            $CommisionPaid = $request->commission_paid;
            $CommisionRate = $request->commission_rate;
            $OfficerName = $request->officer_name;
            $OfficerTitle = $request->officer_title;
            $ProductCode = $coverage->product->code;

            // RiskCovered
            $SumInsured = $request->sum_insured;

            $premiumRate = $coverage->rate / 100;

            $TaxCode = $request->tax_code;
            $IsTaxExempted = $request->is_tax_exempted;
            $TaxExemptionType = $request->tax_exemption_type;
            $TaxExemptionReference = $request->tax_exemption_reference;
            $TaxRate = 0.18;
            // $TaxAmount = $request->tax_amount;
            // Discounts (optional but tag must be present even if empty)
            $DiscountType = $request->discount_type;
            $DiscountRate = $request->discount_rate;
            $DiscountAmount = $request->discount_amount;

            // SubjectMattersCovered
            $SubjectMatterReference = $request->subject_matter_reference;
            $SubjectMatterDescription = $request->subject_matter_description;

            //PolicyHolders
            $PolicyHolderName = $customer->name;
            $PolicyHolderBirthDate = Carbon::parse($customer->dob)->format('Y-m-d');
            $PolicyHolderType = $customer->policy_holder_type_id;
            $PolicyHolderIdNumber = $customer->policy_holder_id_number;
            $PolicyHolderIdType = $customer->policy_holder_id_type_id;
            $Gender = $customer->gender;
            $CountryCode = $customer->district->region->country->code;
            $Region = $customer->district->region->name;
            $District = $customer->district->name;
            $Street = $customer->street;
            $PolicyHolderPhoneNumber = $customer->phone;
            $PolicyHolderFax = $customer->fax;
            $PostalAddress = $customer->postal_address;
            $EmailAddress = $customer->email_address;
            $RiskCode = $coverage->risk_code;

            // Motor details
            $MotorCategory = MotorCategory::find($request->motor_category_id);
            if (!$MotorCategory) {
                return response()->json(['error' => 'Motor category not found'], 404);
            }
            $MotorType =  MotorType::find($request->motor_type_id); // 1 Registered, 2 In transit
            if (!$MotorType) {
                return response()->json(['error' => 'Motor type not found'], 404);
            }
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
            $MotorUsage = MotorUsage::find($request->motor_usage_id);
            if (!$MotorUsage) {
                return response()->json(['error' => 'Motor usage not found'], 404);
            }
            $OwnerName = $customer->name;
            $OwnerCategory = OwnerCategory::find($request->owner_category_id);
            if (!$OwnerCategory) {
                return response()->json(['error' => 'Owner Category not found'], 404);
            }
            // $OwnerAddress = $request->owner_address;

            $premium = $SumInsured * ($coverage->rate / 100);
            $premiumExcludingTax = $premium > $coverage->minimum_amount ? $premium : $coverage->minimum_amount;
            $taxAmount = $premiumExcludingTax * (18 / 100);
            $premiumIncludingTax = $premiumExcludingTax + $taxAmount;

            $quotationD = null;
            $prevCoverNoteRef = null;

            if ($coverNoteType->id == 2 || $coverNoteType->id == 3) {
                $quotationD = Quotation::find($request->quotation_id);

                if ($quotationD) {
                    $prevCoverNoteRef = $quotationD->cover_note_reference;
                } else {
                    Log::warning('No previous quotation found for cover note type ' . $coverNoteType->id);
                }
            }

            // If cover is endorsement i.e. CoverNoteType = 3:

            $endorsementType = EndorsementType::find($request->endorsement_type_id);
            if ($coverNoteType->id == 3) {
                // 1 – Increasing Premium Charged || 2 – Decreasing Premium Charged || 3 – Cover Details Change
                if ($endorsementType->id == 1 || $endorsementType->id == 2 || $endorsementType->id == 3) {
                    // $previousCovernoteReferenceNumber = $quotationD->cover_note_reference;
                    $endorsementReason = $request->endorsement_reason; // required reason form client
                    $endorsementPremiumEarned = null;
                }
                // 4 - Cancellation
                if ($endorsementType->id == 4) {

                    $startDate = Carbon::parse($quotationD->cover_note_start_date);
                    $endDate = Carbon::parse($quotationD->cover_note_end_date);
                    // $todayDate = Carbon::now();
                    $cancellationDate = Carbon::parse($request->cancellation_date);
                    $totalPremiumIncludingTax = $quotationD->total_premium_including_tax;

                    $cancellationDays = $startDate->diffInDays($cancellationDate);
                    $dayCover = $startDate->diffInDays($endDate);

                    // $previousCovernoteReferenceNumber = $quotationD->cover_note_reference;
                    $endorsementReason = $request->endorsement_reason; // required reason form client
                    if ($cancellationDays == 0) {
                        $endorsementPremiumEarned = 0;
                    } else {
                        $endorsementPremiumEarned = ($cancellationDays / $dayCover) * $totalPremiumIncludingTax;
                    }
                }
            }

            if ($coverNoteType->id == 2 || $coverNoteType->id == 3) {
                $quotationD->endorsement_type_id = $endorsementType->id ?? null;
                $quotationD->endorsement_reason = $endorsementReason ?? null;
                $quotationD->endorsement_premium_earned = $endorsementPremiumEarned ?? null;
                $quotationD->save();
            }


            $data = [
                'CoverNoteHdr' => [
                    'RequestId' => generateRequestID(),
                    'CompanyCode' => 'IB10152',
                    'SystemCode' => 'TP_KMJ_001',
                    'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback",
                    'InsurerCompanyCode' => 'ICC125',
                    'TranCompanyCode' => 'IB10152',
                    'CoverNoteType' => $coverNoteType->id,
                ],
                'CoverNoteDtl' => [
                    'CoverNoteNumber' => otherUniqueID(),
                    'PrevCoverNoteReferenceNumber' => $prevCoverNoteRef,
                    'SalePointCode' => $salePointCode,
                    'CoverNoteStartDate' => returnTiraDate($CoverNoteStartDate),
                    'CoverNoteEndDate' => returnTiraDate($CoverNoteEndDate),
                    'CoverNoteDesc' => $CoverNoteDesc ?? "To cover the liability that will arise as a result of professional activities of the insured",
                    'OperativeClause' => $OperativeClause ?? "To cover the liability that will arise as a result of professional activities of the insured",
                    'PaymentMode' => $paymentMode->id,
                    'CurrencyCode' => $currency->code,
                    'ExchangeRate' => $exchangeRate,
                    'TotalPremiumExcludingTax' => $premiumExcludingTax,
                    'TotalPremiumIncludingTax' => $premiumIncludingTax,
                    // Keep tag names exactly as in sample (CommisionPaid/CommisionRate)
                    'CommisionPaid' => $CommisionPaid,
                    'CommisionRate' => $CommisionRate,
                    'OfficerName' => $OfficerName,
                    'OfficerTitle' => $OfficerTitle,
                    'ProductCode' => $ProductCode,
                    'EndorsementType' => $endorsementType->id ?? '',
                    'EndorsementReason' => $endorsementReason ?? '',
                    'EndorsementPremiumEarned' => $endorsementPremiumEarned ?? '',
                    'RisksCovered' => [
                        'RiskCovered' => [
                            [
                                'RiskCode' => $RiskCode,
                                'SumInsured' => $SumInsured,
                                'SumInsuredEquivalent' => $SumInsured,
                                'PremiumRate' => $premiumRate,
                                'PremiumBeforeDiscount' => $premiumExcludingTax,
                                'PremiumAfterDiscount' => $premiumExcludingTax,
                                'PremiumExcludingTaxEquivalent' => $premiumExcludingTax,
                                'PremiumIncludingTax' => $premiumIncludingTax,
                                // DiscountsOffered tag must be present; populate if provided else keep empty
                                'DiscountsOffered' => ($DiscountType !== null && $DiscountRate !== null && $DiscountAmount !== null)
                                    ? [
                                        'DiscountOffered' => [
                                            'DiscountType' => $DiscountType,
                                            'DiscountRate' => $DiscountRate,
                                            'DiscountAmount' => $DiscountAmount,
                                        ]
                                    ]
                                    : '',
                                'TaxesCharged' => [
                                    'TaxCharged' => [
                                        'TaxCode' => $TaxCode,
                                        'IsTaxExempted' => $IsTaxExempted,
                                        'TaxExemptionType' => $TaxExemptionType ?? '',
                                        'TaxExemptionReference' => $TaxExemptionReference ?? '',
                                        'TaxRate' => $TaxRate,
                                        'TaxAmount' => $taxAmount,
                                    ],
                                ],
                            ],
                        ]
                    ],
                    'SubjectMattersCovered' => [
                        'SubjectMatter' => [
                            'SubjectMatterReference' => $SubjectMatterReference,
                            'SubjectMatterDesc' => $SubjectMatterDescription,
                        ],
                    ],
                    'CoverNoteAddons' => [],
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
                            'Street' => $Street ?? '',
                            'PolicyHolderPhoneNumber' => $PolicyHolderPhoneNumber,
                            'PolicyHolderFax' => $PolicyHolderFax ?? '',
                            'PostalAddress' => $PostalAddress,
                            'EmailAddress' => $EmailAddress ?? '',
                        ],
                    ],
                    'MotorDtl' => [
                        'MotorCategory' => $MotorCategory->id,
                        'MotorType' => $MotorType->id,
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
                        'AxleDistance' => $AxleDistance ?? '',
                        'SittingCapacity' => $SittingCapacity ?? '',
                        'YearOfManufacture' => $YearOfManufacture,
                        'TareWeight' => $TareWeight,
                        'GrossWeight' => $GrossWeight,
                        'MotorUsage' => $MotorUsage->id,
                        'OwnerName' => $OwnerName,
                        'OwnerCategory' => $OwnerCategory->id,
                        'OwnerAddress' => $PostalAddress ?? '',
                    ],
                ],
            ];

            $quotationData = [
                "coverage_id" => $coverage->id,
                "customer_id" => $customer->id,
                "cover_note_type_id" => $coverNoteType->id,
                "cover_note_duration_id" => $coverNoteDuration->id,
                "payment_mode_id" => $paymentMode->id,
                "currency_id" => $currency->id,
                "sale_point_code" => $salePointCode,
                "cover_note_desc" => $CoverNoteDesc ?? "",
                "operative_clause" => $OperativeClause ?? "",
                "cover_note_start_date" => $CoverNoteStartDate,
                "cover_note_end_date" => $CoverNoteEndDate,
                "exchange_rate" => $exchangeRate,
                "total_premium_excluding_tax" => $premiumExcludingTax,
                "total_premium_including_tax" => $premiumIncludingTax,
                "commission_paid" => $CommisionPaid,
                "commission_rate" => $CommisionRate,
                "officer_name" => $OfficerName,
                "officer_title" => $OfficerTitle,
                "sum_insured" => $SumInsured,
                "premium_rate" => $premiumRate,
                "premium_before_discount" => $premiumExcludingTax,
                "premium_after_discount" => $premiumExcludingTax,
                "premium_excluding_tax_equivalent" => $premiumExcludingTax,
                "premium_including_tax" => $premiumIncludingTax,
                "tax_code" => $TaxCode,
                "is_tax_exempted" => $IsTaxExempted,
                "tax_rate" => $TaxRate,
                "tax_amount" => $taxAmount,
                "subject_matter_reference" => $SubjectMatterReference,
                "subject_matter_description" => $SubjectMatterDescription,

                // EndorsementType if CoverNoteType is 3 this must be filled
                'endorsement_type_id' => $endorsementType->id ?? null,
                'endorsement_reason' => $endorsementReason ?? null,
                'endorsement_premium_earned' => $endorsementPremiumEarned ?? null,

                // Motors
                'motor_category_id' => $MotorCategory->id,
                'motor_type_id' => $MotorType->id,
                'registration_number' => $RegistrationNumber,
                'chassis_number' => $ChassisNumber,
                'make' => $Make,
                'model' => $Model,
                'model_number' => $ModelNumber,
                'body_type' => $BodyType,
                'color' => $Color,
                'engine_number' => $EngineNumber,
                'engine_capacity' => $EngineCapacity,
                'fuel_used' => $FuelUsed,
                'number_of_axles' => $NumberOfAxles,
                'axle_distance' => $AxleDistance ?? '',
                'sitting_capacity' => $SittingCapacity ?? '',
                'year_of_manufacture' => $YearOfManufacture,
                'tare_weight' => $TareWeight,
                'gross_weight' => $GrossWeight,
                'motor_usage_id' => $MotorUsage->id,
                'owner_name' => $OwnerName,
                'owner_category_id' => $OwnerCategory->id,
                'owner_address' => $PostalAddress ?? '',

                // auth user

                'created_by' => 1,
                'updated_by' => 1,
            ];

            if ($coverNoteType->id == 1 || $coverNoteType->id == 2) {
                $quotation = Quotation::create($quotationData);
            }
            // if ($coverNoteType->id == 2) {
            //     // $quotation = $quotationD;
            //     $quotation = Quotation::create($quotationData);
            // }
            if ($coverNoteType->id == 3) {
                $quotation = $quotationD;
            }


            // Keep empty elements as per sample spec (no cleanup)
            $gen_data = generateXML('MotorCoverNoteRefReq', $data);

            // return $gen_data;

            Log::channel('tiramisxml')->info($gen_data);
            $res = TiraRequest($this->baseUrl . '/ecovernote/api/covernote/non-life/motor/v2/request', $gen_data);

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

            // Extract data kutoka kwenye XML
            $ack = $xml->MotorCoverNoteRefReqAck ?? null;
            // $msgSignature = (string) ($xml->MsgSignature ?? '');

            if ($ack) {
                $acknowledgementId = (string) $ack->AcknowledgementId;
                $requestId = (string) $ack->RequestId;
                $ackStatusCode = (string) $ack->AcknowledgementStatusCode;
                $ackStatusDesc = (string) $ack->AcknowledgementStatusDesc;

                // Update quotation
                $quotation->update([
                    'acknowledgement_id' => $acknowledgementId,
                    'request_id' => $requestId,
                    'acknowledgement_status_code' => $ackStatusCode,
                    'acknowledgement_status_desc' => $ackStatusDesc,
                    // 'msg_signature' => $msgSignature,
                ]);

                Log::channel('tiramisxml')->info('TIRA response saved for Quotation ID: ' . $quotation->id);
            } else {
                Log::warning('TIRA acknowledgment not found in XML response');
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
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
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
                'response' => $res
            ]);
        } catch (\Exception $e) {
            Log::channel('tiramisxml')->info($e->getMessage());
            return response()->json([
                'error' => 'An error occurred while processing your request.',
                'message' => $e->getMessage()
            ]);
        }
    }


    // 2.Reinsurance Submission
    public function reinsuranceSubmission(Request $request)
    {
        Log::info("=== Reinsurance Submission Request Received ===");
        Log::info("Received Reinsurance Submission Request: ");
        // Build ReinsuranceReq per TIRA spec 7.1

        $reinsuranceCategory = ReinsuranceCategory::find($request->reinsurance_category_id);
        $currency = Currency::find($request->currency_id);

        $reinsuranceHdr = [
            'RequestId' => generateRequestID(),
            'CompanyCode' => 'IB10152',
            'SystemCode' => 'TP_KMJ_001',
            'CallBackUrl' => "https://suretech.co.tz/api/tiramis/callback",
            'InsurerCompanyCode' => 'ICC125',
            'CoverNoteReferenceNumber' => $request->cover_note_reference_number,
            'PremiumIncludingTax' => $request->premium_including_tax,
            'CurrencyCode' => $currency->code,
            'ExchangeRate' => $request->exchange_rate,
            'AuthorizingOfficerName' => $request->authorizing_officer_name,
            'AuthorizingOfficerTitle' => $request->authorizing_officer_title,
            'ReinsuranceCategory' => $reinsuranceCategory->id,
        ];

        // Expect an array of participants in reinsurance_details
        $details = [];
        $reinsuranceDetails = $request->reinsurance_details ?? [];
        foreach ($reinsuranceDetails as $detail) {

            $participantType = ParticipantType::find($detail['participant_type_id']);
            $reinsuranceForm = ReinsuranceForm::find($detail['reinsurance_form_id']);
            $reinsuranceType = ReinsuranceType::find($detail['reinsurance_type_id']);


            $details[] = [
                'ParticipantCode' => $detail['participant_code'] ?? '', #given by TIRA
                'ParticipantType' => $participantType->id,
                'ReinsuranceForm' => $reinsuranceForm->id,
                'ReinsuranceType' => $reinsuranceType->id,
                'ReBrokerCode' => $detail['rebroker_code'] ?? '', #given by TIRA
                'BrokerageCommission' => $detail['brokerage_commission'] ?? '',
                'ReinsuranceCommission' => $detail['reinsurance_commission'] ?? '',
                'PremiumShare' => $detail['premium_share'] ?? '',
                'ParticipationDate' => isset($detail['participation_date']) ? returnTiraDate($detail['participation_date']) : '',
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

            return response()->json([
                'success' => 'TRA Response',
                'response' => $res
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
