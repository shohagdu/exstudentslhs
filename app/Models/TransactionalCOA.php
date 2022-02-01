<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class TransactionalCOA extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "acc_transactional_coa";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];

    public static function getAllTransactionalCurrentAsset() {
        $query = DB::table('acc_transactional_coa')
            ->where('deleted_by', null)
            ->where('sub_group_name','=','current_asset');
            $query->where(function($query){
                $query->where('economic_code', 'like','121%')
                    ->orWhere('economic_code', 'like','122%');
            })->select('id','name','economic_code','group_name','sub_group_name');
        return $query->get();
    }

    public static function getOwnerInvestmentTransactionCOA() {
        $query = DB::table('acc_transactional_coa')
            ->where('deleted_by', null)
            ->where('sub_group_name','=','owner_invest')
            ->where('economic_code','like','61%')
            ->select('id','name','economic_code','group_name','sub_group_name')
            ;
        return $query->first();
    }

    public static function getCashTransactionalCurrentAsset() {
        $query = DB::table('acc_transactional_coa')
            ->where('deleted_by', null)
            ->where('sub_group_name','=','current_asset')
            ->where('economic_code', 'like','121%')
            ->select('id','name','economic_code','group_name','sub_group_name');
        return $query->get();
    }

    public static function getBankTransactionalCurrentAsset() {
        $query = DB::table('acc_transactional_coa')
            ->where('deleted_by', null)
            ->where('sub_group_name','=','current_asset')
            ->where('economic_code', 'like','122%')
            ->select('id','name','economic_code','group_name','sub_group_name');
        return $query->get();
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }
    public function updator()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }
}
