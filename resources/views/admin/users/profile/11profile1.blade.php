@extends('admin.layouts.app')
@section('title')
| Profile
@endsection
@push('css_lib')
<style>
    #datepicker input {
        border: 1px solid #9c9c9c;
        margin-right: 5px;
        border-radius: 4px;
        padding: 2px 0px;
        text-align: center;
    }
    .form_button {
        background-color: #808080;
        color: #fff;
        border: 1px solid #808080;
        border-radius: 4px;
        transition: all 0.3s;
        padding: 2px 10px;
    }
    .distance_100 {
        width: 100% !important;
        height: 100% !important;
        position: relative;
    }
    .bg_red {
        background: #EA2027 !important;
    }
    .bg_yellow {
        background: #F79F1F !important;
    }
    .text_white {
        color: #fff !important;
    }
    .tr_bg_gray {
        background: #eee !important;
    }
    .bg_green {
        background: #009432 !important;
    }
    .bg_gray {
        background: #718093 !important;
    }
    .bg_blue {
        background: #0652DD !important;
    }
    .bg_melody {
        background: #f8c291 !important;
    }
    .nav-tabs-custom>.nav-tabs>li.active {
        border-top-color: #3c8dbc !important;
        background-color: #ffffff !important;
        color: #fff !important;
    }

    .nav-tabs-custom>.nav-tabs>li.active>a,
    .nav-tabs-custom>.nav-tabs>li.active:hover>a {
        background-color: transparent !important;
        color: #222 !important;
    }

    .nav-tabs-custom>.nav-tabs>li {
        background-color: #0073b7;
        color: #fff;
        border-radius: 3px;
        /* padding: 10px 20px; */
    }

    .nav-tabs-custom>.nav-tabs>li>a {
        color: #fff !important;
    }

    .nav>li>a {
        position: relative;
        display: block;
        padding: 8px 20px;
    }

    .pic_bg{
        background:url('{{ asset('backend/images/bg-ice.jpg') }}') center center no-repeat;
        width: 240px;
        height: 240px;
        background-size: cover;
        border-radius: 50%;
        margin: auto;
        position: relative;
    }
    .pic_bg .profile_pic {
        position: absolute;
        height: 220px;
        width: 220px;
        background-color: #ffffffbf;
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .pic_bg .profile_pic img{
        border-radius: 50%;
        height: 190px;
        width: 190px;
        top: 50%;
        left: 50%;
        position: absolute;
        transform: translate(-50%, -50%);
    }
    .profile_infos{
        padding: 0px 0px 10px 0px;
        border-radius: 5px;
        text-align: center;
    }

    .profile_infos h3{
        margin: 0px;
        /*margin-bottom: 5px;*/
        /*border-bottom: 1.5px solid #fff;*/
        padding-bottom: 5px;
        background-color: #22222252;
        border-radius: 5px 5px 0px 0px;
        padding: 8px;
        margin-bottom: 5px;
    }

    .profile_infos span{
        font-size: 16px;
    }

    .header_padding{
        padding: 12px 10px;
    }
    .avatar {
        vertical-align: middle;
        width: 50px;
        height: 50px;
        border-radius: 50%;
    }
</style>

@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Profile Info</h1>
    </section>

    @php
        $profile = $user->profile;
    @endphp
    <!-- Main content -->
    <section class="content">
        <div class="box box-success">
            <div class="box-header with-border">
                <h3 class="box-title">Profile Info</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <div class="pull-right">
                    <a href="#" data-toggle="modal" data-target="#modalProfile" title="Update Your Profile"><img src="{{ URL::asset('backend/dist/img/user2-160x160.jpg') }}" alt="Avatar" class="avatar" style="margin-right: 20px;"></a>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <!-- Custom Tabs -->
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="mt-10 active"><a href="#general_info" data-toggle="tab" aria-expanded="true">General Info</a></li>
                
                                <li class="mt-10"><a href="#attendance" data-toggle="tab" aria-expanded="true">Attendance</a></li>
                
                                <li class="mt-10"><a href="#logistics" data-toggle="tab" aria-expanded="false">Logistics</a></li>                
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="general_info">
                
                                    <div class="col-md-12">
                                        <div class="infos">
                                            <div class="row">
                                                <form>                                            
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="name" class="text-md-right">{{ __('Name') }}:<small style="color:red">*</small></label>
                                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name',$user->full_name) }}" required autocomplete="name" tabindex="1" autofocus readonly>
                                            
                                                            @error('name')
                                                                <span class="invalid-feedback" role="alert">
                                                                    <strong>{{ $message }}</strong>
                                                                </span>
                                                            @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="email" class="text-md-right">{{ __('E-Mail Address') }}:<small style="color:red">*</small></label>
                                            
                                                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $user->email) }}" required autocomplete="email" tabindex="2" readonly>
                                            
                                                                @error('email')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                        <div class="form-group">
                                                            <label for="father_name" class="text-md-right">Famther Name:<small style="color:red">*</small></label>
                                            
                                                                <input id="father_name" type="text" class="form-control @error('father_name') is-invalid @enderror" name="father_name" value="{{ old('father_name', $user->father_name) }}" required tabindex="2" readonly>
                                            
                                                                @error('father_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>                                            
                                                        <div class="form-group">
                                                            <label>Joining Date:</label>
                                            
                                                            <div class="input-group date">
                                                                <div class="input-group-addon">
                                                                    <i class="fa fa-calendar"></i>
                                                                </div>
                                                                <input type="text" class="form-control pull-right" id="datepicker" name="dob" autocomplete="off" value="{{date("d-m-Y", strtotime($user->joining_date)) }}" tabindex="5" readonly>
                                                            </div>
                                                            <!-- /.input group -->
                                                        </div>
                                            
                                                        {{-- <div class="form-group">
                                                            <label for="gender">Gender:</label>
                                                            <input required tabindex="6" type="radio" name="gender" value="Male" @if($profile && $profile->gender=="Male")
                                                            checked 
                                                            @elseif(old('gender') == "Male")
                                                            checked 
                                                            @endif>Male
                                                            <input tabindex="7" type="radio"  name="gender" value="Female" 
                                                            @if($profile && $profile->gender== "Female")
                                                            checked 
                                                            @elseif(old('gender') == "Female")
                                                            checked 
                                                            @endif>Female<br>
                                                        </div> --}}
                                                    </div>
                                                    <div class="col-md-6">
                                            
                                                        <div class="form-group">
                                                            <label for="father_name" class="text-md-right">Father Name:<small style="color:red">*</small></label>
                                            
                                                                <input id="father_name" type="text" class="form-control @error('father_name') is-invalid @enderror" name="father_name" value="{{ old('father_name', $user->father_name) }}" required tabindex="2" readonly>
                                            
                                                                @error('father_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>
                                            
                                                        
                                                        <div class="form-group">
                                                            <label for="mother_name" class="text-md-right">Father Name:<small style="color:red">*</small></label>
                                            
                                                                <input id="mother_name" type="text" class="form-control @error('mother_name') is-invalid @enderror" name="mother_name" value="{{ old('mother_name', $user->mother_name) }}" required tabindex="2" readonly>
                                            
                                                                @error('mother_name')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>
                                            
                                                        <div class="form-group">
                                                            <label for="designation" class="text-md-right">Designation:<small style="color:red">*</small></label>
                                            
                                                                <input id="designation" type="text" class="form-control @error('designation') is-invalid @enderror" name="designation" value="{{ old('designation', $user->designation->name) }}" required tabindex="2" readonly>
                                            
                                                                @error('designation')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>
                                            
                                                        <div class="form-group">
                                                            <label for="department" class="text-md-right">Department:<small style="color:red">*</small></label>
                                            
                                                                <input id="department" type="text" class="form-control @error('department') is-invalid @enderror" name="department" value="{{ old('department', $user->department->name) }}" required tabindex="2" readonly>
                                            
                                                                @error('department')
                                                                    <span class="invalid-feedback" role="alert">
                                                                        <strong>{{ $message }}</strong>
                                                                    </span>
                                                                @enderror
                                                        </div>
                                                        <input type="text" class="form-control pull-right" id="datepicker" name="dob" autocomplete="off" value="{{date("d-m-Y", strtotime($user->joining_date)) }}" tabindex="5" readonly>
                                                    </div>
                                                    <!-- /.input group -->
                                                </div>


                                            </div>
                                            <div class="col-md-6">

                                                <div class="form-group">
                                                    <label for="father_name" class="text-md-right">Father Name:<small style="color:red">*</small></label>

                                                    <input id="father_name" type="text" class="form-control @error('father_name') is-invalid @enderror" name="father_name" value="{{ old('father_name', $user->father_name) }}" required tabindex="2" readonly>

                                                    @error('father_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>


                                                <div class="form-group">
                                                    <label for="mother_name" class="text-md-right">Father Name:<small style="color:red">*</small></label>

                                                    <input id="mother_name" type="text" class="form-control @error('mother_name') is-invalid @enderror" name="mother_name" value="{{ old('mother_name', $user->mother_name) }}" required tabindex="2" readonly>

                                                    @error('mother_name')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="designation" class="text-md-right">Designation:<small style="color:red">*</small></label>

                                                    <input id="designation" type="text" class="form-control @error('designation') is-invalid @enderror" name="designation" value="{{ old('designation', $user->designation->name) }}" required tabindex="2" readonly>

                                                    @error('designation')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>

                                                <div class="form-group">
                                                    <label for="department" class="text-md-right">Department:<small style="color:red">*</small></label>

                                                    <input id="department" type="text" class="form-control @error('department') is-invalid @enderror" name="department" value="{{ old('department', $user->department->name) }}" required tabindex="2" readonly>

                                                    @error('department')
                                                    <span class="invalid-feedback" role="alert">
                                                        <strong>{{ $message }}</strong>
                                                    </span>
                                                    @enderror
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>




                            </div>

                            <div class="tab-pane" id="attendance">
                                <div class="row" style="padding-bottom: 15px;"><div class="col-sm-6 col-md-8 "><h2 style="margin-top: 0px;">My Attendance Register
                                    <small>( April, 2020 )</small> <button type="button" class="btn btn-xs btnPrintAction btnNoPrint"><i class="fa fa-print"></i> Print</button> <button onclick="window.location.reload(true);" class="btn btn-info btn-xs btnNoPrint"><i class="fa fa-refresh"></i> Refresh</button></h2></div>
                                    <div class="col-sm-6 col-md-4" style="margin-bottom: 11px;">
                                        <form action="" method="GET" id="sandbox-container" >
                                            <input type="text" name="searchDate" value="{{$attendance_month ?? ''}}" class="form-control pull-right" style="display: inline-block; max-width: 100px;" id="monthpicker">
                                        </form>
                                    </div>
                                </div>

                                <div class="row"><div class="col-md-12 table-responsive-custom"><table class="table table-bordered"><tbody><tr align="center" class="tr_bg_gray"><th style="text-align: center;"> SL# </th> <th class="min_width_220"> Name </th> <th style="text-align: center;"> 01 </th><th style="text-align: center;"> 02 </th><th style="text-align: center;"> 03 </th><th style="text-align: center;"> 04 </th><th style="text-align: center;"> 05 </th><th style="text-align: center;"> 06 </th><th style="text-align: center;"> 07 </th><th style="text-align: center;"> 08 </th><th style="text-align: center;"> 09 </th><th style="text-align: center;"> 10 </th><th style="text-align: center;"> 11 </th><th style="text-align: center;"> 12 </th><th style="text-align: center;"> 13 </th><th style="text-align: center;"> 14 </th><th style="text-align: center;"> 15 </th><th style="text-align: center;"> 16 </th><th style="text-align: center;"> 17 </th><th style="text-align: center;"> 18 </th><th style="text-align: center;"> 19 </th><th style="text-align: center;"> 20 </th><th style="text-align: center;"> 21 </th><th style="text-align: center;"> 22 </th><th style="text-align: center;"> 23 </th><th style="text-align: center;"> 24 </th><th style="text-align: center;"> 25 </th><th style="text-align: center;"> 26 </th><th style="text-align: center;"> 27 </th><th style="text-align: center;"> 28 </th><th style="text-align: center;"> 29 </th><th style="text-align: center;"> 30 </th></tr> <tr><td rowspan="2" style="text-align: center;">1</td><td rowspan="2">Md Maidul Islam</td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_red text_white">A</div><small>&nbsp;</small></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div><small>&nbsp;</small></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div><small>&nbsp;</small></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div><small>&nbsp;</small></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div><small>&nbsp;</small></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td></tr> <tr class="tr_bg_gray" style="font-size: 8px;"><td colspan="50" style="padding: 1px 1px 1px 8px;"><span class="bg_green text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;P&nbsp;&nbsp;</span>&nbsp;&nbsp; Presents: <b>00 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_red text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;A&nbsp;&nbsp;</span>&nbsp;&nbsp; Absents: <b>01 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_gray text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;H&nbsp;&nbsp;</span>&nbsp;&nbsp; Holidays: <b>00 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;CL&nbsp;&nbsp;</span>&nbsp;&nbsp; Casual Leaves: <b>00 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;HL&nbsp;&nbsp;</span>&nbsp;&nbsp; Halfdays Leaves: <b>00 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;SL&nbsp;&nbsp;</span>&nbsp;&nbsp; Sick Leaves: <b>00 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;UL&nbsp;&nbsp;</span>&nbsp;&nbsp; Unpaid Leaves: <b>00 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_yellow text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;F&nbsp;&nbsp;</span>&nbsp;&nbsp; Fridays: <b>04 / 30</b>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                    <span class="bg_melody text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;N&nbsp;&nbsp;</span>&nbsp;&nbsp; Nil Out time: <b>00 / 30</b></td></tr> <tr align="center"><th colspan="50">Attendance Details </th></tr> <tr><td colspan="50" style="padding: 0px;"><table class="table table-bordered" style="margin: 0px;"><tbody><tr align="center" class="tr_bg_gray"><th width="100px" style="text-align: center;"> Date </th><th width="120px" style="text-align: center;"> Work Station </th><th width="100px" title="Time Ascending Order" style="text-align: center;"> In Time <i class="fa fa-arrow-down"></i></th><th width="100px" style="text-align: center;"> Out Time </th><th width="100px" style="text-align: center;"> Duration </th><th width="60px" style="text-align: center;"> Status </th><th> In Comment </th><th> Out Comment </th></tr><tr><td style="text-align: center;">01-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_red text_white">A</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">02-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">03-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">04-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">05-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">06-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">07-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">08-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">09-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">10-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">11-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">12-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">13-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">14-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">15-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">16-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">17-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">18-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">19-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">20-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">21-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">22-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">23-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">24-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100 bg_yellow text_white">Fri</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">25-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">26-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">27-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">28-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">29-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr><tr><td style="text-align: center;">30-04-2020</td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td style="text-align: center;"></td><td class="mar_pad_none" style="text-align: center;"><div class="distance_100">-</div></td><td style="text-align: center;"></td><td style="text-align: center;"></td></tr></tbody></table></td></tr> <tr class="tr_bg_gray" style="font-size: 8px;"><td colspan="50" style="padding: 1px 1px 1px 8px;"><span class="bg_green text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;P&nbsp;&nbsp;</span>&nbsp;&nbsp; Presents: <b>00 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_red text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;A&nbsp;&nbsp;</span>&nbsp;&nbsp; Absents: <b>01 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_gray text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;H&nbsp;&nbsp;</span>&nbsp;&nbsp; Holidays: <b>00 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;CL&nbsp;&nbsp;</span>&nbsp;&nbsp; Casual Leaves: <b>00 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;HL&nbsp;&nbsp;</span>&nbsp;&nbsp; Halfday Leaves: <b>00 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;SL&nbsp;&nbsp;</span>&nbsp;&nbsp; Sick Days: <b>00 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_blue text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;UL&nbsp;&nbsp;</span>&nbsp;&nbsp; Unpaid Days: <b>00 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_yellow text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;F&nbsp;&nbsp;</span>&nbsp;&nbsp; Fridays: <b>04 / 30</b>
                                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



                                        <span class="bg_melody text_white" style="font-size: 7px; font-weight: bold;">&nbsp;&nbsp;N&nbsp;&nbsp;</span>&nbsp;&nbsp; Nil Out time: <b>00 / 30</b></td></tr></tbody></table></div></div>


                                        <hr>

                                    </div>


                                    

                                    <div class="tab-pane" id="logistics">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="infos">
                                                    <table class="table table-bordered table-striped table-hover">
                                                        <thead>
                                                            <tr>
                                                                <td colspan="10">
                                                                    <h3 class="bg-gray header_padding patient_table_title"> <i class="fa fa-info-circle"></i> Requisition Details</h3>
                                                                </td>
                                                            </tr>
                                                        </thead>
                                                        <thead>
                                                            <th class="text-center" style="width: 20px;">SL#</th>
                                                            <th class="text-center">Req. No</th>
                                                            <th>Product</th>
                                                            <th>Qty</th>
                                                        </thead>

                                                        <tbody>
                                                            @foreach($requistions as $key=>$record)
                                                            <tr>
                                                                <td>{{ $key+1 }}</td>
                                                                <td class="text-center">{{ $record->master->requisition_no }}</td>
                                                                <td class="text-center">{{ $record->product->name }}</td>
                                                                <td class="text-center">{{ $record->issue_qty }}</td>
                                                            </tr>
                                                            @endforeach
                                                        </tbody>

                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
        </section>

        <!-- Modal -->
        <div class="modal fade" id="modalProfile" aria-hidden="true" data-keyboard="false" data-backdrop="static">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <form method="POST" enctype="multipart/form-data" id="upload_image_form" action="{{ route('users.profile-photo') }}">
                        @csrf
                        <input type="hidden" value="{{ $user->id }}" name="id" id="id">
                        <div class="modal-header bg-green">
                            <h5 class="modal-title" id="modelHeading">Update Profile Photo</h5>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label for="preview">Profile Photo Preview : </label>
                                            <label id="preview-error" class="error" for="preview"></label>
                                            <img id="image_preview_container" src=""
                                            alt="preview image" style="max-height: 150px;">
                                        </div>
                                        <div class="form-group">
                                            <label for="profile_photo">Profile Photo :</label>
                                            <label id="profile_photo-error" class="error" for="Profile Photo"></label>
                                            <input type="file" name="profile_photo" placeholder="Choose image" id="profile_photo">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-danger pull-left" data-dismiss="modal">Close</button>
                            <button type="submit" id="saveBtn" class="btn btn-success btn-sm">Update Profile</button>
                            <input type="hidden" value="{{ $user->id }}">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endsection

        @push('js_lib')
        <!-- iCheck 1.0.1 -->
        <!-- Select2 -->
        @endpush

        @push('js_custom')
        <script>
            $(function(){

                   //Month picker
                   $('#monthpicker').datepicker({
                    autoclose: true,
                    format: "yyyy-mm",
                    viewMode: "months",
                    minViewMode: "months",

                });


    // $(document).ready(function (e) {
            //Datepicker
            $('#datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy'
            });

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#profile_photo').change(function(){
                let reader = new FileReader();
                reader.onload = (e) => { 
                    $('#image_preview_container').attr('src', e.target.result); 
                }
                reader.readAsDataURL(this.files[0]); 

            });

            // $('#upload_image_form').submit(function(e) {
            //     e.preventDefault();

            //     var formData = new FormData(this);

            //     $.ajax({
            //         type:'POST',
            //         url: "{{ url('users.profile-photo') }}",
            //         data: formData,
            //         cache:false,
            //         contentType: false,
            //         processData: false,
            //         success: (data) => {
            //             this.reset();
            //             alert('Image has been uploaded successfully');
            //         },
            //         error: function(data){
            //             console.log(data);
            //         }
            //     });
            // });
        });
    </script>

    @endpush