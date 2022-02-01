@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ URL::asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/iCheck/all.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('backend/dist/css/reports_css.css') }}">
    <style>
        @media print
            {
            .noprint {display:none;}
            }
    </style>

@endpush

@section('content')
    <!-- Main content -->
    <section id="" class="">
        <!-- Content Wrapper. Contains page content -->
        <div class="">
          <!-- Content Header (Page header) -->
          <section class="content-header">
            <h1>Appointment</h1>
            <ol class="breadcrumb">
	            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
	            <li><a href="#">Reports</a></li>
	            <li class="active">Cash Book</li>
            </ol>
          </section>
          <!-- Main content -->
        <section class="invoice">
            {{-- @foreach ($user as $record) --}}
            <div class="row">
                <div class="col-md-6">
                <p>Memo No.: CIPRB/Employment/{{ date('Y') }}/{{ $user->id }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <p>Date: {{ date('d M Y') }} </p>
                </div>
                <div class="col-md-6">
                    To <br>
                    <strong>Mr. {{ $user->full_name }}</strong><br> 
                    C/o- {{ $user->father_name }} <br>
                    {{ $user->present_address }} 
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6 col-md-offset-3 text-center"><strong><u>Subject: Appointment Letter</u></strong></div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <strong>Dear Mr. {{ $user->full_name }},</strong><br>
                        <p>It is our pleasure to inform you that you have been selected for appointment as <b>{{ $user->designation->name }}</b> under the following terms and conditions: </p><br>

                                <ul>
                                    <ol>1. Project    : <b>Comprehensive Drowning Reduction Project, Barisal (Bhasa)</b></ol>
                                    <ol>2. Duty Station  :  Kolapara, Patuakhali</ol>
                                    <ol>3. Duration of Contract  : You will be on probation period for 03 months from the date of your joining i.e. {{ date('d F Y',strtotime($user->joinig_date)) }} , extendable based on your performance and organizational requirement in future</ol>
                                    <ol>4. Salary :  You are placed in {{ $user->salaryGrade->grade_name }} , Step-4 and your monthly consolidated salary for this post will be Tk. 51,396/= (Taka Fifty One Thousand Three Hundred and Ninety Six) only</ol>
                                    <ol>5. Travel and DSA : The rules should be followed as set in the HR Policy of CIPRB</ol>
                                    <ol>6. Schedule of Work          : You will work as per schedule fixed by the Team Leader, guided by the Director IDRC-B</ol>
                                    <ol>7. Reportable   : You will be reportable to the Director IDRC-B</ol>
                                    <ol>8. Notice Period                  : If CIPRB wants to terminate you or you wish to leave this organization, it can be done by serving one month notice by either party.</ol>
                                    <ol>9. Leave                                 : During probation period you will be entitled casual leave for 03 days and medical leave for 03 days</ol>
                                </ul>
                                <p>If the above terms and conditions are acceptable to you; please sign on both the copies and submit one copy to the Executive Director, CIPRB, House # B-162, Lane # 23, New DOHS, Mohakhali, Dhaka-1206 for record. </p><br>
                                <p>Thanking you.</p><br>
                                <p>Sincerely,</p><br>
                                <p>.........................</p>
                                <strong>X</strong><br>
                                <strong>XX</strong><br>
                                <p>I have read this Letter of Appointment and I willingly agree to accept the terms and conditions, as offered.</p><br>
                                <p>Signature of Contractual Appointee: </p><br>
                                <p>Date: ___________________</p><br><br>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row noprint">
                <div class="col-md-12">
                    <button onclick="window.print();return false;" class="btn btn-default"><i class="fa fa-print" target="_blank"></i> Print</button>
                </div>
            </div>
            {{-- @endforeach --}}
        </section>
          <!-- /.content -->
          <div class="clearfix"></div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js_lib')
<!-- DataTables -->
<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
<script src="{{ asset('backend/plugins/iCheck/icheck.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

@endpush
