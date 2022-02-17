<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;
use DB;
use Auth;

class BankAccModel extends Model
{
    use HasFactory;

    protected $table = "tbl_pos_accounts";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
//    protected $dates = ['deleted_at'];


}
