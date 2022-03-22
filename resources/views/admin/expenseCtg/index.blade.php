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
    $expenseCtg=(!empty($data['expenseCtg'])?$data['expenseCtg']:'');
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
                                <button class="btn btn-primary btn-sm" onclick="addExpenseCtg()"  data-toggle="modal"
                                        data-target="#expenseCtgModal">
                                    <i class="fa fa-plus-circle"></i> Add
                                    Expense Category</button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table  class="data-table table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Title</th>
                                    <th>View Order</th>
                                    <th>Status</th>
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
    <div class="modal fade" id="expenseCtgModal" tabindex="-1" role="dialog" aria-labelledby="expenseModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-10">
                        <h6 class="modal-title" id="exampleModalLabel">Expense Category Information</h6>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <form  action="" id="expenseCtgForm" class="form-horizontal" method="post">

                        <div class="form-group row">
                            <label class="control-label col-sm-4" for="Amount">Expense Category</label>
                            <div class="col-sm-8">
                                <input type="text" name="expenseCtgTtile" id="expenseCtgTtile" placeholder="Enter Category"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-4" for="Ordering">Ordering</label>
                            <div class="col-sm-8">
                                <input type="text" name="viewOrdering" id="viewOrdering" placeholder="Enter Ordering" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-4" for="Amount">Status</label>
                            <div class="col-sm-8">
                                <select type="text" name="isActive" id="isActive"
                                       class="form-control">
                                    <option value="1">Active</option>
                                    <option value="2">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-sm-4">
                                <div id="formOutput"></div>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" name="update_id" id="update_id">
                                <button type="button" class="btn btn-primary submit_btn" onclick="saveCtgExpense()"> <i
                                        class="fa fa-address-book" aria-hidden="true"></i> <span id="submitBtnLabel"></span></button>
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
                    url: base_url + "/expenseCtg",
                    method: "get",
                },
                columns: [
                    {data: 'sl', name: 'sl',class: 'text-left'},
                    {data: 'title', name: 'title',class: 'text-left'},
                    {data: 'view_order', name: 'view_order',class: 'text-left'},
                    {data: 'is_active', name: 'is_active',class: 'text-left'},
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
        function updateExpenseCtg(id){
            $("#update_id").val('');
            $("#submitBtnLabel").html('Update');
            $('#expenseCtgForm')[0].reset();
            $(".submit_btn").attr("disabled", true);
            $("#formOutput").html('');
            $.ajax({
                type: "POST",
                url: base_url + "/expenseCtgShow",
                data: {id:id},
                'dataType': 'json',
                success: function (response) {
                    if (response.status=='success') {
                        var data=response.data;

                        $(".submit_btn").attr("disabled", false);
                        $("#update_id").val(data.id);
                        $("#expenseCtgTtile").val(data.title);
                        $("#viewOrdering").val(data.view_order);
                        $("#isActive").val(data.is_active);

                    } else {

                    }
                }
            });
        }
        function addExpenseCtg(){
            $('#expenseCtgForm')[0].reset();
            $("#submitBtnLabel").html('Save');
            $("#formOutput").html('');
        }
        function saveCtgExpense(){
            $(".submit_btn").attr("disabled", true);
            $("#formOutput").html('');
            $.ajax({
                type: "POST",
                url: base_url + "/expenseCtgStore",
                data: $("#expenseCtgForm").serialize() ,
                'dataType': 'json',
                success: function (data) {
                    $(".submit_btn").attr("disabled", false);
                    if(data.success){
                        $('#expenseCtgModal').modal('toggle');
                        toastr.success(data.success);
                        table.draw();
                    }
                    else{
                        if (data.error.length > 0) {
                            var error_html = '';
                            for (var count = 0; count < data.error.length; count++) {
                                error_html += '<div class="alert alert-danger">' + data.error[count] + '</div> <br/>';
                            }
                            toastr.error(error_html);
                        }else {
                            toastr.error(data.error);
                        }
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

