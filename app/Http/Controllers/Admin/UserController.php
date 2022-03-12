<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;
use Hash;
use App\Models\User;



use Auth;
use Toastr;
use DB;

class UserController extends Controller
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
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::select('users.*')->orderBy('updated_at', 'desc');
            $query->where('users.is_admin','=',0);
            $query->whereIn('users.status',[1,2]);
            if(!empty($request->user_type)) {
                $query->where('users.user_type','=',$request->user_type);
            }else{
                $query->where('users.user_type','!=',5);
            }
            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->where(function($q) use ($searchText){
                    $q->orWhere('users.name', 'like', '%'.$searchText.'%');
                    $q->orWhere('users.email', 'like', '%'.$searchText.'%');
                });
            }

            $total = $query->count();
            $totalFiltered = $total;
            $result = $query->skip($request->start)->take($request->length)->orderBy('id', 'desc')->get();

            $data = [];

            if(count($result)>0) {
                $userRoleInfo = user::userRoleInfo();
                foreach($result as $key => $row) {
                    $rowData=[
                        'sl'                => $key+1,
                        'name'              => (app()->getLocale() == 'en') ? $row->name : $row->name,
                        'email'             => $row->email,
                        'mobile'            => $row->mobile,
                        'user_type'         => (!empty($userRoleInfo[$row->user_type])
                            ?$userRoleInfo[$row->user_type]:''),
                        'mobileBankBkash'   => $row->mobileBankBkash,
                        'userSscBatch'      => $row->userSscBatch,
                        'isActive'          => (!empty($row->status)?(($row->status==1)?"<span style='background-color: green !important;color: #fff;padding: 4px;border-radius: 5px'>Active</span>":"<span style='background-color: red !important;color: #fff;padding: 4px;border-radius: 5px'>Inactive</span>")
                            :''),
                    ];

                    $action='';
                    $action .= ' <a href="' . route('user.edit', $row->id) . '" data-toggle="tooltip"  data-id="' .
                        $row->id . '" data-original-title="Edit" class="btn bg-purple btn-xs edit editData" data-route="' . route('user.edit', $row->id) . '" ><i class="fa fa-edit"></i>Edit</a>';

                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteData"><i class="fa fa-trash"></i> Delete</a>';

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
        $users          = User::all();
        $loginUser      = User::find(Auth::id());
        $user           = Auth::user();
        $roles          = User::userRoleInfo();
        return view('admin.users.index', compact('users','user','loginUser','roles') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = User::userRoleInfo();
        return view('admin.users.create', compact('roles'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated_arr = $this->validate($request,[
            'id' => 'nullable',
            'name' => 'required|string',
            'user_type' => 'required|numeric',
            'email' => 'required|string|email',
            'password' => 'nullable|string',
        ]);

        if($request->id != null) {
            if ($request->has('password')) {
                unset($validated_arr['password']);
            }
            $validated_arr['updated_by'] = Auth::id();
            // code exist check
            $where = [
                ['email', '=', $request->email],
                ['deleted_by', '=', null],
                ['id', '!=', $request->id],
            ];
            $exist = DB::table('users')->where($where)->first();
            if (!empty($exist)) {
                $response = ['error' => 'Email Already Exist'];
                Toastr::error($response['error']);
                return redirect('admin/user/create')
                    ->withInput();
            }
        }
        else
        {
            $this->validate($request, [
                'password' => 'required|string|confirmed',
            ]);

            $validated_arr['created_by']        = Auth::id();
            $validated_arr['project_id']        = $request->project_id;
            $validated_arr['password']          = Hash::make($request->password);

            $validated_arr['mobile']            = $request->mobileNumber;
            $validated_arr['mobileBankBkash']   = $request->bkashNumber;
            $validated_arr['mobile']            = $request->mobileNumber;
            $validated_arr['userSscBatch']      = $request->sscBatch;

            // code exist check
            $where = [
                ['email', '=', $request->email],
                ['deleted_by', '=', null],
            ];
            $exist = DB::table('users')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'Email Already Exist'];
                Toastr::error($response['error']);
                return redirect('admin/user/create')
                    ->withInput();
            }
        }
        //dd($validated_arr);
        User::updateOrCreate(['id' => $request->id], $validated_arr);

        Toastr::success('User saved successfully');
        return redirect()->route('user.userRecord');

    }
    public function toggleStatus(Request $request)
    {
        $user = User::find($request->id);

        if($user->status == 1 )
        {
            $user->status = 0;

            $vals = array(
                'message' => 'User Deativated');
        }
        else if($user->status == 0)
        {
            $user->status = 1;

            $vals = array(
                'message' => 'User Activated');
        }

        $user->save();
        echo json_encode($vals);
        exit();
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // return 'show';
        $user = User::find($id);
        // return $user;
        return view('admin.users.appointment',compact('user'));
    }

    public function extension($id)
    {
        $extension = User::find($id);
        // return $extension;
        return view('admin.users.extension',compact('extension'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit( $id)
    {
        $user           = User::find($id);
        $roles          = User::userRoleInfo();
        return view('admin.users.edit', compact('user', 'roles'));

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
        $this->validate($request,[
            'email' => 'required|string|max:255',
            'name' => 'required|string|max:255'
        ]);

        $user = User::find($request->id);
        if($request->email != $user->email)
        {
            $this->validate($request,[
                'email' => 'required|string|max:255|unique:users'
            ]);
        }
        if(!empty($request->password)) {
            $user->password = Hash::make($request->password);
        }
        $user->name                 = $request->name;
        $user->email                = $request->email;
        $user->mobile               = $request->mobileNumber;
        $user->mobileBankBkash      = $request->bkashNumber;
        $user->user_type            = $request->user_type;
        $user->status               = $request->isActiveStatus;
        $user->userSscBatch         = $request->sscBatch;

        $user->updated_ip           = $this->ipAddress;
        $user->updated_by           = $this->userID;
        $user->updated_at           = $this->createdAt;

        DB::beginTransaction();
        try {
            $user->save();
            DB::commit();
            Toastr::success('User Updated successfully');
            return redirect()->route('user.userRecord');
        }catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
            Toastr::error($response);
            return redirect()->route('user.userRecord');
        }


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request  $request)
    {
        $user = User::find($request->id);
        if(empty($user)){
            return response()->json(['error' => 'This User is not eligible for Deleted']);
        }
        if($user == Auth::user()){
            return response()->json(['error' => 'You can not delete your own account']);
        }

        $user->deleted_at = $this->createdAt;
        $user->deleted_by = $this->userID;
        $user->updated_ip = $this->ipAddress;

        $user->updated_by = $this->userID;
        $user->updated_at = $this->createdAt;
        $user->status     = 0;

        DB::beginTransaction();
        try {
            $user->save();
            DB::commit();
            return response()->json(['success' => 'Successfully Deleted the User']);
        }catch (\Exception $e){
            DB::rollback();
            $response = ['error'=>$e->getMessage()];
            return response()->json($response);
        }
    }
}
