<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Account extends Model
{
    use HasFactory;

    protected $casts = [
        'active' => 'boolean',
    ];

    public function vlan(): HasOne
    {
        return $this->hasOne(LocationVlan::class);
    }
}
