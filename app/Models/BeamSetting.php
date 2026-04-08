<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SmsResponse;

class BeamSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'api_key',
        'secret_key',
        'base_url',
        'source_address',
        'created_by',
    ];

    public static function formatPhoneNumber($phoneNumber) {
        // Remove any non-numeric characters
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

        // Check if the phone number starts with "255" and has 12 digits
        if (substr($phoneNumber, 0, 3) === "255" && strlen($phoneNumber) == 12) {
            return $phoneNumber;
        }

        // If the phone number has 13 digits and starts with "255"
        if (strlen($phoneNumber) == 13) {
            if (substr($phoneNumber, 0, 3) === "255") {
                // Extract the country code and the next nine digits
                $countryCode = substr($phoneNumber, 0, 3);
                $nextNine = substr($phoneNumber, 3, 9);
                $phoneNumber = $countryCode . $nextNine;
                return $phoneNumber;
            }

            // If the phone number has "2550" prefix (e.g., 2550623544601)
            if (substr($phoneNumber, 0, 4) === "2550") {
                // Extract the country code and the next nine digits
                $countryCode = substr($phoneNumber, 0, 3);
                $nextNine = substr($phoneNumber, 4, 9);
                $phoneNumber = $countryCode . $nextNine;
                return $phoneNumber;
            }
        }

        // If the phone number has 10 digits (e.g., 0652543553)
        if (strlen($phoneNumber) == 10) {
            $countryCode = "255";
            $nextNine = substr($phoneNumber, 1, 9);
            $phoneNumber = $countryCode . $nextNine;
            return $phoneNumber;
        }

        // If the phone number has 9 digits (e.g., 652543553)
        if (strlen($phoneNumber) == 9) {
            $countryCode = "255";
            $nextNine = substr($phoneNumber, 0, 9);
            $phoneNumber = $countryCode . $nextNine;
            return $phoneNumber;
        }

        // Return the phone number if none of the above conditions match
        return $phoneNumber;
    }



    public static function formatMessage($message) {
        // Trim leading and trailing whitespace
        $message = trim($message);
        $message = preg_replace('/\s+/', ' ', $message);

        $message = str_replace(["'", '\r'], ['"', "\r"], $message);
        $message = str_replace(["'", '\n'], ['"', "\n"], $message);

        return $message;
    }


    public static function send_sms($message= '' , $phoneNumbers = [],$creatorId="") {

        $sms_settings=BeamSetting::first();
        if(!$sms_settings){
            return [
                'status' => 500,
                'message' => "Sms Settings not set"
            ];
        }

        $api_key=$sms_settings->api_key;
        $secret_key=$sms_settings->secret_key;
        $source_address=$sms_settings->source_address;

        $headers=[
            'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
            'Content-Type: application/json'
        ];

        $recipients = [];
        $recipientId = 1;
        foreach($phoneNumbers as $number){
            $receiver_phone =self::formatPhoneNumber($number);
            $recipients[] = [
                'recipient_id' => $recipientId,
                'dest_addr' => $receiver_phone
            ];
            $recipientId++;

            //return ['status'=>500,'message'=>$receiver_phone];
        }
        $message=self::formatMessage($message);

        $postData =[
            'source_addr' => $source_address,
            'encoding'=>"0",
            'schedule_time' => '',
            'message' => $message,
            'recipients'=>$recipients,

        ];


        $Url ='https://apisms.beem.africa/v1/send';

        $ch = curl_init($Url);
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_POST => TRUE,
            CURLOPT_RETURNTRANSFER => TRUE,
            CURLOPT_HTTPHEADER => $headers,
            CURLOPT_POSTFIELDS => json_encode($postData)
        ));

        $response = curl_exec($ch);
        if ($response === FALSE) {
            return ['status' => 500, 'message' => 'Curl error: ' . curl_error($ch)];
        }

        $response=json_decode($response,TRUE);


        ## save response
        $res = [];
        $res['numbers'] = json_encode($phoneNumbers);
        $res['successful'] = $response['successful'] ?? "";
        $res['request_id'] = $response['request_id'] ?? "";
        $res['code'] = $response['code'] ?? "";
        $res['message'] = $response['message'] ?? "";
        $res['valid'] = $response['valid'] ?? "";
        $res['invalid'] = $response['invalid'] ?? "";
        $res['duplicates'] = $response['duplicates'] ?? "";
        $res['created_by'] = $creatorId ?? 0;

        // Insert using the model
        SmsResponse::create($res);

        if($response['code']==100){
            return ['status'=>200,'message'=>$response['message']];
        }
        else{
            return ['status'=>500,'message'=>$response['message']];
        }

    }

    public static function delivery_report($number,$request_id){
        $creatorId=Auth::user()->id;
        $receiver_phone = self::formatPhoneNumber($number);
        $sms_settings=BeamSetting::first();
        $api_key=$sms_settings->api_key;
        $secret_key=$sms_settings->secret_key;
        $dest_addr =$receiver_phone;
        $request_id = $request_id;

        $headers=[
            'Authorization:Basic ' . base64_encode("$api_key:$secret_key"),
            'Content-Type: application/json'
        ];

        $URL = 'https://dlrapi.beem.africa/public/v1/delivery-reports';


        $body = array('request_id' => $request_id,'dest_addr' => $dest_addr);

        // Setup cURL
        $ch = curl_init();
        error_reporting(E_ALL);
        ini_set('display_errors', 1);

        $URL = $URL . '?' . http_build_query($body);

        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt_array($ch, array(
            CURLOPT_HTTPGET => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => $headers,
        ));

        // Send the request
        $response = curl_exec($ch);
        $response=json_decode($response,TRUE);

        return $response;

    }

    public static function getBalance(){
        // Fetch SMS settings from database
        $creatorId=Auth::user()->id;
        $sms_settings = BeamSetting::first();
        if($sms_settings){
            $api_key = $sms_settings->api_key ?? "";
            $secret_key = $sms_settings->secret_key ?? "";

            // Setup headers
            $headers = [
                'Authorization: Basic ' . base64_encode("$api_key:$secret_key"),
                'Content-Type: application/json'
            ];

            // Set the URL for the API endpoint
            $URL = 'https://apisms.beem.africa/public/v1/vendors/balance';

            // Initialize cURL
            $ch = curl_init();

            // Set cURL options
            curl_setopt($ch, CURLOPT_URL, $URL);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            // Send the request and decode the response
            $response = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // Decode the JSON response
            $response = json_decode($response, true);

            if ($http_code == 200) {
                return [
                    'status' => 200,
                    'data' => $response['data']
                ];
            } else {
                $error_message = isset($response['message']) ? $response['message'] : 'An unexpected error occurred';
                return [
                    'status' => 500,
                    'message' => $error_message
                ];
            }

            return $response;
        }

        return [
            'status' => 500,
            'message' => "Sms Settings not set"
        ];
    }
}
