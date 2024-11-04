<?php

namespace App\Models\Impex;

use App\Models\BankAccount;
use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImportAdeskOperation extends Model
{
    use HasFactory;
    protected $table = 'import_adesk_operations';

    protected $guarded = [];

    protected $primaryKey = 'adesk_id';
    public $incrementing = false;
    protected $keyType = 'int';

    // Создаем вычисляемое поле
    public function getReadyToLoadAttribute(): bool
    {
        return !is_null($this->bank_account_id) && 
               !is_null($this->agreement_id) && 
               !is_null($this->cfs_item_id);
    }

    public function bankAccount():BelongsTo
    {
        return $this->belongsTo(BankAccount::class);
    }
    
    public function project():BelongsTo
    {
        return $this->belongsTo(Project::class);
    }
    
}
