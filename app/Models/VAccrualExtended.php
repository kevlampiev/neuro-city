<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VAccrualExtended extends Model
{
    protected $table = 'v_accruals_extended';
    public $timestamps = false;

    protected $primaryKey = 'id';
    public $incrementing = false; 
    protected $keyType = 'int'; 
}

