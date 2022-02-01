<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
class District extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];


    protected $fillable = ['district_name_en', 'district_name_bn', 'district_shortname_en', 'district_shortname_bn', 'district_code','division_id','note_en','note_bn','created_by'];

    protected $table = 'districts';

    public function division()
    {
        return $this->blongsTo('App\Model\Division');
    }
}
