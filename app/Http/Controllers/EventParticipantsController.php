<?php

namespace App\Http\Controllers;

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
            $userType=(!empty(Auth::user()->user_type)?Auth::user()->user_type:'');
            $searchText = !empty($request->search['value']) ? $request->search['value'] : false;
            $query = EventParticipantsModel::where(['event_participants_info.is_active'=>1])->
            select('event_participants_info.*',"users.name as userName");
            $query->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'event_participants_info.addBy');
            });

            if(isset(Auth::user()->user_type) && (Auth::user()->user_type==2 ||Auth::user()->user_type==3 ||
                    Auth::user()->user_type==5 ||
                    Auth::user()->user_type==6 )){
                $query->where('event_participants_info.addBy', '=', Auth::id());
            }
            $query->when(($request->status), function($query) use($request)  {
                $query->where('approvedStatus', $request->status);
            });
            $query->when(($request->gender), function($query) use($request)  {
                $query->where('gender', $request->gender);
            });
             $query->when(($request->batch), function($query) use($request)  {
                $query->where('batch', $request->batch);
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
                ->orderBy('id', 'DESC')
                ->get();

            $data = [];
            if(count($result) > 0) {
                $sl = $request->start + 1;
                foreach ($result as $key => $row) {
                    $btn = '';

                    if($row->approved_status==2) {
                        $btn .= ' <button type="button" class="btn btn-info btn-sm " data-toggle="modal" data-target="#expenseModal" data-toggle="tooltip" title="Edit Expense Info" onclick="updateParticipantInfo(' . $row->id . ')" id="editExpense_' . $row->id . '" ><i class="fa fa-edit"></i> Edit </button>';

                        if($userType==1 || $userType==2) {
                            $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="' . $row->id
                                . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteData"><i class="fa fa-times"></i> Delete </a>';
                        }
                    }
                    $data[] = [
                        'sl'                        => $sl++,
                        'name'                      => $row->name,
                        'batch'                     => $row->batch,
                        'mobile'                    =>  $row->mobile,
                        'present_address'           =>  $row->present_address,
                        'profession_details'         =>  $row->profession_details,
                        'created_at'        =>  date('d M, Y h:i a',strtotime($row->created_at)),
                        'action'            => $btn,
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
        $data = [
            'page_title'        => 'Participant Record',
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
        //
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
        //
    }
}
