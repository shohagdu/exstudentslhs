@extends('admin.layouts.app')
@php
    $userType=(!empty(Auth::user()->user_type)?Auth::user()->user_type:'');
@endphp
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-th-list"></i> {{ (!empty($data['page_title'])
                            ?$data['page_title']:'')
                            }}</h3>
                            <div class="card-tools no-print">
                                <a class="btn btn-danger btn-sm" href="{{ route('bank.index')
                                }}"><i class="fa fa-backward"></i>
                                    Back</a>
                                <button class="btn btn-warning btn-sm" onclick="window.print()" ><i class="fa
                                fa-print"></i>
                                    Print</button>
                            </div>
                        </div>
                        <div class="card-body">
                            @php
                                $i                    =    1;
                                $bankInfo             =    (!empty($data['bankInfo'])?$data['bankInfo']:'');
                                $bankStatement        =    (!empty($data['bankStatement'])?$data['bankStatement']:'');
                                $transType            =    (!empty($data['transType'])?$data['transType']:'');
                            @endphp
                            <table class="table table-bordered" style="width: 70%;">
                                <tr>
                                    <th style="width: 30%">Bank Name</th>
                                    <td>{{ (!empty($bankInfo->accountName)?$bankInfo->accountName:'') }}</td>
                                </tr>
                                <tr>
                                    <th> Account No.</th>
                                    <td>{{ (!empty($bankInfo->accountNumber)?$bankInfo->accountNumber:'') }}</td>
                                </tr>
                                <tr>
                                    <th> Branch Name</th>
                                    <td>{{ (!empty($bankInfo->accountBranchName)?$bankInfo->accountBranchName:'') }}</td>
                                </tr>
                            </table>
                            <table  class=" table table-bordered table-hover" style="margin-top: 10px">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Trans. Code</th>
                                        <th>Date</th>
                                        <th>Remarks</th>
                                        <th>Receipt No</th>
                                        <th>Trans. Type</th>
                                        <th class="text-right">Debit Amount  </th>
                                        <th  class="text-right">Credit Amount  </th>
                                        <th class="text-right">Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $balance='0.00';
                                        $tDebit='0.00';
                                        $tCredit='0.00';
                                    @endphp
                                    @if(!empty($bankStatement))
                                        @foreach($bankStatement as $statement)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ (!empty($statement->transCode)?$statement->transCode:'') }}</td>
                                                <td>{{ (!empty($statement->trans_date)?date
                                                ('d M, Y',strtotime($statement->trans_date)):'')
                                                }}</td>
                                                <td>{{ (!empty($statement->remarks)?$statement->remarks:'') }}</td>
                                                <td>{{ (!empty($statement->receiptNo)?$statement->receiptNo:'') }}</td>
                                                <td>{{ (!empty($transType[$statement->type])
                                                ?$transType[$statement->type]:'')
                                                }}</td>
                                                <td class="text-right">{{ (!empty($statement->debit_amount)
                                                ?$statement->debit_amount:'0.00') }}</td>
                                                 <td class="text-right">{{ (!empty($statement->credit_amount)
                                                 ?$statement->credit_amount:'0.00') }}</td>
                                                <td class="text-right">
                                                    @php
                                                        $balance+=(!empty
                                                   ($statement->debit_amount-$statement->credit_amount)
                                                ?($statement->debit_amount-$statement->credit_amount):'0.00');
echo (!empty($balance)? number_format($balance,2):'0.00');
                                                    $tDebit+=$statement->debit_amount;
                                                    $tCredit+=$statement->credit_amount;

                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="7" class="text-center">No Data Exist</td>
                                        </tr>
                                    @endif
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="6" class="text-center">Total Summery</th>
                                        <th  class="text-right">{{ !empty($tDebit)?number_format($tDebit,2):'0.00' }}</td>
                                        <th  class="text-right">{{ !empty($tCredit)?number_format($tCredit,2):'0.00' }}</td>
                                        <th  class="text-right">{{ !empty($balance)?number_format($balance,2):'0.00' }}</td>


                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="donationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-10">
                        <h6 class="modal-title" id="exampleModalLabel">Donation Information</h6>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <form  action="" id="donationApprovedForm" method="post">
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="donerName">Doner Name</label>
                            <div class="col-sm-12">
                                <span class="donerName"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="donerMobile">Mobile Number</label>
                            <div class="col-sm-12">
                                <div class="donerMobile"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="sscBatch">SSC Batch</label>
                            <div class="col-sm-12">
                                <div class="sscBatch"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="transactionID">Transaction Number</label>
                            <div class="col-sm-12">
                                <div class="transactionID"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="donationSendedNumber">Send bKash Number</label>
                            <div class="col-sm-8">
                                <div class="donationSendedNumber"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="DonationAmount">Amount</label>
                            <div class="col-sm-8">
                                <div class="DonationAmount"></div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <div id="formOutput"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <input type="hidden" name="update_id" id="update_id">
                                <input type="hidden" name="currentStatus" id="currentStatus">
                                <input type="hidden" name="nextStatus" id="nextStatus">
                                <button type="button" class="btn btn-primary submit_btn" onclick="updateDonationBtn()"> <i class="fa fa-address-book" aria-hidden="true"></i> <span id="submitBtnLabel"></span></button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa
                                fa-times" aria-hidden="true"></i> Close</button>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection


<style>
    .table td{
        font-size: 15px !important;
        padding:2px 5px !important;
    }
    .table th{
        font-size: 13px !important;
        padding:2px 5px !important;
    }
</style>


