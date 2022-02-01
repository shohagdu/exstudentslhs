@extends('admin.layouts.app')

@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <!-- sweetalert2.css -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endpush

@section('content')

<?php
//dd($divisions);
// exit();
?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>District Setup </h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setup Data</a></li>
            <li class="active">District Settings</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <a href="javascript:void(0)" id="createNewdistrict" class="btn bg-camelia"> <i class="fa fa-plus" style="font-size:12px;"></i> Add District</a>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="add_district" aria-hidden="true" data-keyboard="false" data-backdrop="static">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <form id="districtForm" name="districtForm">
                                   <input type="hidden" name="district_id" id="district_id">
                                    <div class="modal-header bg-camelia">
                                        <h5 class="modal-title" id="modelHeading">Add District</h5>
                                    </div>
                                    <div class="modal-body">
                                        <section class="content">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="district_name_en">District Name : <small style="color:red">*</small></label>
                                                                    <label id="district_name_en-error" class="error" for="district_name_en"></label>
                                                                    <input type="text" class="form-control" id="district_name_en" name="district_name_en" value="" maxlength="50" title="District Name (EN)" placeholder="District Name (EN)" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="district_code">District Code : <small style="color:red">*</small></label>
                                                                    <label id="district_code-error" class="error" for="district_code"></label>
                                                                    <input type="text" class="form-control" id="district_code" name="district_code" value="" maxlength="30" title="District Code" placeholder="District Code" required>
                                                                </div>
                                                            </div>

                                                            <div class="col-md-6">
                                                                <div class="form-group">
                                                                    <label for="division_id">Select Division<small style="color:red">*</small></label>
                                                                    <label id="division_id-error" class="error" for="division_id"></label>
                                                                    <select class="form-control" id="division_id" name="division_id" title="Please select a division." style="width: 100%;" required>
                                                                        <option value="">Select Division</option>
                                                                        @foreach($divisions as $division)
                                                                            <option value="{{ $division->id }}">{{ $division->division_name_en }}</option>
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
                                        <button type="submit" id="saveBtn" class="btn btn-success">Save District</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <table class="table table-bordered table-striped table-hover data-table table-responsive">
                            <thead>
                                <tr>
                                    <th style="min-width:10px">SL#</th>
                                    <th style="min-width:100px" class="text-center">District Name</th>
                                    <th style="min-width:100px" class="text-center">District Code</th>
                                    <th style="min-width:100px" class="text-center">Division</th>
                                    <th style="min-width:70px; text-align:center;">Action</th>
                                </tr>
                            </thead>

                            <tbody>

                            </tbody>
                        </table>
                    </div>
                </div>
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
            url: "{{route('district.toggle')}}",
            type:"POST",
            data: { 'id' : id },
            dataType: 'JSON',
            success:function(response){
                toastr.success(response.message);
            },error:function(e){
                console.log(e);
            }
        }); //end of ajax
        ichecker();
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
                url: "{{ route('district.index') }}", // json datasource
                type: 'GET',  // method  , by default get
            },
            columns: [
                {data: 'sl', name: 'sl', class: 'text-center'},
                {data: 'district_name', name: 'district_name', class: 'text-center'},
                {data: 'district_code', name: 'district_code',class: 'text-center'},
                {data: 'division_name', name: 'division_name',class: 'text-center'},
                {data: 'action', name: 'action', orderable: false, searchable: false, class: 'text-center'},
            ],
            drawCallback: function (settings) {
                ichecker();
            }
        });

        $(document).on('click', '#createNewdistrict', function (e) {
            // alert('te');
            $('#saveBtn').val("create-district");
            $('#district_id').val('');
            $('#districtForm').trigger("reset");
            $('#modelHeading').html("Create New District");
            $('#add_district').modal('show');
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

        $('body').on('click', '.editDistrict', function () {
            var district_id = $(this).data('id');
            $.get("{{ route('district.index') }}" +'/' + district_id +'/edit', function (data) {
                $('#modelHeading').html("Edit District");
                $('#saveBtn').html("Update");
                $('#add_district').modal('show');
                $('#district_id').val(data.id);
                $('#district_name_en').val(data.district_name_en);
                $('#district_name_bn').val(data.district_name_bn);
                $('#district_shortname_en').val(data.district_shortname_en);
                $('#district_shortname_bn').val(data.district_shortname_bn);
                $('#district_code').val(data.district_code);
                $('#division_id').val(data.division_id);
                $('#note_en').val(data.note_en);
                $('#note_bn').val(data.note_bn);
            });
        });

        $(document).on('click', '#saveBtn', function (e) {
            //e.preventDefault();
            $("#districtForm").validate({
                debug: true,

                submitHandler: function(form) {
                    $(this).html('Sending..');

                    $.ajax({
                        data: $('#districtForm').serialize(),
                        url: "{{ route('district.store') }}",
                        type: "POST",
                        dataType: 'json',
                        beforeSend:function(){
                            $("#saveBtn").attr("disabled",true);
                        },
                        success: function (data) {
                            $("#saveBtn").attr("disabled",false);
                            if(data.success) {
                                $('#districtForm').trigger("reset");
                                $('#add_district').modal('hide');
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

        // $(document).on('click', '.deleteDistrict', function () {

        //     var id = $(this).data("id");
        //     if (confirm("Are You sure want to delete !")){
        //         $.ajax({
        //             type: "DELETE",
        //             url: "{{ route('district.store') }}"+'/'+id,
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

        $(document).on('click', '.deleteDistrict', function () {
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
                    url: "{{ route('district.store') }}"+'/'+id,
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
                    'Your file has been deleted.',
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

