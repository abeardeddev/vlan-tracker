<?php

use App\Models\Account;
use App\Models\Location;
use App\Models\LocationVlan;
use Livewire\Component;

new class extends Component {
    use Livewire\WithPagination;

    public $location;

    public $locationId;

    public $vlanCount = '';

    public function mount($id)
    {
        $this->location = Location::with(['vlans', 'accounts'])->whereId($id)->first();
        $this->locationId = $id;
    }

    #[Livewire\Attributes\Computed]
    public function accounts()
    {
        return Account::with('vlan')->whereLocationId($this->locationId)->paginate();
    }

    #[Livewire\Attributes\Computed]
    public function accountsWithVlan()
    {
        return Account::whereLocationId($this->locationId)->has('vlan')->count();
    }

    #[Livewire\Attributes\Computed]
    public function accountsWithoutVlan()
    {
        return Account::whereLocationId($this->locationId)->doesntHave('vlan')->count();
    }

    public function toggleVlan($id)
    {
        $vlan = LocationVlan::whereAccountId($id)->first();
        if(!$vlan) {
            $freeVlan = LocationVlan::whereLocationId($this->locationId)->whereNull('account_id')->first();

            if($freeVlan) {
                $freeVlan->account_id = $id;
                $freeVlan->save();
            }
        } else {
            $vlan->account_id = null;
            $vlan->save();
        }
    }

    public function toggleActive($id)
    {
        $account = Account::whereId($id)->first();
        $account->active = !$account->active;
        $account->save();

        if(!$account->active) {
            $vlan = LocationVlan::whereAccountId($id)->first();

            if($vlan) {
                $vlan->account_id = null;
                $vlan->save();
            }
        }
    }

    public function addVlans()
    {
        $lastVlan = LocationVlan::whereLocationId($this->locationId)->orderByDesc('vlan')->first();
        if($lastVlan->vlan >= 4096) {
            $this->addError('vlanCount', 'Cannot have more than 4096 vlans');
            return;
        }

        $vlan = $lastVlan->vlan;

        for($i = 1; $i <= $this->vlanCount; $i++) {

            if(($vlan + $i) >= 4096) {
                break;
            }

            $newVlan = new LocationVlan();
            $newVlan->location_id = $this->locationId;
            $newVlan->vlan = ($vlan + $i);
            $newVlan->save();
        }
    }
};
?>

<div>
    <flux:heading size="xl">Viewing: {{$location->name}}</flux:heading>
    <div class="grid grid-cols-3 gap-6 pt-6">

        <flux:card>
            <div>
                <flux:heading>Total vLANs (Assigned/Total)</flux:heading>
                <flux:text>{{$location->vlans->whereNotNull('account_id')->count()}}
                    / {{$location->vlans->count()}}</flux:text>
            </div>
        </flux:card>

        <flux:card>
            <flux:heading>Accounts With vLANs</flux:heading>
            <flux:text>{{$this->accountsWithVlan}}</flux:text>
        </flux:card>

        <flux:card>
            <flux:heading>Accounts Without vLANs</flux:heading>
            <flux:text>{{$this->accountsWithoutVlan}}</flux:text>
        </flux:card>
    </div>

    <br>
    <flux:card>
        <flux:heading>Add more vLANs</flux:heading>
        <flux:field>
            <flux:input wire:model="vlanCount" placeholder="Total Additional vLANs"></flux:input>
            <flux:error name="vlanCount"></flux:error>
            <flux:button wire:click="addVlans()">Add Additional vLANs</flux:button>
        </flux:field>

    </flux:card>
    <br>

    <flux:table :paginate="$this->accounts">
        <flux:table.columns>
            <flux:table.column>Name</flux:table.column>
            <flux:table.column>Account Number</flux:table.column>
            <flux:table.column>vLAN</flux:table.column>
            <flux:table.column>Active</flux:table.column>
            <flux:table.column>Action</flux:table.column>
        </flux:table.columns>
        <flux:table.rows>
            @foreach($this->accounts as $account)
                <flux:table.row wire:key="account-{{$account->id}}">
                    <flux:table.cell>{{$account->name}}</flux:table.cell>
                    <flux:table.cell>{{$account->account_number}}</flux:table.cell>
                    <flux:table.cell>{{isset($account->vlan->vlan) ? $account->vlan->vlan : null}}</flux:table.cell>
                    <flux:table.cell>{{$account->active == 1 ? 'Active' : 'Inactive'}}</flux:table.cell>
                    <flux:table.cell>
                        <flux:button wire:click="toggleVlan({{$account->id}})">
                            {{isset($account->vlan->vlan) ? 'Remove VLAN' : 'Assign VLAN'}}
                        </flux:button>
                        <flux:button wire:click="toggleActive({{$account->id}})">
                            {{$account->active == 1 ? 'Deactivate' : 'Activate'}}
                        </flux:button>
                    </flux:table.cell>
                </flux:table.row>
            @endforeach
        </flux:table.rows>
    </flux:table>
</div>
