<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\AnchalChild;
use Auth;
use DB;

class DashboardController_old extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        die('ok');
        return view('admin.dashboard');

    }

    public function test()
    {
        die('ok');
        return view('admin.dashboard');

    }

    private function getCoordinatorEmneAnchal() {
        $user = Auth::user();
        if($user->user_type==1) {
            $where = [
                ['coordinator', 'like', '%'.$user->id.'%'],
                ['permission', '!=', null],
                ['deleted_by','=',null]
            ];

            $data = User::query()->select(DB::raw('group_concat(permission) as anchalids'))->where($where)->first();
            if(!empty($data->anchalids)) {
                return explode(',',$data->anchalids);

            }
            return false;
        }
        else {
            $where = [
                ['emine', 'like', '%'.$user->id.'%'],
                ['permission', '!=', null],
                ['deleted_by','=',null]
            ];

            $data = User::query()->select(DB::raw('group_concat(permission) as anchalids'))->where($where)->first();
            if(!empty($data->anchalids)) {
                return explode(',',$data->anchalids);

            }
            return false;
        }
        return false;
    }

    public function loadDashboard(Request $request) {
        $data = [];
        $loginUser = Auth::user();

        $projects = $loginUser->is_admin==1 || $loginUser->access_all_project==1 ? DB::table('project')->get() :  DB::table('project')->where('project_code',$loginUser->project_id)->get();
        if(!empty($projects)) {
            foreach($projects as $item) {

                $query = DB::table('tb_anchal_child_reg')
                    ->select(DB::raw('COUNT(anchal_child_reg_auto_id) total_students'))
                    ->where('project_id',$item->project_code);

                $anchaids = $this->getCoordinatorEmneAnchal();

                if( $loginUser->user_type==1 || $loginUser->user_type==2 ) {
                    if(!empty($anchaids)) {
                        $query->whereIn('tb_anchal_child_reg.anchal_auto_id', $anchaids);
                    }
                    else {
                        $query->where('tb_anchal_child_reg.anchal_auto_id', 0);
                    }
                }
                elseif($loginUser->user_type==3) {
                    if(!empty($loginUser->permission)) {
                        $query->whereIn('tb_anchal_child_reg.anchal_auto_id',explode(",",$loginUser->permission));
                    }
                    else {
                        $query->where('tb_anchal_child_reg.anchal_auto_id', 0);
                    }
                }

                $students =$query->first();
                $summaryQuery = DB::table('tb_mcbi')->select(DB::raw('
                            SUM(`absent_reason` = 1) AS total_sickness,
                            SUM(`absent_reason` = 2) AS total_vacation,
                            SUM(`absent_reason` = 3) AS total_no_specific_reason,
                            SUM(`absent_reason` = 4) AS total_festival,
                            SUM(`absent_reason` = 6) AS total_graduate,
                            SUM(`absent_reason` = 5) AS total_dropout,
                            SUM(`absent_reason` = 7) AS total_other_reason,
                            SUM(`anyinjuryoccurred` = 1) AS total_injured
                        '))
                    ->join('tb_anchal_child_reg', 'tb_anchal_child_reg.child_auto_id', '=', 'tb_mcbi.child_auto_id')
                    ->where('tb_anchal_child_reg.project_id',$item->project_code);

                if( $loginUser->user_type==1 || $loginUser->user_type==2 ) {
                    if(!empty($anchaids)) {
                        $summaryQuery->whereIn('tb_anchal_child_reg.anchal_auto_id', $anchaids);
                    }
                    else {
                        $summaryQuery->where('tb_anchal_child_reg.anchal_auto_id', 0);
                    }
                }
                elseif($loginUser->user_type==3) {
                    if(!empty($loginUser->permission)) {
                        $summaryQuery->whereIn('tb_anchal_child_reg.anchal_auto_id',explode(",",$loginUser->permission));
                    }
                    else {
                        $summaryQuery->where('tb_anchal_child_reg.anchal_auto_id', 0);
                    }
                }


                $monthsummary =   $summaryQuery->first();

                $data[$item->name] = [
                    'student' => $students->total_students,
                    'total_sickness' => $monthsummary->total_sickness,
                    'total_vacation' => $monthsummary->total_vacation,
                    'total_no_specific_reason' => $monthsummary->total_no_specific_reason,
                    'total_festival' => $monthsummary->total_festival,
                    'total_graduate' => $monthsummary->total_graduate,
                    'total_dropout' => $monthsummary->total_dropout,
                    'total_other_reason' => $monthsummary->total_other_reason,
                    'total_injured' => $monthsummary->total_injured,
                ];

            }
        }
        return view('admin.loaddashboard',compact('data'));
    }

}
