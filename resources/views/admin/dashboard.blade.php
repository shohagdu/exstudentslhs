@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
                <div class="card-tools">

                </div>
            </div>
            <div class="card-body">


{{--                {{ dd($coOrdinatorWiseCurrentApprovdAmnt) }}--}}

                <div class="row">
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-info">
                            <div class="inner">
                                <h3>{{ $approvedAmount }}</h3>

                                <p>Total Approved Amount</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-bag"></i>
                            </div>
                        </div>
                    </div>
                    <!-- ./col -->
                    <div class="col-lg-3 col-6">
                        <!-- small box -->
                        <div class="small-box bg-success">
                            <div class="inner">
                                <h3>{{ $pendingAmount }}</h3>
                                <p>Total Pending Amount</p>
                            </div>
                            <div class="icon">
                                <i class="ion ion-stats-bars"></i>
                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty($coOrdinatorWiseCurrentApprovdAmnt))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Total Fund Collection Overview
                            </h3>
                        </div><!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Coordinator Name</th>
                                        <th>Received bKash Number</th>
                                        <th class="text-right">Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $i                = 1;
                                        $totalAmount      = 0;
                                    @endphp
                                    @if(!empty($coOrdinatorWiseCurrentApprovdAmnt))
                                        @foreach($coOrdinatorWiseCurrentApprovdAmnt as $coOrdinatorInfo)
                                            @php($totalAmount+=$coOrdinatorInfo->total)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ (!empty($coOrdinatorInfo->userName)?$coOrdinatorInfo->userName:'')
                                                }}</td>
                                                <td>{{ (!empty($coOrdinatorInfo->mobileBankBkash)
                                                ?$coOrdinatorInfo->mobileBankBkash:'-')
                                                }}</td>
                                                <th  class="text-right">{{ (!empty($coOrdinatorInfo->total)
                                                ?number_format($coOrdinatorInfo->total,2):'0.00')
                                                }}</th>
                                            </tr>
                                        @endforeach

                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th class="text-center" colspan="3">Total Collection Amount</th>
                                        <th class="text-right" >{{ (!empty($totalAmount)?number_format($totalAmount,2):'0
                                        .00') }}</th>
                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection

@push('js_lib')
    <!-- bootstrap time picker -->
    <script src="{{URL::asset('backend/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
    <script src="{{URL::asset('backend/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
    <!-- AdminLTE dashboard demo (This is only for demo purposes) -->
    <script src="{{ URL::asset('backend/dist/js/pages/dashboard.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/printThis/printThis.js') }}"></script>
    <script src="{{ URL::asset('backend/bower_components/moment/min/moment.min.js') }}"></script>
    <script src="{{ URL::asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

    <script src="{{ URL::asset('backend/plugins/fullcalendar/fullcalendar.js') }}"></script>
@endpush
<style>
    .table td{
        font-size: 13px !important;
    }
    .table th{
        font-size: 11px !important;
    }
</style>

