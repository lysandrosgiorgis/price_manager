<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class EnterSoftController extends Controller
{
    private
        $apiUrl = 'https://api.entersoft.gr/api/',      // The API URL
        $subscriptionID = 'karvelasnet',                // The subscription ID
        $SubscriptionPassword = 'Kar2020!',             // The subscription password
        $BridgeID = 'karvelas',                         // The bridge ID
        $BranchID = '001',                              // The branch ID
        $LangID = 'en-US',                              // The selected language
        $UserID = 'Webapi2',                            // The userID
        $Password = 'Wbp2024!',                         // The userID
        $ESApplicationID = 'ecomprofessionalconnector'; // The application ID

    public function debug(){
        $this->login();
    }

    private function login(){
//        $apiCredentials = DB::table('api_credentials')->where([
//            ['api_name', '=', 'entersoft'],
//            ['api_field', '=', 'WebApiToken'],
//            ['expiration', '>', time()],
//        ])->first();
//
//        if($apiCredentials) return $apiCredentials->api_value;

        DB::table('api_credentials')->where([
            ['api_name', '=', 'entersoft'],
            ['api_field', '=', 'WebApiToken'],
        ])->delete();

        $apiFunction = 'login/login';
        $postArgs = [
            'SubscriptionID' => $this->subscriptionID,
            'SubscriptionPassword' => $this->SubscriptionPassword,
            'BridgeID' => $this->BridgeID,
            'Model' => [
                'BranchID' => $this->BranchID,
                'LangID' => $this->LangID,
                'UserID' => $this->UserID,
                'Password' => $this->Password,
            ],
            "Claims" => [
                'ESApplicationID' => $this->ESApplicationID
            ]
        ];

        $response = $this->makeApiCall($apiFunction, 'POST', [], $postArgs);

        DB::table('api_credentials')->insert([
            'api_name' => 'entersoft',
            'api_field' => 'WebApiToken',
            'api_value' => $response['Model']['WebApiToken'],
            'expiration' => date('Y-m-d H:i:s', strtotime($response['Model']['WebApiTokenExpiresAt'])),
        ]);

        echo '<pre>';
        print_r($response);
        echo '</pre>';

        return $response['Model']['WebApiToken'];
    }

    private function makeApiCall($apiFunction = '', $method = 'GET', $getArgs = [], $postArgs = []){
        $url = $this->apiUrl . $apiFunction;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);

        if($method == 'POST'){
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($postArgs));
        }

        $response = curl_exec($curl);
        curl_close($curl);

        return json_decode($response, true);
    }
}
