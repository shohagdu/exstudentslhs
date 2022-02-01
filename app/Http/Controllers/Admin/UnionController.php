<?php

namespace App\Http\Controllers\Admin;

use App\Models\Upazila;
use App\Models\Union;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;
use DB;

class UnionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $upazilas = Upazila::all();

        if ($request->ajax()) {
            $query = DB::table('vw_unions');

            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->where('union_name_en', 'like', '%'.$searchText.'%');
                $query->orWhere('union_code', 'like', '%'.$searchText.'%');
                $query->orWhere('upazila_name_en', 'like', '%'.$searchText.'%');
            }

            $total = $query->count();

            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)->orderBy('id', 'desc')->get();

            $data = [];

            if(count($result)>0) {

                foreach($result as $key => $row) {

                    $rowData=[
                        'sl' => $key+1,
                        'union_name' => (app()->getLocale() == 'en') ? $row->union_name_en : $row->union_name_en,
                        'union_code' => $row->union_code,
                        'upazila_name' => (app()->getLocale() == 'en') ? $row->upazila_name_en : $row->upazila_name_en,
                    ];
                    if($row->status == 1)
                    {
                        $class = "checked";
                        $checked = "true";
                    }
                    else
                    {
                        $class = "";
                        $checked = "false";
                    }

                    $action = '<label><div class="icheckbox_flat-green'. $class. '" aria-checked="'.$checked.'" aria-disabled="false" style="position: relative;"><input type="checkbox" '.$class.' class="flat-red active_status" data-id="'.$row->id.'"  style="position: absolute; opacity: 0;"><ins class="iCheck-helper" style="position: absolute; top: 0%; left: 0%; display: block; width: 100%; height: 100%; margin: 0px; padding: 0px; background: rgb(255, 255, 255); border: 0px; opacity: 0;"></ins></div></label>';

                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="btn bg-purple btn-xs edit editUnion"><i class="fa fa-pencil"></i></a>';

                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteUnion"><i class="fa fa-trash"></i></a>';

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

        return view('admin.setup_data.union',compact('upazilas'));
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
            'union_name_en' => 'required|max:50|min:1'
            // 'union_shortname_en' => 'required|max:30|min:2',
            // 'union_shortname_bn' => 'required|max:30|min:2'
        ]);

        if(!$request->union_id)
        {
            $this->validate($request, [
                'union_name_en' => 'required|unique:unions|max:50|min:2'
                // 'union_code' => 'required|unique:unions|max:50|min:2'
            ]);

        }else{
                    $union = Union::find($request->union_id);
                    if(strtolower($union->union_name_en) != strtolower($request->union_name_en))
                    {
                        $this->validate($request, [
                        'union_name_en' => 'required|unique:unions'
                        ]);
                    }
                    // if(strtolower($union->union_code) != strtolower($request->union_code))
                    // {
                    //     $this->validate($request, [
                    //         'union_code' => 'required|unique:unions'
                    //     ]);

                    // }
            }
        if(!empty($request->union_id)) {
            $projectInfo = Union::find($request->union_id);
            if($projectInfo->union_code != $request->union_code) {
                $child = DB::table('tb_anchal_child_reg')->where(['anchal_union'=>$projectInfo->union_code])->first();
                if(!empty($child)) {
                    $response = ['error'=>'Child found with existing Union code'];
                    return response()->json($response);
                }
            }

            // code exist check
            $where = [
                ['union_code', '=', $request->union_code],
                ['upazila_id', '=', $request->upazila_id],
                ['deleted_by', '=', null],
                ['id', '!=', $request->union_id],
            ];

            $exist = DB::table('unions')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'Union Code Already Exist with this Upazila'];
                return response()->json($response);
            }

        }
        else {
            // code exist check
            $where = [
                ['union_code', '=', $request->union_code],
                ['upazila_id', '=', $request->upazila_id],
                ['deleted_by', '=', null],
            ];

            $exist = DB::table('unions')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'Union Code Already Exist with this Upazila'];
                return response()->json($response);
            }
        }

        //User Id
        $user_id = Auth::id();

        Union::updateOrCreate(['id' => $request->union_id],
            [
                'union_name_en' => $request->union_name_en,
                'union_code' => $request->union_code,
                'upazila_id' => $request->upazila_id,
                'created_by' => $user_id,
            ]);
        return response()->json(['success'=>'Union saved successfully.']);
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
        $union = Union::find($id);
        return response()->json($union);
    }

    public function toggleStatus(Request $request)
    {
        $union = Union::find($request->id);

        if($union->status == 1 )
        {
            $union->status = 0;

            $vals = array(
                'message' => 'Union Deativated');
        }
        else if($union->status == 0)
        {
            $union->status = 1;

            $vals = array(
                'message' => 'Union Activated');
        }

        $union->save();
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
        Union::find($id)->delete();
        return response()->json(['success'=>'Union deleted successfully.']);
    }
}
