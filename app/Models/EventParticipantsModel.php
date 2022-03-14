<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Request;
use DB;
use Auth;

class EventParticipantsModel extends Model
{
    use HasFactory;
    protected $table = "event_participants_info";

    public static function countParticipate($batch) {
        return $batch.rand(99999,10000);
    }

    public static function restrictedBatch(){
        return [2010,2011,2012,2013,2014,2015,2016,2017,2018,2019,2020,2021];
    }
}
