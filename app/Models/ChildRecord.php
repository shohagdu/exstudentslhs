<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use DB;

class ChildRecord extends Model
{
    protected $table = "tb_mcbi";
    protected $guarded = ['id'];
    protected  $primaryKey = 'id';

//    use SoftDeletes;

    protected $dates = ['deleted_at'];

    public function districtInfo()
    {
        return $this->belongsTo(District::class,'district','district_code');
    }
    public function subDistrictInfo()
    {
        return $this->belongsTo(Upazila::class,'subdistrict','upazila_code');
    }
    public function unionInfo()
    {
        return $this->belongsTo(Union::class,'anchal_union','union_code');
    }
    public function projectInfo()
    {
        return $this->belongsTo(Project::class,'project_id','project_code');
    }
    public function childInfo()
    {
        return $this->belongsTo(AnchalChild::class,'child_auto_id','child_auto_id');
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
