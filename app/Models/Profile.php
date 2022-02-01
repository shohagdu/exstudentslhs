<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Profile extends Model
{
    //
    use SoftDeletes;
    protected $table = 'profiles';
    protected $primaryKey = 'id';

    protected $dates = ['deleted_at'];

    protected $fillable=['country','city','address','gender','image','phone','dob','user_id'];

    public function user(){
        return $this->belongsTo('App\User');
    }
}
