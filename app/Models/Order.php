<?php
/*****************************************************************
Name: Cedric Wing

File: Order.php

Description: This file contains the Order Model and its
relation with Pigeons. An Order contains sufficient data for
invocing/inventory for each delivery.
******************************************************************/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'order';
    protected $primaryKey = 'id';
    protected $fillable = ['distance','time','total_cost', 'pigeon_id'];

 
}
