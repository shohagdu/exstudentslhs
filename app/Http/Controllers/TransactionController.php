<?php

namespace App\Http\Controllers;

use App\Models\BankAccModel;
use Illuminate\Http\Request;
use App\Models\TransactionModel;
use Illuminate\Support\Facades\Validator;
use Auth;
use Toastr;
use DB;

class TransactionController extends Controller
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
            $query = TransactionModel::where([
                [
                    'approved_status',
                    '!=',
                    '0'
                ],[
                    'is_active',
                    '=',
                    '1'
                ],[
                    'type',
                    '=',
                    '1'
                ]
            ])->
            select('*');

            $total = $query->count();
            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->when(($searchText), function($query) use ($searchText) {
                    $query->where(function($q) use ($searchText){
                        $q->orWhere('remarks', 'like', '%'.$searchText.'%');
                        $q->orWhere('receiptNo', 'like', '%'.$searchText.'%');
                        $q->orWhere('transCode', 'like', '%'.$searchText.'%');
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
                        $btn .= ' <button type="button" class="btn btn-info btn-sm " data-toggle="modal" data-target="#transactionModal" data-toggle="tooltip" title="Edit  Info" onclick="updateInvInfo(' . $row->id . ')" id="edit_' . $row->id . '" ><i class="fa fa-edit"></i> Edit </button>';

                        if($userType==1 || $userType==2) {
                            $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="' . $row->id
                                . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteData"><i class="fa fa-times"></i> Delete </a>';
                        }
                    }
                    $attachment=(!empty($row->attachmentInfo)?$row->attachmentInfo:'');
                    $transCode=!empty($attachment)?"<a href='".url($attachment)."' target='_blank'>".$row->transCode
                        ."</a>":$row->transCode;

                    $data[] = [
                        'sl'                        => $sl++,
                        'transCode'                 => $transCode,
                        'trans_date'                => (!empty($row->trans_date)?date('d M ,Y',strtotime
                        ($row->trans_date)):''),
                        'remarks'                   => $row->remarks,
                        'receiptNo'                 => (!empty($row->receiptNo)?$row->receiptNo:''),
                        'debit_amount'              => (!empty($row->debit_amount)?$row->debit_amount:''),
                        'credit_amount'             => (!empty($row->credit_amount)?$row->credit_amount:''),
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
        $bankInfo       = BankAccModel::where(['softDelete'=>0])->pluck('accountName','id');
        $data = [
            'page_title'    => 'Bank Account Record',
            'bankInfo'      => $bankInfo,
        ];
        return view('admin.transaction.index',compact('data'));
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
       // dd($request->all());
        $validator = Validator::make($request->all(), [
            'bankID'              => ['required', 'numeric'],
            'transDate'      => ['required'],
            'Amount'          => ['required', 'numeric'],
        ],[
            'bankID.required'                    => 'Bank ID is required',
            'transDate.required'                 => 'Trans. Date is required',
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

        $destinationImagePath = 'uploads/transReceipt';
        $extensionArray = ['jpg', 'jpeg', 'png', 'pdf', 'PNG', 'JPG', 'JPEG', 'PDF'];


        if (!empty($request->file('invoice'))) {
            $image = $request->file('invoice');
            $extension = $image->getClientOriginalExtension();

            $invoiceFile = $image->getSize();
            $invoiceFileMB = number_format($invoiceFile / 1048576, 2);
            if ($invoiceFileMB > 15) {
                $error_array[] = 'File Attachment Maximum size 15 MB ';
                $response = ['error'=> $error_array];
                return response()->json($response);
            }
            if (!in_array($extension, $extensionArray)) {
                $error_array[] = 'File Extension  should be jpg, jpeg, png or pdf ';
                $response = ['error'=> $error_array];
                return response()->json($response);
            }
            if ($invoiceFileMB <= 5 && in_array($extension, $extensionArray)) {
                $file_name = "inv_" .time() .'.' . $extension;
                $image->getClientOriginalName();
                $image->move($destinationImagePath, $file_name);
                $invAttachment = $destinationImagePath . '/' . $file_name;
            } else {
                $invAttachment = '';
            }
        } elseif (!empty($request->invoiceOld)) {
            $invAttachment = $request->invoiceOld;
        } else {
            $invAttachment = '';
        }

        if(empty($request->update_id)) {
            DB::beginTransaction();
            try {
                $transactionId = TransactionModel::getTransactionId();
                $data_arr = [
                    'transCode' => $transactionId,
                    'bank_id' => $request->bankID,
                    'trans_date' => (!empty($request->transDate) ? date('Y-m-d', strtotime($request->transDate)) : ''),
                    'type' => 1,
                    'remarks' => $request->Remarks,
                    'receiptNo' => $request->ReceiptNo,
                    'debit_amount' => $request->Amount,
                    'attachmentInfo' => $invAttachment,
                    'approved_status' => 2,
                    'is_active' => 1,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'created_ip' => $request->ip(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_ip' => $request->ip(),

                ];
                //  dd($data_arr);
                TransactionModel::insert($data_arr);


                DB::commit();
                $redirectTo = route('bankTransaction.index');
                $response = ['success' => "Transaction Saved Successful.", 'redirectTo' => $redirectTo];
                \Toastr::success($response['success']);
            } catch (\Exception $e) {
                DB::rollback();
                $response = ['error' => $e->getMessage()];
            }

            return response()->json($response);
        }else{
            DB::beginTransaction();
            try {
                $transactionId = TransactionModel::getTransactionId();
                $data_arr = [
                    'transCode' => $transactionId,
                    'bank_id' => $request->bankID,
                    'trans_date' => (!empty($request->transDate) ? date('Y-m-d', strtotime($request->transDate)) : ''),
                    'type' => 1,
                    'remarks' => $request->Remarks,
                    'receiptNo' => $request->ReceiptNo,
                    'debit_amount' => $request->Amount,
                    'attachmentInfo' => $invAttachment,
                    'approved_status' => 2,
                    'is_active' => 1,
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_ip' => $request->ip(),

                ];
                //  dd($data_arr);
                TransactionModel::where('id',$request->update_id)->update($data_arr);


                DB::commit();
                $redirectTo = route('bankTransaction.index');
                $response = ['success' => "Transaction Update Successful.", 'redirectTo' => $redirectTo];
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
