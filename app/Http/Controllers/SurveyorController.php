<?php

namespace App\Http\Controllers;

use App\Models\Restoran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class SurveyorController extends Controller
{
    public function testing()
    {
        Artisan::call('cache:clear');
        Artisan::call('optimize');
        Artisan::call('route:cache');
        Artisan::call('route:clear');
        Artisan::call('view:clear');
        Artisan::call('config:cache');
        Artisan::call('config:clear');
        return "All Cache Cleared !!!";

        $restorans = Restoran::all()->filter(function ($restoran) {
            return !is_null($restoran->data_bangunan);
        })->values();

        return $restorans;



    }
}
