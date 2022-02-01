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
            {{-- @foreach ($extension as $record) --}}
            <div class="row">
                <div class="col-md-6">
                    <p>Memo No.: CIPRB/Admin/{{ date('Y') }}/{{ $extension->id }}</p>
                </div>
                <div class="col-md-6 text-right">
                    <p>Date: {{ date('d M Y') }} </p>
                </div>
                <div class="col-md-6">
                    To <br>
                    <strong>Name: {{ $extension->full_name }}</strong><br><br>
                    <strong>Designation: {{ $extension->designation->name }}</strong><br><br>
                    <strong>Duty: ..............................</strong><br><br>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-6 col-md-offset-3 text-center"><strong><u>Subject: Extension of Current Contract</u></strong></div><br>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <strong>Dear {{ $extension->full_name }} ,</strong><br>
                            <p>You will be glad to know that, CIPRB management committee has decided to extend your current contract up to {{ date('d F Y',strtotime($extension->extension_date)) }} at the same position. </p><br>

                            <p>All other terms and conditions as laid out in the job contract previously signed between you and CIPRB will continue to be the same.</p><br>

                            <p>I wish you every success in life. </p><br>
                            <p>Sincerely,</p><br>
                            <p>.........................</p>
                            <strong>X</strong><br>
                            <strong>XX</strong><br><br>
                            <p>CC:</p><br>
                            <p>1. Project Director</p><br>
                            <p>2. Finance & Admin</p><br>
                            <p>3. Personal File</p><br>

                        </div>
                    </div>
                </div>
            </div>

            <div class="row noprint">
                <div class="col-md-12">
                    <button href="" onclick="window.print();return false;" class="btn btn-default" target="_blank"><i class="fa fa-print"></i> Print</button>
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
