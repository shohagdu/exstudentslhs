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
            $query = BankAccModel::select('*')->where([
                [
                    'softDelete',
                    '=',
                    '0'
                ]
            ])
            ->leftJoinSub(self::payableDebitSubQuery(6), 'PC', function($pcs) {
                $pcs->on('PC.bank_id', 'tbl_pos_accounts.id');
            })
            ->leftJoinSub(self::payableCrditSubQuery(6), 'credit', function($credits) {
                $credits->on('credit.bank_id', 'tbl_pos_accounts.id');
            });

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
                    $bank_deposite_debit=(!empty($row->bank_deposite_debit)?$row->bank_deposite_debit:'0.00');
                    $payable_credit=(!empty($row->payable_credit)?$row->payable_credit:'0.00');
                    $balance=(!empty($bank_deposite_debit-$payable_credit)?number_format
                    ($bank_deposite_debit-$payable_credit,2):'0.00');

                    $data[] = [
                        'sl'                        => $sl++,
                        'accountName'               => $row->accountName,
                        'accountNumber'             => $row->accountNumber,
                        'accountBranchName'         => $row->accountBranchName,
                        'currentBalance'            => $balance,
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
    public static function payableDebitSubQuery(int $payableChartOfId)
    {
        return TransactionModel::selectRaw('bank_id, SUM(debit_amount) AS bank_deposite_debit')
            ->whereNotNull('debit_amount')
            ->where('approved_status',2)
            ->where('is_active',1)
            ->where('bank_id', $payableChartOfId)
            ->groupBy('bank_id');
    }
    public static function payableCrditSubQuery(int $payableChartOfId)
    {
        return TransactionModel::selectRaw('bank_id, SUM(credit_amount) AS payable_credit')
            ->whereNotNull('credit_amount')
            ->where('approved_status',2)
            ->where('is_active',1)
            ->where('bank_id', $payableChartOfId)
            ->groupBy('bank_id');
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
        $bankStatement  = TransactionModel::select("transaction_info.*","all_settings.title as expenseCtgTitle")
            ->where(['bank_id'=>$id,'transaction_info.is_active'=>1,'approved_status'=>2])
            ->leftJoin('all_settings', function($join) {
                $join->on('all_settings.id', '=', 'transaction_info.expense_ctg');
            })
            ->orderBy
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
