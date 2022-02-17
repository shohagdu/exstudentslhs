@extends('admin.layouts.app')
@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"/>
@endpush
@php
    $userType=(!empty(Auth::user()->user_type)?Auth::user()->user_type:'');
    $bankInfo=(!empty($data['bankInfo'])?$data['bankInfo']:'');
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
                            <div class="card-tools">
                                <button class="btn btn-primary btn-sm" onclick="addTransaction()"  data-toggle="modal"
                                        data-target="#transactionModal">
                                    <i class="fa fa-plus-circle"></i> Add
                                    Transaction</button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table  class="data-table table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Trans. Code</th>
                                    <th>Trans. Date</th>
                                    <th>Remarks</th>
                                    <th>Receipt ID  </th>
                                    <th>Debit Amount  </th>
                                    <th>Credit Amount  </th>
                                    <th style="width: 20%">Action</th>
                                </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="transactionModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-10">
                        <h6 class="modal-title" id="exampleModalLabel">Transaction Information</h6>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <form  action="" id="transactionForm" class="form-horizontal" method="post">
                        <div class="form-group row">
                            <label class="control-label col-sm-4 text-right" for="bankID">Bank</label>
                            <div class="col-sm-8">
                                <select name="bankID" id="bankID" class="form-control">
                                    <option value="">Select Bank</option>
                                    @if(!empty($bankInfo))
                                        @foreach($bankInfo as $key => $val)
                                            <option value="{{ $key }}">{{ $val }}</option>
                                        @endforeach
                                    @endif
                                </select>
                                @error('bankID')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-4 text-right" for="transDate">Date</label>
                            <div class="col-sm-8">
                                <input type="text" name="transDate" value="{{ date('d-m-Y') }}" id="transDate"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-4 text-right" for="ReceiptNo">Receipt No</label>
                            <div class="col-sm-8">
                                <input type="text" name="ReceiptNo" id="ReceiptNo" placeholder="Enter Receipt No"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-4 text-right" for="Remarks">Remarks</label>
                            <div class="col-sm-8">
                                <textarea type="text" name="Remarks" placeholder="Enter Remarks" id="Remarks"
                                          class="form-control"></textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-4 text-right" for="Amount">Amount</label>
                            <div class="col-sm-8">
                                <input type="text" name="Amount" id="Amount" placeholder="Enter Amount" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <div id="formOutput"></div>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" name="update_id" id="update_id">
                                <button type="button" class="btn btn-primary submit_btn" onclick="saveTransaction()"> <i class="fa fa-address-book" aria-hidden="true"></i> <span id="submitBtnLabel"></span></button>
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

@push('js_lib')
    <!-- DataTables -->
    <script src="{{ URL::asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ URL::asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
@endpush

@push('js_custom')
    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            table = $('.data-table').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
                "sorting": true,
                "processing": true,
                "language": {
                    processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw"></i><span class="sr-only">Loading...</span> '
                },
                serverSide: true,
                ajax: {
                    url: base_url + "/bankTransaction/index",
                    method: "get",
                },
                columns: [
                    {data: 'sl', name: 'sl',class: 'text-left'},
                    {data: 'transCode', name: 'transCode',class: 'text-left'},
                    {data: 'trans_date', name: 'trans_date',class: 'text-left'},
                    {data: 'remarks', name: 'remarks',class: 'text-left'},
                    {data: 'receiptNo', name: 'receiptNo',class: 'text-left'},
                    {data: 'debit_amount', name: 'debit_amount',class: 'text-left'},
                    {data: 'credit_amount', name: 'credit_amount',class: 'text-left'},
                    {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
                ],
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });

        $(document).on('click', '.deleteData', function () {
            var id = $(this).data("id");
            if (confirm("Are You sure want to delete !")){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('donation.donationRecord') }}"+'/'+id,
                    success: function (data) {
                        if(data.success){
                            toastr.success(data.success);
                            table.draw();
                        }
                        else{
                            toastr.error(data.error);
                        }
                    },
                    error: function (data) {
                    }
                });

            }
        });
        function updateDoantionInfo(id){
            $("#update_id").val('');
            $("#submitBtnLabel").html('Approved');
            $('#donationApprovedForm')[0].reset();
            $(".submit_btn").attr("disabled", true);
            $("#formOutput").html('');
            $.ajax({
                type: "POST",
                url: base_url + "/donation/singleDonationInfo",
                data: {id:id},
                'dataType': 'json',
                success: function (response) {
                    if (response.status=='success') {
                        var data=response.data;
                        $(".submit_btn").attr("disabled", false);
                        $("#update_id").val(data.id);
                        $(".donerName").html(data.name);
                        $(".donerMobile").html(data.mobileNumber);
                        $(".sscBatch").html(data.sscBatch);
                        $(".transactionID").html(data.TransactionID);
                        $(".donationSendedNumber").html(data.sendNumber);
                        $(".DonationAmount").html(data.donationAmount);
                        $("#currentStatus").val(data.approvedStatus);
                        $("#nextStatus").val(2);
                    } else {

                    }
                }
            });
        }
        function addTransaction(){
            $('#transactionForm')[0].reset();
            $("#submitBtnLabel").html('Save');
            $("#formOutput").html('');
        }
        function saveTransaction(){
            $(".submit_btn").attr("disabled", true);
            $("#formOutput").html('');
            $.ajax({
                type: "POST",
                url: base_url + "/bankTransaction/store",
                data: $("#transactionForm").serialize() ,
                'dataType': 'json',
                success: function (data) {
                    $(".submit_btn").attr("disabled", false);
                    if(data.success){
                        $('#transactionModal').modal('toggle');
                        toastr.success(data.success);
                        table.draw();
                    }
                    else{
                        toastr.error(data.error);
                    }


                }
            });
        }
    </script>
@endpush
<style>
    .table td{
        font-size: 13px !important;
    }
    .table th{
        font-size: 11px !important;
    }
</style>

