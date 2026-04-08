<?php

namespace App\Http\Controllers;

use App\Models\MuaCallback;
use App\Traits\ActivityLoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class MUAInsuranceController extends Controller
{
    use ActivityLoggableTrait;

    private string $baseUrl;
    private string $username = '58900';
    private string $password = '2021';

    public function __construct()
    {
        $this->baseUrl = 'http://172.20.32.40:58900';
    }

    /**
     * 1) SmartPolicy/New - Create Policy
     */
    public function createPolicy(Request $request)
    {


        // Hapa tunachukua field zote moja moja bila validator
        $data = [
            'api_token' => $request->api_token ?? "",
            'api_user' => $request->api_user ?? "",
            'debitNoteNo' => $request->debitNoteNo,
            'taxInvoiceNo' => $request->taxInvoiceNo,
            'taxInvoiceDate' => $request->taxInvoiceDate,
            'ESDData' => $request->ESDData ?? "",
            'transSource' => $request->transSource,
            'policyFromDate' => $request->policyFromDate,
            'policyExpiryDate' => $request->policyExpiryDate,
            'policyType' => $request->policyType,
            'policyCurrency' => $request->policyCurrency,
            'policyCurrencyRate' => $request->policyCurrencyRate,
            'taxAuthority' => $request->taxAuthority,
            'zrbVerificationCode' => $request->zrbVerificationCode,
            'branchID' => $request->branchID,
            'motorType' => $request->motorType,
            'firstLossPayee' => $request->firstLossPayee,
            'CustomerInfo' => [
                'clientCode' => $request->clientCode,
                'clientType' => $request->clientType,
                'clientName' => $request->clientName,
                'clientAddress' => $request->clientAddress,
                'clientOccupation' => $request->clientOccupation,
                'clientPhysicalAddress' => $request->clientPhysicalAddress,
                'clientMobile' => $request->clientMobile,
                'clientEmail' => $request->clientEmail ?? "",
                'clientTIN' => $request->clientTIN,
                'clientVRN' => $request->clientVRN ?? "",
                'clientIdType' => $request->clientIdType,
                'clientIdNumber' => $request->clientIdNumber,
                'clientRegion' => $request->clientRegion,
                'clientDistrict' => $request->clientDistrict,
                'clientDateBirth' => $request->clientDateBirth,
                'clientGender' => $request->clientGender,
            ],
            'BrokerInfo' => [
                'brokerCode' => $request->brokerCode,
                'brokerName' => $request->brokerName,
                'brokerAddress' => $request->brokerAddress,
                'brokerPhone' => $request->brokerPhone,
                'brokerEmail' => $request->brokerEmail,
                'brokerTIN' => $request->brokerTIN,
                'brokerVRN' => $request->brokerVRN,
            ],
            'brokerPolicyNumber' => $request->brokerPolicyNumber,
            'debitStatus' => $request->debitStatus,
            'totalPremiumAmount' => $request->totalPremiumAmount,
            'receiptNumber' => $request->receiptNumber,
            'receiptMode' => $request->receiptMode,
            'receiptReference' => $request->receiptReference,
            'receiptAmount' => $request->receiptAmount,
            'VehiclesCount' => $request->VehiclesCount,
            'VehiclesInfo' => [
                [
                    "riskNoteNo" => $request->riskNoteNo,
                    "registrationNo" => $request->registrationNo,
                    "vehicleMake" => $request->vehicleMake,
                    "vehicleModel" => $request->vehicleModel,
                    "vehicleCoverType" => $request->vehicleCoverType,
                    "vehicleUse" => $request->vehicleUse,
                    "vehicleType" => $request->vehicleType,
                    "vehicleColor" => $request->vehicleColor,
                    "vehicleEngineNo" => $request->vehicleEngineNo,
                    "vehicleChassisNo" => $request->vehicleChassisNo,
                    "vehicleStickerNo" => $request->vehicleStickerNo,
                    "vehicleManYear" => $request->vehicleManYear,
                    "vehicleCC" => $request->vehicleCC,
                    "vehiclePassangers" => $request->vehiclePassangers,
                    "vehicleValue" => $request->vehicleValue,
                    "vehicleWindScreen" => $request->vehicleWindScreen,
                    "vehicleWindScreenPremuim" => $request->vehicleWindScreenPremuim,
                    "vehicleWindScreenValue" => $request->vehicleWindScreenValue,
                    "vehicleRadio" => $request->vehicleRadio,
                    "vehiclePremiumRate" => $request->vehiclePremiumRate,
                    "vehicleOverridePremiumRate" => $request->vehicleOverridePremiumRate,
                    "vehiclePremium" => $request->vehiclePremium,
                    "vatAmount" => $request->vatAmount,
                    "grossPremium" => $request->grossPremium,
                    "tiraCoverNoteNb" => $request->tiraCoverNoteNb,
                    "tiraStickerNb" => $request->tiraStickerNb,
                    "vehicleGrossWeight" => $request->vehicleGrossWeight,
                    "vehicleTareWeight" => $request->vehicleTareWeight,
                    "vehicleNoOfAxles" => $request->vehicleNoOfAxles,
                    "vehicleAxleDistance" => $request->vehicleAxleDistance,
                    "vehicleOwnerCategory" => $request->vehicleOwnerCategory,
                    "vehicleMotorUsage" => $request->vehicleMotorUsage,
                    "vehicleMotorCategory" => $request->vehicleMotorCategory,
                    "vehicleModelNumber" => $request->vehicleModelNumber,
                    "vehicleFuelUsed" => $request->vehicleFuelUsed,
                    "vehicleOwnerName" => $request->vehicleOwnerName,
                    "vehicleOwnerAddress" => $request->vehicleOwnerAddress,
                    "prevCoverNoteNumber" => $request->prevCoverNoteNumber ?? "",
                ]
            ]
        ];

        // return response()->json($data);

        try {

            $response = Http::withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . '/SmartPolicy/New?portalSite=TZ', $data);

            return response()->json([
                'status' => true,
                'request' => $data,
                'data' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 2) SmartPolicy/TraReceipt
     */
    public function traReceipt(Request $request)
    {
        // UndTrans is policy number form createPolicy response
        $data = [
            'UndTrans' => $request->UndTrans,
        ];

        try {

            $response = Http::withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . '/SmartPolicy/TraReceipt?portalSite=TZ', $data);

            return response()->json([
                'status' => true,
                'data' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 3) SmartPolicy/NewVehVerify
     */
    public function newVehVerify(Request $request)
    {
        $data = [
            'VerifyRef' => $request->VerifyRef,
            'VehCategory' => $request->VehCategory,
            'VehRegistration' => $request->VehRegistration,
            'VehChassisNo' => $request->VehChassisNo ?? '',
        ];
        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . '/SmartPolicy/NewVehVerify?portalSite=TZ', $data);

            return response()->json([
                'status' => true,
                'data' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 4) SmartPolicy/VehVerify
     */
    public function vehVerify(Request $request)
    {
        $argVerifyRef = $request->argVerifyRef;

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->get($this->baseUrl . '/SmartPolicy/VehVerify?portalSite=TZ&argVerifyRef=' . $argVerifyRef);

            return response()->json([
                'status' => true,
                'data' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * 5) SmartPolicy/CBOTReceipt
     */
    public function cbotReceipt(Request $request)
    {
        $data = [
            'UndTrans' => $request->UndTrans,
        ];

        try {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->post($this->baseUrl . '/SmartPolicy/CBOTReceipt?portalSite=TZ', $data);

            return response()->json([
                'status' => true,
                'data' => $response->json()
            ], $response->status());
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }



    // Callback
    public function callback(Request $request)
    {
        try {
            // Save all request data as JSON
            $callback = MuaCallback::create([
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
            $callback = MuaCallback::orderBy('id', 'desc')->get();

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
