<?php
/*****************************************************************
Name: Cedric Wing

File: OrderController.php

Description: This controller manages incoming order 
related HTTP requests. It does validation and passes data 
to Order service for processing.
******************************************************************/

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\OrderService;

use Validator;

class OrderController extends Controller
{
    protected $orderService;
        
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function new(Request $request)
    {
        try
        {
            $validator = Validator::make($request->all(),[
                'distance' => 'required|integer|numeric|min:0|digits_between:1,10',
                'time' => 'required|date_format:Y-m-d H:i:s'
            ]);
            if($validator->fails())
            {
                $response = ['Result' => 'Error', 'Message' => 'Unable to create new order: '.$validator->errors()];
                return response()->json($response, 400);
            }

            $orderResults = $this->orderService->createNewOrder($request);
            
            if(!$orderResults['Result'])
            {
                $response = ['Result' => 'Failed', 'Message' => 'Order is rejected: '.$orderResults['Message']];
                return response()->json($response, 409);
            }

            $response = ['Result' => 'Success', 'Message' => $orderResults['Message']];
            return response()->json($response, 201);

        }
        catch(\Exception $e)
        {
            $response = ['Result' => 'Error', 'Message' => 'Unable to create new order: '.$e->getMessage()];
            return response()->json($response, 500);
        }
    }



}