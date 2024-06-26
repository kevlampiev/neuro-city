<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Agreement extends Model
{
    use HasFactory;


    protected $guarded = [];

    public function agreementType(): BelongsTo
    {
        return $this->belongsTo(AgreementType::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'agreements_seller_id_foreing');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'agreements_buyer_id_foreing');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agreements_created_by_foreing');
    }   

    public function destroyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agreements_droped_by_foreing');
    }   

}
