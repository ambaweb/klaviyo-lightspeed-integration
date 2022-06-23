<?php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class LightSpeedApi 
{
    const TOKEN_END_POINT = 'https://cloud.lightspeedapp.com/oauth/access_token.php';
    const CUSTOMER_END_POINT = 'https://api.lightspeedapp.com/API/V3/Account/145982/Customer.json';

    public static function getToken()
    {
        return Http::post(self::TOKEN_END_POINT,[
            "refresh_token" => env('LIGHT_SPEED_REFRESH_TOKEN'),
            "client_id" => env('LIGHT_SPEED_CLIENT_ID'),
            "client_secret" => env('LIGHT_SPEED_CLIENT_SECRET'),
            "grant_type" => "refresh_token"
        ]);
    }


    public static function createCustomer($data)
    {
        $tokenResponse = self::getToken();

        if ( !$tokenResponse->successful() ) {
            return ['status' => $tokenResponse->status(), 'message' => $tokenResponse['error_description']];
        }

        $response = Http::withToken($tokenResponse->json('access_token'))
        ->post(self::CUSTOMER_END_POINT, $data);

        $message = ($response->successful()) ? 'Customer Created' : $response['error_description'];

        return ['status' => $response->status() , 'message' => $message];
    }
    
}