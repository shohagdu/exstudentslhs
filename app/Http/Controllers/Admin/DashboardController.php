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

        $coOrdinatorWiseCurrentApprovdAmnt  =   '';
        $batchWise  =   '';
        $dateWise   =   '';


        if($userType==1 || $userType==2 ) {

            $coOrdinatorWiseCurrentApprovdAmnt = DonarInfo:: where(['donarinfos.isActive' => 1])
                ->join('users', function ($join) {
                    $join->on('users.id', '=', 'donarinfos.sendNumber');
                    $join->where('donarinfos.sendNumber', '!=', NULL);
                })->where('sendNumber', '!=', NULL)
                ->select('donarinfos.sendNumber',
                    'users.mobileBankBkash', "users.name as userName")
                ->selectRaw("SUM(CASE WHEN donarinfos.approvedStatus = 1 THEN donarinfos.donationAmount ELSE 0 END) AS pendingAmnt, ".
                   "SUM(CASE WHEN donarinfos.approvedStatus = 2 THEN donarinfos.donationAmount ELSE 0 END) AS ApprovedAmnt")
                ->groupBy("sendNumber")->get();

            $batchWise = DonarInfo:: where(['donarinfos.isActive' => 1])
                ->where('sendNumber', '!=', NULL)
                ->select('donarinfos.sscBatch')
                ->selectRaw("SUM(CASE WHEN donarinfos.approvedStatus = 1 THEN donarinfos.donationAmount ELSE 0 END) AS pendingAmnt, ".
                   "SUM(CASE WHEN donarinfos.approvedStatus = 2 THEN donarinfos.donationAmount ELSE 0 END) AS ApprovedAmnt")
                ->groupBy("sscBatch")->orderBy('sscBatch','ASC')->having('ApprovedAmnt','>',0)->get();

            $dateWise = DonarInfo:: where(['donarinfos.isActive' => 1])
                ->where('sendNumber', '!=', NULL)
                ->select(DB::raw('DATE_FORMAT(donarinfos.created_at, "%d %b, %Y") as formatted_created_at'))
                ->selectRaw("SUM(CASE WHEN donarinfos.approvedStatus = 1 THEN donarinfos.donationAmount ELSE 0 END) AS pendingAmnt, ".
                   "SUM(CASE WHEN donarinfos.approvedStatus = 2 THEN donarinfos.donationAmount ELSE 0 END) AS ApprovedAmnt")
                ->groupBy(DB::raw('DATE(created_at)'))->orderBy('created_at','ASC')->having('ApprovedAmnt','>',0)
                ->get();
        }
        return view('admin.dashboard',compact('approvedAmount','pendingAmount','coOrdinatorWiseCurrentApprovdAmnt','batchWise','dateWise','userType'));

    }
}
