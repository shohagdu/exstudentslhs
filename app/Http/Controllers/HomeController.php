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
    public function aboutUs(){
        return view('home.aboutUs');
    }
    public function donationProcess(){
        return view('home.donationProcess');
    }


    public function donationFormAction(request $request){
        $validator = Validator::make($request->all(), [
            'name'              => ['required', 'string', 'max:255'],
            'mobileNumber'      => ['required', 'string',  'max:11'],
            'sscBatch'          => ['required'],
            'sendNumber'        => ['required'],
            'donationBy'        => ['required'],
//            'TransactionID' => ['required', 'string'],
            'donationAmount'    => ['required'],
        ]);

        if ($validator->fails()) {
            return redirect('/')
                ->withErrors($validator)
                ->withInput();
        }

        $data=[
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
            'mobile'                => $data['mobileNumber'],
            'password'              => Hash::make(123456),
            'user_type'             => 4,
            'created_at'            => date('Y-m-d H:i:s'),
            'created_by'            => NULL,
            'created_ip'            => $request->ip(),
        ];

        DB::beginTransaction();
        try {
            $userInfo           =   User::create($userInfo);
            $data['user_id']    =   $userInfo->id;
            DonarInfo::create($data);
            DB::commit();
            $success_output ="Dear {$data['name']}, Thank you for your great generosity! We, at 'Ex. Student Forum of Lemua High School',  greatly appreciate your donation. Your support helps to succeed our mission.";
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
