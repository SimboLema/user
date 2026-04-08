<?php

namespace App\Http\Controllers;

use App\Models\SanlamCallback;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SanlamController extends Controller
{
    // private $url = "https://155.12.76.11:8443/api/v2/non-motor/transaction";
    private $url = "https://155.12.76.11:8443/api/v2/motor/transaction";

    private $apiKey = "xkC7BF7TDCJLX86xQ80SAZcQrpD933DSU3zxroyjJHrB=";

    /**
     * NON FLEET
     */
    public function submitNonFleet(Request $request)
    {
        $payload = [

            "RequestId" => $request->input("RequestId"),
            "SystemCode" => $request->input("SystemCode"),
            "BrokerId" => $request->input("BrokerId"),
            "BranchId" => $request->input("BranchId"),
            "Product" => $request->input("Product"),
            "FleetEntryNo" => $request->input("FleetEntryNo"),
            "TiraProductCode" => $request->input("TiraProductCode"),
            "DebitNoteNumber" => $request->input("DebitNoteNumber"),
            "FleetId" => $request->input("FleetId"),
            "ReceiptNo" => $request->input("ReceiptNo"),
            "ReceiptReferenceNo" => $request->input("ReceiptReferenceNo"),
            "ReceiptAmount" => $request->input("ReceiptAmount"),
            "ReceiptDate" => $request->input("ReceiptDate"),
            "BankCode" => $request->input("BankCode"),
            "IssueDate" => $request->input("IssueDate"),
            "CoverNoteStartDate" => $request->input("CoverNoteStartDate"),
            "CoverNoteEndDate" => $request->input("CoverNoteEndDate"),
            "PaymentMode" => $request->input("PaymentMode"),
            "CurrencyCode" => $request->input("CurrencyCode"),
            "CustomerName" => $request->input("CustomerName"),
            "CustomerBirthDate" => $request->input("CustomerBirthDate"),
            "CustomerIdType" => $request->input("CustomerIdType"),
            "CustomerIdNumber" => $request->input("CustomerIdNumber"),
            "CustomerTIN" => $request->input("CustomerTIN"),
            "CustomerVRN" => $request->input("CustomerVRN"),
            "CustomerGender" => $request->input("CustomerGender"),
            "CustomerCountry" => $request->input("CustomerCountry"),
            "CustomerRegion" => $request->input("CustomerRegion"),
            "CustomerDistrict" => $request->input("CustomerDistrict"),
            "CustomerPhoneNumber" => $request->input("CustomerPhoneNumber"),
            "CustomerAddress" => $request->input("CustomerAddress"),
            "CustomerEmailAddress" => $request->input("CustomerEmailAddress"),
            "CustomerType" => $request->input("CustomerType"),
            "CallbackUrl" => url('/api/sanlam/callback'),
            "CoverType" => $request->input("CoverType"),
            "UsageType" => $request->input("UsageType"),
            "RiskNoteNumber" => $request->input("RiskNoteNumber"),
            "IsVATExempt" => $request->input("IsVATExempt"),
            "SumInsured" => $request->input("SumInsured"),
            "PremiumExcludingVat" => $request->input("PremiumExcludingVat"),
            "VatPercentage" => $request->input("VatPercentage"),
            "VatAmount" => $request->input("VatAmount"),
            "PremiumIncludingVat" => $request->input("PremiumIncludingVat"),
            "TiraRiskCode" => $request->input("TiraRiskCode"),
            "TiraStickerNumber" => $request->input("TiraStickerNumber"),
            "TiraCoverNoteReferenceNumber" => $request->input("TiraCoverNoteReferenceNumber"),
            "RegistrationNumber" => $request->input("RegistrationNumber"),
            "BodyType" => $request->input("BodyType"),
            "SeatingCapacity" => $request->input("SeatingCapacity"),
            "ChassisNumber" => $request->input("ChassisNumber"),
            "Make" => $request->input("Make"),
            "Model" => $request->input("Model"),
            "ModelNumber" => $request->input("ModelNumber"),
            "Color" => $request->input("Color"),
            "EngineNumber" => $request->input("EngineNumber"),
            "EngineCapacity" => $request->input("EngineCapacity"),
            "FuelUsed" => $request->input("FuelUsed"),
            "NumberOfAxles" => $request->input("NumberOfAxles"),
            "AxleDistance" => $request->input("AxleDistance"),
            "YearOfManufacture" => $request->input("YearOfManufacture"),
            "TareWeight" => $request->input("TareWeight"),
            "GrossWeight" => $request->input("GrossWeight"),
        ];


        $response = Http::withHeaders([
            "Authorization" => "Api-Key " . $this->apiKey,
            "Content-Type" => "application/json"
        ])
            ->withoutVerifying()
            ->post($this->url, $payload);


        return response()->json([
            "status" => $response->status(),
            "response" => $response->json(),
            'request' => $payload,
        ]);
    }

    /**
     * FLEET
     */
    public function submitFleet(Request $request)
    {
        $payload = [

            "RequestId" => $request->input("RequestId"),
            "SystemCode" => $request->input("SystemCode"),
            "BrokerId" => $request->input("BrokerId"),
            "BranchId" => $request->input("BranchId"),
            "Product" => $request->input("Product"),
            "TiraProductCode" => $request->input("TiraProductCode"),
            "DebitNoteNumber" => $request->input("DebitNoteNumber"),
            "FleetSize" => $request->input("FleetSize"),
            "FleetId" => $request->input("FleetId"),
            "ReceiptNo" => $request->input("ReceiptNo"),
            "ReceiptReferenceNo" => $request->input("ReceiptReferenceNo"),
            "ReceiptAmount" => $request->input("ReceiptAmount"),
            "ReceiptDate" => $request->input("ReceiptDate"),
            "BankCode" => $request->input("BankCode"),
            "IssueDate" => $request->input("IssueDate"),
            "CoverNoteStartDate" => $request->input("CoverNoteStartDate"),
            "CoverNoteEndDate" => $request->input("CoverNoteEndDate"),
            "PaymentMode" => $request->input("PaymentMode"),
            "CurrencyCode" => $request->input("CurrencyCode"),
            "CustomerName" => $request->input("CustomerName"),
            "CustomerBirthDate" => $request->input("CustomerBirthDate"),
            "CustomerIdType" => $request->input("CustomerIdType"),
            "CustomerIdNumber" => $request->input("CustomerIdNumber"),
            "CustomerTIN" => $request->input("CustomerTIN"),
            "CustomerVRN" => $request->input("CustomerVRN"),
            "CustomerGender" => $request->input("CustomerGender"),
            "CustomerCountry" => $request->input("CustomerCountry"),
            "CustomerRegion" => $request->input("CustomerRegion"),
            "CustomerDistrict" => $request->input("CustomerDistrict"),
            "CustomerPhoneNumber" => $request->input("CustomerPhoneNumber"),
            "CustomerAddress" => $request->input("CustomerAddress"),
            "CustomerEmailAddress" => $request->input("CustomerEmailAddress"),
            "CustomerType" => $request->input("CustomerType"),
            "CallbackUrl" => url('/api/sanlam/callback'),

            "RiskDetails" => $request->input("RiskDetails")

        ];


        $response = Http::withHeaders([
            "Authorization" => "Api-Key " . $this->apiKey,
            "Content-Type" => "application/json"
        ])
            ->withoutVerifying()
            ->post($this->url, $payload);


        return response()->json([
            "status" => $response->status(),
            "response" => $response->json(),
            'request' => $payload,
        ]);
    }

    // Callback
    public function callback(Request $request)
    {
        try {
            // Save all request data as JSON
            $callback = SanlamCallback::create([
                'payload' => $request->all(),
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Callback received successfully',
                'data' => $callback
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getCallback()
    {
        try {
            // Save all request data as JSON
            $callback = SanlamCallback::orderBy('id', 'desc')->get();

            return response()->json([
                'status' => true,
                'message' => 'Callback retrived successfully',
                'data' => $callback
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
