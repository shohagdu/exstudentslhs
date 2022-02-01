<?php

namespace App\Http\Controllers\Admin;

use App\Exports\ChildExport;
use App\Exports\ChildRecordExport;
use App\Imports\DataImportController;
use App\Exports\DataExportController;
use App\Models\Union;
use App\Models\Upazila;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\User;
use App\Models\AnchalChild;
use App\Models\ChildRecord;
use App\Models\District;
use App\Models\Project;

use DataTables;
use Maatwebsite\Excel\Facades\Excel;
use Toastr;
use Auth;
use DB;
use Illuminate\Support\Facades\URL;

class AnchalChildController extends Controller
{
    private $_download_limit;
    public function __construct(AnchalChild $child,ChildRecord $childRecord)
    {
        $this->_download_limit = 500;
        $this->model = $child;
        $this->childRecord = $childRecord;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $anchaids = $this->getCoordinatorEmneAnchal();
//        dd($anchaids);
//        $array = ['tkey'=>10];
//        dd($array['tkey1']);
        $user = Auth::user();
        if($request->ajax()) {

            $query = AnchalChild::select(
                'anchal_child_reg_auto_id',
                'project_id',
                'district',
                'subdistrict',
                'anchal_union',
                'ward',
                'village',
                'child_name',
                'anchal_auto_id',
                'child_auto_id',
                'created_at',
                'updated_at',
                'updated_by',
                'created_by'
            )->where('deleted_by',null)
            ->when( (int) $user->is_admin == 0 && (int) $user->access_all_project !== 1, function($q) use ($user) {
                $q->where('project_id', $user->project_id);
            })
            ->when( $request->project_id, function($q) use ($request) {
                $q->where('project_id', $request->project_id);
            })
            ->when(true, function($q) use ($user) {
                if( $user->user_type==1 || $user->user_type==2 ) {
                    $anchaids = $this->getCoordinatorEmneAnchal();
                    if(!empty($anchaids)) {
                        $q->whereIn('tb_anchal_child_reg.anchal_auto_id', $anchaids);
                    }
                    else {
                        $q->where('tb_anchal_child_reg.anchal_auto_id', 0);
                    }
                }
            })
            ->when( $user->user_type==3, function($q) use ($user) {
                if(!empty($user->permission)) {
                    $q->whereIn('tb_anchal_child_reg.anchal_auto_id', explode(",", $user->permission));
                }
                else {
                    $q->where('tb_anchal_child_reg.anchal_auto_id', 0);
                }
            })
            ->when( !empty($request->search['value']) ? $request->search['value'] : false, function($q) use ($request) {
                $searchText = $request->search['value'];
                $q->where(function($q) use ($searchText){
                    $q->orWhere('anchal_auto_id', 'like', '%'.$searchText.'%');
                    $q->orWhere('child_auto_id', 'like', '%'.$searchText.'%');
                    $q->orWhere('mobile', 'like', '%'.$searchText.'%');
                    $q->orWhere('child_name', 'like', '%'.$searchText.'%');
                });

            });

            $total = $query->count();

            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->orderBy('anchal_auto_id', 'asc')
                ->orderBy('child_auto_id', 'asc')
                ->get();
//            dd($result);
            $data = [];

            if(count($result)>0) {
                $sl = $request->start+1;
                foreach($result as $key => $row) {
                    $childAutoId = $row->child_auto_id;
                    $childInfo = $this->model->find($row->anchal_child_reg_auto_id);
                    // dd($childInfo);
                    $childLink = '<a href="'.route('anchalrecord.childData', $childAutoId).'"  data-id="' . $childAutoId . '" data-original-title="Child ID" title="Child Id">'.$childAutoId.'</a>';
                    $childDetails = '<b>Project:</b> '.$childInfo->projectInfo->name.'<br/>'.'<b>Name:</b> '.$childInfo->child_name.'<br/>'.'<b>Child ID:</b> '.$childLink.'<br/>'.'<b>Anchal:</b> '.$childInfo->anchal_auto_id;
                    $created_at = !empty($row->created_at) ? date('d, M Y h:i:A',strtotime($row->created_at)) : 'N/A';
                    $created_by = !empty($row->created_by) ? User::find($row->created_by)->name : 'N/A';
                    $lastModified = !empty($row->updated_at)?date('d, M Y h:i:A',strtotime($row->updated_at)):'N/A';
                    $modifiedBy = !empty($row->updated_by) ? User::find($row->updated_by)->name : 'N/A';
                    $modifyInfo = '<b>Created By: </b>'.$created_by.'<br/><b>Created At:</b> '.$created_at.'<br/>'.'<b>Last Modified:</b> '.$lastModified.'<br/>'.'<b>Modified By:</b> '.$modifiedBy.'<br/>';

                    $subDistrictInfo = $upazila = Upazila::where(['upazila_code'=>$row->subdistrict,'district_id'=> $row->district])->first();
                    // $unionInfo = Union::where(['union_code'=>$row->anchal_union,'upazila_id'=> $row->subdistrict])->first();
                    $unionInfo = Union::where(['union_code'=>$row->anchal_union,'upazila_id'=> $subDistrictInfo->id])->first();
                   // dd($subDistrictInfo);

                    $rowInfo = [
                        'sl' => $sl++,
                        'child_info'=>$childDetails,
                        'district' => $row->district,
                        'subdistrict' => $row->subdistrict,
                        'anchal_union' => $row->anchal_union,
                        'modify_info' => $modifyInfo,
                        'district_name' =>  !empty($childInfo->districtInfo->district_name_en) ? $childInfo->districtInfo->district_name_en : '',
                        'subdistrict_name' => !empty($subDistrictInfo->upazila_name_en) ? $subDistrictInfo->upazila_name_en  : '',
                        'union_name' => !empty($unionInfo->union_name_en) ? $unionInfo->union_name_en :'',
                    ];
                    $action = '';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->anchal_child_reg_auto_id . '" data-original-title="View" title="Details" class="btn bg-purple btn-sm viewDetails"><i class="fa fa-eye"></i></a>';

                    if($user->hasPermissionTo('child.edit')){
                        $action .= ' <a href="' . route('anchalchild.index') . '/' . $row->anchal_child_reg_auto_id . '/edit" data-toggle="tooltip"  data-original-title="Edit" title="Child Edit" class="btn bg-blue-active btn-sm edit"><i class="fa fa-pencil"></i></a>';
                    }

                    if($user->hasPermissionTo('child.delete')){
                        $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->anchal_child_reg_auto_id . '" data-original-title="Delete" title="Child Delete" class="btn btn-danger btn-sm deleteDistrict"><i class="fa fa-trash"></i></a>';
                    }

                    if($user->hasPermissionTo('childrecord.create')) {
                        $action .= ' <a href="' . route('anchalrecord.childData', $childAutoId) . '" data-toggle="tooltip"  data-id="' . $childAutoId . '" data-original-title="Monthly Entry" title="Monthly Entry" class="btn bg-aqua btn-sm"><i class="fa fa-plus"></i></a>';
                    }

                    $rowInfo['action'] = $action;

                    $data[] = $rowInfo;;
                }
            }
            $json_data = array(
                "draw" => intval($request->draw),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($total),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return  response()->json($json_data); // send data as json format
        }
        $project = $user->is_admin==1 || $user->access_all_project == 1 ? DB::table('project')->get() :  DB::table('project')->where('project_code',$user->project_id)->get();
        return view('admin.anchalchild.index', compact('project','user'));
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $district = District::all();
        $user = User::find(Auth::id());
        $project = $user->is_admin==1 || $user->access_all_project == 1 ? DB::table('project')->get() :  DB::table('project')->where('project_code',$user->project_id)->get();
        return view('admin.anchalchild.create', compact('district','project'));
    }

    public function childData($id) {
        $childInfo = $this->model->where('child_auto_id','=',$id)->first();
        $user = User::find(Auth::id());
        $project = $user->is_admin==1 ? DB::table('project')->get() :  DB::table('project')->where('project_code',$user->project_id)->get();
        return view('admin.anchalchild.child_data.index', compact('user','childInfo','project'));
    }

    public function monthlyData(Request $request) {
        $user = User::find(Auth::id());
        if ($request->ajax()) {
            $query = DB::table('tb_mcbi')->select(
                'tb_mcbi.id',
                'tb_mcbi.child_auto_id',
                'tb_mcbi.yearofentry',
                'tb_mcbi.monthofentry',
                'tb_mcbi.days_present',
                'tb_mcbi.days_absent',
                'tb_mcbi.created_at',
                'tb_mcbi.created_by',
                'tb_anchal_child_reg.project_id','tb_anchal_child_reg.anchal_child_reg_auto_id','tb_anchal_child_reg.anchal_auto_id');

            if($user->is_admin !=1) {
                $query->where("tb_mcbi.project_id",$user->project_id);
            }

            $query->join('tb_anchal_child_reg', 'tb_mcbi.child_auto_id', '=', 'tb_anchal_child_reg.child_auto_id');

            if(!empty($request->yearofentry)) {
                $query->where('tb_mcbi.yearofentry', '=', $request->yearofentry);
            }

            if(!empty($request->monthofentry)) {
                $query->where('tb_mcbi.monthofentry', '=', $request->monthofentry);
            }

            if(!empty($request->child_auto_id)) {
                $query->where('tb_mcbi.child_auto_id', '=', $request->child_auto_id);
            }

            if(!empty($request->anchal_auto_id)) {
                $query->where('tb_anchal_child_reg.anchal_auto_id', '=', $request->anchal_auto_id);
            }

            if(!empty($request->project_id)) {
                $query->where('tb_mcbi.project_id', '=', $request->project_id);
            }

            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->where('tb_mcbi.yearofentry', 'like', '%'.$searchText.'%');
            }



            $total = $query->count();

            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->orderBy('tb_mcbi.yearofentry', 'desc')
                ->orderBy('tb_mcbi.monthofentry', 'desc')
                ->get();

            $data = [];

            if(count($result)>0) {
                $sl = $request->start+1;
                foreach($result as $key => $row) {
                    $childInfo = $this->model->find($row->anchal_child_reg_auto_id);
                    $row->sl = $sl++;
                    $childAutoId = $row->child_auto_id;
                    $row->child_auto_id = '<a href="javascript:void(0);"  data-id="' . $childAutoId . '" data-original-title="Child ID" title="Child Id">'.$childAutoId.'</a>';

                    $anchalPermission = explode(",",$user->permission);
                    if(in_array($row->anchal_auto_id,$anchalPermission)) {
                        $row->child_auto_id = '<a href="'.route('anchalchild.childData', $childAutoId).'"  data-id="' . $childAutoId . '" data-original-title="Child ID" title="Child Id">'.$childAutoId.'</a>';
                    }

                    $action = '';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" title="Details" class="btn bg-purple btn-sm viewDetails"><i class="fa fa-eye"></i></a>';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" title="Edit" class="btn bg-blue-active btn-sm monthlyformEdit"><i class="fa fa-pencil"></i></a>';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" title="Delete" class="btn btn-danger btn-sm deleteDistrict"><i class="fa fa-trash"></i></a>';

//                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" title="Details" class="btn bg-purple btn-sm viewDetails"><i class="fa fa-eye"></i></a>';
                    $row->created_at = date("d M, Y H:i:A",strtotime($row->created_at));
                    $user = User::find($row->created_by);
                    $row->created_by = !empty($user->name) ? $user->name : '';
                    $row->project_name = $childInfo->projectInfo->name;
                    $row->monthofentry = $this->month_name($row->monthofentry);
                    $row->action = $action;
                    $data[] =  (array) $row;;
                }
            }
            $json_data = array(
                "draw" => intval($request->draw),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($total),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return  response()->json($json_data); // send data as json format

        }

        $project = $user->is_admin==1 ? DB::table('project')->get() :  DB::table('project')->where('project_code',$user->project_id)->get();
        $months = $this->getMonthList();
        $years = DB::table('tb_mcbi')->select('yearofentry')->groupBy('yearofentry')->orderBy('yearofentry','desc')->get();
        return view('admin.anchalchild.child_data.monthly_record', compact('user','months','years','project'));
    }

    public function monthlyDetails(Request $request) {
        $data = DB::table('tb_mcbi')->where('id',$request->id)->first();
        $project = Project::find($data->project_id);
        $data->project_id = $project->name;
        $data->monthofentry = $this->month_name($data->monthofentry);
        $data->ifadmittedthismonth = $this->admitThisMonth($data->ifadmittedthismonth);
        $absentReason = !empty($data->absent_reason) ? explode(',',$data->absent_reason) : [];
        $reasonstring = '';
        foreach($absentReason as $item) {
            $reasonstring.=$this->absentReason($data->absent_reason).',';
        }
        $data->absent_reason = $reasonstring;
        $data->current_status = $this->currentStatus($data->current_status);
        $data->who_present_parents_meeting = $this->presentMeetingPerson($data->who_present_parents_meeting);
        $data->anyinjuryoccurred = $this->injuryOccerd($data->anyinjuryoccurred);
        $data->typeofinjury = $this->injuryType($data->typeofinjury);
        $data->placeofinjury = $this->injuryPlace($data->placeofinjury);
        $data->need_medical_care = $this->medicalCare($data->need_medical_care);
        $data->send_to_hospital = $this->sendHospital($data->send_to_hospital);
        $data->treatment_outcome = $this->treatment($data->treatment_outcome);

        return view('admin.anchalchild.child_data.view', compact('data'));
    }

    private function admitThisMonth($id) {
        if($id==1) {
            return 'Yes';
        }
        elseif($id==2) {
            return 'NO';
        }
        elseif($id==3) {
            return 'Re-admitted';
        }
        else {
            return 'N/A';
        }
    }
    private function absentReason($id) {
        if($id==1) {
            return 'Sickness';
        }
        elseif($id==2) {
            return 'Vacation';
        }
        elseif($id==3) {
            return 'No specific reason';
        }
        elseif($id==4) {
            return 'Festival';
        }
        elseif($id==5) {
            return 'Dropout';
        }
        elseif($id==6) {
            return 'Graduate';
        }
        elseif($id==7) {
            return 'Others';
        }
        else {
            return 'N/A';
        }
    }

    private function currentStatus($id) {
        if($id==1) {
            return 'Attending Anchal';
        }
        elseif($id==2) {
            return 'Graduate';
        }
        elseif($id==3) {
            return 'Drop out';
        }
        elseif($id==4) {
            return 'Child died (drowning)';
        }
        elseif($id==5) {
            return 'Child died (other cause) ';
        }
        elseif($id==6) {
            return 'Others';
        }
        else {
            return 'N/A';
        }
    }

    private function presentMeetingPerson($id) {
        if($id==1) {
            return 'Mother';
        }
        elseif($id==2) {
            return 'Father';
        }
        elseif($id==3) {
            return 'Other Male gaurdian';
        }
        elseif($id==4) {
            return 'Other Female gaurdian';
        }
        elseif($id==5) {
            return 'No one';
        }
        else {
            return 'N/A';
        }
    }
    private function injuryOccerd($id) {
        if($id==1) {
            return 'Yes';
        }
        elseif($id==2) {
            return 'No';
        }
        else {
            return 'N/A';
        }
    }
    private function injuryPlace($id) {
        if($id==1) {
            return 'Anchal room';
        }
        elseif($id==2) {
            return 'Bed room';
        }
        elseif($id==3) {
            return 'Drawing room';
        }
        elseif($id==4) {
            return 'Kitchen';
        }
        elseif($id==5) {
            return 'Bathroom';
        }
        elseif($id==6) {
            return 'Courtyard';
        }
        elseif($id==7) {
            return 'Ditches';
        }
        elseif($id==8) {
            return 'Road';
        }
        elseif($id==9) {
            return 'Pond';
        }
        elseif($id==10) {
            return 'River';
        }
        elseif($id==11) {
            return 'water Container';
        }
        elseif($id==12) {
            return 'Other Water Bodies';
        }
        elseif($id==99) {
            return 'Others';
        }
        else {
            return 'N/A';
        }
    }
    private function injuryType($id) {
        if($id==1) {
            return 'Suicide';
        }
        elseif($id==2) {
            return 'Road accident';
        }
        elseif($id==3) {
            return 'Violence';
        }
        elseif($id==4) {
            return 'Fall';
        }
        elseif($id==5) {
            return 'Cut with sharp object';
        }
        elseif($id==6) {
            return 'Burn';
        }
        elseif($id==7) {
            return 'Drowning';
        }
        elseif($id==8) {
            return 'Poisoning';
        }
        elseif($id==9) {
            return 'Mechanical Injury';
        }
        elseif($id==10) {
            return 'Electrocution';
        }
        elseif($id==11) {
            return 'Insect/Animal bite bite';
        }
        elseif($id==12) {
            return 'Sprain/strain';
        }
        elseif($id==13) {
            return 'Hit with blunt object';
        }
        elseif($id==14) {
            return 'Suffocation';
        }
        elseif($id==15) {
            return 'Don’t know';
        }
        elseif($id==16) {
            return 'Others';
        }
        else {
            return 'N/A';
        }
    }
    private function medicalCare($id){
        if($id==1) {
            return 'yes';
        }
        elseif($id==2) {
            return 'No';
        }
        else {
            return 'N/A';
        }
    }
    private function sendHospital($id){
        if($id==1) {
            return 'yes';
        }
        elseif($id==2) {
            return 'No';
        }
        else {
            return 'N/A';
        }
    }
    private function treatment($id){
        if($id==1) {
            return 'Cured';
        }
        elseif($id==2) {
            return 'Under treatment';
        }
        else {
            return 'N/A';
        }
    }
    public function getChildData(Request $request) {
        if ($request->ajax()) {

            $query = DB::table('tb_mcbi');
            $query->where('child_auto_id','=',$request->childid);
            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->where('yearofentry', 'like', '%'.$searchText.'%');
            }

            $total = $query->count();

            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->orderBy('yearofentry', 'desc')
                ->orderBy('monthofentry', 'desc')
                ->get();

            $data = [];

            if(count($result)>0) {
                $sl = $request->start+1;
                foreach($result as $key => $row) {
                    $row->sl = $sl++;
                    $action = '';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="View" title="Details" class="btn bg-purple btn-sm viewDetails"><i class="fa fa-eye"></i></a>';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" title="Edit" class="btn bg-blue-active btn-sm monthlyformEdit"><i class="fa fa-pencil"></i></a>';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" title="Delete" class="btn btn-danger btn-sm deleteDistrict"><i class="fa fa-trash"></i></a>';
                    $row->created_at = date("d M, Y H:i:A",strtotime($row->created_at));
                    $user = User::find($row->created_by);
                    $row->created_by = !empty($user->name) ? $user->name : '';
                    $row->monthofentry = $this->month_name($row->monthofentry);
                    $row->action = $action;
                    $data[] =  (array) $row;;
                }
            }
            $json_data = array(
                "draw" => intval($request->draw),   // for every request/draw by clientside , they send a number as a parameter, when they recieve a response/data they first check the draw number, so we are sending same number in draw.
                "recordsTotal" => intval($total),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );
            return  response()->json($json_data); // send data as json format

        }
    }

