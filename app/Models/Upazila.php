<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Upazila extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    

    protected $fillable = ['upazila_name_en', 'upazila_code','district_id','created_by'];

    protected $table = 'upazilas';

    public function district()
    {
        return $this->blongsTo('App\Model\District');
    }
}
