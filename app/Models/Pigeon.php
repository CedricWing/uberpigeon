<?php
/*****************************************************************
Name: Cedric Wing

File: Pigeon.php

Description: This file contains the Pigen Model.
The Pigeon Mddel contains all other pigeon attributes which are
static. It also contains a 'ready' timer to signify if a pigeon is
ready.
******************************************************************/
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Model\Constraint;

class Pigeon extends Model
{
    protected $table = 'pigeon';
    protected $primaryKey = 'id';
    protected $fillable = ['name','cost'];

    public function constraint()
    {
        return $this->hasMany('App\Models\Constraint');
    }

    public static function add($data)
    {
        $pigeon = Pigeon::create([
            'name' => $data['name'],
            'cost' => $data['cost']
        ]);
        $pigeon->constraint()->createMany([
            ['name' => 'speed', 'value'=> $data['speed']],
            ['name' => 'downtime', 'value'=> $data['downtime']],
            ['name' => 'range', 'value'=> $data['range']],
        ]);
    }

    public static function getAllWithConstraint()
    {
        return Pigeon::with('constraint')
                        ->get();
    }



}
