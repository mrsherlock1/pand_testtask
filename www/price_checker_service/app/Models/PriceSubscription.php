<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PriceSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['link','current_price'];

    public function users() : HasMany
    {
        return $this->hasMany(SubscribedUser::class);
    }
}
