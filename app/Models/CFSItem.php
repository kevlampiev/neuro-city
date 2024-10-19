<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


class CFSItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'cfs_items';

    public function user():HasOne
    {
        return $this->hasOne(User::class);
    }

    public function cfsItemGroup():BelongsTo
    {
        return $this->belongsTo(CFSItemGroup::class, 'group_id', 'id');
    }
}