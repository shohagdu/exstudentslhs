<?php

namespace App\Http\Controllers;

use App\Models\BankAccModel;
use App\Models\TransactionModel;
use Illuminate\Http\Request;
use App\Models\ExpenseCtgModel;
use App\Models\ExpenseModel;
use Auth;
use Illuminate\Support\Facades\Validator;
use Toastr;
use DB;

class ExpenseController extends Controller
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
            $query = ExpenseModel::where(['transaction_info.type'=>3,'approved_status'=>2,'transaction_info.is_active'=>1])->
            select('transaction_info.*',"users.name as userName","all_settings.title as expenseCtgTitle");
            $query->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'transaction_info.transBy');
            });
            $query->leftJoin('all_settings', function($join) {
                $join->on('all_settings.id', '=', 'transaction_info.expense_ctg');
            });

            if(isset(Auth::user()->user_type) && (Auth::user()->user_type==2 ||Auth::user()->user_type==3 ||
                    Auth::user()->user_type==4)){
                $query->where('transaction_info.transBy', '=', Auth::id());
            }
            if(!empty($request->expenseBy)){
                $query->where('transaction_info.transBy', '=', $request->expenseBy);
            }
            $query->when(($request->status), function($query) use($request)  {
                $query->where('approvedStatus', $request->status);
            });
            $query->when(($request->expenseCtgSearch), function($query) use($request)  {
                $query->where('expense_ctg', $request->expenseCtgSearch);
            });

            $total = $query->count();
            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->when(($searchText), function($query) use ($searchText) {
                    $query->where(function($q) use ($searchText){
                        $q->orWhere('transaction_info.transCode', 'like', '%'.$searchText.'%');
                        $q->orWhere('transaction_info.remarks', 'like', '%'.$searchText.'%');
                        $q->orWhere('transaction_info.receiptNo', 'like', '%'.$searchText.'%');
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
                        $btn .= ' <button type="button" class="btn btn-info btn-sm " data-toggle="modal" data-target="#expenseModal" data-toggle="tooltip" title="Edit Expense Info" onclick="updateExpenseInfo(' . $row->id . ')" id="editExpense_' . $row->id . '" ><i class="fa fa-edit"></i> Edit </button>';

                        if($userType==1 || $userType==2) {
                            $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="' . $row->id
                                . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteData"><i class="fa fa-times"></i> Delete </a>';
                        }
                    }
                    $attachment=(!empty($row->attachmentInfo)?$row->attachmentInfo:'');
                    $ctg=!empty($attachment)?"<a href='".url($attachment)."' target='_blank'>".$row->expenseCtgTitle
                        ."</a>":$row->expenseCtgTitle;
                    $data[] = [
                        'sl'                => $sl++,
                        'transCode'         => $row->transCode,
                        'bank_id'           => $row->transCode,
                        'trans_date'        => (!empty($row->trans_date) ? date('d M, Y', strtotime($row->trans_date))
                            : ''),
                        'remarks'           =>  $row->remarks,
                        'expenseCtg'        =>  $ctg,
                        'expenseBy'         =>  $row->transBy,
                        'credit_amount'     =>  $row->credit_amount,
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
        $bankInfo           = BankAccModel::where(['softDelete'=>0])->pluck('accountName','id');
        $expenseCtg         = ExpenseCtgModel::where(['is_active'=>1,'type'=>1])->pluck('title','id');
        $data = [
            'page_title'        => 'Expense Record',
            'bankInfo'          => $bankInfo,
            'expenseCtg'        => $expenseCtg
        ];


        return view('admin.expense.index',compact('data'));
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
            'bankID'                => ['required', 'numeric'],
            'transDate'             => ['required'],
            'expense_ctg'           => ['required'],
            'Amount'                => ['required', 'numeric'],
        ],[
            'bankID.required'                    => 'Bank ID is required',
            'transDate.required'                 => 'Trans. Date is required',
            'expense_ctg.required'               => 'Expense Category is required',
            'Amount.required'                    => 'Amount is required',
        ]);
        $error_array=array();
        if ($validator->fails()) {
            foreach ($validator->messages()->getMessages() as $field_name => $messages) {
                $error_array[] = $messages;
            }

            $response = ['error'=> $error_array];
            return response()->json($response);
        }

        $destinationImagePath = 'uploads/expenseAttach';
        $extensionArray = ['jpg', 'jpeg', 'png', 'pdf', 'PNG', 'JPG', 'JPEG', 'PDF'];

        if (!empty($request->file('invoice'))) {
            $image = $request->file('invoice');
            $extension = $image->getClientOriginalExtension();

            $vaccineCardImageSize = $image->getSize();
            $vaccineCardImageSizeMB = number_format($vaccineCardImageSize / 1048576, 2);
            if ($vaccineCardImageSizeMB > 15) {
                $error_array[] = 'File Attachment Maximum size 15 MB ';
                $response = ['error'=> $error_array];
                return response()->json($response);
            }
            if (!in_array($extension, $extensionArray)) {
                $error_array[] = 'File Extension  should be jpg, jpeg, png or pdf ';
                $response = ['error'=> $error_array];
                return response()->json($response);
            }
            if ($vaccineCardImageSizeMB <= 5 && in_array($extension, $extensionArray)) {
                $file_name = "exp_" .time() .'.' . $extension;
                $image->getClientOriginalName();
                $image->move($destinationImagePath, $file_name);
                $expenseAttachment = $destinationImagePath . '/' . $file_name;
            } else {
                $expenseAttachment = '';
            }
        } elseif (!empty($request->invoiceOld)) {
            $expenseAttachment = $request->invoiceOld;
        } else {
            $expenseAttachment = '';
        }

        if(empty($request->update_id)) {
            DB::beginTransaction();
            try {
                $transactionId = TransactionModel::getTransactionId();

                $data_arr = [
                    'transCode' => $transactionId,
                    'bank_id' => $request->bankID,
                    'trans_date' => (!empty($request->transDate) ? date('Y-m-d', strtotime($request->transDate)) : ''),
                    'type' => 3,
                    'remarks' => $request->Remarks,
                    'transBy' => $request->expenseBy,
                    'expense_ctg' => $request->expense_ctg,
                    'credit_amount' => $request->Amount,
                    'attachmentInfo' => $expenseAttachment,
                    'approved_status' => 2,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'created_ip' => $request->ip(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_ip' => $request->ip()
                ];

                //  dd($data_arr);
                ExpenseModel::insert($data_arr);
                DB::commit();
                $redirectTo = route('expenseRecord');
                $response = ['success' => "Expense Saved Successful.", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['error' => $e->getMessage()];
            }
            return response()->json($response);
        }else{

            DB::beginTransaction();
            try {
                $data_arr = [
                    'bank_id' => $request->bankID,
                    'trans_date' => (!empty($request->transDate) ? date('Y-m-d', strtotime($request->transDate)) : ''),
                    'type' => 3,
                    'remarks' => $request->Remarks,
                    'transBy' => $request->expenseBy,
                    'expense_ctg' => $request->expense_ctg,
                    'credit_amount' => $request->Amount,
                    'attachmentInfo' => $expenseAttachment,
                    'is_active' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_ip' => $request->ip()
                ];

                //  dd($data_arr);
                ExpenseModel::where('id',$request->update_id)->update($data_arr);
                DB::commit();
                $redirectTo = route('expenseRecord');
                $response = ['success' => "Expense Update Successful.", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['error' => $e->getMessage()];
            }
            return response()->json($response);

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
            $info   =   ExpenseModel::where(['id'=>$request->id])->first();
            DB::commit();
            $info->transDataTitle=(!empty($info->trans_date)?date('d-m-Y',strtotime($info->trans_date)):'');
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
            $info   =   ExpenseModel::where(['id'=>$id])->first();
            if(!empty($info)) {
                $deleteDataInfo = [
                    'is_active'     => 0,
                    'updated_at'    => date('Y-m-d H:i:s'),
                    'updated_by'    => Auth::id(),
                    'updated_ip'    => ''
                ];

                ExpenseModel::where('id',$id)->update($deleteDataInfo);

                DB::commit();
                $redirectTo = route('expenseRecord');
                $response = ['success' => "Expense Delete Successful.", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            }else{
                DB::rollback();
                $response = ['status'=>'error','message'=>'Failed to delete information','data'=>[]];
            }
        }catch (\Exception $e){
            DB::rollback();
            $response = ['status'=>'error','message'=>$e->getMessage(),'data'=>[]];
        }
        return response()->json($response);
    }
}
