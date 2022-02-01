<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Hash;
use Toastr;
use App\User;
use App\Models\Profile;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;

class ProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $month = date('m');
        $year = date('Y');
        $logisticts = User::find($user->id);
        return view('admin.users.profile.profile1',compact('logisticts','user'));

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

    public function profilePhoto(Request $request)
    {
        // return $request;
        request()->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


       if ($request->hasFile('profile_photo'))
        {
            $profile_photo = $request->profile_photo;
            $profile_photo_new = time() . '_' . Auth::id() . '_' . $profile_photo->getClientOriginalName();
            $profile_photo->move('uploads/profile', $profile_photo_new);
            $imagename = '/uploads/profile/' . $profile_photo_new;

            Profile::where('user_id','=', $request->id)->update([
            'image' =>  $imagename,
        ]);

        }
        Toastr::success('Profile data updated successfully');
        return redirect()->route('profile');
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
        $user = User::find($id);
        $attendances = Attendance::where('emp_id','=',$user->id)->get();


        $requistions = RequisitionDetails::join('requisitions', function($join){
            return $join->on('requisitions.id', 'requisition_child.requisition_id')
            ->where('requisitions.row_status', Requisitions::$RECEIVE);
        })

        ->where('user_id', $user->id)
        ->get();

        $designations = Designations::all();
        $departments = Departments::all();
        $salary_grade = DB::table('salary_grade')->select('id','grade_name')->where('status','1')->orderBy('order_id','ASC')->get();


        $logistictsData = User::find($id);
        $logisticts = $logistictsData->logistics();
        $leaveSummary = $logistictsData->leaveSummary();
        $travels = $logistictsData->travels();
        return view('admin.users.profile.profile1',compact('travels','leaveSummary','logisticts','attendances','requistions', 'user','designations','departments','salary_grade'));
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
    public function update(Request $request)
    {
        $this->validate($request,[
            'email' => 'required|string|email|max:255',
            'password' => 'nullable|string|min:6|confirmed',
            'full_name' => 'required',
        ]);

        if($request->email != Auth::user()->email)
        {
            $this->validate($request,[
                'email' => 'required|string|email|max:255|unique:users'
            ]);
        }


        $user = Auth::user();
        $user->email = $request->email;
        $user->name = $request->full_name;
        if($request->password)
        {
            $user->password = Hash::make($request->password);
        }
        $user->updated_by = Auth::id();
        $user->save();

        $redirectTo = route('profile');
        $response = ['success'=>"Profile Data Save successful.", 'redirectTo' => $redirectTo];
        \Toastr::success($response['success']);

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
        //
    }
}
