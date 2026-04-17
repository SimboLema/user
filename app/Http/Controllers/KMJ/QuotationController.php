<?php

namespace App\Http\Controllers\KMJ;

use App\Http\Controllers\Controller;
use App\Models\Models\KMJ\AddonProduct;
use App\Models\Models\KMJ\Country;
use App\Models\Models\KMJ\Coverage;
use App\Models\Models\KMJ\CoverNoteDuration;
use App\Models\Models\KMJ\CoverNoteType;
use App\Models\Models\KMJ\Currency;
use App\Models\Models\KMJ\Customer;
use App\Models\Models\KMJ\EndorsementType;
use App\Models\Models\KMJ\Insuarer;
use App\Models\Models\KMJ\Insurance;
use App\Models\Models\KMJ\MotorCategory;
use App\Models\Models\KMJ\MotorType;
use App\Models\Models\KMJ\MotorUsage;
use App\Models\Models\KMJ\OwnerCategory;
use App\Models\Models\KMJ\ParticipantType;
use App\Models\Models\KMJ\Payment;
use App\Models\Models\KMJ\PaymentMode;
use App\Models\Models\KMJ\PolicyHolderIdType;
use App\Models\Models\KMJ\PolicyHolderType;
use App\Models\Models\KMJ\Product;
use App\Models\Models\KMJ\Quotation;
use App\Models\Models\KMJ\QuotationDocument;
use App\Models\Models\KMJ\QuotationFleetDetail;
use App\Models\Models\KMJ\Region;
use App\Models\Models\KMJ\ReinsuranceCategory;
use App\Models\Models\KMJ\ReinsuranceForm;
use App\Models\Models\KMJ\ReinsuranceType;
use App\Traits\ImageTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Mail\QuotationCreatedMail;
use App\Mail\QuotationApprovedMail;
use Illuminate\Support\Facades\Mail;
use App\Services\WhatsappService;

class QuotationController extends Controller
{
    protected WhatsappService $whatsAppService;

    public function __construct(WhatsappService $whatsAppService)
    {
        $this->whatsAppService = $whatsAppService;
    }
    public function index()
    {
        $quotations = Quotation::orderBy('id', 'desc')->get();
        $insurance = Insurance::all();
        $insuarers = Insuarer::all();

        $currencies = Currency::whereIn('id', [1,2])->get();
        $reinsurance_categories = ReinsuranceCategory::all();
        $participant_types = ParticipantType::all();
        $reinsurance_forms = ReinsuranceForm::all();
        $reinsurance_types = ReinsuranceType::all();

        return view('kmj.quotation.index', compact(
            'quotations',
            'insuarers',
            'insurance',
            'currencies',
            'reinsurance_categories',
            'participant_types',
            'reinsurance_forms',
            'reinsurance_types'
        ));
    }

    public function quotationReport(Request $request)
    {
        $query = Quotation::query();
        $insurance = Insurance::all();

        // return response()->json($request->all());


        // Use user input if available
        if ($request->filled('from_date')) {
            $from = Carbon::parse($request->from_date)->format('Y-m-d');
            $query->whereDate('cover_note_end_date', '>=', $from);
        }

        if ($request->filled('to_date')) {
            $to = Carbon::parse($request->to_date)->format('Y-m-d');
            $query->whereDate('cover_note_end_date', '<=', $to);
        }

        // -----------------------------
        // FILTER BY INSURANCE
        // -----------------------------
        if ($request->filled('insurance_id')) {
            $insuranceId = $request->insurance_id;

            $query->whereHas('coverage.product.insurance', function ($q) use ($insuranceId) {
                $q->where('id', $insuranceId);
            });
        }

        // -----------------------------
        // FILTER BY PRODUCT
        // -----------------------------
        if ($request->filled('product_id')) {
            $productId = $request->product_id;

            $query->whereHas('coverage.product', function ($q) use ($productId) {
                $q->where('id', $productId);
            });
        }

        // -----------------------------
        // FILTER BY COVERAGE
        // -----------------------------
        if ($request->filled('coverage_id')) {
            $coverageId = $request->coverage_id;

            $query->where('coverage_id', $coverageId);
        }

        $quotations = $query->orderBy('id', 'asc')->get();

        return view('kmj.quotation.report', compact('quotations', 'insurance'));
    }

    public function show($id)
    {
        $quotation = Quotation::find($id);
        return view('kmj.quotation.show', compact('quotation'));
    }

