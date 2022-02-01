@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- sweetalert2.css -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Union Setup </h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setup Data</a></li>
            <li class="active">Union Setup</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="javascript:void(0)" id="createNewUnion" class="btn bg-camelia"> <i class="fa fa-plus" style="font-size:12px;"></i> Add Union</a>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="add_union" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="unionForm" name="unionForm">
                                    <input type="hidden" name="union_id" id="union_id">
                                    <div class="modal-header bg-camelia">
                                        <h5 class="modal-title" id="exampleModalLabel">Add Union</h5>
                                    </div>
                                    <div class="modal-body">
                                        <section class="content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="union_name_en">Union Name : <small style="color:red">*</small></label>
                                                                    <label id="union_name_en-error" class="error" for="union_name_en"></label>
                                                                    <input type="text" class="form-control" id="union_name_en" name="union_name_en" value="" maxlength="50" placeholder="Union Name (EN)" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="union_code_en">Union Code : <small style="color:red">*</small></label>
                                                                    <label id="union_code-error" class="error" for="union_code"></label>
                                                                    <input type="text" class="form-control" id="union_code" name="union_code" value="" maxlength="30" placeholder="Union Code" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="select_upazila_en">Select Upazila :<small style="color:red">*</small></label>
                                                                    <label id="upazila_id-error" class="error" for="upazila_id"></label>
                                                                    <select class="form-control" id="upazila_id" required name="upazila_id" style="width: 100%;">
                                                                        <option value="">Select Upazila</option>
                                                                        @foreach ($upazilas as $upazila)
                                                                            <option value="{{ $upazila->id }}">{{ $upazila->upazila_name_en }}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                                        <button type="submit" id="saveBtn" class="btn btn-success">Save Union</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped table-hover data-table table-responsive">
                            <thead>
                                <tr>
                                    <th style="min-width:10px" class="text-center">SL</th>
                                    <th style="min-width:100px" class="text-center">Union Name</th>
                                    <th style="min-width:100px" class="text-center">Union Code</th>
                                    <th style="min-width:100px" class="text-center">Upazila</th>
                                    <th style="min-width:70px; text-align:center;">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->

            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js_lib')
    <!-- Select2 -->
    <script src="{{ asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
    <!-- bootstrap datepicker -->
    <script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
    <!-- sweetalert2.js -->
    <script src="{{ asset('backend/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
@endpush

@push('js_custom')

<script type="text/javascript">


    function active_action(id) {
        $.ajax({
            url: "{{route('union.toggle')}}",
            type:"POST",
            data: { 'id' : id },
            dataType: 'JSON',
            success:function(response){
                toastr.success(response.message);
            },error:function(e){
                console.log(e);
            }
        }); //end of ajax
    }

    function ichecker()
    {
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass: 'icheckbox_flat-green',
        radioClass   : 'iradio_flat-green'
        });
    }

    $(function () {



        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            responsive: true,
            serverSide: true,
            order: [[1, 'asc']],
            ajax: {
                url: "{{ route('union.index') }}", // json datasource
                type: 'GET',  // method  , by default get
            },
            columns: [
                {data: 'sl', name: 'sl', class: 'text-center'},
                {data: 'union_name', name: 'union_name', class: 'text-center'},
                {data: 'union_code', name: 'union_code', class: 'text-center'},
                {data: 'upazila_name', name: 'upazila_name', class: 'text-center'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
            ],
            drawCallback: function (settings) {
                ichecker();
            }
        });

        $(document).on('click', '#createNewUnion', function (e) {
            // $('#createNewUnion').click(function () {
            $('#saveBtn').val("create-union");
            $('#union_id').val('');
            $('#unionForm').trigger("reset");
            $('#modelHeading').html("Create New Union");
            $('#add_union').modal('show');
        });



        $(document).on("ifChecked", '.active_status', function (event) {
            var id = $(this).attr("data-id");
            // alert(id);
            active_action(id);

        });
        $(document).on("ifUnchecked", '.active_status', function (event) {
            var id = $(this).attr("data-id");
            // alert(id);
            active_action(id);

        });

        $('body').on('click', '.editUnion', function () {
            var union_id = $(this).data('id');
            $.get("{{ route('union.index') }}" +'/' + union_id +'/edit', function (data) {
                $('#modelHeading').html("Edit Union");
                $('#saveBtn').html("Update");
                $('#add_union').modal('show');
                $('#union_id').val(data.id);
                $('#union_name_en').val(data.union_name_en);
                $('#union_name_bn').val(data.union_name_bn);
                $('#union_shortname_en').val(data.union_shortname_en);
                $('#union_shortname_bn').val(data.union_shortname_bn);
                $('#union_code').val(data.union_code);
                $('#upazila_id').val(data.upazila_id);
                $('#note_en').val(data.note_en);
                $('#note_bn').val(data.note_bn);
            });
        });

        $(document).on('click', '#saveBtn', function (e) {

            $("#unionForm").validate({
                debug: true,

                submitHandler: function(form) {
                    $(this).html('Sending..');

                    $.ajax({
                        data: $('#unionForm').serialize(),
                        url: "{{ route('union.store') }}",
                        type: "POST",
                        dataType: 'json',
                        beforeSend:function(){
                            $("#saveBtn").attr("disabled",true);
                        },
                        success: function (data) {
                            $("#saveBtn").attr("disabled",false);
                            if(data.success) {
                                $('#unionForm').trigger("reset");
                                $('#add_union').modal('hide');
                                table.draw();
                                toastr.success(data.success);
                            }
                            else {
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

        // $('body').on('click', '.deleteUnion', function () {

        //     var id = $(this).data("id");
        //     if(confirm("Are You sure want to delete !")){

        //         $.ajax({
        //             type: "DELETE",
        //             url: "{{ route('union.store') }}"+'/'+id,
        //             success: function (data) {
        //                 table.draw();
        //             },
        //             error: function (data) {
        //                 //console.log('Error:', data);
        //             }
        //         });
        //     }
        // });

        $(document).on('click', '.deleteUnion', function () {
            var id = $(this).data("id");

            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                if (result.value) {
                    $.ajax({
                    type: "DELETE",
                    url: "{{ route('union.store') }}"+'/'+id,
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
                        //console.log('Error:', data);
                    }
                });
                    Swal.fire(
                    'Deleted!',
                    'Your record has been deleted.',
                    'success'
                    )
                }
            });
        });

    });
</script>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <script>
                toastr.error('{{ $error }}');
            </script>
        @endforeach
    @endif
@endpush

