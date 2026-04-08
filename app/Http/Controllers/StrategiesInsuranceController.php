<?php

namespace App\Http\Controllers;

use App\Models\StrategyCallback;
use App\Traits\ActivityLoggableTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Throwable;

class StrategiesInsuranceController extends Controller
{
    use ActivityLoggableTrait;


    /**
     * Base URL ya Premia (Azentio)
     */
    private string $baseUrl = 'http://192.168.1.7:9080/STGS';

    private string $baseUrl1 = 'http://192.168.1.7:9080/STGS/convertToXML';


    /**
     * ================================
     * STRATEGY 1: CREATE ASSURED
     * ================================
     */
    public function createAssured(Request $request)
    {

        try {
            $payload = [
                'PCOM_ASSURED' => [
                    'ASSR_NAME'       => $request->ASSR_NAME,
                    'ASSR_DOB'        => $request->ASSR_DOB,
                    'ASSR_PHONE'     => $request->ASSR_PHONE,
                    'ASSR_TYPE'      => $request->ASSR_TYPE,
                    'ASSR_PAN_NO'    => $request->ASSR_PAN_NO,
                    'ASSR_ADDR_01'   => $request->ASSR_ADDR_01,
                    'ASSR_CUST_CODE' => $request->ASSR_CUST_CODE,
                    'ASSR_CIVIL_ID'  => $request->ASSR_CIVIL_ID,
                    'ASSR_SSN_NO'    => $request->ASSR_SSN_NO,
                    'ASSR_EMAIL_1'   => $request->ASSR_EMAIL_1 ?? '',
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/convertXML', $payload);

            return response()->json([
                'strategy' => 'ASSURED_CREATION',
                'status'   => 'SUCCESS',
                'request'  => $payload,
                'response' => $response->json(),
            ]);
        } catch (Throwable $e) {

            return response()->json([
                'strategy' => 'ASSURED_CREATION',
                'status'   => 'FAILED',
                'error'    => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ================================
     * STRATEGY 2: CREATE POLICY
     * ================================
     */
    public function createPolicy(Request $request)
    {
        /**
         * ASSR CODE inatakiwa kutoka Strategy ya kwanza
         * unaweza kuipokea kama param au kuhardcode kwa test
         */

        try {
            $assrCode = $request->assr_code;

            $payload = [
                'PGIT_POL_RISK_ADDL_INFO' => [
                    'PGIT_POL_RISK_ADDL_INFO_01' => [[
                        'RISKINFO' => [[
                            'PRAI_EFF_FM_DT' => $request->PRAI_EFF_FM_DT,
                            'PRAI_EFF_TO_DT' => $request->PRAI_EFF_TO_DT,
                            'PRAI_RISK_ID'   => $request->PRAI_RISK_ID,
                            'PRAI_CODE_21'   => $request->PRAI_CODE_21,
                            'PRAI_CODE_03'   => $request->PRAI_CODE_03,
                            'PRAI_CODE_04'   => $request->PRAI_CODE_04,

                            // UNIQUE VALUES
                            'PRAI_DATA_01' =>  $request->PRAI_DATA_01,
                            'PRAI_DATA_05' =>  $request->PRAI_DATA_05,
                            'PRAI_DATA_03' =>  $request->PRAI_DATA_03,
                            'PRAI_DATA_02' =>  $request->PRAI_DATA_02,

                            'PRAI_NUM_09'    => $request->PRAI_NUM_09,
                            'PRAI_NUM_03'    => $request->PRAI_NUM_03,
                            'PRAI_CODE_01'   => $request->PRAI_CODE_01,
                            'PRAI_NUM_01'    => $request->PRAI_NUM_01,
                            'PRAI_CODE_13'   => $request->PRAI_CODE_13,
                            'PRAI_NUM_02'    => $request->PRAI_NUM_02,
                            'PRAI_NUM_04'    => $request->PRAI_NUM_04,
                            'PRAI_CODE_05'   => $request->PRAI_CODE_05,
                            'PRAI_PREM_FC'   => $request->PRAI_PREM_FC,
                        ]]
                    ]]
                ],

                'PGIT_POLICY' => [
                    'POL_DIVN_CODE'         => $request->POL_DIVN_CODE,
                    'POL_PROD_CODE'         => $request->POL_PROD_CODE,
                    'POL_CUST_CODE'         => $request->POL_CUST_CODE,
                    'POL_ASSR_CODE'         => $assrCode,
                    'POL_SRC_CODE'          => $request->POL_SRC_CODE,
                    'POL_ISSUE_DT'          => $request->POL_ISSUE_DT,
                    'POL_SRC_TYPE'          => $request->POL_SRC_TYPE,
                    'POL_FLEX_14'           => $request->POL_FLEX_14,
                    'POL_FLEX_13'           => $request->POL_FLEX_13,
                    'POL_FLEX_17'           => $request->POL_FLEX_17,
                    'POL_PREM_CURR_CODE'    => $request->POL_PREM_CURR_CODE,
                    'POL_DFLT_SI_CURR_CODE' => $request->POL_DFLT_SI_CURR_CODE,
                    'POL_FM_DT'             => $request->POL_FM_DT,
                    'POL_TO_DT'             => $request->POL_TO_DT,
                ],
                "RECEIPT" => [
                    "RECEIPT_MODE" => $request->RECEIPT_MODE,
                    "BANK_CODE" => $request->BANK_CODE,
                    "BANK_NAME" => $request->BANK_NAME,
                    "RECEIPT_REF_NO" => $request->RECEIPT_REF_NO,
                ]
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post($this->baseUrl . '/convertToXML', $payload);

            return response()->json([
                'strategy' => 'POLICY_CREATION',
                'status'   => 'SUCCESS',
                'request'  => $payload,
                'response' => $response->json(),
            ]);
        } catch (Throwable $e) {


            return response()->json([
                'strategy' => 'POLICY_CREATION',
                'status'   => 'FAILED',
                'error'    => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * ================================
     * STRATEGY 3: FULL FLOW (ASSURED → POLICY)
     * ================================
     */
    public function fullStrategyFlow()
    {
        // STEP 1: ASSURED
        try {
            $assuredResponse = Http::post(
                $this->baseUrl . '/convertXML',
                [
                    'PCOM_ASSURED' => [
                        'ASSR_NAME'       => 'Kelvin',
                        'ASSR_DOB'        => '28 FEB 2000',
                        'ASSR_PHONE'     => '868787766',
                        'ASSR_TYPE'      => '01',
                        'ASSR_PAN_NO'    => '767546',
                        'ASSR_ADDR_01'   => 'ITALY',
                        'ASSR_CUST_CODE' => 'DC0000008',
                        'ASSR_CIVIL_ID'  => '33356345',
                        'ASSR_SSN_NO'    => '876567',
                        'ASSR_EMAIL_1'   => '',
                    ]
                ]
            )->json();

            if (($assuredResponse['P_STATUS'] ?? '') !== 'Success') {
                return response()->json([
                    'step' => 'ASSURED',
                    'error' => $assuredResponse
                ], 400);
            }

            $assrCode = $assuredResponse['P_ASSR_CODE'];

            // STEP 2: POLICY
            $policyResponse = Http::post(
                $this->baseUrl . '/convertToXML',
                [
                    'PGIT_POL_RISK_ADDL_INFO' => [
                        'PGIT_POL_RISK_ADDL_INFO_01' => [[
                            'RISKINFO' => [[
                                'PRAI_EFF_FM_DT' => '28 FEB 2025',
                                'PRAI_EFF_TO_DT' => '26 FEB 2026',
                                'PRAI_RISK_ID'   => '1',
                                'PRAI_DATA_01'   => 'VROSSI' . rand(1000, 9999),
                                'PRAI_DATA_05'   => '10153' . rand(1000, 9999),
                                'PRAI_DATA_03'   => 'VROSSI' . rand(1000, 9999),
                                'PRAI_DATA_02'   => 'VROSSI' . rand(1000, 9999),
                                'PRAI_PREM_FC'   => '1000',
                            ]]
                        ]]
                    ],
                    'PGIT_POLICY' => [
                        'POL_PROD_CODE' => '1002',
                        'POL_CUST_CODE' => 'CC0000554',
                        'POL_ASSR_CODE' => $assrCode,
                        'POL_SRC_CODE'  => 'AG0000002',
                        'POL_ISSUE_DT'  => '28 FEB 2025',
                        'POL_SRC_TYPE'  => '2',
                        'POL_FM_DT'     => '28 FEB 2025',
                        'POL_TO_DT'     => '27 FEB 2026',
                    ]
                ]
            )->json();

            return response()->json([
                'strategy' => 'FULL_STRATEGY',
                'status'   => 'SUCCESS',
                'assured' => $assuredResponse,
                'policy'  => $policyResponse
            ]);
        } catch (Throwable $e) {


            return response()->json([
                'strategy' => 'FULL_STRATEGY',
                'status'   => 'FAILED',
                'error'    => $e->getMessage(),
            ], 500);
        }
    }

    public function callback(Request $request)
    {
        try {
            // Save all request data as JSON
            $callback = StrategyCallback::create([
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
            $callback = StrategyCallback::orderBy('id', 'desc')->get();

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