    public function getProducts($insuranceId)
    {
        // Return both id and product_name
        return Product::where('insurance_id', $insuranceId)
            ->get(['id', 'name']);
    }

    public function getCoverages($productId)
    {
        // Return both id and risk_name
        return Coverage::where('product_id', $productId)
            ->get(['id', 'risk_name']);
    }

    //getQuotation
    public function getQuotation(Request $request)
    {
        $coverageId = $request->coverage_id;
        $coverage = Coverage::find($coverageId);
        $countries = Country::orderBy('name', 'asc')->get();

        $regions = Region::where('country_id', 219)->get();
        $policyHolderType = PolicyHolderType::all();
        $policyHolderIdType = PolicyHolderIdType::all();

        // product
        $coverNoteTypes = CoverNoteType::all();
        $coverNoteDurations = CoverNoteDuration::all();
        $paymentModes = PaymentMode::all();
        $currencies = Currency::where('id', 1)->get();

        //motor
        $motorCategories = MotorCategory::all();
        $motorTypes = MotorType::all();
        $motorUsages = MotorUsage::all();
        $ownerCategories = OwnerCategory::all();


        return view('kmj.quotation.create', compact(
            'regions',
            'policyHolderType',
            'policyHolderIdType',
            'coverage',
            'coverNoteDurations',
            'coverNoteTypes',
            'paymentModes',
            'currencies',
            'motorCategories',
            'motorTypes',
            'motorUsages',
            'ownerCategories',
            'countries'
        ));
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

    public function store(Request $request)
    {
        try {
            $customer_id = $request->customer_id;

            // Coverages
            $coverage = Coverage::find($request->coverage_id);

            // Auth user
            $user = Auth::user();

            // Customer
            if (!empty($customer_id)) {
                $customer = Customer::find($customer_id);
            } else {
                $customer = new Customer();
                $customer->name = $request->name;
                $customer->dob = $request->dob;
                $customer->policy_holder_type_id = $request->policy_holder_type_id;
                $customer->tin_number = $request->tin_number;
                $customer->policy_holder_id_number = $request->policy_holder_id_number;
                $customer->policy_holder_id_type_id = $request->policy_holder_id_type_id;
                $customer->gender = $request->gender;
                $customer->district_id = $request->district_id;
                $customer->street = $request->street;
                $customer->phone = $request->phone;
                $customer->fax = $request->fax;
                $customer->postal_address = $request->postal_address;
                $customer->email_address = $request->email_address;
                $customer->save();
            }

            // cover note
            $coverNoteType = CoverNoteType::find(1);
            $coveNoteDuration = CoverNoteDuration::find($request->cover_note_duration_id);

            $cover_note_desc = $request->cover_note_desc;
            $operative_clause = $request->operative_clause;

            $cover_note_start_date = $request->cover_note_start_date;

            $coverNoteStartDate = Carbon::parse($cover_note_start_date);
            $coverNoteEndDate = $coverNoteStartDate->copy()->addMonths($coveNoteDuration->months ?? 12)->subDay();

            // currency
            $currency = Currency::find($request->currency_id);
            $exchange_rate = $request->exchange_rate;
            $sumInsured = $request->sum_insured;

            $commission_rate = $request->commission_rate;
            $commission_paid = $request->commission_paid;
            $is_tax_exempted = $request->is_tax_exempted;

            // payment Mode
            $payment_mode = PaymentMode::find($request->payment_mode_id);

            $cheque_number = $request->cheque_number;
            $cheque_bank_name = $request->cheque_bank_name;
            $cheque_date = $request->cheque_date;
            $eft_payment_phone_no = $request->eft_payment_phone_no;
            //        $eft_date = $request->eft_date;
            $description = $request->description;


            $salePointCode = "SP235";
            $taxCode = "VAT-MAINLAND";
            $taxRate = 0.18;

            $subjectMatterReference = "HSB001";
            $subjectMatterDescription = "COVERS THE INSURED SUBJECT MATTER AGAINST LOSS OR DAMAGE AS PER POLICY TERMS AND CONDITIONS.";


            // Motor
            $motor_category_id = $request->motor_category_id;
            $motor_type_id = $request->motor_type_id;
            $registration_number = $request->registration_number;
            $chassis_number = $request->chassis_number;
            $make = $request->make;
            $model = $request->model;
            $model_number = $request->model_number;
            $body_type = $request->body_type;
            $color = $request->color;
            $engine_number = $request->engine_number;
            $engine_capacity = $request->engine_capacity;
            $fuel_used = $request->fuel_used;
            $number_of_axles = $request->number_of_axles;
            $axle_distance = $request->axle_distance ?: null;
            $sitting_capacity = $request->sitting_capacity ?: null;
            $year_of_manufacture = $request->year_of_manufacture ?: null;
            $tare_weight = $request->tare_weight ?: null;
            $gross_weight = $request->gross_weight ?: null;
            $motor_usage_id = $request->motor_usage_id;
            $owner_category_id = $request->owner_category_id;

            // calculation
            $premium1 = $this->motorPremiumCalculation($coveNoteDuration->months, $coverage->id, $sitting_capacity, $sumInsured, $motor_usage_id);
            $premiumValue = $premium1['premium'];

            $premium = $coverage->product->insurance->id == 2 ? $premiumValue : $sumInsured * ($coverage->rate / 100);
            $premiumExcludingTax = $premium > $coverage->minimum_amount ? $premium : $coverage->minimum_amount;
            $taxAmount = $premiumExcludingTax * (18 / 100);
            $premiumIncludingTax = $premiumExcludingTax + $taxAmount;

            $premiumRate = $coverage->rate / 100;

            // region and district
            $district = $customer->district;
            $region = $district->region;
            $country = $region->country;
            $Street = $customer->street;

            // product
            $product = $coverage->product;

            // NIMEONGEZA LEO
            $insurer = Insuarer::find($coverage->product->insurance->id);

            $status = 'pending'; // default

            if ($insurer && $insurer->auto_approval_limit !== null) {
                if ($sumInsured <= $insurer->auto_approval_limit) {
                    $status = 'approved';
                }
            }
            //

            $quotationData = [
                "insuarer_id" => $coverage->product->insurance->id,
                "coverage_id" => $coverage->id,
                "customer_id" => $customer->id,
                "cover_note_type_id" => $coverNoteType->id,
                "cover_note_duration_id" => $coveNoteDuration->id,
                "payment_mode_id" => $payment_mode->id,
                "currency_id" => $currency->id,
                "sale_point_code" => $salePointCode,
                "cover_note_desc" => $cover_note_desc ?? "",
                "operative_clause" => $operative_clause ?? "",
                "cover_note_start_date" => $coverNoteStartDate,
                "cover_note_end_date" => $coverNoteEndDate,
                "exchange_rate" => $exchange_rate,
                "total_premium_excluding_tax" => $premiumExcludingTax,
                "total_premium_including_tax" => $premiumIncludingTax,
                "commission_paid" => $commission_paid,
                "commission_rate" => $commission_rate,
                "sum_insured" => $sumInsured,
                //Nimeongeza leo
                "status" => $status,
                //
                "premium_rate" => $premiumRate,
                "premium_before_discount" => $premiumExcludingTax,
                "premium_after_discount" => $premiumExcludingTax,
                "premium_excluding_tax_equivalent" => $premiumExcludingTax,
                "premium_including_tax" => $premiumIncludingTax,
                "tax_code" => $taxCode,
                "is_tax_exempted" => $is_tax_exempted,
                "tax_rate" => $taxRate,
                "tax_amount" => $taxAmount,
                "subject_matter_reference" => $subjectMatterReference,
                "subject_matter_description" => $subjectMatterDescription,
                "description" => $description,
                'created_by' => Auth::user()->id,
                'updated_by' => Auth::user()->id,


                // Motors
                'motor_category_id' => $motor_category_id,
                'motor_type_id' => $motor_type_id,
                'registration_number' => $registration_number,
                'chassis_number' => $chassis_number,
                'make' => $make,
                'model' => $model,
                'model_number' => $model_number,
                'body_type' => $body_type,
                'color' => $color,
                'engine_number' => $engine_number,
                'engine_capacity' => $engine_capacity,
                'fuel_used' => $fuel_used,
                'number_of_axles' => $number_of_axles,
                'axle_distance' => $axle_distance ?? '',
                'sitting_capacity' => $sitting_capacity,
                'year_of_manufacture' => $year_of_manufacture,
                'tare_weight' => $tare_weight,
                'gross_weight' => $gross_weight,
                'motor_usage_id' => $motor_usage_id,
                'owner_name' => $customer->name,
                'owner_category_id' => $owner_category_id,
                'owner_address' => $customer->postal_address ?? '',
            ];

            $quotation = Quotation::create($quotationData);

            $payment = new Payment();
            $payment->quotation_id = $quotation->id;
            $payment->payment_mode_id = $payment_mode->id;
            $payment->amount = $premium;
            $payment->payment_date = now();
            $payment->cheque_number = $cheque_number;
            $payment->cheque_bank_name = $cheque_bank_name;
            $payment->cheque_date = $cheque_date;
            $payment->reference_no = 'PAY-' . strtoupper(uniqid('', true));
            $payment->eft_payment_phone_no = $eft_payment_phone_no;
            $payment->created_by = $user->id;
            $payment->save();

            $imageTrait = new ImageTrait();

            if ($request->hasFile('uploads')) {
                foreach ($request->file('uploads') as $file) {
                    $originalName = $file->getClientOriginalName();
                    $path = $imageTrait->uploadFile($file, 'quotations');

                    // Save to quotation_documents table
                    QuotationDocument::create([
                        'quotation_id' => $quotation->id,
                        'name' => $originalName,
                        'file_path' => $path,
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }

            // Send email to insurer
                $insurer = $quotation->insuarer;
                if ($insurer && $insurer->email) {
                    try {
                        $mail = new QuotationCreatedMail($quotation);
                        $mail->send($insurer->email, $insurer->name ?? 'Insurer');
                    } catch (Exception $mailException) {
                        Log::error('Mailtrap email failed: ' . $mailException->getMessage());
                    }
                }

            //send whatsapp sms to insuarer
            $this->sendQuotationToInsurer($quotation, $insurer);

            return redirect()
                ->route('kmj.quotation')
                ->with('success', 'Quotation Created Successfully!');
        } catch (Exception $e) {

            return response()->json([
                'success' => false,
                'error' => 'Error: ' . $e->getMessage(),
                'message' => $e,
            ]);
        }
    }

    //function ya kutuma sms za whatsaap kwa insuarer

    private function sendQuotationToInsurer($quotation, $insuarer)
    {
        try {
            // Check if insurer has a phone number
            if (!$insuarer->phone) {
                Log::warning('Insurer ' . $insuarer->id . ' does not have a phone number');
                return;
            }

            // Format phone number (remove any non-numeric characters)
            $phoneNumber = preg_replace('/[^0-9]/', '', $insuarer->phone);

            // Build the message
            $message = "Hello " . $insuarer->name . ",\n\n";
            $message .= "A new quotation has been created for your review.\n\n";
            $message .= "Quotation Details:\n";
            $message .= "- Customer: " . $quotation->customer->name . "\n";
            $message .= "- Registration: " . $quotation->registration_number . "\n";
            $message .= "- Premium: " . number_format($quotation->total_premium_including_tax, 2) . " " . $quotation->currency->code . "\n";
            $message .= "- Start Date: " . $quotation->cover_note_start_date->format('d-m-Y') . "\n\n";
            $message .= "Please log in to the system to review and approve.\n\n";
            $message .= "Quotation Reference: #" . $quotation->id;

            // Send via WhatsApp
            $result = $this->whatsAppService->sendTextMessage($phoneNumber, $message);

            if ($result['success']) {
                Log::info('WhatsApp message sent to insurer ' . $insuarer->id);
            } else {
                Log::error('Failed to send WhatsApp to insurer: ' . json_encode($result));
            }
        } catch (\Exception $e) {
            Log::error('Error sending WhatsApp to insurer: ' . $e->getMessage());
        }
    }


    // tabs
    // Covernote tab
    public function covernote($id)
    {
        $quotation = Quotation::find($id);
        $coverNoteDurations = CoverNoteDuration::all();
        $ddonProducts = AddonProduct::all();
        $coverage = $quotation->coverage;
        $quotationFleetDetails = QuotationFleetDetail::where('quotation_id', $id)->orderBy('created_at', 'desc')->get();
        $currencies = Currency::where('id', 1)->get();
        $reinsurance_categories = ReinsuranceCategory::all();
        $participant_types = ParticipantType::all();
        $reinsurance_forms = ReinsuranceForm::all();
        $reinsurance_types = ReinsuranceType::all();
        return view('kmj.quotation.tabs.covernote', compact('quotation', 'coverNoteDurations', 'quotationFleetDetails', 'coverage', 'ddonProducts', 'currencies', 'reinsurance_categories', 'participant_types', 'reinsurance_forms', 'reinsurance_types'));
    }

    // Payment tab
    public function payment($id)
    {
        $quotation = Quotation::find($id);
        $payments = Payment::where('quotation_id', $quotation->id)->get();
        return view('kmj.quotation.tabs.payments', compact('quotation', 'payments'));
    }

    // Transaction tab
    public function transaction($id)
    {
        $quotation = Quotation::find($id);
        return view('kmj.quotation.tabs.transactions', compact('quotation'));
    }

    // Addons tab
    public function addons($id)
    {
        $quotation = Quotation::find($id);
        $addons = AddonProduct::all();
        return view('kmj.quotation.tabs.addons', compact('quotation','addons'));
    }

    // Customer tab
    public function customer($id)
    {
        $quotation = Quotation::find($id);
        $regions = Region::where('country_id', 219)->get();
        $policyHolderType  = PolicyHolderType::all();
        $policyHolderIdType  = PolicyHolderIdType::all();
        $customer  = $quotation->customer;
        return view('kmj.quotation.tabs.customer', compact('quotation', 'customer', 'regions', 'policyHolderType', 'policyHolderIdType'));
    }

    // Motor details tab
    public function motorDetails($id)
    {
        $quotation = Quotation::find($id);
        return view('kmj.quotation.tabs.motor-details', compact('quotation'));
    }

    // Documents tab
    public function documents($id)
    {
        $quotation = Quotation::find($id);
        $quotationDocuments = QuotationDocument::where('quotation_id', $quotation->id)->get();
        return view('kmj.quotation.tabs.documents', compact('quotation', 'quotationDocuments'));
    }

    // Endorsement tab
    public function endorsement($id)
    {
        $quotation = Quotation::find($id);
        $endorsementTypes = EndorsementType::all();
        $paymentModes = PaymentMode::all();
        $quotationEndorsements = $quotation->quotationEndorsements()->with(['payment.paymentMode'])->orderBy('created_at', 'desc')->get();
        return view('kmj.quotation.tabs.endorsements', compact('quotation', 'endorsementTypes', 'paymentModes', 'quotationEndorsements'));
    }

    public function downloadCoverNote($id)
    {
        try {
            Log::info("Quotation download initiated", ['quotation_id' => $id]);

            ini_set('max_execution_time', 300);
            ini_set('memory_limit', '256M');

            $quotation = Quotation::find($id);

            if (!$quotation) {
                Log::warning("Quotation not found", ['quotation_id' => $id]);
                return back()->with('error', 'Quotation haijapatikana.');
            }

            // ✅ Convert images to base64
            $relianceLogo = processImage(public_path('assets/dash/board_files/reliance.png'));
            $kmjLogo = processImage(public_path('assets/dash/board_files/logoo.png'));
            $qrCode = processImage(public_path('assets/dash/board_files/qr.png'));
            $suretechLogo = processImage(public_path('assets/dash/board_files/logo1.png'));


            $pdf = Pdf::loadView('kmj.quotation.downloads.covernote_pdf', [
                'quotation' => $quotation,
                'relianceLogo' => $relianceLogo,
                'kmjLogo' => $kmjLogo,
                'qrCode' => $qrCode,
                'suretechLogo' => $suretechLogo,
            ]);

            $pdf->setOptions([
                // 'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
            ]);

            //            $pdf->setPaper('a4', 'portrait');

            //            Log::info("PDF generated successfully", ['quotation_id' => $quotation->id]);

            return $pdf->stream('covernote_' . $quotation->id . '.pdf');
        } catch (\Exception $e) {
            Log::error("Error downloading quotation PDF", [
                'quotation_id' => $id,
                'error_message' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return back()->with('error', 'Kuna tatizo wakati wa kudownload PDF: ' . $e->getMessage());
        }
    }


    public function downloadQuotation($id)
    {
    $quotation = Quotation::with([
        'customer',
        'coverage.product',
        'insuarer'
    ])->find($id);

    if (!$quotation) {
        Log::warning("Quotation not found", ['quotation_id' => $id]);
        return back()->with('error', 'Quotation haijapatikana.');
    }

    Log::info("Quotation loaded, generating PDF", ['quotation_id' => $quotation->id]);

    //  Build details array (moved from Blade)
    $quotationDetails = [
        'Customer Name' => ucwords(strtolower($quotation->customer->name ?? '')),
        'Product' => ucwords(strtolower($quotation->coverage->product->name ?? '')),
        'Coverage' => ucwords(strtolower($quotation->coverage->risk_name ?? '')),
        'Insurer Name' => ucwords(strtolower($quotation->insuarer->name ?? '')),
        'Intermediary' => 'KMJ Insurance Brokers Ltd',

        'Cover Note Start Date' => optional($quotation->cover_note_start_date)->format('d M Y'),
        'Cover Note End Date' => optional($quotation->cover_note_end_date)->format('d M Y'),

        'Exchange Rate' => number_format($quotation->exchange_rate ?? 0, 2),

        'Total Premium (Excl. Tax)' => number_format($quotation->total_premium_excluding_tax ?? 0, 2),
        'Total Premium (Incl. Tax)' => number_format($quotation->total_premium_including_tax ?? 0, 2),
    ];

    //  Additional financial details (only if not fleet)
    if (empty($quotation->fleet_id)) {
        $quotationDetails['Sum Insured'] = number_format($quotation->sum_insured ?? 0, 2);
        $quotationDetails['Premium Rate (%)'] = number_format(($quotation->premium_rate ?? 0) * 100, 2);
        $quotationDetails['Premium Before Discount'] = number_format($quotation->premium_before_discount ?? 0, 2);
        $quotationDetails['Premium After Discount'] = number_format($quotation->premium_after_discount ?? 0, 2);
        $quotationDetails['Premium Excl. Tax (Equivalent)'] = number_format($quotation->premium_excluding_tax_equivalent ?? 0, 2);
        $quotationDetails['Premium Incl. Tax'] = number_format($quotation->premium_including_tax ?? 0, 2);
        $quotationDetails['Tax Rate (%)'] = number_format(($quotation->tax_rate ?? 0) * 100, 2);
        $quotationDetails['Tax Amount'] = number_format($quotation->tax_amount ?? 0, 2);

        if (optional($quotation->coverage->product)->insurance_id == 2) {
            $quotationDetails['Sticker Number'] = $quotation->sticker_number ?? '';
        }

        $quotationDetails['Cover Note Reference'] = $quotation->cover_note_reference ?? '';
    }

    //Status fields
    $quotationDetails['Acknowledgement Status Code'] = $quotation->acknowledgement_status_code ?? '';
    $quotationDetails['Acknowledgement Status Description'] = $quotation->acknowledgement_status_desc ?? '';
    $quotationDetails['Response Status Code'] = $quotation->response_status_code ?? '';
    $quotationDetails['Response Status Description'] = $quotation->response_status_desc ?? '';
    $quotationDetails['Request ID'] = $quotation->request_id ?? '';

    //Pass BOTH variables to Blade
    $pdf = Pdf::loadView(
        'kmj.quotation.downloads.quotation_pdf',
        compact('quotation', 'quotationDetails')
    );

    return $pdf->stream('quotation_' . $quotation->id . '.pdf');
    }


    public function downloadPayment($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            Log::warning("Payment not found", ['payment_id' => $id]);
            return back()->with('error', 'Payment not found.');
        }

        Log::info("Payment loaded, generating PDF", ['payment_id' => $payment->id]);

        $pdf = Pdf::loadView('kmj.quotation.downloads.payment_pdf', compact('payment'));
        return $pdf->stream('receipt_' . $payment->id . '.pdf');
    }

    public function searchQuotation(Request $request)
    {
        $query = $request->q ?? '';

        $quotations = Quotation::with('customer')
            ->where('cover_note_reference', 'like', "%{$query}%")
            ->get()
            ->map(function ($q) {
                return [
                    'id' => $q->id,
                    'cover_note_reference' => $q->cover_note_reference,
                    'customer_name' => ucwords(strtolower($q->customer->name)) ?? '-',
                    'total_premium_including_tax' => number_format($q->total_premium_including_tax),
                ];
            });

        return response()->json($quotations);
    }

    //Sending email when quotation is verified
    public function verify(Request $request, $id)
    {
        try {
            $quotation = Quotation::findOrFail($id);

            // Update quotation status to verified
            $quotation->update([
                'status' => 'verified', // or whatever your status field is
                'updated_by' => Auth::id(),
            ]);

            // Send verification email to insurer
            $insurer = $quotation->insuarer;
            if ($insurer && $insurer->email) {
                Mail::to($insurer->email)->send(new QuotationApprovedMail($quotation));
            }

            return redirect()
                ->route('kmj.quotation')
                ->with('success', 'Quotation Verified Successfully! Email sent to insurer.');
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Error: ' . $e->getMessage(),
            ]);
        }
    }


}
