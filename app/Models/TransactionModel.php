<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;
use DB;
use Auth;

class TransactionModel extends Model
{
    use HasFactory;
    protected $table = "transaction_info";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];

    public static function getTransactionId() {
        return "TR#".Auth::id()."-".date("ymdHis");
    }
    public static function transType() {
        return [
            1=>'Direct Bank Deposit',
            2=>'Deposit From Fund Collector',
            3=>'Expense',
        ];
    }

}
