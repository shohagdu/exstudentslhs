<?php

namespace App\Http\Controllers\Admin;

use App\Models\DonarInfo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Auth;

class DashboardController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userType           = Auth::user()->user_type;
        $restritedUserType  = array(2, 3, 4);

        // Approved Amount
        $query    =   DonarInfo:: where(['isActive'=>1,'approvedStatus'=>2]);
                    $query->when((isset($userType) && (in_array($userType,$restritedUserType))), function($query) use
                    ($userType)  {
                        $query->where('sendNumber', Auth::id());
                    });
        $approvedAmount=$query->sum('donationAmount');

        // Pending Amount
        $pendingQuery    =   DonarInfo:: where(['isActive'=>1,'approvedStatus'=>1]);
        $pendingQuery->when((isset($userType) && (in_array($userType,$restritedUserType))), function($pendingQuery) use
                    ($userType)  {
            $pendingQuery->where('sendNumber', Auth::id());
                    });
        $pendingAmount=$pendingQuery->sum('donationAmount');

        if($userType==1 || $userType==2 ) {
            $coOrdinatorWiseCurrentApprovdAmnt = DonarInfo:: where(['donarinfos.isActive' => 1, 'donarinfos.approvedStatus' => 2])
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'donarinfos.sendNumber');
                    $join->where('donarinfos.sendNumber', '!=', NULL);
                })->where('sendNumber', '!=', NULL)
                ->select
                ('donarinfos.sendNumber', DB::raw('sum(donarinfos.donationAmount) as total'), 'users.mobileBankBkash', "users.name as userName")
                ->groupBy("sendNumber")->get();
        }else{
            $coOrdinatorWiseCurrentApprovdAmnt='';
        }

        return view('admin.dashboard',compact('approvedAmount','pendingAmount','coOrdinatorWiseCurrentApprovdAmnt'));

    }
}
