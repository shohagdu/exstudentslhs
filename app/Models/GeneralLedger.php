<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Request;
use DB;
use Auth;

class GeneralLedger extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = "acc_general_ledger";
    protected $primaryKey = 'id';
    protected $guarded = ['id'];
    protected $dates = ['deleted_at'];
    public static $CAPITALINVESTMENT = "capital_investment";

    public static function getTransactionId() {
        return "TR#".Auth::id().date("ymdHis");
    }

    public static function getTransactionDetails($id) {
        $query = DB::table('acc_general_ledger')
            ->where('acc_general_ledger.deleted_by', null)
            ->where('acc_general_ledger.status', '=','active')
            ->where('acc_general_ledger.transaction_parent_id','=',$id)
            ->join('acc_transactional_coa', function($join){
                $join->on('acc_transactional_coa.id','=','acc_general_ledger.credit_id');
                $join->orOn('acc_transactional_coa.id','=','acc_general_ledger.debit_id');
            })
            ->select('acc_general_ledger.*','acc_transactional_coa.name','acc_transactional_coa.economic_code','acc_transactional_coa.group_name','acc_transactional_coa.sub_group_name');
        return $query->get();
    }

    public static function deleteTransaction($id) {
        return DB::table('acc_general_ledger')
            ->where('id','=',$id)
            ->orWhere('transaction_parent_id','=',$id)
            ->update([
                'status' => 'inactive',
                'deleted_by' => Auth::id(),
                'deleted_at' => date('Y-m-d H:i:s'),
                'updated_by_ip' => Request::ip(),
                'updated_by' => Auth::id(),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
    }

    public function creator()
    {
        return $this->belongsTo('App\User', 'created_by');
    }

    public function updator()
    {
        return $this->belongsTo('App\User', 'updated_by');
    }

    public function debitCOA()
    {
        return $this->belongsTo('App\Models\TransactionalCOA', 'debit_id');
    }

    public function creditCOA()
    {
        return $this->belongsTo('App\Models\TransactionalCOA', 'credit_id');
    }
}