    public function loadMonthlyDataForm(Request $request) {
        $child_auto_id = $request->id;
        $today = date("d-m-Y");
        return view('admin.anchalchild.child_data.monthly_form',compact('today','child_auto_id'));
    }

    public function loadMonthlyDataFormEdit(Request $request) {
        $data = $this->childRecord->find($request->id);
        $monthname = $this->month_name($data->monthofentry);
        $today = date("d-m-Y");
        $absent_reason = !empty($data->absent_reason) ? explode(',',$data->absent_reason) : [];
        return view('admin.anchalchild.child_data.monthly_form_edit',compact('monthname','data','today','absent_reason'));
    }

    public function saveMonthlyData(Request $request) {
        $this->validate($request, [
            'child_auto_id' => 'required',
            'yearofentry' => 'required',
            'monthofentry' => 'required',
            'ifadmittedthismonth' => 'required',
            'days_present' => 'required',
           // 'days_absent' => 'required',
            'days_anchal_open' => 'required',
            'who_present_parents_meeting' => 'required',
            'anyinjuryoccurred' => 'required',
        ]);

        if($request->anyinjuryoccurred==1) {
            $this->validate($request, [
                'timeofinjury' => 'required',
                'dateofinjury' => 'required',
                'placeofinjury' => 'required',
                'typeofinjury' => 'required',
                'need_medical_care' => 'required',
                'send_to_hospital' => 'required',
                'treatment_outcome' => 'required',
            ]);
        }
        $where = [
            ['monthofentry','=',$request->monthofentry],
            ['yearofentry','=',$request->yearofentry],
            ['child_auto_id','=',$request->child_auto_id]
        ];
        if(!empty($request->id)) {
            $where[] = ['id','!=',$request->id];
        }

        $exist = DB::table('tb_mcbi')->where($where)->get();
        if(count($exist)>0) {
            $response = ['error'=>'This month of this student already exists!'];
            return response()->json($response);
        }
        DB::beginTransaction();
        $childInfo = DB::table('tb_anchal_child_reg')->where('child_auto_id',$request->child_auto_id)->first();
        try {
            $data_arr = [
                'project_id' => !empty($childInfo->project_id) ? $childInfo->project_id : null,
                'child_auto_id' => !empty($request->child_auto_id) ? $request->child_auto_id : null,
                'yearofentry' => !empty($request->yearofentry) ? $request->yearofentry : null,
                'monthofentry' => !empty($request->monthofentry) ? $request->monthofentry : null,
                'ifadmittedthismonth' => !empty($request->ifadmittedthismonth) ? $request->ifadmittedthismonth : null,
                'days_present' => !empty($request->days_present) ? $request->days_present : null,
                'days_absent' => !empty($request->days_absent) ? $request->days_absent : null,
                'days_anchal_open' => !empty($request->days_anchal_open) ? $request->days_anchal_open : null,
                'absent_reason' => !empty($request->absent_reason) ? implode(',',  $request->absent_reason) : null,
                'absent_other_reason' => !empty($request->absent_other_reason) ? $request->absent_other_reason : null,
                'ifdropout_graduate_date' => !empty($request->ifdropout_graduate_date) ? date("Y-m-d",strtotime($request->ifdropout_graduate_date)) : null,
                'current_status' => !empty($request->current_status) ? $request->current_status : null,
                'other_current_status' => !empty($request->other_current_status) ? $request->other_current_status : null,
                'who_present_parents_meeting' => !empty($request->who_present_parents_meeting) ? $request->who_present_parents_meeting : null,
                'anyinjuryoccurred' => !empty($request->anyinjuryoccurred) ? $request->anyinjuryoccurred : null,
                'timeofinjury' => !empty($request->timeofinjury) ? $request->timeofinjury : null,
                'dateofinjury' => !empty($request->dateofinjury) ? $request->dateofinjury : null,
                'placeofinjury' => !empty($request->placeofinjury) ? $request->placeofinjury : null,
                'other_placeofinjury' => !empty($request->other_placeofinjury) ? $request->other_placeofinjury : null,
                'typeofinjury' => !empty($request->typeofinjury) ? $request->typeofinjury : null,
                'other_typeofinjury' => !empty($request->other_typeofinjury) ? $request->other_typeofinjury : null,
                'howsevere_the_injury' => !empty($request->howsevere_the_injury) ? $request->howsevere_the_injury : 0,
                'need_medical_care' => !empty($request->need_medical_care) ? $request->need_medical_care : null,
                'send_to_hospital' => !empty($request->send_to_hospital) ? $request->send_to_hospital : null,
                'treatment_outcome' => !empty($request->treatment_outcome) ? $request->treatment_outcome : null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
            ChildRecord::updateOrCreate(['id' => $request->id],$data_arr);
            $redirectTo = route('anchalchild.childData',$request->child_auto_id);
            $response = ['success'=>"Child Monthly Data Save successful.", 'redirectTo' => $redirectTo];
            \Toastr::success($response['success']);
            DB::commit();
        }
        catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
        }
        return response()->json($response);

    }

