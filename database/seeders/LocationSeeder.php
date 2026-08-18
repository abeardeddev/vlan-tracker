<?php

namespace Database\Seeders;

use App\Models\Location;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Location::create([
            'name' => 'Wrexham University Racecourse',
            'identifier' => 'LOC000068',
            'address' => 'Racecourse Ground, Wrexham',
        ]);
        Location::create([
            'name' => 'Shard London',
            'identifier' => 'LOC000089',
            'address' => 'Shard Building, London',
        ]);
    }
}
