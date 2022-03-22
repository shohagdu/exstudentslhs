<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseCtgModel;

use Auth;
use Illuminate\Support\Facades\Validator;
use Toastr;
use DB;

class ExpenseCtgController extends Controller
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
            $query = ExpenseCtgModel::where([['type','=',1],['is_active','!=',0]])->
            select('all_settings.*');


            $query->when(($request->status), function($query) use($request)  {
                $query->where('approvedStatus', $request->status);
            });

            $total = $query->count();
            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->when(($searchText), function($query) use ($searchText) {
                    $query->where(function($q) use ($searchText){
                        $q->orWhere('all_settings.title', 'like', '%'.$searchText.'%');
                    });
                })
                ->orderBy('view_order', 'ASC')
                ->orderBy('id', 'DESC')
                ->get();


            $data = [];
            if(count($result) > 0) {
                $sl = $request->start + 1;

                foreach ($result as $key => $row) {
                        $btn = '';

                        $btn .= ' <button type="button" class="btn btn-info btn-sm " data-toggle="modal" data-target="#expenseCtgModal" data-toggle="tooltip" title="Edit Expense Category" onclick="updateExpenseCtg(' . $row->id . ')" id="editExpense_' . $row->id . '" ><i class="fa fa-edit"></i> Edit </button>';

                    $data[] = [
                        'sl'                    => $sl++,
                        'title'                 => $row->title,
                        'is_active'             => (!empty($row->is_active)?(($row->is_active==1)?"Active":"Inactive"):''),
                        'view_order'            => (!empty($row->view_order)?$row->view_order:''),
                        'action'                => $btn,
                    ];
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
        $data = [
            'page_title'        => 'Expense Category Record',
        ];

        return view('admin.expenseCtg.index',compact('data'));
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
        $validator = Validator::make($request->all(), [
            'expenseCtgTtile'                => ['required'],
        ],[
            'expenseCtgTtile.required'       => 'Expense Ctg Titile is required',
        ]);
        $error_array=array();
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages;
            }

            $response = ['error'=> $error_array];
            return response()->json($response);
        }
        DB::beginTransaction();
        try {
            if(empty($request->update_id)) {
                $data_arr = [
                    'title' => $request->expenseCtgTtile,
                    'type' => 1,
                    'view_order' => $request->viewOrdering,
                    'is_active' => $request->isActive,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'created_ip' => $request->ip(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_ip' => $request->ip(),

                ];
                //  dd($data_arr);
                ExpenseCtgModel::insert($data_arr);


                DB::commit();
                $redirectTo = route('expenseCtg');
                $response = ['success' => "Expense Category Saved Successful.", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            }else{
                $data_arr = [
                    'title' => $request->expenseCtgTtile,
                    'type' => 1,
                    'view_order' => $request->viewOrdering,
                    'is_active' => $request->isActive,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'created_ip' => $request->ip(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_ip' => $request->ip(),

                ];

                ExpenseCtgModel::where('id',$request->update_id)->update($data_arr);

                DB::commit();
                $redirectTo = route('expenseCtg');
                $response = ['success' => "Expense Category Update Successful.", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            }


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
    public function show(Request $request)
    {
        DB::beginTransaction();
        try {
            $info   =   ExpenseCtgModel::where(['id'=>$request->id])->first();
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
        //
    }
}
