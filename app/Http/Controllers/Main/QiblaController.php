<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class QiblaController extends Controller
{
    public function getQibla($lat,$long) 
    {
        $client = new Client();
        $uri = "http://api.aladhan.com/v1/qibla/".$lat."/".$long;

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
