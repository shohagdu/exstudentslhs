<?php

namespace App\Http\Controllers;
use App\Models\SmsHistory;
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
//        public function __construct()
//        {
//            $this->middleware('guest');
//        }

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
            'mobileNumber'      => ['required',   'min:11'  ,'numeric'],
            'sscBatch'          => ['required'],
            'sendNumber'        => ['required'],
            //'donationBy'        => ['required'],
//            'TransactionID' => ['required', 'string'],
            'donationAmount'    => ['required','numeric'],
            'TransactionMobileNumber'=> ['required',  'min:11'  ,'numeric'],
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
            'user_type'             => 5,
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
    public function sendSms(){
        $getSms=SmsHistory::select('id','mobile_number','msg')->where(['send_status'=>1])->orderBy('id','ASC')->limit(10)->get();
        if(!empty($getSms[0])){
            foreach ($getSms as $key=>$row){
                if(!empty($row->mobile_number)) {
                    $sendSmsLog[$key] = [
                        'mobile_no' =>  $row->mobile_number,
                        'message'   =>  $row->msg,
                    ];
                    $url = 'https://www.24bulksmsbd.com/api/smsSendApi';
                    $data = array(
                        'customer_id' => 66,
                        'api_key' => 171574717329433701673050507,
                        'message' => $row->msg,
                        'mobile_no' => $row->mobile_number
                    );

                    $curl = curl_init($url);
                    curl_setopt($curl, CURLOPT_POST, true);
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
                    $output = curl_exec($curl);
                    curl_close($curl);
                    $response=(!empty($output)?json_decode($output,true):'');

                    if(!empty($response)) {
                        $updateInfo = [
                            'send_status' => 2,
                            'updated_at' => date('Y-m-d H:i:s'),
                        ];
                        if (!empty($response['status']) && $response['status'] == 'ok') {
                            $updateInfo['success_status'] = 1;
                            $sendSmsLog[$key]['status'] = (!empty($response['message'])?$response['message']:'').' <> Successfully Send Sms';
                        } else {
                            $updateInfo['success_status'] = 2;
                            $sendSmsLog[$key]['status']= (!empty($response['message'])?$response['message']:''). ' <> Failed to Send Sms';
                        }
                        SmsHistory::where('id', $row->id)->update($updateInfo);
                    }


                }
            }

            if(!empty($sendSmsLog)){
                $si=1;
                echo "<table border='1px' rules='all' style='border: 1px solid #eee;'>";
                    echo "<tr>";
                        echo "<td>S/N</td>";
                        echo "<td>Mobile Number</td>";
                        echo "<td>Message</td>";
                        echo "<td>Status</td>";
                    echo "</tr>";
                    foreach ($sendSmsLog as $log){
                        echo "<tr>";
                            echo "<td>".$si++."</td>";
                            echo "<td>".$log['mobile_no']."</td>";
                            echo "<td>".$log['message']."</td>";
                            echo "<td>".$log['status']."</td>";
                        echo "</tr>";
                    }
                echo "</table>";
            }

        }else{
            echo "<h1 style='text-align: center;'>No SMS Pending here....</h1>";
        }
    }


}
