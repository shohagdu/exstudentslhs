<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\TransactionalCOA;
use App\Models\GeneralLedger;
use App\Models\DonarInfo;
use Auth;
use Toastr;
use DB;



class DonationController extends Controller
{
    private $createdAt;
    private $userID;
    private $ipAddress;
    public function __construct()
    {
        $this->createdAt    = date('Y-m-d H:i:s');
        $this->ipAddress    = \request()->ip();
        $this->userID       = $this->middleware(function ($request, $next) {
            $this->userID = Auth::user()->id;
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $searchText = !empty($request->search['value']) ? $request->search['value'] : false;
            $query = DonarInfo::where([
                [
                    'isActive',
                    '=',
                    '1'
                ]

            ])->
            select('donarinfos.*','users.mobileBankBkash',"users.name as userName");
            $query->leftJoin('users', function($join) {
                $join->on('users.id', '=', 'donarinfos.sendNumber');
            });
            if(isset(Auth::user()->user_type) && (Auth::user()->user_type==2 ||Auth::user()->user_type==3 ||
                Auth::user()->user_type==4)){
                $query->where('donarinfos.sendNumber', '=', Auth::id());
            }
            if(!empty($request->collectionCoOrdinator)){
                $query->where('donarinfos.sendNumber', '=', $request->collectionCoOrdinator);
            }
            $total = $query->count();
            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)
                ->when(($searchText), function($query) use ($searchText) {
                    $query->where(function($q) use ($searchText){
                        $q->orWhere('donarinfos.name', 'like', '%'.$searchText.'%');
                        $q->orWhere('donarinfos.mobileNumber', 'like', '%'.$searchText.'%');
                        $q->orWhere('donarinfos.TransactionID', 'like', '%'.$searchText.'%');
                        $q->orWhere('donarinfos.TransactionMobileNumber', 'like', '%'.$searchText.'%');
                        $q->orWhere('donarinfos.donationAmount', 'like', '%'.$searchText.'%');
                    });
                })
                ->when(($request->status), function($query) use($request)  {
                    $query->where('approvedStatus', $request->status);
                })

                ->orderBy('id', 'DESC')
                ->get();


            $data = [];
            if(count($result) > 0) {
                $sl = $request->start + 1;

                foreach ($result as $key => $row) {
                    $btn = '';

                    if($row->approvedStatus==1) {
                        $btn .= ' <button type="button" class="btn btn-info btn-sm " data-toggle="modal" data-target="#donationModal" data-toggle="tooltip" title="View Donation Modal" onclick="updateDoantionInfo(' . $row->id . ')" id="editUserBasicInfo_' . $row->id . '" ><i class="glyphicon glyphicon-pencil"></i><i class="fa fa-eye"></i> View </button>';
                    }


                    // $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" title="Details"  data-id="' .      $row->id . '" data-original-title="Transaction Details" class="btn btn-success btn-sm viewStockDetail"><i class="fa fa-eye"></i></a>';

                 //   $btn .= ' <button href="'.$editRoute.'" data-toggle="tooltip" title="Edit"  data-id="' .   $row->id .'" data-original-title="Edit" class="btn bg-purple btn-sm  editData"><i class="fa fa-edit"></i></a>';

                //    $btn .= ' <a href="javascript:void(0)" data-toggle="tooltip" title="Delete"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-sm deleteData"><i class="fa fa-trash"></i></a>';

                    $data[] = [
                        'sl'                => $sl++,
                        'name'              => $row->name,
                        'mobileNumber'      => $row->mobileNumber,
                        'sscBatch'          => $row->sscBatch,
                        'sendNumber'        => $row->mobileBankBkash." (".$row->userName.")",
                        'TransactionID'     => $row->TransactionID,
                        'donationAmount'    => $row->donationAmount,
                        'TransactionMobileNumber'    => $row->TransactionMobileNumber,
                        'created_at'        => date('d M, Y h:i a',strtotime($row->created_at)),
                        'action'            => $btn,
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
            'breadcrumb' => [
                [
                    'title' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'active' => true,
                ],
                [
                    'title' => 'Account Transaction',
                    'url' => route('admin.dashboard'),
                    'active' => true,
                ],
                [
                    'title' => 'Capital Investment',
                    'url' => false,
                    'active' => false,
                ]
            ],
            'page_title'=> 'Capital Investment'
        ];
        $fundCoordinator    =   User:: select(DB::raw("CONCAT(mobileBankBkash,' (',name,')') AS name"),'id')->where(['user_type'=>3,'status'=>1])->pluck('name','id');
        return view('admin.donation.index',compact('data','fundCoordinator'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = [
            'breadcrumb' => [
                [
                    'title' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'active' => true,
                ],
                [
                    'title' => 'Account Transaction',
                    'url' => route('admin.dashboard'),
                    'active' => true,
                ],
                [
                    'title' => 'Capital Investment',
                    'url' => route('accounting_transaction.capital_investment'),
                    'active' => true,
                ],
                [
                    'title' => 'Add',
                    'url' => false,
                    'active' => false,
                ]
            ],
            'page_title'=> 'Add Capital Investment',
            'receiveing_accounts' => TransactionalCOA::getAllTransactionalCurrentAsset()
        ];
        return view('admin.accounting_transaction.capital_investment.create',compact('data'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'invest_amount' => 'required|numeric',
            'comment' => 'required',
            'receiving_account' => 'required|integer'
        ]);

        DB::beginTransaction();

        try {
            $transactionId = GeneralLedger::getTransactionId();
            $data_arr = [
                'transaction_id' => $transactionId,
                'amount' => $request->invest_amount,
                'comment' => $request->comment,
                'transaction_type' => GeneralLedger::$CAPITALINVESTMENT,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::id(),
                'created_by_ip' => $request->ip(),
                'updated_at' => date('Y-m-d H:i:s'),
                'updated_by' => Auth::id(),
                'updated_by_ip' => $request->ip(),

            ];

            GeneralLedger::insert($data_arr);

            $primaryId = DB::getPdo()->lastInsertId();

            $transactionDetails = [
                [
                    'transaction_parent_id' => $primaryId,
                    'debit_id' => $request->receiving_account,
                    'credit_id' => null,
                    'amount' => $request->invest_amount,
                    'transaction_type' => GeneralLedger::$CAPITALINVESTMENT,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'created_by_ip' => $request->ip(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_by_ip' => $request->ip()
                ],
                [
                    'transaction_parent_id' => $primaryId,
                    'credit_id' => TransactionalCOA::getOwnerInvestmentTransactionCOA()->id,
                    'debit_id' => null,
                    'amount' => $request->invest_amount,
                    'transaction_type' => GeneralLedger::$CAPITALINVESTMENT,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::id(),
                    'created_by_ip' => $request->ip(),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'updated_by' => Auth::id(),
                    'updated_by_ip' => $request->ip()
                ]
            ];
            GeneralLedger::insert($transactionDetails);

            DB::commit();
            $redirectTo = route('accounting_transaction.capital_investment');
            $response = ['success'=>"Transaction Saved Successful.", 'redirectTo' => $redirectTo];
            \Toastr::success($response['success']);
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
        $debitDetails = GeneralLedger::select('*')->where(
            [
                [
                    'transaction_parent_id',
                    '=',
                    $id
                ],
                [
                    'debit_id',
                    '>',
                    0
                ]
            ]
        )->first($id);
        $data = [
            'breadcrumb' => [
                [
                    'title' => 'Dashboard',
                    'url' => route('admin.dashboard'),
                    'active' => true,
                ],
                [
                    'title' => 'Account Transaction',
                    'url' => route('admin.dashboard'),
                    'active' => true,
                ],
                [
                    'title' => 'Capital Investment',
                    'url' => route('accounting_transaction.capital_investment'),
                    'active' => true,
                ],
                [
                    'title' => 'Add',
                    'url' => false,
                    'active' => false,
                ]
            ],
            'page_title'=> 'Edit Capital Investment',
            'receiveing_accounts' => TransactionalCOA::getAllTransactionalCurrentAsset(),
            'transaction_info' => GeneralLedger::find($id),
            'transaction_details' => GeneralLedger::getTransactionDetails($id),
            'debit_details' => $debitDetails,
        ];
        return view('admin.accounting_transaction.capital_investment.edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'update_id' => 'required|numeric',
        ]);

        if($request->currentStatus==$request->nextStatus) {
            $response = ['error' =>'This Application has current status and next status is same.'];
            return response()->json($response);
        }

        $currentData = DonarInfo::find($request->update_id);
        if($currentData->approvedStatus==$request->nextStatus){
            $response = ['error' =>'This Application has current status and next status is same.'];
            return response()->json($response);
        }

        DB::beginTransaction();
        try {
            $updateInfo=[
                'updated_at'        => date('Y-m-d H:i:s'),
                'updated_by'        => Auth::id(),
                'updated_ip'     => $request->ip()
            ];

            $data = [
                'approvedStatus'    => 2,
                'processInfo'       => json_encode($updateInfo),
                'updated_at'        => date('Y-m-d H:i:s'),
                'updated_by'        => Auth::id(),
                'updated_ip'     => $request->ip()
            ];
            DonarInfo::where('id',$request->update_id)->update($data);
            DB::commit();
            $redirectTo = route('donation.donationRecord');
            $response = ['success'=>"Successfully Approved", 'redirectTo' => $redirectTo];
            \Toastr::success($response['success']);
        } catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
        }

        return response()->json($response);
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
            GeneralLedger::deleteTransaction($id);
            DB::commit();
            $redirectTo = route('accounting_transaction.capital_investment');
            $response = ['success'=>"Transaction Deleted Successful.", 'redirectTo' => $redirectTo];
            \Toastr::success($response['success']);
        }
        catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
        }

        return response()->json($response);

    }
    public function singleDonationInfo(Request $request){
        DB::beginTransaction();
        try {
            $info   =   DonarInfo::where(['id'=>$request->id])->first();
            DB::commit();
            $response = ['status'=>'success', 'message'=>"Data Found Successfully", 'data' => $info];
        }catch (\Exception $e){
            DB::rollback();
            $response = ['status'=>'error','message'=>$e->getMessage(),'data'=>[]];
        }
        return response()->json($response);
    }

}
