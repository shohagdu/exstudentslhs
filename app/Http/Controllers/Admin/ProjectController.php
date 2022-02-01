<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use DataTables;
use DB;

class ProjectController extends Controller
{
    public function __construct(Project $workStation)
    {
        $this->model = $workStation;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
//        $user = Auth::user();
        if ($request->ajax()) {
            $query = DB::table('project');
            $query->where("deleted_at","=",null);

            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->where('name', 'like', '%'.$searchText.'%');
                $query->orWhere('project_code', 'like', '%'.$searchText.'%');
            }

            $total = $query->count();

            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)->orderBy('id', 'desc')->get();

            $data = [];

            if(count($result)>0) {

                foreach($result as $key => $row) {

                    $rowData=[
                        'sl' => $key+1,
                        'name' => $row->name,
                        'project_code' => $row->project_code,
                    ];
                    if($row->status == 1)
                    {
                        $class = "checked";
                    }
                    else
                    {
                        $class = "";
                    }

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

        return view('admin.setup_data.project');
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
        $this->validate($request,[
            'name' => 'required|max:50|min:2',
            'project_code' => 'required|numeric',
        ]);

        if(!empty($request->workstation_id)) {
            $projectInfo = Project::find($request->workstation_id);
            if($projectInfo->project_code != $request->project_code) {
                $child = DB::table('tb_anchal_child_reg')->where(['project_id'=>$projectInfo->project_code])->first();
                if(!empty($child)) {
                    $response = ['error'=>'Child found with existing project code'];
                    return response()->json($response);
                }
            }
        }

        //User Id
        $user_id = Auth::id();

        Project::updateOrCreate(['id' => $request->workstation_id],
            [
                'name' => $request->name,
                'project_code' => $request->project_code,
                'created_by' => $user_id,
            ]);
        return response()->json(['success'=>'Project saved successfully.']);
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
        $upazila = Project::find($id);
        return response()->json($upazila);
    }


    public function toggleStatus(Request $request)
    {
        $upazila = Project::find($request->id);

        if($upazila->status == 1 )
        {
            $upazila->status = 0;

            $vals = array(
                'message' => 'Project Deativated');
        }
        else if($upazila->status == 0)
        {
            $upazila->status = 1;

            $vals = array(
                'message' => 'Project Activated');
        }

        $upazila->save();
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
        // $attendance = Attendance::where('work_station',$id)->first();
        $anchalChild = false;
        if(empty($attendance)) {
            Project::find($id)->delete();
            return response()->json(['success' => 'Project deleted successfully.']);
        }
        else {
            return response()->json(['error' => 'Child Found with this project']);
        }
    }
}
