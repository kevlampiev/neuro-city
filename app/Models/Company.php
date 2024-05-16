<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneOrMany;

class Company extends Model
{
    use HasFactory;

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
}
