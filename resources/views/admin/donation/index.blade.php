@extends('admin.layouts.app')
@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"/>
@endpush

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-th-list"></i> Donation Record</h3>
{{--                            <div class="card-tools">--}}
{{--                                --}}
{{--                                <a class="btn btn-primary btn-sm" href="{{ route('accounting_transaction--}}
{{--                                .capital_investment.create') }}"><i class="fa fa-plus-circle"></i> Add Manual--}}
{{--                                    Donation</a>--}}
{{--                            </div>--}}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table  class="data-table table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Donar Name</th>
                                    <th>Mobile Number</th>
                                    <th>SSC Batch</th>
                                    <th>Send bKash Mobile  </th>
                                    <th>Transaction ID </th>
                                    <th>Amount </th>
                                    <th>Created At</th>
                                    <th></th>
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
                    <form class="form-horizontal" action="" id="empDirecotryInfoForm" method="post">

                        <div class="form-group">
                            <label class="control-label col-sm-12" for="name">Donar Name</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="name" placeholder="Enter Name" name="name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="empID">Mobile Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="MobileNumber" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="empID">SSC Batch</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="MobileNumber" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="empID">Sended bKash Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="MobileNumber" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="empID">Transaction Number</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="MobileNumber" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-sm-12" for="empID">Amount</label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="MobileNumber" >
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <div id="saved_form_output"></div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-offset-2 col-sm-8">
                                <input type="hidden" name="update_id" id="update_id">
                                <button type="button" class="btn btn-primary submit_btn" onclick="save_employee_direcotry_info_btn()"> <i class="glyphicon glyphicon-ok-sign"></i> <span id="submitBtnLabel"></span></button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="glyphicon glyphicon-remove"></i> Close</button>
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
                ajax: "{{ route('donation.donationRecord') }}",
                columns: [
                    {data: 'sl', name: 'sl',class: 'text-left'},
                    {data: 'name', name: 'name',class: 'text-left'},
                    {data: 'mobileNumber', name: 'mobileNumber',class: 'text-left'},
                    {data: 'sscBatch', name: 'sscBatch',class: 'text-center'},

                    {data: 'sendNumber', name: 'sendNumber',class: 'text-center'},
                    {data: 'TransactionID', name: 'TransactionID',class: 'text-center'},
                    {data: 'donationAmount', name: 'donationAmount',class: 'text-center'},

                    {data: 'created_at', name: 'created_at',class: 'text-center'},
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
                    url: "{{ route('accounting_transaction.capital_investment') }}"+'/'+id,
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
            $("#submitBtnLabel").html('Update');
            $('#empDirecotryInfoForm')[0].reset();
            $(".submit_btn").attr("disabled", true);
            $("#saved_form_output").html('');

            $.ajax({
                type: "POST",
                url: base_url + "/single_employee_direcotry_info",
                data: {id:id},
                'dataType': 'json',
                success: function (response) {
                    if (response.status=='success') {
                        var data=response.data;
                        $("#update_id").val(data.id);
                        $("#empID").val(data.EmpID);
                        $("#name").val(data.EmpName);
                        // $("#designation_id").val(data.DesignationID);
                        $('#designation_id').val(data.DesignationID).trigger('change');

                        $("#mobile_1").val(data.Mobile_1);
                        $("#mobile_2").val(data.Mobile_2);
                        $("#mobile_3").val(data.Mobile_3);

                        $("#email_1").val(data.Email_1);
                        $("#email_2").val(data.Email_2);

                        $("#display_position").val(data.display_position);
                        $("#is_active").val(data.is_active);

                    } else {


                    }
                }
            });


        }
    </script>

@endpush

