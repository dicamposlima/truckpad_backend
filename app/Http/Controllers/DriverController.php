<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Builder;

use App\Driver;
use App\Track;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of Drivers.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            //$drivers = Track::with("drivers")->toSql();
            //$tracks = Track::where('has_truckload', 0)->get();
            //dd(\Illuminate\Support\Facades\DB::getQueryLog());
            // Retrieve posts with at least one comment containing words like foo%...
            $drivers = Driver::whereHas('tracks', function (Builder $query) {
                $query->where('has_truckload', 0);
            })->get();

            if(count($drivers)){
                return response()->json([
                    "status" => 200,
                    "type" => "success",
                    "data" => $drivers
                ], 200);
            } else {
                return response()->json([
                    "status" => 200,
                    "type" => "success",
                    "data" => []
                ], 200);
            }
        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "type" =>  "failure",
                "title" => "Internal Server Error",
                "detail" => $e->getMessage()
            ], 500);
        }
    }
}
