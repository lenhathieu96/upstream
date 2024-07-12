<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testCallApi()
    {
        $logTestingUrl = config('upstream.HEROMARKET_URL') . '/api/v2/auth/log-tesing-api';
        try {
            $response = Http::withOptions(['verify' => false])->post($logTestingUrl, ['email' => 'test@farm-angel.com']);

            $response = json_decode($response->getBody(), true);
            
            return $response['message'] ?? 'success default';
        } catch (\Exception $exception) {
            \Log::info($exception->getMessage());
        }
    }

    public function testPhoneExist($phone)
    {
        return is_phone_exist_in_hero($phone) ? 'phone exist' : 'phone is available';
    }

    public function testEmailExist($email)
    {
        return is_email_exist_in_hero($email) ? 'email exist' : 'email is available';
    }
}
