@extends('admin.layouts.app')
@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"/>
@endpush

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-th-list"></i> User Record</h3>
                            <div class="card-tools">
                                <a class="btn btn-primary btn-sm" href="{{ route('user.create') }}"><i class="fa
                                fa-plus-circle"></i> Add New</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select class="form-control" name="user_type" id="user_type" required  >
                                            <option value="">Select Role</option>
                                            @if(!empty($roles))
                                                @foreach($roles as $rKey=> $role)
                                                    <option value="{{ $rKey}}"   >{{$role}}</option>
                                                @endforeach
                                            @endif
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <table  class="table table-bordered table-striped table-hover data-table">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width: 20px;">SL</th>
                                        <th class="text-left">Name</th>
                                        <th class="text-left">Email</th>
                                        <th class="text-left">Mobile</th>
                                        <th class="text-left">bKash</th>
                                        <th class="text-left">Type</th>
                                        <th class="text-left">Status</th>
                                        <th style="width: 170px;">Action</th>
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
            var tableUser = $('.data-table').DataTable({
                processing: true,
                responsive: true,
                serverSide: true,
                order: [[1, 'asc']],
                ajax: {
                    url: base_url + "/user/userRecord",
                    method: "get",
                    data: function (d) {
                        (d.user_type = $("#user_type").val())
                    },
                },
                columns: [
                    {data: 'sl', name: 'sl',class: 'text-center'},
                    {data: 'name', name: 'name',class: 'text-left'},
                    {data: 'email', name: 'email',class: 'text-left'},
                    {data: 'mobile', name: 'mobile',class: 'text-left'},
                    {data: 'mobileBankBkash', name: 'mobileBankBkash',class: 'text-left'},
                    {data: 'user_type', name: 'user_type',class: 'text-left'},
                    {data: 'isActive', name: 'isActive',class: 'text-left'},
                    {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
                ],
            });
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $("#user_type").change(function () {
                tableUser.draw();
            });

            $(document).on('click', '.deleteData', function () {
                var id = $(this).data("id");
                if (confirm("Are You sure want to delete !")){
                    $.ajax({
                        type: "post",
                        data:{id:id},
                        url: "{{ route('user.destroy') }}",
                        success: function (data) {
                            if(data.success){
                                toastr.success(data.success);
                                tableUser.draw();
                            }
                            else{
                                toastr.error(data.error);
                            }
                        },
                        error: function (data) {
                            //console.log('Error:', data);
                        }
                    });

                }
            });

            $(document).on('click', '#saveBtn', function (e) {
                $("#entryForm").validate({
                    debug: true,
                    submitHandler: function(form) {
                        $(this).html('Sending..');

                        $.ajax({
                            data: $('#entryForm').serialize(),
                            url: "{{ route('user.store') }}",
                            type: "POST",
                            dataType: 'json',
                            beforeSend:function(){
                                $("#saveBtn").attr("disabled",true);
                            },
                            success: function (data) {
                                $("#saveBtn").attr("disabled",false);
                                if(data.success){
                                    $('#entryForm').trigger("reset");
                                    $('#form_modal').modal('hide');
                                    table.draw();
                                    toastr.success(data.success);
                                }
                                else{
                                    toastr.error(data.error);
                                }
                            },
                            error: function (data) {
                                $("#saveBtn").attr("disabled",false);
                                $.each(data.responseJSON.errors, function(error){
                                    toastr.error(data.responseJSON.errors[error]);
                                });
                                // console.log('Error:', data);
                                $('#saveBtn').html('Save Changes');
                            }
                        });
                    }
                });
            });
        });
    </script>
@endpush
