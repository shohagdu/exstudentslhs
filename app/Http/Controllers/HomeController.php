<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use App\Models\DonarInfo;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function index(){
        return view('home.donationForm');
    }
    public function donationSuccess(){
        //return view('home.donationSuccess');
    }


    public function donationFormAction(request $request){
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'mobileNumber' => ['required', 'string',  'max:11'],
            'sscBatch' => ['required', 'string'],
            'sendNumber' => ['required', 'string'],
            'donationBy' => ['required', 'string'],
//            'TransactionID' => ['required', 'string'],
            'donationAmount' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        $data=[
            'user_id'               => '',
            'name'                  => $request->name,
            'mobileNumber'          => $request->mobileNumber,
            'sscBatch'              => $request->sscBatch,
            'sendNumber'            => $request->sendNumber,
            'donationBy'            => $request->donationBy,
            'TransactionID'         => $request->TransactionID,
            'donationAmount'        => $request->donationAmount,
            'created_at'            => date('Y-m-d H:i:s'),
            'created_by'            => NULL,
            'created_ip'            => $request->ip(),
        ];

        $userInfo=[
            'name'                  => $data['name'],
            'email'                 => $data['mobileNumber'],
            'password'              => Hash::make(123456),
            'user_type'             => 4,
            'created_at'            => date('Y-m-d H:i:s'),
            'created_by'            => NULL,
            'created_ip'            => $request->ip(),
        ];

        DB::beginTransaction();
        try {
            User::create($userInfo);
            DonarInfo::create($data);
            DB::commit();
            $success_output = 'Thanks for this donation. ';

            Session::flash('message', $success_output);
            return redirect('/');
        } catch (\Exception $e) {
            DB::rollback();
            $error = $e->getMessage();
            Session::flash('message', $error);
            return redirect('/');
        }

    }

}
