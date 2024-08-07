<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AgreementType extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'segment'];

    public static function rules(): array
    {
        return [
            'name' => 'required|min:3',
            
        ];
    }

    public function agreements(): HasMany
    {
        return $this->hasMany(Agreement::class);
    }


}
