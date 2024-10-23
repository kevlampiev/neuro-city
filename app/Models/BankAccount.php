<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BankAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function owner():BelongsTo
    {
        return $this->belongsTo(Company::class, 'owner_id');
    }

    public function bank():BelongsTo
    {
        return $this->belongsTo(Company::class, 'bank_id');
    }

}
