<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    

    protected $fillable = ['division_name_en','division_code','created_by'];

    protected $table = 'divisions';

}
