<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\BankAccModel;
use App\Models\TransactionModel;
use Auth;
use Toastr;
use DB;

class BankAccController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $searchText = !empty($request->search['value']) ? $request->search['value'] : false;
            $query = BankAccModel::where([
                [
                    'softDelete',
                    '=',
                    '0'
                ]
            ])->
            select('*');

            $total = $query->count();
            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->when(($searchText), function($query) use ($searchText) {
                    $query->where(function($q) use ($searchText){
                        $q->orWhere('accountNumber', 'like', '%'.$searchText.'%');
                    });
                })
                ->orderBy('id', 'DESC')
                ->get();


            $data = [];
            if(count($result) > 0) {
                $sl = $request->start + 1;

                foreach ($result as $key => $row) {
                    $editRoute = url('admin/bank/statement/'.$row->id);
                    $btn = '';
                    $btn .= ' <a href="'.$editRoute.'" data-toggle="tooltip" title="Statement"  data-id="' . $row->id
                        . '" class="btn bg-purple btn-sm "><i class="fa fa-share-alt"></i></a>';

                    $data[] = [
                        'sl'                        => $sl++,
                        'accountName'               => $row->accountName,
                        'accountNumber'             => $row->accountNumber,
                        'accountBranchName'         => $row->accountBranchName,
                        'currentBalance'            => 0.00,
                        'action'        => $btn,
                    ];
                }
            }

            $json_data = array(
                "draw"              => intval($request->draw),
                "recordsTotal"      => intval($total),
                "recordsFiltered"   => intval($totalFiltered),
                "data"              => $data   // total data array
            );
            return  response()->json($json_data); // send data as json format

        }
        $data = [
            'page_title'=> 'Bank Account Record'
        ];
        return view('admin.bank.index',compact('data'));
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
    public function statement($id)
    {
        $bankInfo       = BankAccModel::where(['id'=>$id])->first();
        $bankStatement  = TransactionModel::where(['bank_id'=>$id,'is_active'=>1,'approved_status'=>2])->orderBy
        ('trans_date','ASC')->get();
        $transType      = TransactionModel::transType();
        $data = [
            'page_title'        => 'Bank Account Statement',
            'bankInfo'          => $bankInfo,
            'bankStatement'     => $bankStatement,
            'transType'         => $transType
        ];
        return view('admin.bank.statement',compact('data'));
    }

}
