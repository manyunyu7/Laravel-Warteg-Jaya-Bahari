<?php

namespace App\Http\Controllers;

use App\Models\DataBangunanResto;
use Illuminate\Http\Request;

class DataBangunanController extends Controller
{
    public function saveLuasTanah(Request $request)
    {
        // Retrieve the request input for the specified fields
        $luas_bangunan = $request->input('panjang_bangunan', '0');
        $lebar_bangunan = $request->input('lebar_bangunan', '0');
        $luas_tanah = $request->input('panjang_tanah', '0');
        $lebar_tanah = $request->input('lebar_tanah', '0');

        // Check if a record with the specified resto_id already exists
        $resto_id = $request->restoId; // Provide the specific resto_id value you want to check
        $existingRecord = DataBangunanResto::where('resto_id', $resto_id)->first();

        if ($existingRecord) {
            // Update the existing record
        } else {
            $existingRecord = new DataBangunanResto();
        }

        $existingRecord->luas_bangunan = $luas_bangunan;
        $existingRecord->lebar_bangunan = $lebar_bangunan;
        $existingRecord->luas_tanah = $luas_tanah;
        $existingRecord->lebar_tanah = $lebar_tanah;

        if ($existingRecord->save()) {
            return response()->json([
                'message' => 'Data saved successfully',
                'status' => true
            ]);
        } else {
            return response()->json([
                'message' => 'Data saved successfully',
                'status' => false
            ]);
        }
    }
}
