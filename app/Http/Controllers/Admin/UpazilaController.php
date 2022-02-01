<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use App\Models\Upazila;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DB;

class UpazilaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $districts = District::all();
        if ($request->ajax()) {

            $query = DB::table('vw_upazilas');

            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->where('upazila_name_en', 'like', '%'.$searchText.'%');
                $query->orWhere('upazila_code', 'like', '%'.$searchText.'%');
                $query->orWhere('district_name_en', 'like', '%'.$searchText.'%');
            }

            $total = $query->count();

            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)->get();

            $data = [];

            if(count($result)>0) {

                foreach($result as $key => $row) {

                    $rowData=[
                       'sl' => $key+1,
                       'upazila_name' => (app()->getLocale() == 'en') ? $row->upazila_name_en : $row->upazila_name_bn,
                       'upazila_code' => $row->upazila_code,
                       'district_name' => (app()->getLocale() == 'en') ? $row->district_name_en : $row->district_name_bn,
                    ];
                    $class = $row->status == 1 ? 'checked' : '';

                    $action = '<label>
                                <input type="checkbox" '.$class.' class="flat-red active_status" data-id="'.$row->id.'" >
                            </label>';

                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="btn bg-purple btn-xs edit editUpazila"><i class="fa fa-pencil"></i></a>';

                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteUpazila"><i class="fa fa-trash"></i></a>';

                    $rowData['action'] = $action;

                    $data[] = $rowData;
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

        return view('admin.setup_data.upazila',compact('districts'));
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
       //return response()->json($request);
        $this->validate($request,[
            'upazila_name_en' => 'required|max:50|min:1'
            // 'upazila_shortname_en' => 'required|max:30|min:2',
            // 'upazila_shortname_bn' => 'required|max:30|min:2'
        ]);

        if(!$request->upazila_id)
        {
            $this->validate($request, [
                'upazila_name_en' => 'required|unique:upazilas|max:50|min:1'
                // 'upazila_code' => 'required|unique:upazilas|max:50|min:2'
            ]);
            }else
            {
            $upazila = Upazila::find($request->upazila_id);
            if(strtolower($upazila->upazila_name_en) != strtolower($request->upazila_name_en))
            {
                $this->validate($request, [
                'upazila_name_en' => 'required|unique:upazilas'
                ]);
            }
            // if(strtolower($upazila->upazila_code) != strtolower($request->upazila_code))
            // {
            //     $this->validate($request, [
            //         'upazila_code' => 'required|unique:upazilas'
            //     ]);
            // }
        }

        if(!empty($request->upazila_id)) {
            $projectInfo = Upazila::find($request->upazila_id);
            if($projectInfo->upazila_code != $request->upazila_code) {
                $child = DB::table('tb_anchal_child_reg')->where(['subdistrict'=>$projectInfo->upazila_code])->first();
                if(!empty($child)) {
                    $response = ['error'=>'Child found with existing Upazila code'];
                    return response()->json($response);
                }
            }

            // code exist check
            $where = [
                ['upazila_code', '=', $request->upazila_code],
                ['district_id', '=', $request->district_id],
                ['deleted_by', '=', null],
                ['id', '!=', $request->upazila_id],
            ];

            $exist = DB::table('upazilas')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'Upazila Code Already Exist with this District'];
                return response()->json($response);
            }

        }
        else {
            // code exist check
            $where = [
                ['upazila_code', '=', $request->upazila_code],
                ['district_id', '=', $request->district_id],
                ['deleted_by', '=', null],
            ];

            $exist = DB::table('upazilas')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'Upazila Code Already Exist with this District'];
                return response()->json($response);
            }
        }

        //User Id
        $user_id = Auth::id();

        Upazila::updateOrCreate(['id' => $request->upazila_id],
            [
                'upazila_name_en' => $request->upazila_name_en,
                'upazila_code' => $request->upazila_code,
                'district_id' => $request->district_id,
                'created_by' => $user_id,
            ]);
        return response()->json(['success'=>'Upazila saved successfully.']);
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
        $upazila = Upazila::find($id);
        return response()->json($upazila);
    }


    public function toggleStatus(Request $request)
    {
        $upazila = Upazila::find($request->id);

        if($upazila->status == 1 )
        {
            $upazila->status = 0;

            $vals = array(
                'message' => 'Upazila Deativated');
        }
        else if($upazila->status == 0)
        {
            $upazila->status = 1;

            $vals = array(
                'message' => 'Upazila Activated');
        }

        $upazila->save();
        json_encode($vals);
        echo json_encode($vals);
        exit();
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
        Upazila::find($id)->delete();
        return response()->json(['success'=>'Upazila deleted successfully.']);
    }
}
