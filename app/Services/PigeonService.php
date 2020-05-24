<?php
/*****************************************************************
Name: Cedric Wing

File: PigeonService.php

Description: This service adds a Pigeon
******************************************************************/
namespace App\Services;

use App\Models\Pigeon;

class PigeonService
{
    public function addPigeon($data)
    {
        try
        {
            Pigeon::add($data);
        }
        catch (Exception $e)
        {
            throw $e;
        }
    }
} 