    private function getMonthList() {
        return array(
            1 => 'January',
            2 => 'February',
            3 => 'March',
            4 => 'April',
            5 => 'May',
            6 => 'June',
            7 => 'July',
            8 => 'August',
            9 => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December'
        );
    }

    public function month_name($month_num) {
        $month_array = $this->getMonthList();
        return $month_array[$month_num];
    }

    public function monthCheck(Request $request)
    {
        $child_auto_id = $request->child_auto_id;
        $year = $request->year;
        //duplicate entry off for view form
        $data = DB::table('tb_mcbi')->where(['yearofentry'=>$year,'child_auto_id'=>$child_auto_id])->get();
        $data_array_1 = [];
        if( count($data) > 0 ) {
            foreach( $data as $key => $item ) {
                $data_array_1[$item->monthofentry] = $item->monthofentry;
            }
        }
        $selectMonth = !empty($request->select_month) ? $request->select_month : '';
        $option_html = '';
        for( $i=1; $i <= 12; $i++ ) {
            $option_html .= (in_array( $i, $data_array_1 )) && $i!=$selectMonth ? '<option disabled value="'.$i.'">'.$this->month_name($i).' - Done</option>' : '<option value="'.$i.'">'.$this->month_name($i).'</option>';
        }
        echo $option_html;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'project' => 'required',
            'district' => 'required',
            'subdistrict' => 'required',
            'anchal_union' => 'required|max:2|min:2',
            'ward' => 'required',
            'anchal_number' => 'required',
            'anchal_auto_id' => 'required',
            'child_number' => 'required|min:2|max:2',
            'child_auto_id' => 'required',
            'child_birthdate' => 'required',
            'admission_date' => 'required',
            'parents_name' => 'required',
            'mobile' => 'required|min:11|max:11',
            'village' => 'required',
        ]);

        $where = [
            ['anchal_number','=', $request->anchal_number],
            ['child_auto_id','=', $request->child_auto_id]
        ];

        if(!empty($request->id)) {
            $where[] = ['anchal_child_reg_auto_id','!=', $request->id];
        }

        if($request->child_number<10 && strlen($request->child_number)<2){
            $mainInput = (int) $request->child_number;
            $request->child_number = '0'.$mainInput;
        }

        $exist = $this->model->where($where)->get();
        if(count($exist)>0) {
            $response = ['error'=>'This student already exists with this anchal!'];
            return response()->json($response);
        }


        DB::beginTransaction();

        try {

            if(!empty($request->id)) {
                $childInfo = $this->model->find($request->id);
                $monthData = $this->childRecord->find(
                    ['child_auto_id'=>$childInfo->child_auto_id]
                );
                if( !empty(count($monthData)) &&  ($childInfo->child_auto_id!=$request->child_auto_id) ) {
                    // need all data respective child auto id
                    DB::table('tb_mcbi')
                        ->where('child_auto_id', $childInfo->child_auto_id)
                        ->update(['child_auto_id' => $request->child_auto_id]);
                }
            }

            $child_birthdate = date("Y-m-d", strtotime($request->child_birthdate));
            $admission_date = date("Y-m-d", strtotime($request->admission_date));

            $data_arr = [
                'project_id' => !empty($request->project) ? $request->project : null,
                'district' => !empty($request->district) ? $request->district : null,
                'subdistrict' => !empty($request->subdistrict) ? $request->subdistrict : null,
                'anchal_union' => !empty($request->anchal_union) ? $request->anchal_union : null,
                'ward' => !empty($request->ward) ? $request->ward : null,
                'village' => !empty($request->village) ? $request->village : null,
                'anchal_number' => !empty($request->anchal_number) ? $request->anchal_number : null,
                'anchal_auto_id' => !empty($request->anchal_auto_id) ? $request->anchal_auto_id : null,
                'anchal_ma' => !empty($request->anchal_ma) ? $request->anchal_ma : null,
                'child_number' => !empty($request->child_number) ? $request->child_number : null,
                'child_auto_id' => !empty($request->child_auto_id) ? $request->child_auto_id : null,
                'child_name' => !empty($request->child_name) ? $request->child_name : null,
                'sex' => !empty($request->sex) ? $request->sex : null,
                'child_birthdate' => !empty($child_birthdate) ? $child_birthdate : null,
                'admission_date' => !empty($admission_date) ? $admission_date : null,
                'age' => !empty($request->age) ? $request->age : 0,
                'parents_name' => !empty($request->parents_name) ? $request->parents_name : null,
                'mobile' => !empty($request->mobile) ? $request->mobile : null,
                'created_by' => Auth::id(),
                'updated_by' => Auth::id(),
            ];
            AnchalChild::updateOrCreate(['anchal_child_reg_auto_id' => $request->id],$data_arr);
            $redirectTo = route('anchalchild.index');
            $response = ['success'=>"Child Save successful.", 'redirectTo' => $redirectTo];
            \Toastr::success($response['success']);
            DB::commit();
        }
        catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
        }
        return response()->json($response);

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = $this->model->find($id);
        $subDistrictInfo = Upazila::where(['upazila_code'=>$data->subdistrict,'district_id'=> $data->district])->first();
        $unionInfo = Union::where(['union_code'=>$data->anchal_union,'upazila_id'=> $subDistrictInfo->id])->first();
        return view('admin.anchalchild.view', compact('data','subDistrictInfo','unionInfo'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = $this->model->find($id);
        $district = District::all();
        $upazila = Upazila::where(['upazila_code'=>$data->subdistrict,'district_id'=>$data->district])->get();
        //dd($upazila);
        $subDistrictInfo = Upazila::where(['upazila_code'=>$data->subdistrict,'district_id'=> $data->district])->first();
        $unions = Union::where(['upazila_id'=>$subDistrictInfo->id])->get();

        $selectedUpazila = Upazila::where(['upazila_code'=>$data->subdistrict,'district_id'=> $data->district])->first();
        $selectedUnion = Union::where(['union_code'=>$data->anchal_union,'upazila_id'=> $data->subdistrict])->first();

        $user = User::find(Auth::id());
        $project = $user->is_admin==1 || $user->access_all_project == 1 ? DB::table('project')->get() :  DB::table('project')->where('project_code',$user->project_id)->get();
        return view('admin.anchalchild.edit', compact('data','district','project','upazila','unions','selectedUpazila','selectedUnion'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        DB::beginTransaction();

        try {
            $childInfo = $this->model->find($id);
            $this->model->where('anchal_child_reg_auto_id',$id)->delete();
            $record = $this->childRecord->where('child_auto_id',$childInfo->child_auto_id)->first();
            if(!empty($record)) {
                $this->childRecord->where('child_auto_id', $childInfo->child_auto_id)->delete();
            }
            DB::commit();
            $response = ['success' => 'Successfully deleted the Child'];
        }
        catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
        }

        return response()->json($response);
    }

    public function childImport(Request $request) {

        $this->validate($request,[
            'child_file' => 'required',
        ]);
        $path1 = $request->file('child_file')->store('temp');
        $path=storage_path('app').'/'.$path1;
        $import = new DataImportController;
        Excel::import($import,$path);
        $data = $import->getData();



        DB::beginTransaction();
        try {

            if(!empty($data))
            {
                foreach($data as $key => $value) {

                    $project_id = $data[$key][0];

                    if(!is_numeric($project_id)) {  continue; }

                        $child_auto_id = $data[$key][10];
                        $anchal_number = $data[$key][6];
                        $unionCode = strlen(trim($data[$key][3]))==1 ? '0'.$data[$key][3] : $data[$key][3];
                        $childNumber = strlen(trim($data[$key][9]))==1 ? '0'.$data[$key][9] : $data[$key][9];

                        $insert_data = array(
                            'project_id'  => $data[$key][0],
                            'district'  => $data[$key][1],
                            'subdistrict'  => $data[$key][2],
                            'anchal_union' => $unionCode,
                            'ward'   => $data[$key][4],
                            'village' => $data[$key][5],
                            'anchal_number' => $anchal_number,
                            'anchal_auto_id' => $data[$key][7],
                            'anchal_ma' => $data[$key][8],
                            'child_number' => $childNumber,
                            'child_auto_id' => $data[$key][10],
                            'child_name' => $data[$key][11],
                            'sex' => $data[$key][12],
                            'child_birthdate' => date("Y-m-d",strtotime($data[$key][13])),
                            'admission_date' => date("Y-m-d",strtotime($data[$key][14])),
                            'age' => $data[$key][15],
                            'parents_name' => $data[$key][16],
                            'mobile' => $data[$key][17],
                            'created_by' => Auth::id(),
                            'updated_by' => Auth::id(),
                            'input_type' => 'bulk',
                        );
                        AnchalChild::updateOrCreate([
                            'anchal_number' => $anchal_number,
                            'child_auto_id' => $child_auto_id,
                        ], $insert_data);
                }
            }
            $redirectTo = route('anchalchild.index');
            $response = ['success'=>"Child Uploaded successfuly", 'redirectTo' => $redirectTo];
            \Toastr::success($response['success']);
            DB::commit();
        }
        catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
        }
        return response()->json($response);
    }

    public function childExportForm(Request $request) {
        $district = District::all();
        $project = Project::all();
        $user = Auth::user();
        if($user->is_admin==1) {
            $total = !empty($request->project_id) ? AnchalChild::where('project_id','=',$request->project_id)->count() : AnchalChild::count();
        }
        else {
            $total = AnchalChild::where('project_id','=',$user->project_id)->count();
        }
        $params              = array();
        // get the item
        $data['total']           = $total;
        $data['total_processed'] = 0;
        $data['process_percent'] = 0;
        $data['limit']           = $this->_download_limit;
        return view('admin.anchalchild.childexportform',compact('data','district','user','project'));
    }

    public function exportChild(Request $request)
    {
        $query = $this->model->latest();
        $user = Auth::user();
        $start = !empty($request->start) ? $request->start : 1;
        $end = !empty($request->end) ? $request->end : 'all';;
        if($user->is_admin !=1) {
            $query->where('project_id','=',$user->project_id);
        }
        if(!empty($request->project_id)) {
            $query->where('project_id','=',$request->project_id);
        }
        if(!empty($request->district)) {
            $query->where('district','=',$request->district);
        }
        if(!empty($request->subdistrict)) {
            $query->where('subdistrict','=',$request->subdistrict);
        }
        if(!empty($request->anchal_union)) {
            $query->where('anchal_union','=',$request->anchal_union);
        }
        if(!empty($request->start)) {
            $query->skip($start);
        }
        if(!empty($request->end)) {
            $query->take($end);
        }


        $data = $query
            ->get();
//        dd($data);
        $header = [
            'SL',
            'Project',
            'District',
            'Sub District',
            'Union',
            'Ward',
            'Village',
            'Anchal Number',
            'Anchal Auto ID',
            'Anchal Ma',
            'Child Number',
            'Child Auto ID',
            'Child Name',
            'Sex',
            'Child Birth Day',
            'Admission Date',
            'Age',
            'Parents Name',
            'Mobile',
            'Created At',
        ];

        $data_array = [];
        foreach($data as $row)
        {
            $sex = $row->sex==1 ? 'Male' : "Female";

            $zilaName = !empty($row->districtInfo->district_name_en) ? $row->unionInfo->district_name_en : "N/A";
            $upazilaName = !empty($row->subDistrictInfo->upazila_name_en) ? $row->subDistrictInfo->upazila_name_en : "N/A";
            $unionName = !empty($row->unionInfo->union_name_en) ? $row->unionInfo->union_name_en : "N/A";
            $birthDay = !empty($row->child_birthdate) ? date('d/m/Y',strtotime($row->child_birthdate)) : 'N/A';
            $admissionDate = !empty($row->admission_date) ? date('d/m/Y',strtotime($row->admission_date)) : 'N/A';
            $created_at = !empty($row->created_at) ? date('d/m/Y',strtotime($row->created_at)) : 'N/A';
            $newRow = [
                $row->anchal_child_reg_auto_id,
                $row->projectInfo->name,
                $zilaName,
                $upazilaName,
                $unionName,
                $row->ward,
                $row->village,
                $row->anchal_number,
                $row->anchal_auto_id,
                $row->anchal_ma,
                $row->child_number,
                $row->child_auto_id,
                $row->child_name,
                $sex,
                $birthDay,
                $admissionDate,
                $row->age,
                $row->parents_name,
                $row->mobile,
                $created_at,
            ];

            $newArray = array_combine($header,$newRow);

            $data_array[] = $newArray;
        }

        $fileName="childlist_".date("Y-m-d H:i:A").'_'.$start.'--'.$end.$request->export_type;

        return Excel::download(new DataExportController($data_array,$header), $fileName);

    }

    public function exportDemo4(Request $request)
    {
        ini_set('memory_limit', '-1');
        $query = $this->model->latest();
        $user = Auth::user();
        $start = !empty($request->start) ? $request->start : 1;
        $end = !empty($request->end) ? $request->end : 'all';;
        if($user->is_admin ==0 && $user->access_all_project !=1) {
            $query->where('project_id','=',$user->project_id);
        }
        if(!empty($request->project_id)) {
            $query->where('project_id','=',$request->project_id);
        }
        if(!empty($request->district)) {
            $query->where('district','=',$request->district);
        }
        if(!empty($request->subdistrict)) {
            $query->where('subdistrict','=',$request->subdistrict);
        }
        if(!empty($request->anchal_union)) {
            $query->where('anchal_union','=',$request->anchal_union);
        }

        if( $user->user_type==1 || $user->user_type==2 ) {
            $anchaids = $this->getCoordinatorEmneAnchal();
            if(!empty($anchaids)) {
                $query->whereIn('anchal_auto_id', $anchaids);
            }
            else {
                $query->where('anchal_auto_id', 0);
            }
        }
        elseif($user->user_type==3) {
            if(!empty($user->permission)) {
                $query->whereIn('anchal_auto_id', explode(",", $user->permission));
            }
            else {
                $query->where('anchal_auto_id', 0);
            }
        }

        if(!empty($request->start)) {
            $query->skip($start);
        }
        if(!empty($request->end)) {
            $query->take($end);
        }
        $data = $query
            ->get();
        $header = [
            'SL',
            'Project',
            'District',
            'Sub District',
            'Union',
            'Ward',
            'Village',
            'Anchal Number',
            'Anchal Auto ID',
            'Anchal Ma',
            'Child Number',
            'Child Auto ID',
            'Child Name',
            'Sex',
            'Child Birth Day',
            'Admission Date',
            'Age',
            'Parents Name',
            'Mobile',
            'Created At',
            'Updated At',
        ];

        if(count($data) > 0) {
            $delimiter = ",";
            $filename = "child_data_" . date('Y-m-d') . ".csv";

            //create a file pointer
            $f = fopen('php://memory', 'w');

            //set column headers
            fputcsv($f, $header, $delimiter);

            $sl=1;
            foreach ($data as $row) {
                $lineData['sl '] = $sl++;
                $lineData['project_id '] = $row->project_id;
                $lineData['district'] = $row->district;
                $lineData['subdistrict'] = $row->subdistrict;
                $lineData['anchal_union'] = $row->anchal_union;
                $lineData['ward '] = $row->ward;
                $lineData['village '] = $row->village;
                $lineData['anchal_number '] = $row->anchal_number;
                $lineData['anchal_auto_id '] = $row->anchal_auto_id;
                $lineData['anchal_ma '] = $row->anchal_ma;
                $lineData['child_number '] = $row->child_number;
                $lineData['child_auto_id '] = $row->child_auto_id;
                $lineData['child_name '] = $row->child_name;
                $lineData['sex '] = $row->village;
                $lineData['child_birthdate '] = !empty($row->child_birthdate) ? date('d/m/Y', strtotime($row->child_birthdate)) : 'N/A';
                $lineData['admission_date '] = !empty($row->admission_date) ? date('d/m/Y', strtotime($row->admission_date)) : 'N/A';
                $lineData['age '] = $row->age;
                $lineData['parents_name '] = $row->parents_name;
                $lineData['mobile '] = $row->mobile;
                $lineData['created_at '] = !empty($row->created_at) ? date('d/m/Y', strtotime($row->created_at)) : 'N/A';
                $lineData['updated_at '] = !empty($row->updated_at) ? date('d/m/Y', strtotime($row->updated_at)) : 'N/A';
                fputcsv($f, $lineData, $delimiter);
            }

            //move back to beginning of file
            fseek($f, 0);

            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');
            header('Cache-Control: max-age=0');
            //output all remaining data on a file pointer
            fpassthru($f);
            die();
        }
    }

    public function exportDemo1() {

        $cacheMethod = \PHPExcel_CachedObjectStorageFactory:: cache_to_phpTemp;

        $cacheSettings = array( 'memoryCacheSize' => -1);

        \PHPExcel_Settings::setCacheStorageMethod($cacheMethod, $cacheSettings);

        $objPHPExcel = new \PHPExcel();
        $i = 1;
        $patList = 50000;
        for ($r = 0; $r < $patList; $r++) {

            $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$i", $r)
                ->setCellValue("B$i", $r);
            $i++;
        }

        $objPHPExcel->getActiveSheet()->setTitle('demo excel');

        header('Content-Type: application/vnd.ms-excel');

        header('Content-Disposition: attachment;filename="result.xls"');

        header('Cache-Control: max-age=0');

        ob_clean();

        flush();

        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');

        $objWriter->save('php://output');

        die();
    }

    public function exportDemo2(Request $request) {

        ini_set('memory_limit', '-1');
        //include database configuration file
        $fields = array('child_auto_id', 'yearofentry', 'district', 'subdistrict', 'anchal_union', 'ward', 'village', 'anchal_number', 'anchal_auto_id', 'anchal_ma', 'child_number', 'child_name', 'sex', 'child_birthdate', 'admission_date', 'age', 'parents_name', 'mobile', 'created_by', 'created_at');

        $user = Auth::user();

        $query = DB::table('tb_mcbi')->select(
            'tb_mcbi.*');
//            'tb_anchal_child_reg.project_id','tb_anchal_child_reg.anchal_child_reg_auto_id','tb_anchal_child_reg.anchal_auto_id');

        if($user->is_admin !=1) {
            $query->where("tb_mcbi.project_id",$user->project_id);
        }

//        $query->join('tb_anchal_child_reg', 'tb_mcbi.child_auto_id', '=', 'tb_anchal_child_reg.child_auto_id');

        if(!empty($request->yearofentry)) {
            $query->where('tb_mcbi.yearofentry', '=', $request->yearofentry);
        }

        if(!empty($request->monthofentry)) {
            $query->where('tb_mcbi.monthofentry', '=', $request->monthofentry);
        }

        if(!empty($request->child_auto_id)) {
            $query->where('tb_mcbi.child_auto_id', '=', $request->child_auto_id);
        }

        if(!empty($request->anchal_auto_id)) {
            $query->where('tb_anchal_child_reg.anchal_auto_id', '=', $request->anchal_auto_id);
        }

        if(!empty($request->project_id)) {
            $query->where('tb_mcbi.project_id', '=', $request->project_id);
        }

        if(!empty($request->search['value'])) {
            $searchText = $request->search['value'];
            $query->where('tb_mcbi.yearofentry', 'like', '%'.$searchText.'%');
        }

//        $data = $query->skip(10)->take(5)->get();
        $data = $query->skip(1)->take(500)->get();
        if(count($data) > 0){
            $delimiter = ",";
            $filename = "mcbi_data_" . date('Y-m-d') . ".csv";

            //create a file pointer
            $f = fopen('php://memory', 'w');

            //set column headers
            fputcsv($f, $fields, $delimiter);

            //output each row of the data, format line as csv and write to file pointer
            foreach($data as $key => $item){
                $row = (array) $item;
                $lineData['project_id '] = $row['project_id'];
                $lineData['child_auto_id '] = $row['child_auto_id'];
                $lineData['yearofentry '] = $row['yearofentry'];
                $lineData['monthofentry '] = $row['monthofentry'];
                $lineData['ifadmittedthismonth'] = $row['ifadmittedthismonth'];
                $lineData['days_present'] = $row['days_present'];
                $lineData['days_absent'] = $row['days_absent'];
                $lineData['days_anchal_open'] = $row['days_anchal_open'];
                $lineData['absent_reason'] = $row['absent_reason'];
                $lineData['absent_other_reason'] = $row['absent_other_reason'];
                $lineData['ifdropout_graduate_date'] = $row['ifdropout_graduate_date'];
                $lineData['current_status'] = $row['current_status'];
                $lineData['other_current_status'] = $row['other_current_status'];
                $lineData['who_present_parents_meeting'] = $row['who_present_parents_meeting'];
                $lineData['anyinjuryoccurred'] = $row['anyinjuryoccurred'];
                $lineData['timeofinjury'] = $row['timeofinjury'];
                $lineData['dateofinjury'] = $row['dateofinjury'];
                $lineData['placeofinjury'] = $row['placeofinjury'];
                $lineData['other_placeofinjury'] = $row['other_placeofinjury'];
                $lineData['typeofinjury'] = $row['typeofinjury'];
                $lineData['other_typeofinjury'] = $row['other_typeofinjury'];
                $lineData['howsevere_the_injury'] = $row['howsevere_the_injury'];
                $lineData['need_medical_care'] = $row['need_medical_care'];
                $lineData['send_to_hospital'] = $row['send_to_hospital'];
                $lineData['treatment_outcome'] = $row['treatment_outcome'];
                $lineData['created_by'] = $row['created_by'];
                $lineData['created_at'] = (($row['created_at'] && ($row['created_at'] != '0000-00-00')) ? date("d/m/Y H:i:s A", strtotime($row['created_at'])) : "");

                // $lineData[] = $row;

//                for ($i=1; $i <= 12; $i++) {
//
//                    $lineData[$i.'_yearofentry'] = $row[$i.'_yearofentry'];
//                    $lineData[$i.'_monthofentry'] = $row[$i.'_monthofentry'];
//                    $lineData[$i.'_ifadmittedthismonth'] = $row[$i.'_ifadmittedthismonth'];
//                    $lineData[$i.'_days_present'] = $row[$i.'_days_present'];
//                    $lineData[$i.'_days_absent'] = $row[$i.'_days_absent'];
//                    $lineData[$i.'_days_anchal_open'] = $row[$i.'_days_anchal_open'];
//                    $lineData[$i.'_absent_reason'] = $row[$i.'_absent_reason'];
//                    $lineData[$i.'_absent_other_reason'] = $row[$i.'_absent_other_reason'];
//                    $lineData[$i.'_ifdropout_graduate_date'] = (($row[$i.'_ifdropout_graduate_date'] && ($row[$i.'_ifdropout_graduate_date'] != '0000-00-00')) ? date("d/m/Y", strtotime($row[$i.'_ifdropout_graduate_date'])) : "");
//                    $lineData[$i.'_current_status'] = $row[$i.'_current_status'];
//                    $lineData[$i.'_other_current_status'] = $row[$i.'_other_current_status'];
//                    $lineData[$i.'_who_present_parents_meeting'] = $row[$i.'_who_present_parents_meeting'];
//                    $lineData[$i.'_anyinjuryoccurred'] = $row[$i.'_anyinjuryoccurred'];
//                    $lineData[$i.'_timeofinjury'] = $row[$i.'_timeofinjury'];
//                    $lineData[$i.'_dateofinjury'] = $row[$i.'_dateofinjury'];
//                    $lineData[$i.'_placeofinjury'] = $row[$i.'_placeofinjury'];
//                    $lineData[$i.'_other_placeofinjury'] = $row[$i.'_other_placeofinjury'];
//                    $lineData[$i.'_typeofinjury'] = $row[$i.'_typeofinjury'];
//                    $lineData[$i.'_other_typeofinjury'] = $row[$i.'_other_typeofinjury'];
//                    $lineData[$i.'_howsevere_the_injury'] = $row[$i.'_howsevere_the_injury'];
//                    $lineData[$i.'_need_medical_care'] = $row[$i.'_need_medical_care'];
//                    $lineData[$i.'_send_to_hospital'] = $row[$i.'_send_to_hospital'];
//                    $lineData[$i.'_treatment_outcome'] = $row[$i.'_treatment_outcome'];
//                    $lineData[$i.'_created_by'] = $row[$i.'_created_by'];
//                    $lineData[$i.'_created_at'] = (($row[$i.'_created_at'] && ($row[$i.'_created_at'] != '0000-00-00')) ? date("d/m/Y H:i:s A", strtotime($row[$i.'_created_at'])) : "");
//                    $lineData[$i.'_month_break'] = $row[$i.'_month_break'];
//                }

                fputcsv($f, $lineData, $delimiter);
            }

            // echo '<pre/>';
            // print_r($lineData);
            // exit();

            //move back to beginning of file
            fseek($f, 0);

            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }
    }

    public function exportDemo3(){
        ini_set('memory_limit', '-1');
        //include database configuration file
        $year = isset($_GET['year']) ? $_GET['year'] : 0;
        $fields = array('child_auto_id', 'yearofentry', 'district', 'subdistrict', 'anchal_union', 'ward', 'village', 'anchal_number', 'anchal_auto_id', 'anchal_ma', 'child_number', 'child_name', 'sex', 'child_birthdate', 'admission_date', 'age', 'parents_name', 'mobile', 'created_by', 'created_at');

//        for ($i=1; $i <= 12; $i++) {
//            $fields[] = $i.'_yearofentry';
//            $fields[] = $i.'_monthofentry';
//            $fields[] = $i.'_ifadmittedthismonth';
//            $fields[] = $i.'_days_present';
//            $fields[] = $i.'_days_absent';
//            $fields[] = $i.'_days_anchal_open';
//            $fields[] = $i.'_absent_reason';
//            $fields[] = $i.'_absent_other_reason';
//            $fields[] = $i.'_ifdropout_graduate_date';
//            $fields[] = $i.'_current_status';
//            $fields[] = $i.'_other_current_status';
//            $fields[] = $i.'_who_present_parents_meeting';
//            $fields[] = $i.'_anyinjuryoccurred';
//            $fields[] = $i.'_timeofinjury';
//            $fields[] = $i.'_dateofinjury';
//            $fields[] = $i.'_placeofinjury';
//            $fields[] = $i.'_other_placeofinjury';
//            $fields[] = $i.'_typeofinjury';
//            $fields[] = $i.'_other_typeofinjury';
//            $fields[] = $i.'_howsevere_the_injury';
//            $fields[] = $i.'_need_medical_care';
//            $fields[] = $i.'_send_to_hospital';
//            $fields[] = $i.'_treatment_outcome';
//            $fields[] = $i.'_created_by';
//            $fields[] = $i.'_created_at';
//            $fields[] = $i.'_month_break';
//        }

//get records from database
//        if ($year > 0) {
//            $query = $db->query("SELECT * FROM mcbi_multitple WHERE yearofentry = '$year' OR yearofentry IS NULL");
//        } else {
//            $query = $db->query("SELECT * FROM mcbi_multitple");
//        }
        $data = DB::table('tb_mcbi')->get();
        if(count($data) > 0){
            $delimiter = ",";
            $filename = "mcbi_data_" . date('Y-m-d') . ".csv";

            //create a file pointer
            $f = fopen('php://memory', 'w');

            //set column headers
            fputcsv($f, $fields, $delimiter);

            //output each row of the data, format line as csv and write to file pointer
            foreach($data as $key => $item){
                $row = (array) $item;
                $lineData['child_auto_id'] = $row['child_auto_id'];
                $lineData['yearofentry'] = $row['yearofentry'];
                $lineData['district'] = $row['district'];
                $lineData['subdistrict'] = $row['subdistrict'];
                $lineData['anchal_union'] = $row['anchal_union'];
                $lineData['ward'] = $row['ward'];
                $lineData['village'] = $row['village'];
                $lineData['anchal_number'] = $row['anchal_number'];
                $lineData['anchal_auto_id'] = $row['anchal_auto_id'];
                $lineData['anchal_ma'] = $row['anchal_ma'];
                $lineData['child_number'] = $row['child_number'];
                $lineData['child_name'] = $row['child_name'];
                $lineData['sex'] = $row['sex'];
                $lineData['child_birthdate'] = $row['child_birthdate'];
                $lineData['admission_date'] = $row['admission_date'];
                $lineData['age'] = $row['age'];
                $lineData['parents_name'] = $row['parents_name'];
                $lineData['mobile'] = $row['mobile'];
                $lineData['created_by'] = $row['created_by'];
                $lineData['created_at'] = (($row['created_at'] && ($row['created_at'] != '0000-00-00')) ? date("d/m/Y H:i:s A", strtotime($row['created_at'])) : "");

                // $lineData[] = $row;

//                for ($i=1; $i <= 12; $i++) {
//
//                    $lineData[$i.'_yearofentry'] = $row[$i.'_yearofentry'];
//                    $lineData[$i.'_monthofentry'] = $row[$i.'_monthofentry'];
//                    $lineData[$i.'_ifadmittedthismonth'] = $row[$i.'_ifadmittedthismonth'];
//                    $lineData[$i.'_days_present'] = $row[$i.'_days_present'];
//                    $lineData[$i.'_days_absent'] = $row[$i.'_days_absent'];
//                    $lineData[$i.'_days_anchal_open'] = $row[$i.'_days_anchal_open'];
//                    $lineData[$i.'_absent_reason'] = $row[$i.'_absent_reason'];
//                    $lineData[$i.'_absent_other_reason'] = $row[$i.'_absent_other_reason'];
//                    $lineData[$i.'_ifdropout_graduate_date'] = (($row[$i.'_ifdropout_graduate_date'] && ($row[$i.'_ifdropout_graduate_date'] != '0000-00-00')) ? date("d/m/Y", strtotime($row[$i.'_ifdropout_graduate_date'])) : "");
//                    $lineData[$i.'_current_status'] = $row[$i.'_current_status'];
//                    $lineData[$i.'_other_current_status'] = $row[$i.'_other_current_status'];
//                    $lineData[$i.'_who_present_parents_meeting'] = $row[$i.'_who_present_parents_meeting'];
//                    $lineData[$i.'_anyinjuryoccurred'] = $row[$i.'_anyinjuryoccurred'];
//                    $lineData[$i.'_timeofinjury'] = $row[$i.'_timeofinjury'];
//                    $lineData[$i.'_dateofinjury'] = $row[$i.'_dateofinjury'];
//                    $lineData[$i.'_placeofinjury'] = $row[$i.'_placeofinjury'];
//                    $lineData[$i.'_other_placeofinjury'] = $row[$i.'_other_placeofinjury'];
//                    $lineData[$i.'_typeofinjury'] = $row[$i.'_typeofinjury'];
//                    $lineData[$i.'_other_typeofinjury'] = $row[$i.'_other_typeofinjury'];
//                    $lineData[$i.'_howsevere_the_injury'] = $row[$i.'_howsevere_the_injury'];
//                    $lineData[$i.'_need_medical_care'] = $row[$i.'_need_medical_care'];
//                    $lineData[$i.'_send_to_hospital'] = $row[$i.'_send_to_hospital'];
//                    $lineData[$i.'_treatment_outcome'] = $row[$i.'_treatment_outcome'];
//                    $lineData[$i.'_created_by'] = $row[$i.'_created_by'];
//                    $lineData[$i.'_created_at'] = (($row[$i.'_created_at'] && ($row[$i.'_created_at'] != '0000-00-00')) ? date("d/m/Y H:i:s A", strtotime($row[$i.'_created_at'])) : "");
//                    $lineData[$i.'_month_break'] = $row[$i.'_month_break'];
//                }

                fputcsv($f, $lineData, $delimiter);
            }

            // echo '<pre/>';
            // print_r($lineData);
            // exit();

            //move back to beginning of file
            fseek($f, 0);

            //set headers to download file rather than displayed
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="' . $filename . '";');

            //output all remaining data on a file pointer
            fpassthru($f);
        }
    }

}
