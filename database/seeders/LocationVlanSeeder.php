<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Location;
use App\Models\LocationVlan;
use Illuminate\Database\Seeder;

class LocationVlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $locations = Location::all();

        $startingVlan = 3000;

        foreach ($locations as $location) {
            $accounts = Account::where('location_id', $location->id)
                ->orderBy('id')
                ->get();

            foreach ($accounts as $index => $account) {

                LocationVlan::factory()->create([
                    'account_id' => $account->id,
                    'location_id' => $location->id,
                    'vlan' => $startingVlan + $index,
                ]);
            }
        }
    }
}
