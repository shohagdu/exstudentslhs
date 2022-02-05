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
    //    public function __construct()
    //    {
    //        dd($this->middleware('guest'));
    //    }

    public function index(){
        $fundCoordinator    =   User:: select(DB::raw("CONCAT(mobileBankBkash,' (',name,')') AS name"),'id')->where(['user_type'=>3,'status'=>1])->pluck('name','id');
        return view('home.donationForm', compact('fundCoordinator'));
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
            'mobileNumber'      => ['required', 'string',  'min:11'],
            'sscBatch'          => ['required'],
            'sendNumber'        => ['required'],
            //'donationBy'        => ['required'],
//            'TransactionID' => ['required', 'string'],
            'donationAmount'    => ['required'],
            'TransactionMobileNumber'=> ['required'],
        ],[
            'name.required'                         => 'আপনার নাম প্রদান করুন',
            'mobileNumber.required'                 => 'আপনার মোবাইল নাম্বার প্রদান করুন',
            'sscBatch.required'                     => 'আপনার এস.এস.সি ব্যাচ চিহ্নিত করুন',
            'donationAmount.required'               => 'আপনার ডোনেশানের পরিমান প্রদান করুন',
            'sendNumber.required'                   => 'যে বিকাশ নাম্বারে টাকা পাঠাবেন চিহ্নিত',
            'TransactionMobileNumber.required'      => 'যে বিকাশ নাম্বার থেকে টাকা পাঠানো হয়েছে প্রদান করুন'
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
            'TransactionMobileNumber'=> $request->TransactionMobileNumber,
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
            $success_output ="Dear {$data['name']}, Thank you for your great generosity! We, at 'Ex. Student Forum of Lemua High School',  greatly appreciate your donation. Your support helps to succeed our mission. ";
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
