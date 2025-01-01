<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanPayment extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function agreement():BelongsTo
    {
        return $this->belongsTo(Agreement::class, 'agreement_id', 'id');
    }

    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function cfsItem():BelongsTo
    {
        return $this->belongsTo(CFSItem::class);
    }

}
