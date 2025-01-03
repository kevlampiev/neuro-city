<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\Product;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlanAccrual extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded =[];

    public function product():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function agreement():BelongsTo
    {
        return $this->belongsTo(Agreement::class, 'agreement_id', 'id');
    }

    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function plItem():BelongsTo
    {
        return $this->belongsTo(PlItem::class);
    }

    /**
     * Get the computed amount.
     *
     * @return float
     */
    public function getAmountAttribute()
    {
        return $this->units_count * $this->amount_per_unit;
    }

}