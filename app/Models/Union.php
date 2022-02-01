<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Union extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    protected $fillable = ['union_name_en','union_code','upazila_id','created_by'];

    protected $table = 'unions';

    public function upazila()
    {
        return $this->blongsTo('App\Model\Upazila');
    }
}
