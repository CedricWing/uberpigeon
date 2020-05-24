<?php
/*****************************************************************
Name: Cedric Wing

File: Constraint.php

Description: This file contains the Constraints Model and its
relation with Pigeons, Constriants are attributes that pigeons
are limited to. eg. range, downtime, speed..etc
******************************************************************/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Constraint extends Model
{
    protected $table = 'constraint';
    protected $primaryKey = 'id';
    protected $fillable = ['name','value','pigeon_id'];


    public function pigeon()
    {
        return $this->belongsTo('App\Models\Pigeon');
    }

}
