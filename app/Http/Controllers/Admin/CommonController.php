<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\Models\Upazila;
use App\Models\Union;

use DataTables;
use Toastr;
use Auth;
use DateTime;
use DateInterval;
use DatePeriod;
use DB;
use Hash;

class CommonController extends Controller
{

    public function __construct()
    {
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


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

    }

    public function getSubDistrict(Request $request) {
        if(!empty($request->district_id)) {
            $subDistrict = Upazila::where('district_id',$request->district_id)->get();
            return response()->json(['status'=>'success','data'=>$subDistrict,'message' =>'data found']);
        }
        return response()->json(['status'=>'error','data'=>[],'message' =>'data found']);
    }
    public function getUnion(Request $request) {
        if(!empty($request->upazila_id)) {
            $unionData = Union::where('upazila_id',$request->upazila_id)->get();
            return response()->json(['status'=>'success','data'=>$unionData,'message' =>'data found']);
        }
        return response()->json(['status'=>'error','data'=>[],'message' =>'data found']);
    }

    public function ageCalculator(Request $request) {
        $date1 = isset($request->date1) ? trim($request->date1) : "";
        $date2 = isset($request->date2) ? trim($request->date2) : "";
        $earlier = new DateTime(date("Y-m-d",strtotime($date1)));
        $later = new DateTime(date("Y-m-d",strtotime($date2)));
        $diff = $later->diff($earlier)->format("%a");
        return response()->json(['status'=>'success','counter'=>$diff,'message' =>'data found']);
    }

    public function download(Request $request) {
        $file_path = 'uploads/'.$request->module.'/'.$request->filename;
        if(file_exists($file_path)) {
            return response()->download($file_path);
        }
        else {
            \Toastr::success("Sorry File Removed By Admin");
            return back();
        }
    }

    public function datamigrationRegs(Request $getData) {
        dd($getData->all());
        $data = DB::table('tb_anchal_child_reg')->take($getData->limit)->skip($getData->offset)->get();
        if(count($data)>0) {
            try {
                DB::beginTransaction();
                foreach($data as $request) {
                    $project_id = !empty($getData->project_id) ? $getData->project_id : 1;
                    $anchal_auto_id = !empty($request->anchal_auto_id) ? $request->anchal_auto_id : null;
                    $child_auto_id = !empty($request->child_auto_id) ? $request->child_auto_id : null;
                    $data_arr = [
                        'project_id' => $project_id,
                        'district' => !empty($request->district) ? $request->district : null,
                        'subdistrict' => !empty($request->subdistrict) ? $request->subdistrict : null,
                        'anchal_union' => !empty($request->anchal_union) ? $request->anchal_union : null,
                        'ward' => !empty($request->ward) ? $request->ward : null,
                        'village' => !empty($request->village) ? $request->village : null,
                        'anchal_number' => !empty($request->anchal_number) ? $request->anchal_number : 0,
                        'anchal_auto_id' => $project_id.$anchal_auto_id,
                        'anchal_ma' => !empty($request->anchal_ma) ? $request->anchal_ma : null,
                        'child_number' => !empty($request->child_number) ? $request->child_number : null,
                        'child_auto_id' => $project_id.$child_auto_id,
                        'child_name' => !empty($request->child_name) ? $request->child_name : '',
                        'sex' => !empty($request->sex) ? $request->sex : 0,
                        'child_birthdate' => !empty($request->child_birthdate) ? $request->child_birthdate : null,
                        'admission_date' => !empty($request->admission_date) ? $request->admission_date : null,
                        'age' => !empty($request->age) ? $request->age : 0,
                        'parents_name' => !empty($request->parents_name) ? $request->parents_name : '',
                        'mobile' => !empty($request->mobile) ? $request->mobile : null,
                        'created_at' => !empty($request->created_at) ? $request->created_at : date("Y-m-d H:i:s"),
                        'updated_at' => !empty($request->updated_at) ? $request->updated_at : date("Y-m-d H:i:s"),
                        'created_by' => Auth::id(),
                        'updated_by' => Auth::id(),
                    ];
                    DB::table('tb_anchal_child_reg_new')->insert(
                        $data_arr
                    );
                }
                DB::commit();
                dd('data inserted complete');
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['error' => $e->getMessage()];
                dd($response);
            }
        }
        else {
            dd('data not found');
        }
    }

