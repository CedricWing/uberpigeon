<?php

/*****************************************************************
Name: Cedric Wing

File: PigeonController.php

Description: This controller manages incoming pigeon 
related HTTP requests. It does validation and passes data 
to Pigeon service for processing.
******************************************************************/
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\PigeonService;

use Validator;

class PigeonController extends Controller
{
    protected $pigeonService;
    
    public function __construct(PigeonService $pigeonService)
    {
        $this->pigeonService = $pigeonService;
    }

    public function add(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),[
                'name' => 'required|alpha_num|max:50',
                'cost' => 'required|integer|numeric|min:0|digits_between:1,10',
                'speed' => 'required|integer|numeric|min:0|digits_between:1,10',
                'downtime' => 'required|integer|numeric|min:0|digits_between:1,10',
                'range' => 'required|integer|numeric|min:0|digits_between:1,10',
            ]);
            if($validator->fails())
            {
                $response = ["Result" => "Error", "Message" => "Unable to add pigeon: ".$validator->errors()];
                return response()->json($response, 400);
            }
            $this->pigeonService->addPigeon($request);
            $response = ["Result" => "Success", "Message" => "Pigeon <". $request['name']. "> added"];
            return response()->json($response, 201);
        }
        catch(\Exception $e)
        {
            $response = ["Result" => "Error", "Message" => "Unable to add pigeon: ".$e->getMessage()];
            return response()->json($response, 500);
        }
    }
}