<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class PrayerTimeController extends Controller
{
    public function getPrayTime($city)
    {
        $client = new Client();
        $uri = "https://api.pray.zone/v2/times/today.json?city=".$city;

        $response = $client->request('GET', $uri, [
            'verify' => false,
        ]);

        if (filter_var($uri, FILTER_VALIDATE_URL) == false) {
            return response()->json([
                'success' => false,
                'code' => 404,
                'message' => $uri." is not a valid URL", 
                'data' => null
            ]);
        }else{
            return json_decode($response->getBody());
        }
    }
}
