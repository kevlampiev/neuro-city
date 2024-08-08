<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class CfCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cf_categories';

    protected $fillable = [
        'group_id',
        'category_name',
        'description',
    ];

    /**
     * Связь "многие к одному" с моделью CfCategoryGroup.
     */
    public function cfCategoryGroup()
    {
        return $this->belongsTo(CfCategoryGroup::class, 'group_id');
    }
}
