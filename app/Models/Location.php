<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    public function accounts(): HasMany
    {
        return $this->hasMany(Account::class);
    }

    public function vlans(): HasMany
    {
        return $this->hasMany(LocationVlan::class);
    }
}
