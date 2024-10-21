<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class CFSItemGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];
    protected $table = 'cfs_item_groups';

    public function user():HasOne
    {
        return $this->hasOne(User::class);
    }

    public function cfsItems():HasMany
    {
        return $this->hasMany(CFSItem::class, 'group_id', 'id');
    }
}
