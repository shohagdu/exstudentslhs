<?php

namespace App\Http\Controllers\Admin;

use App\Models\DonarInfo;
use App\Models\EventParticipantsModel;
use App\Models\TransactionModel;
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
        $userSscBatch=(!empty(Auth::user()->userSscBatch)?Auth::user()->userSscBatch:'');
        $restritedUserType  = array(2, 3, 4,7);

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
        $batchWise          =   '';
        $dateWise           =   '';
        $participantYear    =   '';
        $batchWiseBestAmount=   '';
        $expenseInfo=   '';


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
                   "SUM(CASE WHEN donarinfos.approvedStatus = 2 THEN donarinfos.donationAmount ELSE 0 END) AS ApprovedAmnt," ."COUNT(CASE when donarinfos.approvedStatus = 2 THEN donarinfos.id ELSE NULL END) AS ApprovedParticipatent,COUNT(CASE when (donarinfos.approvedStatus = 1 || donarinfos.approvedStatus = 2)   THEN donarinfos.id ELSE NULL END) AS totalParticipatent")
                ->groupBy("sscBatch")->orderBy('sscBatch','ASC')->having('ApprovedAmnt','>',0)->get();


            $dateWise = DonarInfo:: where(['donarinfos.isActive' => 1])
                ->where('sendNumber', '!=', NULL)
                ->select(DB::raw('DATE_FORMAT(donarinfos.created_at, "%d %b, %Y") as formatted_created_at'))
                ->selectRaw("SUM(CASE WHEN donarinfos.approvedStatus = 1 THEN donarinfos.donationAmount ELSE 0 END) AS pendingAmnt, ".
                   "SUM(CASE WHEN donarinfos.approvedStatus = 2 THEN donarinfos.donationAmount ELSE 0 END) AS ApprovedAmnt")
                ->groupBy(DB::raw('DATE(created_at)'))->orderBy('created_at','ASC')->having('ApprovedAmnt','>',0)
                ->get();

            $batchWiseBestAmount = DonarInfo:: where(['donarinfos.isActive' => 1])
                ->where('sendNumber', '!=', NULL)
                ->select('donarinfos.sscBatch')
                ->selectRaw("SUM(CASE WHEN donarinfos.approvedStatus = 1 THEN donarinfos.donationAmount ELSE 0 END) AS pendingAmnt, ".
                    "SUM(CASE WHEN donarinfos.approvedStatus = 2 THEN donarinfos.donationAmount ELSE 0 END) AS ApprovedAmnt," ."COUNT(CASE when donarinfos.approvedStatus = 2 THEN donarinfos.id ELSE NULL END) AS ApprovedParticipatent,COUNT(CASE when (donarinfos.approvedStatus = 1 || donarinfos.approvedStatus = 2)   THEN donarinfos.id ELSE NULL END) AS totalParticipatent")
                ->groupBy("sscBatch")->orderBy('ApprovedAmnt','DESC')->having('ApprovedAmnt','>',0)->get();

            $participantYear=EventParticipantsModel:: where(['event_participants_info.is_active' => 1])
                ->selectRaw("batch, ".
                    "COUNT(*) AS total,"."COUNT(CASE WHEN event_participants_info.approved_status = 4 THEN event_participants_info.id ELSE NULL END) AS ApprovedParticipant,"."COUNT(CASE WHEN event_participants_info.approved_status = 2 THEN event_participants_info.id ELSE NULL END) AS pendingParticipant")
                ->groupBy(DB::raw('batch'))->orderBy('batch','ASC')
                ->get();

            $expenseInfo = TransactionModel::selectRaw('expense_ctg, SUM(credit_amount) AS expenseAmount,all_settings.title')
                ->leftJoin('all_settings', function($join) {
                $join->on('all_settings.id', '=', 'transaction_info.expense_ctg');
                })
                ->whereNotNull('credit_amount')
                ->where('transaction_info.type',3)
                ->where('approved_status',2)
                ->where('transaction_info.is_active',1)
                ->orderBy('view_order','ASC')->groupBy('expense_ctg')->get();
        }


        $participant    =   EventParticipantsModel:: where(['is_active'=>1,'approved_status'=>2]);
        $participant->when((isset($userType) && (in_array($userType,$restritedUserType))), function($query) use
        ($userSscBatch)  {
            $query->where('batch', $userSscBatch);
        });
        $totalParticpant=$participant->count('*');


        $participantApproved    =   EventParticipantsModel:: where(['is_active'=>1,'approved_status'=>4]);
        $participantApproved->when((isset($userType) && (in_array($userType,$restritedUserType))), function($query) use
        ($userSscBatch)  {
            $query->where('batch', $userSscBatch);
        });
        $totalParticpantApproved=$participantApproved->count('*');




        return view('admin.dashboard',compact('approvedAmount','pendingAmount','coOrdinatorWiseCurrentApprovdAmnt','batchWise','dateWise','userType','totalParticpant','participantYear','totalParticpantApproved','batchWiseBestAmount','expenseInfo'));

    }
}
