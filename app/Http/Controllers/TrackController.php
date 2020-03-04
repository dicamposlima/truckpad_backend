<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Track;
use App\Driver;
use Illuminate\Http\Request;

class TrackController extends Controller
{

    /**
     * Display a listing of Tracking
     *
     * @return \Illuminate\Http\Response
     */
    public function tracking()
    {
        try {

           $tracks = Driver::whereHas('tracks', function (Builder $query) {
                $query->where('on_way', 1);
            })->with("tracks")->get();

            return response()->json([
                "status" => 200,
                "type" => "success",
                "data" => count($tracks) ? $tracks : []
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                "status" => 500,
                "type" =>  "failure",
                "title" => "Internal Server Error",
                "detail" => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created Driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if($validator = $this->validateData($request)) {
            return response()->json([
                "status" => 400,
                "type" =>  "failure",
                "title" => "Validation errors",
                "detail" => $validator->errors()->all()
            ], 400);
        }

       try {
            $track = new Track();
            $track = $this->setDataRequest($request, $track);

            if($track->save()) {
                return response()->json([
                    "status" => 201,
                    'data' => "Saved successfully",
                ], 201);
            } else {
                return response()->json([
                    "status" => 400,
                    "type" =>  "failure",
                    "title" => "Saving data",
                    "detail" => "Erro saving data, please try again."
                ], 400);
            }
        } catch (Exception $e) {
            return response()->json([
                "status" => 500,
                "type" =>  "failure",
                "title" => "Internal Server Error",
                "detail" => $e->getMessage()
            ], 500);
        }
    }

     /**
     * Validate de data according to the rules below.
     * @param  \Illuminate\Http\Request  $request
     * @return bool | array
     */
    private function validateData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "payload.latitude" => [
                "required",
                "max:180",
            ],
            "payload.longitude" => [
                "required",
                "max:180",
            ],
            "payload.on_way" => "required|integer|between:0,1",
            "payload.has_truckload" => "required|integer|between:0,1",
            "payload.driver_id" => "required|integer|exists:drivers,id",
            "payload.type_id" => "required|integer|exists:types,id",
        ]);
        if ($validator->fails()) {
            return $validator;
        }
    }

    /**
     * Set Data from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Track $track|null
     */
    private function setDataRequest(Request $request, Track $track = null)
    {
        if(!$track)
            $track = [];

        $track["latitude"] = $request->payload["latitude"];
        $track["longitude"] = $request->payload["longitude"];
        $track["on_way"] = $request->payload["on_way"];
        $track["has_truckload"] = $request->payload["has_truckload"];
        $track["driver_id"] = $request->payload["driver_id"];
        $track["type_id"] = $request->payload["type_id"];

        return $track;
    }
}
