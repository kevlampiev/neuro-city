<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        return $this->belongsTo(Company::class, 'seller_id');
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'buyer_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agreements_created_by_foreing');
    }   

    public function destroyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agreements_droped_by_foreing');
    }   

    public function documents(): BelongsToMany
    {
        return $this->belongsToMany(Document::class, 'agreement_document');
    }   

}