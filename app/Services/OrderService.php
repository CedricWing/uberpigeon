<?php
/*****************************************************************
Name: Cedric Wing

File: OrderService.php

Description: This service validates if an order is to be rejected
and assign the order to the most eligible pigeon for the delivery
******************************************************************/


namespace App\Services;

use App\Models\Order;
use App\Models\Constraint;
use App\Models\Pigeon;
use Carbon\Carbon;

class OrderService
{
    public function createNewOrder($data)
    {
        try
        {
            $validatedResults = $this->validateOrder($data);
            if(!$validatedResults['Result']) 
                return $validatedResults;

            /*
                Most eligible pigeon rules
                ==========================

                1. Select pigeon based on  min ( Delivery time + downtime + current downtime countdown )
                    Reason: Allow for more capacity in pigeon inventory (time for pigeons to be available)

                2. Select pigeon based on min(range)
                    Reason: Reserve pigeons that could make further trips
            */
                $tripDistance = $data['distance'];
                $eligiblePigeons = $validatedResults['PigeonList']->sort(function($a, $b) use ($tripDistance){


                /*
                    ASSUMPTION:
                    Time taken to make delivery does not include time for pigeon to fly back
                    If it does, $timeForReturn = 2
                */
                $timeForReturn = 1;

                $constraintA = $a->constraint();
                $tripTimeA = $tripDistance  / ($constraintA->where('name','speed')->value('value'));
                $totalTimeA = ($tripTimeA * $timeForReturn) + $constraintA->where('name','downtime')->value('value') + $a->downtime_countdown;
                $rangeA = $constraintA->where('name','range')->value('value');

                $constraintB = $b->constraint();
                $tripTimeB = $tripDistance  / ($constraintB->where('name','speed')->value('value'));
                $totalTimeB = ($tripTimeB * $timeForReturn) + $constraintB->where('name','downtime')->value('value') + $b->downtime_countdown;;
                $rangeB = $constraintB->where('name','range')->value('value');

                if($totalTimeA == $totalTimeB)
                {
                    if($rangeA == $rangeB)
                        return 0;

                    return  $rangeA < $rangeB ? -1 : 1;
                }
                return $totalTimeA < $totalTimeB ? -1 : 1; 
                });

                Order::create([
                    'distance' => $tripDistance,
                    'time' => $data['time'],
                    'total_cost' => $tripDistance * $eligiblePigeons[0]->cost,
                    'pigeon_id' => $eligiblePigeons[0]->id
                ]);
            
                
                return ['Result' => true, 'Message' => 'Your order is created and will be delivered by ' .$eligiblePigeons[0]->name ];
        }
        catch(\Exception $e)
        {
            throw $e;
        }
    }



    public function validateOrder($data)
    {
        /*
            1) Get list of all pigeons including pigeons that are available before the delivery deadline
                Current downtime countdown + current time < delivery time
        */
        $allPigeons = Pigeon::getAllWithConstraint();
        $readyPigeons = $allPigeons->filter(
            function($pigeons) use ($data)
            {
                $currentTime = Carbon::now();
                $currenDowntime = $pigeons->downtime_countdown;
                return $currentTime->addHour($currenDowntime)->lessThan($data['time']);
            }
        );
        if($readyPigeons->isEmpty())
            return ['Result' => false, 'Message' => 'No pigeons are availble before delivery time!'];



        /*
            2) Get list of pigeon which are in range
                Bird's range >= delivery distance
        */
        $inRangePigeons = $readyPigeons->filter(
            function($pigeons) use ($data)
            {
                $range = $pigeons->constraint()
                                 ->where('name','range')
                                 ->value('value');


                /*
                ASSUMPTION:
                 Pigeon's range does not cover distance needed for return trip
                 If return trip is taken into consideration, $returnTrip = 2
                */
                $returnTrip = 1;

                return $range >= ( $data['distance'] * $returnTrip );
            }
        );
        if($inRangePigeons->isEmpty())
            return ['Result' => false, 'Message' => 'No pigeons are able to fly that far!'];



        /*
            3) Get list of pigeon that are fast enough to meet delivery time
              Delivery distance (km) / pigeon speed (km/h) +  currentTime  + Current downtime countdown    <= delivery time
        */
        $inTimePigeons = $inRangePigeons->filter(
            function($pigeons) use ($data)
            {
                $timeRequired =  $data['distance'] / ($pigeons->constraint()
                                                    ->where('name','speed')
                                                    ->value('value'));
                $currenDowntime = $pigeons->downtime_countdown;
                $currentTime = Carbon::now();
                return $currentTime->addHour($timeRequired + $currenDowntime)->lessThan($data['time']);
            }
        );
        if($inTimePigeons->isEmpty())
            return ['Result' => false, 'Message' => 'No pigeons are fast enough!'];

        return ['Result' => true, 'PigeonList' => $inTimePigeons];
    }
} 