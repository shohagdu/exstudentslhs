@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
            </div>
            <div class="card-body">
                @if(!empty($userType) && ($userType==1 || $userType==2 || $userType==3 ))
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
                @endif

                @if(!empty($userType) && ($userType==1 || $userType==7 ))
                    <div class="row">
                        <div class="col-lg-3 col-6">
                            <!-- small box -->
                            <div class="small-box bg-info">
                                <div class="inner">
                                    <h3>{{ (!empty($totalParticpant)?$totalParticpant:'0') }}</h3>

                                    <p>Registered Participant</p>
                                </div>
                                <div class="icon">
                                    <i class="ion ion-bag"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif



                @php
                    $i                              = 1;
                    $totalAmount                    = 0;
                    $pendingAmount                  = 0;
                    $totalApprovedParticipatent     = 0;

                    $iBatch                = 1;
                    $totalAmountBatch      = 0;

                    $iDate                  = 1;
                    $totalAmountDate        = 0;
                @endphp
                @if(!empty($userType) && ($userType==1 || $userType==2))
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-chart-pie mr-1"></i>
                                Total Fund Collection Overview
                            </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>S/N</th>
                                        <th>Coordinator</th>
                                        <th>Received bKash</th>
                                        <th class="text-right">Received</th>
                                        <th class="text-right">Pending</th>
                                    </tr>
                                </thead>
                                <tbody>

                                    @if(!empty($coOrdinatorWiseCurrentApprovdAmnt))
                                        @foreach($coOrdinatorWiseCurrentApprovdAmnt as $coOrdinatorInfo)
                                            @php($totalAmount+=$coOrdinatorInfo->ApprovedAmnt)
                                            @php($pendingAmount+=$coOrdinatorInfo->pendingAmnt)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ (!empty($coOrdinatorInfo->userName)?$coOrdinatorInfo->userName:'')
                                                }}</td>
                                                <td>{{ (!empty($coOrdinatorInfo->mobileBankBkash)
                                                ?$coOrdinatorInfo->mobileBankBkash:'-')
                                                }}</td>
                                                <th  class="text-right">{{ (!empty($coOrdinatorInfo->ApprovedAmnt)
                                                ?number_format($coOrdinatorInfo->ApprovedAmnt,2):'0.00')
                                                }}</th>
                                                <th  class="text-right">{{ (!empty($coOrdinatorInfo->pendingAmnt)
                                                ?number_format($coOrdinatorInfo->pendingAmnt,2):'0.00')
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
                                        <th class="text-right" >{{ (!empty($pendingAmount)?number_format($pendingAmount,2):'0
                                        .00') }}</th>

                                    </tr>
                                </tfoot>

                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Batch Wise Collection Overview
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%">S/N</th>
                                        <th>Batch</th>
                                        <th>Total Participator </th>
                                        <th class="text-right">Received</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(!empty($batchWise))
                                        @foreach($batchWise as $batch)
                                            @php($totalAmountBatch += $batch->ApprovedAmnt)
                                            @php($totalApprovedParticipatent += $batch->ApprovedParticipatent)
                                            <tr>
                                                <td>{{ $iBatch++ }}</td>
                                                <td>{{ (!empty($batch->sscBatch)?$batch->sscBatch:'')
                                                    }}</td>
                                                <td class="text-center">{{ (!empty($batch->ApprovedParticipatent)
                                                ?$batch->ApprovedParticipatent:'')
                                                    }}</td>

                                                <th  class="text-right">{{ (!empty($batch->ApprovedAmnt)
                                                    ?number_format($batch->ApprovedAmnt,2):'0.00')
                                                    }}</th>

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center" colspan="2">Total  Amount</th>
                                        <th class="text-center" >{{ (!empty($totalApprovedParticipatent)
                                        ?$totalApprovedParticipatent:'0
                                            ') }}</th>
                                        <th class="text-right" >{{ (!empty($totalAmountBatch)?number_format($totalAmountBatch,2):'0
                                            .00') }}</th>

                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 col-12">
                        <div class="card pull-right">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Date Wise Collection Overview
                                </h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                                        <i class="fas fa-times"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <th style="width: 10%">S/N</th>
                                        <th>Date</th>
                                        <th class="text-right">Received</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(!empty($dateWise))
                                        @foreach($dateWise as $dateCol)
                                            @php($totalAmountDate += $dateCol->ApprovedAmnt)
                                            <tr>
                                                <td>{{ $iDate++ }}</td>
                                                <td>{{ (!empty($dateCol->formatted_created_at)?$dateCol->formatted_created_at:'')
                                                    }}</td>
                                                <th  class="text-right">{{ (!empty($dateCol->ApprovedAmnt)
                                                    ?number_format($dateCol->ApprovedAmnt,2):'0.00')
                                                    }}</th>

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center" colspan="2">Total  Amount</th>
                                        <th class="text-right" >{{ (!empty($totalAmountBatch)?number_format($totalAmountBatch,2):'0
                                            .00') }}</th>
                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
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
        font-size: 14px !important;
    }
    .table th{
        font-size: 13px !important;
    }
</style>

