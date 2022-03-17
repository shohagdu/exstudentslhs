<title>{{ env('APP_NAME') }} @yield('title') </title>
<!-- Font Awesome -->
<link rel="stylesheet" href="{{ URL::asset('backend/plugins/fontawesome-free/css/all.min.css') }}"/>
<!-- Theme style -->
<link rel="stylesheet" href="{{ URL::asset('backend/dist/css/adminlte.min.css') }}"/>
@php
    $userType           = (!empty(Auth::user()->user_type)?Auth::user()->user_type:'');
    $userSscBatch       = (!empty(Auth::user()->userSscBatch)?Auth::user()->userSscBatch:'');
    $record             = (!empty($data['record'])?$data['record']:'');
    if(!empty($userType) && $userType==7 ){
        $sscBatch=$userSscBatch;
    }else{
        $sscBatch='-';
    }
    $i=1;
@endphp
<div class="card-header" style="border-bottom:1px solid #fff;">
    <div class="card-tools">
        <button class="btn btn-primary btn-sm no-print"  onclick="window.print()" >
            <i class="fa fa-print"></i> Print</button>
    </div>
</div>
<div >
    <div id="image">
        <img src="{{ url('backend/images/logo/esfLogo.png') }}" alt="..." />
    </div>
    <table  style="width: 100%;">
        <tr>
            <td style="width: 20%;vertical-align: middle"><img src="{{ url('backend/images/logo/logo.jpg') }}"
                                                          style="height: 110px"></td>
            <td style="width: 60%;text-align: center">
                <h2>Ex. Students Forum, Lemua High School</h2>
                <div style="font-size: 22px">Feni Sadar, Feni</div>
                <h5 style="padding-top:30px;font-weight: bold;">শিক্ষক মহোদয়দের বিদায় সংবর্ধনা অনুষ্ঠান-২০২২</h5>
                <h4 style="font-weight: bold;padding-top:10px;text-decoration: underline;">অংশগ্রহণকারীদের তথ্য </h4>
            </td>
            <td style="width: 20%;text-align: right;vertical-align: middle"><img src="{{ url('backend/images/logo/esfLogo.png') }}"
                                                           style="height:
            150px"></td>
        </tr>
    </table>

    <table id="table-style" class="table table-bordered">
        <thead>
            <tr>
                <th style="width: 3%;text-align: center">S/N</th>
                <th style="width: 15%">Application ID</th>
                <th >Name</th>
                <th style="width: 15%;text-align: center">Batch</th>
                <th style="width: 15%;text-align: center">Mobile</th>
            </tr>
        </thead>
        <tbody>
            @if(!empty($record))
                @foreach($record as $row)
                    <tr>
                        <td style="text-align: center">{{ $i++ }}</td>
                        <td>{{ !empty($row->participantID)?$row->participantID:'' }}</td>
                        <td>{{ !empty($row->name)?ucwords($row->name):'' }}</td>
                        <td style="text-align: center">{{ !empty($row->batch)?$row->batch:'' }}</td>
                        <td style="text-align: center">{{ !empty($row->mobile)?str_pad(substr($row->mobile, -4), strlen($row->mobile), '*',
                        STR_PAD_LEFT):'' }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>

    </table>
</div>
<style>
    #table-style td{
        /*padding:5px;*/
        /*margin: 2px;*/
    } #table-style th{
        /*padding:5px;*/
    }
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }

    #image img {
        /* the actual 'watermark' */
        position: absolute;
        top: 30%; /* or whatever */
        left: 25%; /* or whatever, position according to taste */
        opacity: 0.1; /* Firefox, Chrome, Safari, Opera, IE >= 9 (preview) */
    }


</style>
