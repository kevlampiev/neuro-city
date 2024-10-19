<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlItemGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function plItems():HasMany
    {
        return $this->hasMany(PlItem::class);
    }

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