    public function datamigrationMonth(Request $getData) {
        dd($getData->all());
        $data = DB::table('tb_mcbi_2')->take($getData->limit)->skip($getData->offset)->get();
        if(count($data)>0) {

            try {
                DB::beginTransaction();
                foreach($data as $request) {
                    $project_id = !empty($getData->project_id) ? $getData->project_id:1;
                    $childAutoId = !empty($request->child_auto_id) ? $request->child_auto_id : null;
                    $data_arr = [
                        'project_id' => $project_id,
                        'child_auto_id' => $project_id.$childAutoId,
                        'yearofentry' => !empty($request->yearofentry) ? $request->yearofentry : null,
                        'monthofentry' => !empty($request->monthofentry) ? $request->monthofentry : null,
                        'ifadmittedthismonth' => !empty($request->ifadmittedthismonth) ? $request->ifadmittedthismonth : null,
                        'days_present' => !empty($request->days_present) ? $request->days_present : 0,
                        'days_absent' => !empty($request->days_absent) ? $request->days_absent : 0,
                        'days_anchal_open' => !empty($request->days_anchal_open) ? $request->days_anchal_open : 0,
                        'absent_reason' => !empty($request->absent_reason) ? $request->absent_reason : null,
                        'absent_other_reason' => !empty($request->absent_other_reason) ? $request->absent_other_reason : null,
                        'ifdropout_graduate_date' => !empty($request->ifdropout_graduate_date) && $request->ifdropout_graduate_date !='0000-00-00'  ? $request->ifdropout_graduate_date : null,
                        'current_status' => !empty($request->current_status) ? $request->current_status : null,
                        'other_current_status' => !empty($request->other_current_status) ? $request->other_current_status : null,
                        'who_present_parents_meeting' => !empty($request->who_present_parents_meeting) ? $request->who_present_parents_meeting : null,
                        'anyinjuryoccurred' => !empty($request->anyinjuryoccurred) ? $request->anyinjuryoccurred : null,
                        'timeofinjury' => !empty($request->timeofinjury) ? $request->timeofinjury : null,
                        'dateofinjury' => !empty($request->dateofinjury) && $request->dateofinjury !='0000-00-00'  ? $request->dateofinjury : null,
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
                        'created_at' => !empty($request->created_at) ? $request->created_at : date("Y-m-d H:i:s"),
                        'updated_at' => !empty($request->updated_at) ? $request->updated_at : date("Y-m-d H:i:s"),
                    ];
                    DB::table('tb_mcbi')->insert(
                        $data_arr
                    );
                }
                DB::commit();
                dd('data inserted complete');
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['error' => $e->getMessage()];
                dd($response);
            }
        }
        else {
            dd('data not found');
        }
    }


    public function datamigrateUsers(Request $getData) {
        dd($getData->all());
        $data = DB::table('users_anchalbkum')->take($getData->limit)->skip($getData->offset)->get();
        if(count($data)>0) {

            try {
                DB::beginTransaction();
                foreach($data as $request) {
                    $data_arr = [
                        'project_id' => $getData->project_id,
                        'name' => !empty($request->name) ? $request->name : '',
                        'user_type' => !empty($request->user_type) ? $request->user_type : 0,
                        'email' => !empty($request->email) ? $request->email : '',
                        'password' => Hash::make($request->password),
                        'created_at' => date("Y-m-d H:i:s"),
                        'created_by' => Auth::id(),
                    ];
                    DB::table('users')->insert(
                        $data_arr
                    );
                }
                DB::commit();
                dd('data inserted complete');
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['error' => $e->getMessage()];
                dd($response);
            }
        }
        else {
            dd('data not found');
        }
    }
}
