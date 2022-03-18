<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCtgModel;
use App\Models\SmsHistory;
use Illuminate\Http\Request;
use App\Models\EventParticipantsModel;
use Auth;
use Illuminate\Support\Facades\Validator;
use Toastr;
use DB;

class EventParticipantsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $userSscBatch=(!empty(Auth::user()->userSscBatch)?Auth::user()->userSscBatch:'');

            $searchText = !empty($request->search['value']) ? $request->search['value'] : false;
            $query = EventParticipantsModel::where(['event_participants_info.is_active'=>1])->
            select('event_participants_info.*',"users.name as userName","professionInfo.title as professionTitle");
            $query->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'event_participants_info.addBy');
            });
            $query->leftJoin('all_settings as professionInfo', function($join) {
                $join->on('professionInfo.id', '=', 'event_participants_info.profession');
            });


            if(isset(Auth::user()->user_type) && (Auth::user()->user_type==7 )){
                $query->where('event_participants_info.batch', '=', $userSscBatch);
            }
            $query->when(($request->status), function($query) use($request)  {
                $query->where('approved_status', $request->status);
            });
            $query->when(($request->gender), function($query) use($request)  {
                $query->where('gender', $request->gender);
            });
             $query->when(($request->sscBatchSearch), function($query) use($request)  {
                $query->where('batch', $request->sscBatchSearch);
            });
            $query->when(($request->currentProfessionSearch), function($query) use($request)  {
                $query->where('profession', $request->currentProfessionSearch);
            });


            $total = $query->count();
            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->when(($searchText), function($query) use ($searchText) {
                    $query->where(function($q) use ($searchText){
                        $q->orWhere('event_participants_info.name', 'like', '%'.$searchText.'%');
                        $q->orWhere('event_participants_info.mobile', 'like', '%'.$searchText.'%');
                    });
                })
                ->orderBy('id', 'ASC')
                ->get();

            $data = [];

            if(count($result) > 0) {
                $sl = $request->start + 1;
                foreach ($result as $key => $row) {
                    $btn = '';

                    if($row->approved_status==2) {
                        $btn .= ' <button onclick="confirmJoinUs(' . $row->id . ')"   data-id="' . $row->id
                            . '" data-original-title="Confirm to Join Us" class="btn btn-success btn-xs confirmedJoinUs "><i class="fa fa-envelope-open"></i> Waiting </button>';
                        $btn .= ' <button type="button" class="btn btn-info btn-xs " data-toggle="modal" data-target="#participantModal" data-toggle="tooltip" title="Edit Expense Info" onclick="updateParticipantInfo(' . $row->id . ')" id="edit_' . $row->id . '" ><i class="fa fa-edit"></i> Edit </button>';
                    }

                    if($row->approved_status==4) {
                        $btn .= ' <button type="button" class="btn btn-info btn-xs " ><i class="fa fa-check"></i> Confirmed </button>';
                    }
                    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="' . $row->id
                        . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteData"><i class="fa fa-times"></i> Delete </a>';
                    $data[] = [
                        'sl'                        => $sl++,
                        'name'                      => $row->name,
                        'batch'                     => $row->batch,
                        'genderTitle'               => (!empty($row->gender)?(($row->gender==1)?"Male":"Female"):''),
                        'mobile'                    =>  $row->mobile,
                        'professionTitle'           =>  $row->professionTitle,
                        'present_address'           =>  $row->present_address,
                        'profession_details'        =>  $row->profession_details,
                        'created_at'                =>  date('d M, Y h:i a',strtotime($row->created_at)),
                        'action'                    => $btn,
                    ];
                }
            }

            $json_data = array(
                "recordsTotal" => intval($total),  // total number of records
                "recordsFiltered" => intval($totalFiltered), // total number of records after searching, if there is no searching then totalFiltered = totalData
                "data" => $data   // total data array
            );

            return  response()->json($json_data); // send data as json format

        }
        $profession         = ExpenseCtgModel::where(['is_active'=>1,'type'=>2])->pluck('title','id');
        $userSscBatch       = (!empty(Auth::user()->userSscBatch)?Auth::user()->userSscBatch:'');
        $userType           = (!empty(Auth::user()->user_type)?Auth::user()->user_type:'');
        $data = [
            'page_title'        => 'Participant Record',
            'profession'        => $profession,
            'userSscBatch'      => $userSscBatch,
            'userType'          => $userType,
        ];

        return view('admin.participant.index',compact('data'));
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
        $userType=(!empty(Auth::user()->user_type)?Auth::user()->user_type:'');
        $validator = Validator::make($request->all(), [
            'sscBatch'              => ['required'],
            'name'                  => ['required'],
            'mobile'                => ['required', 'numeric'],
            'gender'                => ['required', 'numeric'],
            'currentProfession'     => ['required', 'numeric'],
        ],[
            'sscBatch.required'                 => 'The SSC Batch is required',
            'name.required'                     => 'The Name is required',
            'mobile.required'                   => 'The Mobile is required',
            'gender.required'                   => 'The Gender is required',
            'currentProfession.required'        => 'The Current Profession is required',
        ]);
        $error_array=array();
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages;
            }

            $response = ['error'=> $error_array];
            return response()->json($response);
        }
        $restrictedBatch = EventParticipantsModel::restrictedBatch();
        if($userType!=1) {
            if (!empty($request->sscBatch) && in_array($request->sscBatch, $restrictedBatch)) {
                $participant = EventParticipantsModel:: where(['is_active' => 1, 'approved_status' => 2]);
                $participant->where('batch', $request->sscBatch);
                $countParticpant = $participant->count();
                if (!empty($countParticpant) && $countParticpant >= 50) {
                    $error_array[] = 'Your Registration Limit is Over, Please remove some one who will not confirmed.';
                    $response = ['error' => $error_array];
                    return response()->json($response);
                }
            }
        }


        $destinationImagePath = 'uploads/participant';
        $extensionArray = ['jpg', 'jpeg', 'png', 'pdf', 'PNG', 'JPG', 'JPEG', 'PDF'];

        if (!empty($request->file('invoice'))) {
            $image = $request->file('invoice');
            $extension = $image->getClientOriginalExtension();

            $picture = $image->getSize();
            $pictureMB = number_format($picture / 1048576, 2);
            if ($pictureMB > 15) {
                $error_array[] = 'File Attachment Maximum size 15 MB ';
                $response = ['error'=> $error_array];
                return response()->json($response);
            }
            if (!in_array($extension, $extensionArray)) {
                $error_array[] = 'File Extension  should be jpg, jpeg, png or pdf ';
                $response = ['error'=> $error_array];
                return response()->json($response);
            }
            if ($pictureMB <= 5 && in_array($extension, $extensionArray)) {
                $file_name = "profile_" .time() .'.' . $extension;
                $image->getClientOriginalName();
                $image->move($destinationImagePath, $file_name);
                $picture = $destinationImagePath . '/' . $file_name;
            } else {
                $picture = '';
            }
        } elseif (!empty($request->imageOld)) {
            $picture = $request->imageOld;
        } else {
            $picture = '';
        }

        if(empty($request->update_id)) {
            DB::beginTransaction();
            try {
                $participantID = EventParticipantsModel::countParticipate($request->sscBatch);

                $dataArray = [
                    'participantID'     => $participantID,
                    'batch'             => $request->sscBatch,
                    'name'              => $request->name,
                    'gender'            => $request->gender,
                    'mobile'            => $request->mobile,
                    'present_address'   => $request->present_address,
                    'profession'        => $request->currentProfession,
                    'profession_details' => $request->currentProfessionDetails,
                    'facebookLink'      => $request->FacebookLink,
                    'addBy'             => NULL,
                    'image'             => $picture,
                    'approved_status'   => 2,
                    'is_active'         => 1,
                    'created_time'      => date('Y-m-d H:i:s'),
                    'created_by'        => Auth::id(),
                    'created_ip'        => $request->ip(),
                    'updated_at'      => date('Y-m-d H:i:s'),
                    'updated_by'        => Auth::id(),
                    'updated_ip'        => $request->ip()
                ];
                //dd($dataArray);

                EventParticipantsModel::insert($dataArray);
                DB::commit();
                $redirectTo = route('participantsRecord');
                $response = ['success' => "Your Information Successfully Saved", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
                return response()->json($response);
            } catch (\Exception $e) {
                DB::rollback();
                $error_array[] =$e->getMessage();
                $response = ['error' =>$error_array ];
                return response()->json($response);
            }

        }else{

            DB::beginTransaction();
            try {

                $dataArray = [
                    'batch'             => $request->sscBatch,
                    'name'              => $request->name,
                    'gender'            => $request->gender,
                    'mobile'            => $request->mobile,
                    'present_address'   => $request->present_address,
                    'profession'        => $request->currentProfession,
                    'profession_details' => $request->currentProfessionDetails,
                    'facebookLink'      => $request->FacebookLink,
                    'addBy'             => NULL,
                    'image'             => $picture,
                    'approved_status'   => 2,
                    'is_active'         => 1,
                    'updated_at'      => date('Y-m-d H:i:s'),
                    'updated_by'        => Auth::id(),
                    'updated_ip'        => $request->ip()
                ];
                //dd($dataArray);

                EventParticipantsModel::where('id',$request->update_id)->update($dataArray);

                DB::commit();
                $redirectTo = route('participantsRecord');
                $response = ['success' => "Your Information Successfully Update", 'redirectTo' =>
                    $redirectTo];
                \Toastr::success($response['success']);
                return response()->json($response);
            } catch (\Exception $e) {
                DB::rollback();
                $error_array[] =$e->getMessage();
                $response = ['error' =>$error_array ];
                return response()->json($response);
            }

        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        DB::beginTransaction();
        try {
            $info   =   EventParticipantsModel::where(['id'=>$request->id])->first();
            DB::commit();
            $response = ['status'=>'success', 'message'=>"Data Found Successfully", 'data' => $info];
        }catch (\Exception $e){
            DB::rollback();
            $response = ['status'=>'error','message'=>$e->getMessage(),'data'=>[]];
        }
        return response()->json($response);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
            $info   =   EventParticipantsModel::where(['id'=>$id])->first();
            if(!empty($info)) {
                $deleteDataInfo = [
                    'is_active'     => 0,
                    'updated_at'    => date('Y-m-d H:i:s'),
                    'updated_by'    => Auth::id(),
                    'updated_ip'    => ''
                ];

                EventParticipantsModel::where('id',$id)->update($deleteDataInfo);
                DB::commit();
                $redirectTo = route('participantsRecord');
                $response = ['success' => "Request Delete Successfully", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            }else{
                DB::rollback();
                $response = ['status'=>'error','message'=>'Failed to delete Request','data'=>[]];
            }
        }catch (\Exception $e){
            DB::rollback();
            $response = ['status'=>'error','message'=>$e->getMessage(),'data'=>[]];
        }
        return response()->json($response);
    }
    public function printParticipant($sscBatch=NULL)
    {
        DB::beginTransaction();
        try {
            $info   =   EventParticipantsModel::where(['is_active'=>1,'approved_status'=>4])->when(($sscBatch),
                function($query) use
            ($sscBatch)  {
                if($sscBatch!='-') {
                    $query->where('batch', $sscBatch);
                }
            })->orderBy('batch')->orderBy('id')->get();
            $data=[
                'record'=>$info
            ];
            return view('admin.participant.printParticipant',compact('data'));
        }catch (\Exception $e){
            DB::rollback();
            return 'No Record Exist';
        }

    }

    public function confirmToJoinUs(Request $request)
    {
        DB::beginTransaction();
        try {
            $info   =   EventParticipantsModel::where(['id'=>$request->id])->first();

            if(!empty($info) && $info->approved_status==2) {
                $deleteDataInfo = [
                    'approved_status'       => 4,
                    'updated_at'            => date('Y-m-d H:i:s'),
                    'updated_by'            => Auth::id(),
                    'updated_ip'            => ''
                ];
                $sms ="Congratulation! \r\nDear {$info->name}, Your participation has been confirmed in the teachers farewell ceremony-2022 of Lemua High School . \r\n \r\nRegards\r\n Ex. Students Forum of LHS";
                $smsHistory=[
                    'donar_id'         => $info->id,
                    'mobile_number'    => (!empty($info->mobile)? substr($info->mobile, -11):''),
                    'msg'              => $sms,
                    'send_status'      => 1,
                    'ins_date'         => date('Y-m-d H:i:s'),
                    'ins_by'           => Auth::id()
                ];

                EventParticipantsModel::where('id',$request->id)->update($deleteDataInfo);
                SmsHistory::create($smsHistory);
                DB::commit();
                $redirectTo = route('participantsRecord');
                $response = ['success' => "This Applicant Confirmed to Join with Us", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            }else{
                DB::rollback();
                $response = ['status'=>'error','message'=>'Failed to Confirmed Request','data'=>[]];
            }
        }catch (\Exception $e){
            DB::rollback();
            $response = ['status'=>'error','message'=>$e->getMessage(),'data'=>[]];
        }
        return response()->json($response);
    }
}
