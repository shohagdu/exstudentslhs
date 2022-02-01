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
        <h1>Division Setup </h1>

        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setup Data</a></li>
            <li class="active">Division Settings</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="javascript:void(0)" id="createNewDivision" class="btn bg-camelia"> <i class="fa fa-plus" style="font-size:12px;"></i> Add Division</a>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="add_division" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header bg-camelia">
                                    <h5 class="modal-title" id="modelHeading">Add Division</h5>
                                </div>
                                <form id="divisionForm" name="divisionForm">
                                    <input type="hidden" name="division_id" id="division_id">
                                    <div class="modal-body">
                                        <section class="content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-body">
                                                        <div class="form-group">
                                                            <label for="name">Division Name : <small style="color:red">*</small></label>
                                                            <label id="division_name_en-error" class="error" for="division_name_en"></label>
                                                            <input type="text" class="form-control" id="division_name_en" name="division_name_en" value="" maxlength="50" placeholder="Division Name (EN)" required>

                                                        </div>
                                                        <div class="form-group">
                                                            <label for="name">Division Code : <small style="color:red">*</small></label>
                                                            <label id="division_code-error" class="error" for="division_code"></label>
                                                            <input type="text" class="form-control" id="division_code" name="division_code" value="" maxlength="20" placeholder="Division code (EN)" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                                        <button type="submit" id="saveBtn" class="btn btn-success">Save</button>
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
                                    <th style="min-width:10px">SL</th>
                                    <th style="min-width:100px" class="text-center">Division Name</th>
                                    <th style="min-width:100px" class="text-center">Division Code</th>
                                    <th style="width:70px; text-align:center;">Action</th>
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
    $(function () {
        function ichecker()
            {
                $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
                checkboxClass: 'icheckbox_flat-green',
                radioClass   : 'iradio_flat-green'
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

                function active_action(id) {
                    $.ajax({
                        url: "{{route('division.toggle')}}",
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
            }

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            order: [[1, 'asc']],
            ajax: {
                url: "{{ route('division.index') }}", // json datasource
                type: 'GET',  // method  , by default get
            },
            columns: [
                {data: 'sl', name: 'sl', class: 'text-center'},
                {data: 'division_name', name: 'division_name', class: 'text-center'},
                {data: 'division_code', name: 'division_code',class: 'text-center'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
            ],
            drawCallback: function (settings) {
                ichecker();
            }

        });

        $('#createNewDivision').click(function () {
            $('#saveBtn').val("create-division");
            $('#division_id').val('');
            $('#divisionForm').trigger("reset");
            $('#modelHeading').html("Create New Division");
            $('#add_division').modal('show');
        });

        $('body').on('click', '.editDivision', function () {
            var division_id = $(this).data('id');
            $.get("{{ route('division.index') }}" +'/' + division_id +'/edit', function (data) {
                $('#modelHeading').html("Edit Division");
                $('#saveBtn').html("Update");
                $('#add_division').modal('show');
                $('#division_id').val(data.id);
                $('#division_name_en').val(data.division_name_en);
                $('#division_name_bn').val(data.division_name_bn);
                $('#division_shortname_en').val(data.division_shortname_en);
                $('#division_shortname_bn').val(data.division_shortname_bn);
                $('#division_code').val(data.division_code);
                $('#note_en').val(data.note_en);
                $('#note_bn').val(data.note_bn);
            });
        });

        $(document).on('click', '#saveBtn', function (e) {
           // e.preventDefault();

            $("#divisionForm").validate({
                debug: true,

                submitHandler: function(form) {
                    $(this).html('Sending..');
                    $.ajax({
                        data: $('#divisionForm').serialize(),
                        url: "{{ route('division.store') }}",
                        type: "POST",
                        dataType: 'json',
                        beforeSend:function(){
                            $("#saveBtn").attr("disabled",true);
                        },
                        success: function (data) {
                            $("#saveBtn").attr("disabled",false);
                            $('#divisionForm').trigger("reset");
                            $('#add_division').modal('hide');
                            table.draw();
                            toastr.success(data.success);
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

        // $('body').on('click', '.deleteDivision', function () {

        //     var id = $(this).data("id");
        //     if (confirm("Are You sure want to delete !")){

        //         $.ajax({
        //             type: "DELETE",
        //             url: "{{ route('division.store') }}"+'/'+id,
        //             success: function (data) {
        //                 table.draw();
        //                 toastr.success(data.success);
        //             },
        //             error: function (data) {
        //                 //console.log('Error:', data);
        //             }
        //         });
        //     }
        // });


        $(document).on('click', '.deleteDivision', function () {
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
                    url: "{{ route('division.store') }}"+'/'+id,
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

