<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Addon;
use App\Models\Models\KMJ\AddonProduct;
use App\Models\Models\KMJ\Country;
use App\Models\Models\KMJ\Coverage;
use App\Models\Models\KMJ\CoverNoteDuration;
use App\Models\Models\KMJ\CoverNoteType;
use App\Models\Models\KMJ\Currency;
use App\Models\Models\KMJ\Customer;
use App\Models\Models\KMJ\EndorsementType;
use App\Models\Models\KMJ\PaymentMode;
use App\Models\Models\KMJ\PolicyHolderIdType;
use App\Models\Models\KMJ\PolicyHolderType;
use App\Models\Models\KMJ\Product;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\QuotationEndorsement;
use App\Models\Models\KMJ\QuotationFleetDetail;
use App\Models\Models\KMJ\Region;
use Carbon\Carbon;
use FontLib\Table\Type\fpgm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class FleetMotorController extends Controller
{

    public function motorPremiumCalculation($duration, $coverage_id, $sitting_capacities, $sum_insured, $motor_usage)
    {


        try {


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

    public function index()
    {
        $countries = Country::orderBy('name', 'asc')->get();
        $policyHolderType = PolicyHolderType::all();
        $policyHolderIdType = PolicyHolderIdType::all();
        $currencies = Currency::where('id', 1)->get();
        $coverNoteDurations = CoverNoteDuration::all();
        $paymentModes = PaymentMode::all();
        $coverNoteTypes = CoverNoteType::all();
        $products = Product::with('coverages')->where('insurance_id', 2)->get();
        $quotations = Quotation::whereNotNull('fleet_id')
            ->where('fleet_id', '!=', '')
            ->orderBy('created_at', 'desc')
            ->get();


        return view('kmj.quotation.fleet-motor.index', compact('countries', 'policyHolderIdType', 'policyHolderType', 'coverNoteDurations', 'currencies', 'paymentModes', 'products', 'quotations', 'coverNoteTypes',));
    }

    public function store(Request $request)
    {

        // Validate required fields
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|exists:customers,id',
            // 'fleet_type' => 'required',
            'fleet_size' => 'required|numeric',
            'comprehensive_insured' => 'required|numeric',
            'cover_note_duration_id' => 'required|exists:cover_note_durations,id',
            'cover_note_start_date' => 'required|date',
            'currency_id' => 'required|exists:currencies,id',
            'payment_mode_id' => 'required|exists:payment_modes,id',
            'product_id' => 'required|exists:products,id',
            'coverage_id' => 'required|exists:coverages,id',
            // 'cover_note_type_id' => 'required',
            // 'addons' => 'nullable|array',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput(); // preserves the old input
        }

        // Prefix
        $prefix = 'FL';
        $fleetId = $prefix . now()->format('YmdHis') . mt_rand(10, 99);

        $coveNoteDuration = CoverNoteDuration::find($request->cover_note_duration_id);
        $cover_note_start_date = $request->cover_note_start_date;
        $coverNoteStartDate = Carbon::parse($cover_note_start_date);
        $coverNoteEndDate = $coverNoteStartDate->copy()->addMonths($coveNoteDuration->months ?? 12)->subDay();

        $data = $request->all();
        $data['cover_note_type_id'] = 1;
        $data['fleet_type'] = 1;
        $data['fleet_id'] = $fleetId;
        $data['sale_point_code'] = "SP235";
        $data['created_by'] = Auth::user()->id;
        $data['updated_by'] = Auth::user()->id;
        $data["cover_note_start_date"] = $coverNoteStartDate;
        $data["cover_note_end_date"] = $coverNoteEndDate;
        $data["subject_matter_reference"] = 'HSB001';
        $data["subject_matter_description"] = 'COVERS THE INSURED SUBJECT MATTER AGAINST LOSS OR DAMAGE AS PER POLICY TERMS AND CONDITIONS.';
        $data["cover_note_desc"] = 'PROVIDES PROTECTION AGAINST ANY INSURED PERILS AS SPECIFIED UNDER THE COVER NOTE AGREEMENT.';
        $data["operative_clause"] = 'THE INSURANCE COVER SHALL APPLY ONLY TO LOSSES OCCURRING DURING THE PERIOD OF INSURANCE AND ARISING DIRECTLY FROM INSURED RISKS.';


        $quotation = Quotation::create($data);

        return redirect()->back()
            ->with('success', 'Quotation created successfully!');

        // return response()->json([
        //     'success' => true,
        //     'quotation' => $quotation,
        // ]);
    }

    public function createFleetDetail(Request $request)
    {

        // return response()->json($request->all());

        $quotation = Quotation::find($request->quotation_id);

        $coverage = Coverage::find($quotation->coverage_id);
        $customer = Customer::find($quotation->customer_id);
        $coveNoteDuration = CoverNoteDuration::find($quotation->cover_note_duration_id);

        $sitting_capacity = $request->sitting_capacity ?: null;
        $sumInsured = $request->sum_insured;
        $motor_usage_id = $request->motor_usage;
        $is_tax_exempted = $request->is_tax_exempted;



        $taxCode = "VAT-MAINLAND";
        $taxRate = 0.18;

        $subjectMatterReference = "HSB001";
        $subjectMatterDescription = "COVERS THE INSURED SUBJECT MATTER AGAINST LOSS OR DAMAGE AS PER POLICY TERMS AND CONDITIONS.";

        // calculation
        $premium1 = $this->motorPremiumCalculation($coveNoteDuration->months, $coverage->id, $sitting_capacity, $sumInsured, $motor_usage_id);
        $premiumValue = $premium1['premium'];

        $premium = $coverage->product->insurance->id == 2 ? $premiumValue : $sumInsured * ($coverage->rate / 100);
        $premiumExcludingTax = $premium > $coverage->minimum_amount ? $premium : $coverage->minimum_amount;
        $taxAmount = $premiumExcludingTax * $taxRate;
        $premiumIncludingTax = $premiumExcludingTax + $taxAmount;

        $premiumRate = $coverage->rate / 100;


        $data = [
            // Fleet / CoverNote fields
            'fleet_entry' => $request->fleet_entry,
            'cover_note_number' => otherUniqueID(),
            // 'prev_cover_note_reference_number' => 'CN-0000',
            'cover_note_desc' => $request->cover_note_desc,
            'operative_clause' => $request->operative_clause,
            // 'endorsement_type' => 'STANDARD ENDORSEMENT',
            // 'endorsement_reason' => 'STANDARD REASON',
            // 'endorsement_premium_earned' => 0.00,

            // RiskCovered fields
            // 'risk_code' => 'RC-001',
            'sum_insured' => $sumInsured,
            'sum_insured_equivalent' => $sumInsured,
            'premium_rate' =>  $premiumRate,
            'premium_before_discount' => $premiumExcludingTax,
            'premium_after_discount' => $premiumExcludingTax,
            'premium_excluding_tax_equivalent' => $premiumExcludingTax,
            'premium_including_tax' => $premiumIncludingTax,
            'discount_type' => 1,
            'discount_rate' => 0,
            'discount_amount' => 0,
            'tax_code' => $taxCode,
            'is_tax_exempted' => $is_tax_exempted,
            'tax_exemption_type' => null,
            'tax_exemption_reference' => null,
            'tax_rate' => $taxRate,
            'tax_amount' => $taxAmount,

            // SubjectMatter
            'subject_matter_reference' => $subjectMatterReference,
            'subject_matter_desc' => $subjectMatterDescription,

            // MotorDtl fields
            'motor_category' => $request->motor_category,
            'motor_type' => $request->motor_type,
            'registration_number' => $request->registration_number,
            'chassis_number' => $request->chassis_number,
            'make' => $request->make,
            'model' => $request->model,
            'model_number' => $request->model_number,
            'body_type' => $request->body_type,
            'color' => $request->color,
            'engine_number' => $request->engine_number,
            'engine_capacity' => $request->engine_capacity,
            'fuel_used' => $request->fuel_used,
            'number_of_axles' => $request->number_of_axles,
            'axle_distance' => $request->axle_distance,
            'sitting_capacity' => $request->sitting_capacity,
            'year_of_manufacture' => $request->year_of_manufacture,
            'tare_weight' => $request->tare_weight,
            'gross_weight' => $request->gross_weight,
            'motor_usage' => $request->motor_usage,
            'owner_name' => $customer->name,
            'owner_category' => $request->owner_category,
            'owner_address' => $customer->postal_address ?? '',

            // Relations
            'quotation_id' => $quotation->id,
        ];
        $addonIds = $request->input('addons', []);
        $data['addons'] = !empty($addonIds) ? json_encode($addonIds) : json_encode([]);

        // $quotation->total_premium_excluding_tax ??= 0;
        // $quotation->total_premium_including_tax ??= 0;
        // $quotation->total_premium_excluding_tax += $premiumExcludingTax;
        // $quotation->total_premium_including_tax += $premiumIncludingTax;
        // $quotation->save();

        $quotationFleetDetail = QuotationFleetDetail::create($data);

        // Decode JSON (ikiwa tayari ni string)
        // Initialize totals
        $totalExclTax = 0;
        $totalInclTax = 0;
        foreach ($addonIds as $index => $addonProductId) {
            $addonProduct = AddonProduct::find($addonProductId);
            if (!$addonProduct) continue;

            $premiumExclTax = $addonProduct->amount;
            $taxRate = 0.18;
            $taxAmount = $premiumExclTax * $taxRate;
            $premiumInclTax = $premiumExclTax + $taxAmount;

            // Add to totals
            $totalExclTax += $premiumExclTax;
            $totalInclTax += $premiumInclTax;

            Addon::create([
                'quotation_id' => $quotation->id,
                'quotation_fleet_details_id' => $quotationFleetDetail->id,
                'addon_product_id' => $addonProductId,
                'addon_reference' => $index + 1,
                'addon_desc' => $addonProduct->description,
                'addon_amount' => $addonProduct->amount,
                'addon_rate' => $addonProduct->rate,
                'premium_excluding_tax' => $premiumExclTax,
                'premium_excluding_tax_equivalent' => $premiumExclTax,
                'premium_including_tax' => $premiumInclTax,
                'tax_code' => "VAT-MAINLAND",
                'is_tax_exempted' => "N",
                'tax_exemption_type' => null,
                'tax_exemption_reference' => null,
                'tax_rate' => $taxRate,
                'tax_amount' => $taxAmount,
            ]);
        }



        $quotationFleetDetail->premium_before_discount += $totalExclTax;
        $quotationFleetDetail->premium_after_discount += $totalExclTax;
        $quotationFleetDetail->premium_excluding_tax_equivalent += $totalExclTax;
        $quotationFleetDetail->premium_including_tax += $totalInclTax;
        $quotationFleetDetail->save();


        $quotation->total_premium_excluding_tax ??= 0;
        $quotation->total_premium_including_tax ??= 0;
        $quotation->total_premium_excluding_tax += $quotationFleetDetail->premium_before_discount;
        $quotation->total_premium_including_tax +=  $quotationFleetDetail->premium_including_tax;
        $quotation->save();


        return redirect()
            ->back()
            ->with('success', 'Quotation Fleet Detail Created Successfully!');
    }

    public function editView($id)
    {
        $quotationFleetDetail = QuotationFleetDetail::find($id);
        return view('kmj.quotation.fleet-motor.edit_fleet_detail_view', compact('quotationFleetDetail'));
    }

    public function update(Request $request, $id)
    {
        $quotationFleetDetail = QuotationFleetDetail::findOrFail($id);
        $quotationFleetDetail->update($request->all());

        return redirect()->back()->with('success', 'Fleet details updated successfully.');
    }

    public function editQuoation($id)
    {
        $quotation = Quotation::find($id);
        return view('kmj.quotation.fleet-motor.edit_quotation_view', compact('quotation'));
    }

    public function updateQuotation(Request $request, $id)
    {
        $quotation = Quotation::findOrFail($id);
        $quotation->update($request->all());

        return redirect()->back()->with('success', 'Quotation updated successfully.');
    }

    public function endorsementView($id)
    {
        $quotationFleetDetail = QuotationFleetDetail::find($id);
        $quotationFleetDetailEndorsements = QuotationEndorsement::where('quotation_fleet_detail_id',  $id)->get();
        $endorsementTypes = EndorsementType::all();
        $paymentModes = PaymentMode::all();

        return view('kmj.quotation.fleet-motor.endorsement_view', compact('quotationFleetDetail', 'quotationFleetDetailEndorsements', 'endorsementTypes', 'paymentModes'));
    }
}
