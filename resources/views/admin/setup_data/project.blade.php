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
        <h1>Project Setup </h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setup Data</a></li>
            <li class="active">Project Settings</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header text-right">
                    <a href="javascript:void(0)" id="createNewUpazila" class="btn  btn-camelia"> <i class="fa fa-plus"></i> Add Project</a>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="add_upazila" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="upazilaForm" name="upazilaForm">
                                   <input type="hidden" name="workstation_id" id="workstation_id">
                                    <div class="modal-header bg-camelia">
                                        <h4 class="modal-title" id="exampleModalLabel">Add Project</h5>
                                    </div>
                                    <div class="modal-body">
                                        <section class="content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group">
                                                                    <label for="upazila_code">Project Name : <small style="color:red">*</small></label>
                                                                    <label id="upazila_code-error" class="error" for="upazila_code"></label>
                                                                    <input type="text" class="form-control" id="name" name="name" value="" maxlength="30" title="Project Name" placeholder="Project Name" required/>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="upazila_code">Project Code : <small style="color:red">*</small></label>
                                                                    <label id="upazila_code-error" class="error" for="upazila_code"></label>
                                                                    <input type="number" min="1" max="100" class="form-control" autocomplete="off" id="project_code" name="project_code"  maxlength="30" title="Project Code" placeholder="Project Code" required/>
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
                                    <th class="text-center">SL</th>
                                    <th class="text-center">Name</th>
                                    <th class="text-center">Project Code</th>
                                    <th class="text-center">Action</th>
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

    function ichecker()
        {
            $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
            checkboxClass: 'icheckbox_flat-green',
            radioClass   : 'iradio_flat-green'
            });
        }



    function active_action(id) {
        $.ajax({
            url: "{{route('project.toggle')}}",
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
                url: "{{ route('project.index') }}", // json datasource
                type: 'GET',  // method  , by default get
            },
            columns: [
                {data: 'sl', name: 'sl', class: 'text-center'},
                {data: 'name', name: 'name', class: 'text-center'},
                {data: 'project_code', name: 'project_code', class: 'text-center'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
            ],
            drawCallback: function (settings) {
                ichecker();
            }
        });

        $(document).on('click', '#createNewUpazila', function (e) {
            // $('#createNewUpazila').click(function () {
            $('#saveBtn').val("create-upazila");
            $('#workstation_id').val('');
            $('#upazilaForm').trigger("reset");
            $('#modelHeading').html("Create New Upazila");
            $('#add_upazila').modal('show');
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

        $('body').on('click', '.editUpazila', function () {
            var upazila_id = $(this).data('id');
            $.get("{{ route('project.index') }}" +'/' + upazila_id +'/edit', function (data) {
                $('#modelHeading').html("Edit Project");
                $('#saveBtn').html("Update");
                $('#add_upazila').modal('show');
                $('#workstation_id').val(data.id);
                $('#name').val(data.name);
                $('#project_code').val(data.project_code);
            });
        });

        $(document).on('click', '#saveBtn', function (e) {
            //e.preventDefault();
            $("#upazilaForm").validate({
                debug: true,

                submitHandler: function(form) {
                    $(this).html('Sending..');
                    $.ajax({
                        data: $('#upazilaForm').serialize(),
                        url: "{{ route('project.store') }}",
                        type: "POST",
                        dataType: 'json',
                        beforeSend:function(){
                            $("#saveBtn").attr("disabled",true);
                        },
                        success: function (data) {
                            $("#saveBtn").attr("disabled",false);
                            if(data.success){
                                $('#upazilaForm').trigger("reset");
                                $('#add_upazila').modal('hide');
                                table.draw();
                                toastr.success(data.success);
                            }
                            else{
                                toastr.error(data.error);
                            }
                        },
                        error: function (data) {
                            $.each(data.responseJSON.errors, function(error){
                                toastr.error(data.responseJSON.errors[error]);
                            });
                            $('#saveBtn').html('Save Changes');
                        }
                    });
                }
            });
        });

        // $('body').on('click', '.deleteUpazila', function () {

        //     var id = $(this).data("id");
        //     if(confirm("Are You sure want to delete !")){
        //         $.ajax({
        //             type: "DELETE",
        //             url: "{{ route('upazila.store') }}"+'/'+id,
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

        $(document).on('click', '.deleteUpazila', function () {
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
                    url: "{{ route('project.store') }}"+'/'+id,
                    success: function (data) {
                        if(data.success){
                            toastr.success(data.success);
                            table.draw();
                            Swal.fire(
                                'Deleted!',
                                'Your record has been deleted.',
                                'success'
                            )
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

