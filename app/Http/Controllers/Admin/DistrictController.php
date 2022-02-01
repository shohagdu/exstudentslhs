<?php

namespace App\Http\Controllers\Admin;

use App\Models\District;
use App\Models\Division;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Str;
use DB;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $divisions = Division::all();

        if ($request->ajax()) {

                $query = DB::table('vw_districts');

                if(!empty($request->search['value'])) {
                    $searchText = $request->search['value'];
                    $query->where('district_name_en', 'like', '%'.$searchText.'%');
                    $query->orWhere('district_code', 'like', '%'.$searchText.'%');
                    $query->orWhere('division_name_en', 'like', '%'.$searchText.'%');
                }

                $total = $query->count();

                $totalFiltered = $total;

                $result = $query->skip($request->start)->take($request->length)->get();

                $data = [];

                if(count($result)>0) {

                    foreach($result as $key => $row) {

                        $rowData=[
                            'sl' => $key+1,
                            'district_name' => (app()->getLocale() == 'en') ? $row->district_name_en : $row->district_name_en,
                            'district_code' => $row->district_code,
                            'division_name' => (app()->getLocale() == 'en') ? $row->division_name_en : $row->division_name_en,
                        ];
                        $class = $row->status == 1 ? 'checked' : '';

                        $action = '<label>
                                <input type="checkbox" '.$class.' class="flat-red active_status" data-id="'.$row->id.'" >
                            </label>';

                        $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="btn bg-purple btn-xs edit editDistrict"><i class="fa fa-pencil"></i></a>';

                        $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteDistrict"><i class="fa fa-trash"></i></a>';

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

        return view('admin.setup_data.district',compact('divisions'));
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
            'district_name_en' => 'required|max:50|min:1'
            // 'district_shortname_en' => 'required|max:30|min:2',
            // 'district_shortname_bn' => 'required|max:30|min:2'
        ]);

        if(!$request->district_id)
        {
            $this->validate($request, [
                'district_name_en' => 'required|unique:districts|max:50|min:1'
                // 'district_code' => 'required|unique:districts|max:50|min:2'
            ]);
        }
        else {
            $district = District::find($request->district_id);
            if(strtolower($district->district_name_en) != strtolower($request->district_name_en))
            {
                $this->validate($request, [
                'district_name_en' => 'required|unique:districts'
                ]);
            }
        }



        if(!empty($request->district_id)) {
            $projectInfo = District::find($request->district_id);
            if($projectInfo->district_code != $request->district_code) {
                $child = DB::table('tb_anchal_child_reg')->where(['district'=>$projectInfo->district_code])->first();
                if(!empty($child)) {
                    $response = ['error'=>'Child found with existing District code with this Division'];
                    return response()->json($response);
                }
            }

            // code exist check
            $where = [
                ['district_code', '=', $request->district_code],
                ['division_id', '=', $request->division_id],
                ['deleted_by', '=', null],
                ['id', '!=', $request->district_id],
            ];

            $exist = DB::table('districts')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'District Code Already Exist with this Division'];
                return response()->json($response);
            }
        }
        else {
            // code exist check
            $where = [
                ['district_code', '=', $request->district_code],
                ['division_id', '=', $request->division_id],
                ['deleted_by', '=', null],
            ];

            $exist = DB::table('districts')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'District Code Already Exist'];
                return response()->json($response);
            }
        }

        //User Id
        $user_id = Auth::id();

        District::updateOrCreate(['id' => $request->district_id],
            [
                'district_name_en' => $request->district_name_en,
                'district_code' => $request->district_code,
                'division_id' => $request->division_id,
                'created_by' => $user_id,
            ]);
        return response()->json(['success'=>'District saved successfully.']);
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
        $district = District::find($id);
        return response()->json($district);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function toggleStatus(Request $request)
    {
        $district = District::find($request->id);

        if($district->status == 1 )
        {
            $district->status = 0;

            $vals = array(
                'message' => 'District Deativated');
        }
        else if($district->status == 0)
        {
            $district->status = 1;

            $vals = array(
                'message' => 'District Activated');
        }

        $district->save();
        json_encode($vals);
        echo json_encode($vals);
        exit();
    }


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
        District::find($id)->delete();
        return response()->json(['success'=>'District deleted successfully.']);
    }
}
