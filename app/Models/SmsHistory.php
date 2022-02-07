<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsHistory extends Model
{
    use HasFactory;
    protected $table = 'sms_history';
    protected $fillable = [
        'donar_id',
        'mobile_number',
        'msg',
        'send_status',
        'created_at',
        'ins_by',
        'updated_at'
    ];
}
