<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CfCategoryGroup extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cf_category_groups';

    protected $fillable = [
        'group_name',
        'category_type',
        'flow_type',
        'description',
    ];

    /**
     * Связь "один ко многим" с моделью CfCategory.
     */
    public function cfCategories()
    {
        return $this->hasMany(CfCategory::class, 'group_id');
    }
}
