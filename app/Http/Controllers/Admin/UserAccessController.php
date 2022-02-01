<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Toastr;

class UserAccessController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //


        // $role_names = ["General Employee", "Approval User", "Buyer User"];

        // foreach ($role_names as $key => $role_name) {
        //     $role = Role::create(['name' => $role_names]);
        // }


        // $permission_names = ["All Employees", "Departments", "Designations", "Manage Salary", "Salary List", "New Increment", "Increment List", "Make Payment", "Generate Payslip", "Salary Sheet", "Provident Fund", "Salary Grade", "Own Leave Register", "Leave Entry", "Leave Approve Register", "Leave Types", "Leave Summary", "Requisition Register", "Supervise Register", "Approve Register", "Purchase Register", "Issue Register", "Requisition Entry", "Project List", "Project Category", "Product List", "Product Category", "Product Return", "Create Attendance", "My Attendance", "Division", "District", "Upazila", "Union", "Project Assign", "Suppliers"];



        // foreach ($permission_names as $key => $permission_name) {
        //     Permission::create(['name' => $permission_name]);
        // }

        // $admin_role = Role::create(['name' => "Admin"]);

        // $permissions = Permission::all();

        // $admin_role->syncPermissions($permissions);

        // $Approval_User_Role = Role::findByName("Approval User");
        // $Buyer_User_Role = Role::findByName("Buyer User");

        // $permissions = Permission::whereIn('name', ['Project Management', 'Product Management', 'Requisition Approve', 'Requisition Purchase', 'Leave Application Approve', 'Project Management', 'Product Management'])->get();

        // $Approval_User_Role->syncPermissions($permissions);
        // $Buyer_User->syncPermissions($permissions);

        // var_dump($super_admin_role);
        // var_dump($admin_role);
        // var_dump($permissions);

        // var_dump($approval_user->getAllPermissions());
        // var_dump($buyer_user->getAllPermissions());

        // die();


        // $users = User::where('type', 2)->get();
        // $roles = Role::all();

        // foreach ($users as $key => $user) {
        //     $user->syncRoles($roles);

        //     echo($user->getRoleNames());
        // }
        // return ;

    }

    public function user_permissions($id)
    {
        $permissions = Permission::all();
        $user = User::find($id);
        $user_permissions = $user->getAllPermissions()->pluck('id');
//        dd($user_permissions);
        if(empty($user))
            return redirect()->back();


        // return $permissions;
        // return $user;
        return view('admin.users.user-access', compact('permissions', 'user'));
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
        $user = User::find($request->user_id);
        $user->syncPermissions($request->permission);
        Toastr::success('Assigned forms updated successfully');
        return redirect()->back();

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
