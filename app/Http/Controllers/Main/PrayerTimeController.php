<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PrayerTimeController extends Controller
{
    public function getPrayTime()
    {
        $client = new Client();
        $uri = "https://api.aladhan.com/v1/timings/1656852244596?latitude=37.5640455&longitude=126.8340033&method=11";

        $response = $client->request('GET', $uri, [
            'verify' => false,
        ]);

        $body = json_decode($response->getBody());
        $data = $body->data;

        if (filter_var($uri, FILTER_VALIDATE_URL) == false) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => $uri." is not a valid URL", 
                'data' => null
            ]);
        }else{
            return $data;
        }
    }
}
