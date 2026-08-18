<?php

use App\Models\Location;
use Livewire\Component;

new class extends Component {
    public $locations;

    public function mount()
    {
        $this->locations = Location::all();
    }
};
?>

<div>
    <flux:heading size="xl">Locations</flux:heading>

    <flux:table>
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Identifier</flux:table.column>
            <flux:table.column>Address</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($this->locations as $location)
                <flux:table.row>
                    <flux:table.cell>{{$location->name}}</flux:table.cell>
                    <flux:table.cell>{{$location->identifier}}</flux:table.cell>
                    <flux:table.cell>{{$location->address}}</flux:table.cell>
                    <flux:table.cell><flux:button as="a" :href="route('locations.show', $location->id)" wire:navigate>View</flux:button></flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
