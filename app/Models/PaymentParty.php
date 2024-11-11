<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentParty extends Model
{
    protected $table = 'v_payments_parties';
    public $timestamps = false;
}
