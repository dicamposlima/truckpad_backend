<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

use App\Driver;
use App\Track;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Display a listing of Drivers
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $drivers = Driver::orderBy("name")->get();

            return response()->json([
                "status" => 200,
                "type" => "success",
                "data" => count($drivers) ? $drivers : []
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
     * Display a listing of Drivers which has no truckload.
     *
     * @return \Illuminate\Http\Response
     */
    public function hasTruckloadList()
    {
        try {
            $drivers = Driver::orderBy("name")->whereHas('tracks', function (Builder $query) {
                $query->where('has_truckload', 0);
            })->get();

            return response()->json([
                "status" => 200,
                "type" => "success",
                "data" => count($drivers) ? $drivers : []
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
     * Display the quantity of Drivers who have vehicles.
     *
     * @return \Illuminate\Http\Response
     */
    public function hasVehiclesQtd()
    {
        try {
            $drivers = Driver::where('has_vehicles', 1)->get();

            return response()->json([
                "status" => 200,
                "type" => "success",
                "data" => count($drivers) ? $drivers->count() : 0
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
            $driver = new Driver();
            $driver = $this->setDataRequest($request, $driver);

            if($driver->save()) {
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
     * Update the Driver.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
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
            $driver = Driver::where('id', $id)->first();
            if(!$driver) {
                return response()->json([
                    "status" => 400,
                    "type" =>  "failure",
                    "title" => "Saving data",
                    "detail" => "Erro saving data, please try again."
                ], 400);
            }
            $driverData = $this->setDataRequest($request);
            if($driver->update($driverData)){
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
     * Update the Driver status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(Request $request, $id)
    {
        try {
            $driver = Driver::where('id', $id)->first();
            if(!$driver) {
                return response()->json([
                    "status" => 400,
                    "type" =>  "failure",
                    "title" => "Saving data",
                    "detail" => "Erro saving data, please try again."
                ], 400);
            }

            if($driver->update(["active" => $request->payload["active"]])){
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
            "payload.name" => [
                "required",
                "min:3",
            ],
            "payload.cpf" => [
                "required",
                "min:11",
            ],
            "payload.cnh" => [
                "required",
                "min:11",
            ],
            "payload.date_of_birth" => [
                "required",
            ],
            "payload.phone" => [
                "integer"
            ],
            "payload.gender" => [
                "required",
                "string",
                Rule::in(['M', 'F', 'ND']),
            ],
            "payload.has_vehicles" => "required|integer|between:0,1",
            "payload.cnh_type" => [
                "required",
                "string",
                Rule::in(['A', 'B', 'C', 'D', 'E']),
            ]
        ]);
        if ($validator->fails()) {
            return $validator;
        }
    }

    /**
     * Set Data from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Hotel $driver|null
     */
    private function setDataRequest(Request $request, Driver $driver = null)
    {
        if(!$driver)
            $driver = [];

        $driver["name"] = $request->payload["name"];
        $driver["date_of_birth"] = $request->payload["date_of_birth"];
        $driver["gender"] = $request->payload["gender"];
        $driver["has_vehicles"] = $request->payload["has_vehicles"];
        $driver["cnh_type"] = $request->payload["cnh_type"];
        $driver["cnh"] = $request->payload["cnh"];
        $driver["cpf"] = $request->payload["cpf"];
        $driver["phone"] = isset($request->payload["phone"]) ? $request->payload["phone"] : null;

        return $driver;
    }
}
