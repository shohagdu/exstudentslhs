<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Hash;
use App\User;
use App\Models\Profile;
use App\Models\AnchalChild;

use App\Models\Designations;
use App\Models\Departments;
use App\Models\Project;

use DataTables;
use Toastr;
use Auth;
use DB;
use DateTime;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = User::select('users.*','project.name as project_name')->orderBy('updated_at', 'desc');
            $query->join('project', function ($join) use ($request) {
                $join->on('project.project_code','=','users.project_id');
                if(!empty($request->project_id)) {
                    $join->where('project.project_code', '=', $request->project_id);
                }
            });
            $query->where('users.is_admin','=',0);
            if(!empty($request->user_type)) {
                $query->where('users.user_type','=',$request->user_type);
            }
            if(!empty($request->search['value'])) {
                $searchText = $request->search['value'];
                $query->where(function($q) use ($searchText){
                    $q->orWhere('users.name', 'like', '%'.$searchText.'%');
                    $q->orWhere('users.email', 'like', '%'.$searchText.'%');
                    $q->orWhere('project.name', 'like', '%'.$searchText.'%');
                });
            }

            $total = $query->count();

            $totalFiltered = $total;

            $result = $query->skip($request->start)->take($request->length)->orderBy('id', 'desc')->get();

            $data = [];

            if(count($result)>0) {

                foreach($result as $key => $row) {
                    $projectName = $row->project_name;
                    $typeName = '';
                    if($row->user_type==1) {
                        $typeName = 'Coordinator';
                    }
                    elseif($row->user_type==2) {
                        $typeName = 'MNE';
                    }
                    elseif($row->user_type==3) {
                        $typeName = 'Regular User';
                    }
                    elseif($row->user_type==0) {
                        $typeName = 'Regular User';
                    }

                    $rowData=[
                        'sl' => $key+1,
                        'name' => (app()->getLocale() == 'en') ? $row->name : $row->name,
                        'email' => $row->email,
                        'project_name' => $projectName,
                        'type_name' => $typeName,
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
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Edit" class="btn bg-purple btn-xs edit editData" data-route="' . route('users.edit', $row->id) . '" ><i class="fa fa-pencil"></i></a>';
                    $action .= ' <a class="btn btn-xs btn-warning" href="' . route('user_permissions', $row->id) . '"><i class="fa fa-shield" title="Assigned Forms"></i></a>';
                    $action .= ' <a href="javascript:void(0)" data-toggle="tooltip"  data-id="' . $row->id . '" data-original-title="Delete" class="btn btn-danger btn-xs deleteData"><i class="fa fa-trash"></i></a>';
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
        $users = User::all();
        $coordinator = User::where([['user_type','=',1],['is_admin','=',0]])->get();
        $emine = User::where([['user_type','=',2],['is_admin','=',0]])->get();
        $loginUser = User::find(Auth::id());
        $user = Auth::user();

        $anchalQuery = AnchalChild::query()
            ->select('anchal_auto_id','project_id','project.name');
        $anchalQuery->join('project', function ($join) use ($request) {
                $join->on('project.project_code','=','tb_anchal_child_reg.project_id');
            });
        $anchal = $anchalQuery->groupBy('anchal_auto_id')
            ->get();

        $project = $loginUser->is_admin==1 ? DB::table('project')->get() :  DB::table('project')->where('project_code',$loginUser->project_id)->get();
        return view('admin.users.index', compact('anchal','emine','coordinator','users','user','project') );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $roles = Role::all();
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
        // return $request;
        $validated_arr = $this->validate($request,[
            'id' => 'nullable',
            'permission' => 'nullable',
            'access_all_project' => 'nullable',
            'emine' => 'nullable',
            'coordinator' => 'nullable',
            'name' => 'required|string',
            'project_id' => 'required',
            'user_type' => 'required|numeric',
            'email' => 'required|string|email',
//            'email' => 'required|string|email|unique:users,email,'. $request->id,
            'password' => 'nullable|string|min:8',
        ]);
        // return $request;
        $validated_arr['access_all_project'] = !empty($request->access_all_project) ? 1: 0;
        $validated_arr['coordinator'] = !empty($request->coordinator) ? implode(',',$request->coordinator): null;
        $validated_arr['emine'] = !empty($request->emine) ? implode(',',$request->emine): null;
        $validated_arr['permission'] = !empty($request->permission) ? preg_replace('/\s+/', '', $request->permission) : null;

        if($request->id != null) {
            if ($request->has('password')) {
                unset($validated_arr['password']);
            }
            $validated_arr['updated_by'] = Auth::id();

            // code exist check
            $where = [
                ['email', '=', $request->email],
//                ['project_id', '=', $request->project_id],
                ['deleted_by', '=', null],
                ['id', '!=', $request->id],
            ];
            $exist = DB::table('users')->where($where)->first();
            if (!empty($exist)) {
                $response = ['error' => 'Email Already Exist'];
                return response()->json($response);
            }
        }
        else
        {
            $this->validate($request, [
                'password' => 'required|string|min:8|confirmed',
            ]);

            $validated_arr['created_by'] = Auth::id();
            $validated_arr['project_id'] = $request->project_id;
            $validated_arr['password'] = Hash::make($request->password);

            // code exist check
            $where = [
                ['email', '=', $request->email],
//                ['project_id', '=', $request->project_id],
                ['deleted_by', '=', null],
            ];
            $exist = DB::table('users')->where($where)->first();
            if(!empty($exist)) {
                $response = ['error'=>'Email Already Exist'];
                return response()->json($response);
            }
        }

        $user = User::updateOrCreate(['id' => $request->id], $validated_arr);
        $imagename ='/uploads/profile/avatar.jpg';
        // if(!empty($request->image)) {
        //     $profile_photo = $request->image;
        //     $profile_photo_new = time() . '_' . Auth::id() . '_' . $profile_photo->getClientOriginalName();
        //     $profile_photo->move('uploads/profile', $profile_photo_new);
        //     $imagename = '/uploads/profile/' . $profile_photo_new;
        // }

        // Profile::where('user_id','=', $request->id)->update([
        // 'image' =>  $imagename,
        if(!empty($request->id)) {
            // Profile::where('user_id','=', $request->id)->update([
            //     'image' =>  $imagename,
            // ]);
        }
        else  {
            $profile = new Profile;
            $profile->user_id = $user->id;
            $profile->image = $imagename;
            $profile->save();
        }

        return response()->json(['success'=>'Employee saved successfully.']);

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
    public function edit($id)
    {
        //
        $user = User::find($id);
        return $user;
        // $roles = Role::all();
        // return view('admin.users.edit', compact('user', 'roles'));

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
        $this->validate($request,[
            'email' => 'required|string|email|max:255',
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = User::find($id);
        if($request->email != $user->email)
        {
            $this->validate($request,[
                'email' => 'required|string|email|max:255|unique:users'
            ]);
        }

        if($request->password)
        {
            $user->password = Hash::make($request->password);
        }
        $user->name = $request->name;
        $user->email = $request->email;

        // $user->syncRoles($request->role);

        $user->updated_by = Auth::id();
        $user->save();
        Toastr::success('User Updated successfully');
        return redirect()->route('users.index');
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
        $user = User::find($id);
        if($user == Auth::user())
        {
            // Toastr::error('You can not delete your own account');
            // return redirect()->back();
            return response()->json(['error' => 'You can not delete your own account']);
        }
        elseif ($user->last_login != null) {
//            return response()->json(['error' => 'This user can be deleted now']);
        }
        $user->deleted_by = Auth::id();
        $user->save();
        $user->delete();
        return response()->json(['success' => 'Successfully deleted the User']);

        // Toastr::success('Successfully deleted the User');
        // return redirect()->route('users.index');
    }
}
