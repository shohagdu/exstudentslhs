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
}
