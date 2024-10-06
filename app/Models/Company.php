<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded =[];

    public function employees():HasMany
    {
        return $this->hasMany(CounterpartyEmployee::class);
    }

    public function notes():HasMany
    {
        return $this->hasMany(CompanyNote::class);
    }

    public function staff():HasMany
    {
        return $this->hasMany(CounterpartyEmployee::class);
    }

    public function agreements_sail():HasMany
    {
        return $this->hasMany(Agreement::class, 'seller_id', 'id');
    }

    public function agreements_buy():HasMany
    {
        return $this->hasMany(Agreement::class, 'buyer_id', 'id');
    }
}
