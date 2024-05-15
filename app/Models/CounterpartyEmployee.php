<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CounterpartyEmployee extends Model
{
    use HasFactory;

    protected $table = 'company_employees';

    public function company():BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
}
