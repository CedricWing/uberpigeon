<?php
/*****************************************************************
Name: Cedric Wings

File: DatabaseSeeder.php

Description: This file contains the seeding mechanism required
to populate pigeons at the start
******************************************************************/
use Illuminate\Database\Seeder;
use App\Models\Pigeon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call('PigeonConstraintTableSeeder');
    }
}

class PigeonConstraintTableSeeder extends Seeder {

    public function run()
    {
        Pigeon::add([
            'name' => 'Antonio',
            'cost' => 2,
            'downtime' => 2,
            'speed' => 70,
            'range' => 600
        ]);
        Pigeon::add([
            'name' => 'Bonito',
            'cost' => 2,
            'downtime' => 3,
            'speed' => 80,
            'range' => 500
        ]);
        Pigeon::add([
            'name' => 'Carillo',
            'cost' => 2,
            'downtime' => 3,
            'speed' => 65,
            'range' => 1000
        ]);
        Pigeon::add([
            'name' => 'Alejandro',
            'cost' => 2,
            'downtime' => 2,
            'speed' => 70,
            'range' => 800

        ]);      
    }

}