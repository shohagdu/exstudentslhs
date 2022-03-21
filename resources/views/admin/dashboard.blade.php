@extends('admin.layouts.app')
@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Dashboard</h3>
            </div>
            <div class="card-body">

                    <div class="row">
                        @if(!empty($userType) && ($userType==1 || $userType==2 || $userType==3 ))
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
                        @endif
                            @if(!empty($userType) && ($userType==1 || $userType==7 ))
                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ (!empty($totalParticpant)?$totalParticpant:'0') }}</h3>

                                            <p>Waiting for Approved Participant</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag"></i>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3 col-6">
                                    <!-- small box -->
                                    <div class="small-box bg-info">
                                        <div class="inner">
                                            <h3>{{ (!empty($totalParticpantApproved)?$totalParticpantApproved:'0') }}</h3>
                                            <p>Approved Participant</p>
                                        </div>
                                        <div class="icon">
                                            <i class="ion ion-bag"></i>
                                        </div>
                                    </div>
                                </div>


                            @endif
                    </div>






                @php
                    $i                              = 1;
                    $totalAmountCordinator          = 0;
                    $totalAmount                    = 0;
                    $pendingAmount                  = 0;
                    $totalApprovedParticipatent     = 0;

                    $iBatch                = 1;
                    $totalAmountBatch      = 0;
                    $totalparticipate      = 0;

                    $iDate                  = 1;
                    $totalAmountDate        = 0;
                    $iBestBatch             = 1;

                    $iExpense               = 1;
                    $totalExpenseAmount     = 0;
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
                                            @php($totalAmountCordinator+=$coOrdinatorInfo->ApprovedAmnt)
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
                                        <th class="text-right" >{{ (!empty($totalAmountCordinator)?number_format($totalAmountCordinator,2):'0
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
                                            @php($totalAmount += $batch->ApprovedAmnt)
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
                                        <th class="text-right" >{{ (!empty($totalAmount)?number_format($totalAmount,2):'0
                                            .00') }}</th>

                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Total Expense Overview
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
                                        <th>Expense Category</th>
                                        <th class="text-right">Expense Amount</th>
                                    </tr>
                                    </thead>
                                    <tbody>

                                    @if(!empty($expenseInfo))
                                        @foreach($expenseInfo as $expense)
                                            @php($totalExpenseAmount += $expense->expenseAmount)

                                            <tr>
                                                <td>{{ $iExpense++ }}</td>
                                                <td>{{ (!empty($expense->title)?$expense->title:'')
                                                    }}</td>
                                                <th  class="text-right">{{ (!empty($expense->expenseAmount)
                                                    ?number_format($expense->expenseAmount,2):'0.00')
                                                    }}</th>

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center" colspan="2">Total Expense Amount</th>
                                        <th class="text-right" >{{ (!empty($totalExpenseAmount)?number_format($totalExpenseAmount,2):'0
                                            .00') }}</th>

                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-6 col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Best Batch Collection Overview
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

                                    @if(!empty($batchWiseBestAmount))
                                        @foreach($batchWiseBestAmount as $batch)
                                            @php($totalAmountBatch += $batch->ApprovedAmnt)
                                            @php($totalApprovedParticipatent += $batch->ApprovedParticipatent)
                                            <tr>
                                                <td>{{ $iBestBatch++ }}</td>
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
                                        <th class="text-right" >{{ (!empty($totalAmountDate)?number_format($totalAmountDate,2):'0
                                            .00') }}</th>
                                    </tr>
                                    </tfoot>

                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-8 col-12">
                        <div class="card pull-right">
                            <div class="card-header">
                                <h3 class="card-title">
                                    <i class="fas fa-chart-pie mr-1"></i>
                                    Batch Overview Participants Overview
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
                                        <th>Year</th>
                                        <th class="text-center">Total Registered</th>
                                        <th class="text-center">Approved</th>
                                        <th class="text-center">Waiting for SMS</th>
                                        <th class="text-center"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @php($ij=1)
                                    @php($totalparticipate=0)
                                    @php($totalparticipateApproved=0)
                                    @php($totalparticipatePending=0)
                                    @if(!empty($participantYear))
                                        @foreach($participantYear as $participate)
                                            @php($totalparticipate += $participate->total)
                                            @php($totalparticipateApproved += $participate->ApprovedParticipant)
                                            @php($totalparticipatePending += $participate->pendingParticipant)

                                            <tr>
                                                <td>{{ $ij++ }}</td>
                                                <td>{{ (!empty($participate->batch)?$participate->batch:'')
                                                    }}</td>
                                                <th  class="text-center">{{ (!empty($participate->total)
                                                    ?$participate->total:'0')
                                                    }}</th>
                                                <th  class="text-center" style="color:green;font-weight: bold">{{
                                                (!empty($participate->ApprovedParticipant)
                                                    ?$participate->ApprovedParticipant:'0')
                                                    }}</th>
                                                 <th  class="text-center" style="color:red;font-weight: bold">
                                                     {{ (!empty
                                                 ($participate->pendingParticipant)
                                                    ?$participate->pendingParticipant:'0')
                                                    }}</th>


                                                <th>
                                                    <a href="{{ url('admin/printParticipant/'.$participate->batch) }}" target="_blank" class="btn
                                btn-info btn-xs" >
                                                        <i class="fa fa-print"></i> Print</a>
                                                </th>

                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                    <tfoot>
                                    <tr>
                                        <th class="text-center" colspan="2">Total  Registered Students</th>
                                        <th class="text-center" >{{ (!empty($totalparticipate)?$totalparticipate:'0') }}</th>
                                        <th class="text-center" style="color:green;font-weight: bold" >{{ (!empty($totalparticipateApproved)
                                        ?$totalparticipateApproved:'0') }}</th>
                                        <th class="text-center" style="color:red;font-weight: bold" >{{ (!empty
                                        ($totalparticipatePending)
                                        ?$totalparticipatePending:'0') }}</th>


                                        <th>
                                            <a href="{{ url('admin/printParticipant/-') }}" target="_blank" class="btn
                                btn-info btn-xs" >
                                                <i class="fa fa-print"></i> Print</a>
                                        </th>
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

