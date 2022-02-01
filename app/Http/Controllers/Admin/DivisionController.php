<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Auth;
use App\Models\Division;
use DB;

class DivisionController extends Controller
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
        if ($request->ajax()) {
            $query = DB::table('divisions')->where("deleted_at","=",null);

            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->orWhere('division_code', 'like', '%'.$searchText.'%');
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
                        'division_name' => (app()->getLocale() == 'en') ? $row->division_name_en : $row->division_name_en,
                        'division_code' => $row->division_code,
                    ];
                    $class = $row->status == 1 ? 'checked' : '';

                    $action = '<label>
                                <input type="checkbox" '.$class.' class="flat-red active_status" data-id="'.$row->id.'" >
                            </label>';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="btn bg-purple btn-xs edit editDivision"><i class="fa fa-pencil"></i></a>';

                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteDivision"><i class="fa fa-trash"></i></a>';

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

        return view('admin.setup_data.division');
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
            'division_name_en' => 'required|max:50|min:1',
            'division_code' => 'required|max:50|min:1',
        ]);

        if(!$request->division_id)
        {
            $this->validate($request, [
                'division_name_en' => 'required|unique:divisions|max:50|min:1',
                'division_code' => 'required|unique:divisions|max:50|min:1'
            ]);
            }else
            {
                $division = Division::find($request->division_id);
            if(strtolower($division->division_name_en) != strtolower($request->division_name_en))
            {
                $this->validate($request, [
                'division_name_en' => 'required|unique:divisions'
                ]);
            }
            if(strtolower($division->division_code) != strtolower($request->division_code))
            {
                $this->validate($request, [
                'division_code' => 'required|unique:divisions'
                ]);
            }
        }

        //User Id
        $user_id = Auth::id();

        Division::updateOrCreate(['id' => $request->division_id],
            [
                'division_name_en' => $request->division_name_en,
                'division_code' => $request->division_code,
                'created_by' => $user_id,
            ]);
        return response()->json(['success'=>'Division saved successfully.']);
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
        $division = Division::find($id);
        return response()->json($division);
    }

    public function toggleStatus(Request $request)
    {
        $division = Division::find($request->id);

        if($division->status == 1 )
        {
            $division->status = 0;

            $vals = array(
                'message' => 'Division Deativated');
        }
        else if($division->status == 0)
        {
            $division->status = 1;

            $vals = array(
                'message' => 'Division Activated');
        }

        $division->save();
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
        Division::find($id)->delete();
        return response()->json(['success'=>'Division deleted successfully.']);
    }
}
