<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class AnchalChild extends Model
{
    public static $PENDING = 0;
    public static $COMPLETE = 1;

    public static $SUPERVISE_USER_COL = "supervisor_user_id";
    public static $APPROVE_USER_COL = "approval_user_id";
    public static $PURCHASE_USER_COL = "buyer_user_id";
    public static $ISSUE_USER_COL = "issuer_user_id";

    protected $table = "tb_anchal_child_reg";
    protected $guarded = ['anchal_child_reg_auto_id'];
    protected  $primaryKey = 'anchal_child_reg_auto_id';

//    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function districtInfo()
    {
        return $this->belongsTo(District::class,'district','district_code');
    }
    public function subDistrictInfo()
    {
        return $this->belongsTo(Upazila::class,'subdistrict','upazila_code');
       //return $this->hasMany(Upazila::class,['subdistrict','district'],['upazila_code','district_id']);
    }
    public function unionInfo()
    {
        return $this->belongsTo(Union::class,'anchal_union','union_code');
    }
    public function projectInfo()
    {
        return $this->belongsTo(Project::class,'project_id','project_code');
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
