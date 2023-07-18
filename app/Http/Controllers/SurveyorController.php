<?php

namespace App\Http\Controllers;

use App\Models\Restoran;
use Illuminate\Http\Request;

class SurveyorController extends Controller
{
    public function testing()
    {
        $restorans = Restoran::all()->filter(function ($restoran) {
            return !is_null($restoran->data_bangunan);
        })->values();

        return $restorans;
    }
}
