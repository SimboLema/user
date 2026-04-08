<?php

namespace App\Http\Controllers\KMJ\Api;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\Coverage;
use App\Models\Models\KMJ\CoverNoteDuration;
use App\Models\Models\KMJ\EndorsementType;
use App\Models\Models\KMJ\Payment;
use App\Models\Models\KMJ\PaymentMode;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\QuotationEndorsement;
use App\Models\Models\KMJ\QuotationFleetDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuotationEndorsementController extends Controller
{
    public function makeEndorsement(Request $request, $id)
    {
        // return $request->all();
        if ($request->has('quotation_id')) {
            return $this->handleQuotationEndorsement($request, $id);
        }

        if ($request->has('quotation_fleet_detail_id')) {
            return $this->handleFleetDetailEndorsement($request, $id);
        }

        return response()->json([
            'error' => 'Invalid request: neither quotation_id nor quotation_fleet_detail_id found.'
        ], 400);


        // try {
        //     // Start database transaction
        //     DB::beginTransaction();

        //     $quotation = Quotation::find($id);
        //     $endorsement_type = EndorsementType::find($request->endorsement_type_id);
        //     $description = $request->description;
        //     $cancellation_date = Carbon::parse($request->cancellation_date);

        //     $endorsementReason = $description;
        //     $endorsementPremiumEarned = null;

        //     if ($endorsement_type->id == 1 || $endorsement_type->id == 2 || $endorsement_type->id == 3) {
        //         // Additional logic can be added here based on endorsement type
        //         $endorsementPremiumEarned = null;
        //     } elseif ($endorsement_type->id == 4) {

        //         // Additional logic for cancellation
        //         $start_date = Carbon::parse($quotation->cover_note_start_date);
        //         $end_date = Carbon::parse($quotation->cover_note_end_date);
        //         $cancellation_dat = Carbon::parse($cancellation_date);
        //         $total_premium_including_tax = $quotation->total_premium_including_tax;

        //         Log::info('=== Endorsement Type 4 Calculation ===');
        //         Log::info('Start Date: ' . $start_date);
        //         Log::info('End Date: ' . $end_date);
        //         Log::info('Cancellation Date: ' . $cancellation_dat);
        //         Log::info('Total Premium Including Tax: ' . $total_premium_including_tax);

        //         $cancellation_days = $start_date->diffInDays($cancellation_dat);
        //         $day_covered = $start_date->diffInDays(date: $end_date);

        //         Log::info('Cancellation Days: ' . $cancellation_days);
        //         Log::info('Days Covered: ' . $day_covered);

        //         if ($cancellation_days == 0) {
        //             $endorsementPremiumEarned = 0;
        //             Log::info('Premium Earned: 0 (cancellation days = 0)');
        //         } else {
        //             $endorsementPremiumEarned = ($cancellation_days / $day_covered) * $total_premium_including_tax;
        //             Log::info('Premium Earned Calculated: ' . $endorsementPremiumEarned);
        //         }
        //     }

        //     $quotation->endorsement_type_id = $endorsement_type->id;
        //     $quotation->endorsement_reason = $endorsementReason;
        //     $quotation->endorsement_premium_earned = $endorsementPremiumEarned;
        //     $quotation->cover_note_type_id = 3;
        //     $quotation->save();

        //     if (!empty($quotation->fleet_id)) {

        //         $data = [
        //             'quotation_id' => $quotation->id,
        //             'endorsement_type_id' => $endorsement_type->id,
        //             // 'previous_covernote_reference_number' => $quotation->prev_cover_note_reference_number,
        //             'description' => $endorsementReason,
        //             'old_premium' => $quotation->total_premium_including_tax,
        //             'new_premium' => $endorsementPremiumEarned,
        //             'endorsement_premium_earned' => $endorsementPremiumEarned,
        //             'cancellation_date' => $cancellation_date,
        //             // 'status' => $status,
        //         ];

        //         foreach ($quotation->quotationFleetDetails as $fleetDetail) {

        //             $fleetDetail->endorsement_type = $endorsement_type->id;
        //             $fleetDetail->endorsement_reason = $endorsementReason;
        //             $fleetDetail->endorsement_premium_earned = $endorsementPremiumEarned;
        //             $fleetDetail->save();
        //         }
        //     } else {



        //         $data = [
        //             'quotation_id' => $quotation->id,
        //             'endorsement_type_id' => $endorsement_type->id,
        //             'previous_covernote_reference_number' => $quotation->prev_cover_note_reference_number,
        //             'description' => $endorsementReason,
        //             'old_premium' => $quotation->total_premium_including_tax,
        //             'new_premium' => $endorsementPremiumEarned,
        //             'endorsement_premium_earned' => $endorsementPremiumEarned,
        //             'cancellation_date' => $cancellation_date,
        //             // 'status' => $status,
        //         ];
        //     }

        //     $quotationEndorsement = QuotationEndorsement::create($data);

        //     // Payments of Quotation Endorsement can be handled here
        //     $payment_mode = PaymentMode::find($request->payment_mode_id ?? 1);
        //     $cheque_number = $request->cheque_number;
        //     $cheque_bank_name = $request->cheque_bank_name;
        //     $cheque_date = $request->cheque_date;
        //     $eft_payment_phone_no = $request->eft_payment_phone_no;

        //     Payment::create([
        //         'quotation_id' => $quotation->id,
        //         'quotation_endorsement_id' => $quotationEndorsement->id,
        //         'payment_mode_id' => $payment_mode->id,
        //         'amount' => $endorsementPremiumEarned ?? 0,
        //         'payment_date' => Carbon::now(),
        //         'cheque_number' => $cheque_number,
        //         'cheque_bank_name' => $cheque_bank_name,
        //         'cheque_date' => $cheque_date,
        //         'reference_no' => 'PAY-' . strtoupper(uniqid('', true)),
        //         'eft_payment_phone_no' => $eft_payment_phone_no,
        //         // 'status' => 'completed',
        //         'created_by' => auth()->id(),
        //     ]);

        //     // Send Tira then update  wait update call

        //     // Commit transaction
        //     DB::commit();

        //     return redirect()
        //         ->back()
        //         ->with('success', 'Endorsement created successfully!');
        // } catch (\Exception $e) {
        //     // Rollback on failure
        //     DB::rollBack();

        //     Log::error('Endorsement creation failed: ' . $e->getMessage());

        //     return redirect()
        //         ->back()
        //         ->with('error', 'Something went wrong while creating the endorsement. Please try again.');
        // }
    }


    private function handleQuotationEndorsement(Request $request, $id)
    {
        try {

            DB::beginTransaction();

            $sumInsured = $request->sum_insured;
            $quotation = Quotation::find($id);
            $endorsement_type = EndorsementType::find($request->endorsement_type_id);
            $description = $request->description;
            $cancellation_date = Carbon::parse($request->cancellation_date);

            $endorsementReason = $description;
            $endorsementPremiumEarned = null;

            // change sum insured
            if ($endorsement_type->id == 1 || $endorsement_type->id == 2) {
                $quotation->sum_insured = $sumInsured;
                // $quotation->sum_insured_equivalent = $sumInsured;
                $quotation->save();

                $taxRate = 0.18;
                $coverage = Coverage::find($quotation->coverage_id);
                $coveNoteDuration = CoverNoteDuration::find($quotation->cover_note_duration_id);
                $sitting_capacity = $quotation->sitting_capacity ?: null;
                $motor_usage_id = $quotation->motor_usage_id;


                // calculation
                $premium1 = $this->motorPremiumCalculation($coveNoteDuration->months, $coverage->id, $sitting_capacity, $sumInsured, $motor_usage_id);
                $premiumValue = $premium1['premium'];

                $premium = $coverage->product->insurance->id == 2 ? $premiumValue : $sumInsured * ($coverage->rate / 100);
                $premiumExcludingTax = $premium > $coverage->minimum_amount ? $premium : $coverage->minimum_amount;
                $taxAmount = $premiumExcludingTax * $taxRate;
                $premiumIncludingTax = $premiumExcludingTax + $taxAmount;

                //update fleet detail
                $quotation->update([
                    "total_premium_excluding_tax" => $premiumExcludingTax,
                    "total_premium_including_tax" => $premiumIncludingTax,
                    'premium_before_discount' => $premiumExcludingTax,
                    'premium_after_discount' => $premiumExcludingTax,
                    'premium_excluding_tax_equivalent' => $premiumExcludingTax,
                    'premium_including_tax' => $premiumIncludingTax,
                    'tax_amount' => $taxAmount,
                ]);
            }


            /** ========== 1. Calculate Premium Based on Type ========= */
            if (in_array($endorsement_type->id, [1, 2, 3])) {
                $endorsementPremiumEarned = null;
            } elseif ($endorsement_type->id == 4) {

                $start_date = Carbon::parse($quotation->cover_note_start_date);
                $end_date = Carbon::parse($quotation->cover_note_end_date);

                $cancellation_days = $start_date->diffInDays($cancellation_date);
                $days_covered = $start_date->diffInDays($end_date);

                if ($cancellation_days == 0) {
                    $endorsementPremiumEarned = 0;
                } else {
                    $endorsementPremiumEarned =
                        ($cancellation_days / $days_covered) * $quotation->total_premium_including_tax;
                }
            }

            /** ========== 2. Update Main Quotation ========= */
            $quotation->update([
                'endorsement_type_id' => $endorsement_type->id,
                'endorsement_reason' => $endorsementReason,
                'endorsement_premium_earned' => $endorsementPremiumEarned,
                'cover_note_type_id' => 3
            ]);

            /** ========== 3. Prepare Endorsement Record ========= */
            if (!empty($quotation->fleet_id)) {

                $data = [
                    'quotation_id' => $quotation->id,
                    'endorsement_type_id' => $endorsement_type->id,
                    'description' => $endorsementReason,
                    'old_premium' => $quotation->total_premium_including_tax,
                    'new_premium' => $endorsementPremiumEarned,
                    'endorsement_premium_earned' => $endorsementPremiumEarned,
                    'cancellation_date' => $cancellation_date,
                ];

                foreach ($quotation->quotationFleetDetails as $fleetDetail) {
                    $fleetDetail->update([
                        'endorsement_type' => $endorsement_type->id,
                        'endorsement_reason' => $endorsementReason,
                        'endorsement_premium_earned' => $endorsementPremiumEarned
                    ]);
                }
            } else {

                $data = [
                    'quotation_id' => $quotation->id,
                    'endorsement_type_id' => $endorsement_type->id,
                    'previous_covernote_reference_number' => $quotation->prev_cover_note_reference_number,
                    'description' => $endorsementReason,
                    'old_premium' => $quotation->total_premium_including_tax,
                    'new_premium' => $endorsementPremiumEarned,
                    'endorsement_premium_earned' => $endorsementPremiumEarned,
                    'cancellation_date' => $cancellation_date
                ];
            }

            $quotationEndorsement = QuotationEndorsement::create($data);

            /** ========== 4. Create Payment ========== */
            $payment_mode = PaymentMode::find($request->payment_mode_id ?? 1);

            Payment::create([
                'quotation_id' => $quotation->id,
                'quotation_endorsement_id' => $quotationEndorsement->id,
                'payment_mode_id' => $payment_mode->id,
                'amount' => $endorsementPremiumEarned ?? 0,
                'payment_date' => now(),
                'reference_no' => 'PAY-' . strtoupper(uniqid()),
                'cheque_number' => $request->cheque_number,
                'cheque_bank_name' => $request->cheque_bank_name,
                'cheque_date' => $request->cheque_date,
                'eft_payment_phone_no' => $request->eft_payment_phone_no,
                'created_by' => auth()->id()
            ]);

            DB::commit();

            return back()->with('success', 'Endorsement created successfully!');
        } catch (\Exception $e) {

            DB::rollBack();
            Log::error($e);

            return back()->with('error', 'Error creating endorsement.');
        }
    }


    private function handleFleetDetailEndorsement(Request $request, $id)
    {

        $sumInsured = $request->sum_insured;
        $endorsement_type = EndorsementType::find($request->endorsement_type_id);
        $fleetDetail = QuotationFleetDetail::findOrFail($id);
        $quotation = Quotation::find($fleetDetail->quotation_id);
        $description = $request->description;
        $cancellation_date = Carbon::parse($request->cancellation_date);

        $endorsementReason = $description;
        $endorsementPremiumEarned = null;


        // change sum insured
        if ($endorsement_type->id == 1 || $endorsement_type->id == 2) {

            // update quotation fleet detail [minus]
            $fleetQutat = $fleetDetail->quotation;
            $fleetQutat->total_premium_excluding_tax -= $fleetDetail->premium_excluding_tax_equivalent;
            $fleetQutat->total_premium_including_tax -= $fleetDetail->premium_including_tax;
            $fleetQutat->save();


            $fleetDetail->sum_insured = $sumInsured;
            $fleetDetail->sum_insured_equivalent = $sumInsured;
            $fleetDetail->save();

            $taxRate = 0.18;
            $coverage = Coverage::find($quotation->coverage_id);
            $coveNoteDuration = CoverNoteDuration::find($quotation->cover_note_duration_id);
            $sitting_capacity = $fleetDetail->sitting_capacity ?: null;
            $motor_usage_id = $fleetDetail->motor_usage;


            // calculation
            $premium1 = $this->motorPremiumCalculation($coveNoteDuration->months, $coverage->id, $sitting_capacity, $sumInsured, $motor_usage_id);
            $premiumValue = $premium1['premium'];

            $premium = $coverage->product->insurance->id == 2 ? $premiumValue : $sumInsured * ($coverage->rate / 100);
            $premiumExcludingTax = $premium > $coverage->minimum_amount ? $premium : $coverage->minimum_amount;
            $taxAmount = $premiumExcludingTax * $taxRate;
            $premiumIncludingTax = $premiumExcludingTax + $taxAmount;

            // update quotation fleet detail [plus]
            $fleetQutat->total_premium_excluding_tax += $premiumExcludingTax;
            $fleetQutat->total_premium_including_tax += $premiumIncludingTax;
            $fleetQutat->save();

            //update fleet detail
            $fleetDetail->update([
                'premium_before_discount' => $premiumExcludingTax,
                'premium_after_discount' => $premiumExcludingTax,
                'premium_excluding_tax_equivalent' => $premiumExcludingTax,
                'premium_including_tax' => $premiumIncludingTax,
                'tax_amount' => $taxAmount,
            ]);
        }


        if (in_array($endorsement_type->id, [1, 2, 3])) {
            $endorsementPremiumEarned = null;
        } elseif ($endorsement_type->id == 4) {

            $start_date = Carbon::parse($quotation->cover_note_start_date);
            $end_date = Carbon::parse($quotation->cover_note_end_date);

            $cancellation_days = $start_date->diffInDays($cancellation_date);
            $days_covered = $start_date->diffInDays($end_date);

            if ($cancellation_days == 0) {
                $endorsementPremiumEarned = 0;
            } else {
                $endorsementPremiumEarned =
                    ($cancellation_days / $days_covered) * $fleetDetail->premium_including_tax;
            }
        }


        $fleetDetail->update([
            'endorsement_type' => $request->endorsement_type_id,
            'endorsement_reason' => $request->description,
            'endorsement_premium_earned' => $endorsementPremiumEarned
        ]);

        $data = [
            'quotation_id' => $quotation->id,
            'quotation_fleet_detail_id' => $fleetDetail->id,
            'endorsement_type_id' => $endorsement_type->id,
            'previous_covernote_reference_number' => $quotation->prev_cover_note_reference_number,
            'description' => $endorsementReason,
            'old_premium' => $quotation->total_premium_including_tax,
            'new_premium' => $endorsementPremiumEarned,
            'endorsement_premium_earned' => $endorsementPremiumEarned,
            'cancellation_date' => $cancellation_date
        ];

        $quotationEndorsement = QuotationEndorsement::create($data);
        $payment_mode = PaymentMode::find($request->payment_mode_id ?? 1);
        Payment::create([
            'quotation_id' => $quotation->id,
            'quotation_endorsement_id' => $quotationEndorsement->id,
            'payment_mode_id' => $payment_mode->id,
            'amount' => $endorsementPremiumEarned ?? 0,
            'payment_date' => now(),
            'reference_no' => 'PAY-' . strtoupper(uniqid()),
            'cheque_number' => $request->cheque_number,
            'cheque_bank_name' => $request->cheque_bank_name,
            'cheque_date' => $request->cheque_date,
            'eft_payment_phone_no' => $request->eft_payment_phone_no,
            'created_by' => auth()->id()
        ]);


        return back()->with('success', 'Fleet Detail endorsement processed.');
    }

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
}
