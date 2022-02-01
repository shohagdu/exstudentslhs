@extends('admin.layouts.app')

@section('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}" />

@endsection
@push('css_lib')
<style>
.alert-warning-ext{background-color: #480f0f !important;}
  .alert-ext{background-color: #da0d0d !important;}
</style>
  @endpush
@push('css_custom')
    <style>

        label.error{

            margin-top: 5px!important;

        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered li{
            list-style: none;
            background-color: #027fbb;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered li span{
            list-style: none;
            color: #fff;
        }



        .select2-container--default .select2-search--inline .select2-search__field {
            background: #fff;
            border: none;
            outline: 0;
            box-shadow: none;
            -webkit-appearance: textfield;
            padding: 5px 0px 0px 0px;
            margin: 0;
        }
    </style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>
            Users List
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">User Management</a></li>
            <li class="active">Users List</li>
        </ol>
    </section>
    <?php
        $queryString = $_SERVER['QUERY_STRING'];
        $request = request()->all();
    ?>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">

                <div class="box box-primary">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-10 col-xs-12 text-left">
                                <form  action="{{route('users.index')}}" method="get"  class="form-inline" enctype="multipart/form-data">
                                    @if($user->is_admin==1 || $user->access_all_project==1)
                                        <select style="" class="form-control input-sm" name="project_id">
                                            <option selected value="">Select Project</option>
                                            @foreach($project as $item)
                                                <option {{ !empty($request['project_id']) && $request['project_id']==$item->project_code ? 'selected' : ''}} value="{{$item->project_code}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <select style="" class="form-control input-sm"  name="user_type">
                                        <option value="">Select Type</option>
                                        <option {{ !empty($request['user_type']) && $request['user_type'] ==3 ? 'selected' : '' }} value="3">Regular User</option>
                                        <option {{ !empty($request['user_type']) && $request['user_type']==1 ? 'selected' : '' }} value="1">Coordinator</option>
                                        <option {{ !empty($request['user_type']) && $request['user_type']==2 ? 'selected' : '' }} value="2">MNE</option>
                                    </select>
                                    <button type="submit" style=""  class="btn btn-sm btn-success" style="border-radius: 0px;"><span class="fa fa-search"></span> Search</button>
                                    <a href="{{route('users.index')}}" style="" class="btn btn-sm btn-danger" style="border-radius: 0px;"><span class="fa fa-refresh"></span></a>
                                </form>
                            </div>
                            <div class="col-md-2">
                                <a href="javascript:void(0)" id="createNewData" class="btn btn-success btn-sm pull-right"> <i class="fa fa-plus" style="font-size:12px;"></i> Add User</a>
                            </div>
                        </div>

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example1" class="table table-bordered table-striped table-hover data-table table-responsive">
                            <thead>
                            <tr>
                                <th class="text-left" style="width: 20px;">SL</th>
                                <th class="text-left">Project</th>
                                <th class="text-left">Name</th>
                                <th class="text-left">Email</th>
                                <th class="text-left">Type</th>
                                <th style="width: 170px;">Action</th>
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
            <!-- /.col -->
        </div>
        <!-- /.row -->
    </section>
    <!-- /.content -->
    <!-- Modal -->
    <div id="user_view" class="modal fade" role="dialog">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Title</h4>
                    <small class="modal-category">Category</small>
                </div>
                <div class="modal-body">
                    <div class="modal-image text-center align-items-center"><img src="" alt="" style="max-height: 200px;"></div>
                    <br>
                    <p class="modal-details">Content here...</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="form_modal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <form id="entryForm" name="entryForm" enctype="multipart/form-data">
        <input type="hidden" name="id" id="id">
        <div class="modal-dialog modal-lg">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header bg-blue">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" id="modelHeading">Add User</h4>
                </div>
                <div class="modal-body">
                    <div class="row">

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="user_name" class=" control-label">Full Name: <small style="color:red">*</small></label>
                                <input type="text" class="form-control" autocomplete="off" name="name" id="user_name" placeholder="User Full Name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                            <label for="email" class=" control-label">Email: <small style="color:red">*</small></label>
                                <input type="email" class="form-control" autocomplete="off" name="email" id="email" placeholder="Email" required>
                            </div>
                        </div>
                        <div class="col-md-6 password_el">
                            <div class="form-group">
                                <label for="password" class="text-md-right">Password: <small style="color:red">*</small></label>
                                <input id="password" type="password" autocomplete="off" class="form-control" name="password" required placeholder="Minimum 8 character">
                            </div>
                        </div>
                        <div class="col-md-6 password_el">
                            <div class="form-group">
                                <label for="confirm-password" class="text-md-right">Confirm Password: <small style="color:red">*</small></label>
                                <input id="confirm-password" type="password" autocomplete="off" class="form-control" name="password_confirmation" required placeholder="Same to Password">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department" class=" control-label">Type <small style="color:red">*</small></label>
                                <select class="form-control"  name="user_type" id="user_type" required>
                                    <option value="">Select Type</option>
                                    <option value="3">Regular User</option>
                                    <option value="1">Coordinator</option>
                                    <option value="2">MNE</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="department" class=" control-label">Project <small style="color:red">*</small></label>
                                <select class="form-control " required name="project_id" id="project_id">
                                    <option value="">Select Project</option>
                                    @foreach($project as $item)
                                        <option value="{{$item->project_code}}">{{$item->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-md-6 coordinator_emine">
                            <div class="form-group">
                                <label for="department" class=" control-label">Coordinator <small style="color:red">*</small></label>
                                <select class="form-control select2" multiple data-placeholder="Select Coordinator" style="width: 100%;" name="coordinator[]" id="coordinator">
                                    @if(!empty($coordinator))
                                        @foreach($coordinator as $item)
                                            <option value="{{$item->id}}">{{$item->name.'['.$item->email.']'}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 coordinator_emine">
                            <div class="form-group">
                                <label for="department" class="control-label">MNE <small style="color:red">*</small></label>
                                <select class="form-control select2" multiple data-placeholder="Select MNE" style="width: 100%;" name="emine[]" id="emine">
                                    @if(!empty($emine))
                                        @foreach($emine as $item)
                                            <option value="{{$item->id}}">{{$item->name.'['.$item->email.']'}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="confirm-password" class="text-md-left">Access All Project</label>
                                <input type="checkbox" name="access_all_project" id="access_all_project" value="1"/>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="confirm-password" class="text-md-right">Anchal Permission:</label>
{{--                                <select class="form-control select2" multiple data-placeholder="Select Anchal" style="width: 100%;" name="permission[]" id="permission">--}}
{{--                                    @if(!empty($anchal))--}}
{{--                                        @foreach($anchal as $item)--}}
{{--                                            <option value="{{$item->anchal_auto_id}}">{{$item->anchal_auto_id.' ['.$item->name.']'}}</option>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </select>--}}
                                <textarea id="permission" class="form-control" name="permission" placeholder="Anchal Permission"></textarea>
                                <span>insert anchal ID with Comma Seperator : EX-> 11000,10010</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <img id="blah"  style="height:100px;width:100px;display:none;" src="" alt="your image" />
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                    <button type="submit" name="submit" class="btn btn-success pull-right" id="saveBtn">Save</button>
                </div>
            </div>
        </div>
        </form>
    </div>
@endsection


@push('js_custom')
<script>

    function ichecker()
    {
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass : 'icheckbox_flat-green',
        radioClass : 'iradio_flat-green'
        });
    }



    function active_action(id) {
        $.ajax({
            url: "{{route('users.toggle')}}",
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

    function initDatePicker(){

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
           // startDate: new Date(),

        });
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $('#blah').attr('src', e.target.result);
                $('#blah').css("display","block");
            }
            reader.readAsDataURL(input.files[0]);
        }
    }

    $("#imgInp").change(function(){
        readURL(this);
    });

$(function () {

    // $('body').addClass('sidebar-collapse');
    $('.select2').select2();
    var table = $('.data-table').DataTable({
        processing: true,
        responsive: true,
        serverSide: true,
        order: [[1, 'asc']],
        ajax: {
            url: "{{ route('users.index') }}?<?php echo $queryString; ?>", // json datasource
            type: 'GET',  // method  , by default get
        },
        columns: [
            {data: 'sl', name: 'sl',class: 'text-center'},
            {data: 'project_name', name: 'project_name',class: 'text-center'},
            {data: 'name', name: 'name',class: 'text-center'},
            {data: 'email', name: 'email',class: 'text-center'},
            {data: 'type_name', name: 'type_name',class: 'text-center'},
            {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
        ],
        drawCallback: function (settings) {
            ichecker();
        }
    });

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    initDatePicker();

    $(".view").click(function(){
        var id = $(this).attr("data-id");
        $.ajax({
            url: "users/view_data",
            type:"POST",
            data: { 'id' : id },
            dataType: 'JSON',
            success:function(data){

                $('.modal-title').text(data.title);
                $('.modal-category').html('Category: <b>'+data.category+'</b>');
                $('.modal-details').html(data.content);
                $('.modal-image').children('img').attr('src', data.image);


                $('#user_view').modal('show');
            },error:function(){
                alert("error!!!!");
            }
        }); //end of ajax
    });


    $('#createNewData').click(function () {
        $('#saveBtn').val("Create Employee");
        $('#id').val('');
        $('.password_el').show();
        $('#entryForm').trigger("reset");
        $('#modelHeading').html("Create New User");
        $("#coordinator").select2();
        $("#emine").select2();
        // $("#permission").select2();
        $('#form_modal').modal('show');
    });

    $('body').on('click', '.editData', function () {
        // $('#coordinator').select2();
        // $('#emine').select2();
        var id = $(this).data('id');
        $.get("{{ route('users.index') }}" +'/' + id +'/edit', function (data) {
            $('#modelHeading').html("Edit User");
            $('#saveBtn').val("Edit User");
            $('#id').val(data.id);
            $('#user_name').val(data.name);
            $('#email').val(data.email);
            $('#project_id').val(data.project_id);

            $('#password').val('');
            $('.password_el').hide();
            $('#confirm-password').val('');
            $('#password').removeAttr('required');
            $('#confirm-password').removeAttr('required');
            $('#user_type').val(data.user_type);

            if(data.user_type==3) {
                $('.coordinator_emine').show();
            }
            else {
                $('.coordinator_emine').hide();
            }

            if(data.coordinator!=null) {
                var ids = data.coordinator.split(",");
                $('#coordinator').val(ids).change();
            }
            else {
                $('#coordinator').val([]).change();
            }

            if(data.emine !=null) {
                var ids = data.emine.split(",");
                $('#emine').val(ids).change();
            }
            else {
                $('#emine').val([]).change();
            }

            if(data.access_all_project==1) {
                $('#access_all_project').prop('checked',true);
            }

            // if(data.permission!=null) {
            //     var ids = data.permission.split(",");
            //     $('#permission').val(ids).change();
            // }
            // else {
            //     $('#permission').val([]).change();
            // }
            $('#permission').val(data.permission);


            initDatePicker();

            $('#form_modal').modal('show');
        });
    });

    $(document).on("ifChecked", '.active_status', function (event) {
        var id = $(this).attr("data-id");
        active_action(id);
        // table.draw();
    });
    $(document).on("ifUnchecked", '.active_status', function (event) {
        var id = $(this).attr("data-id");
        // alert(id);
        active_action(id);
        // table.draw();
    });



    $(document).on('click', '.deleteData', function () {
        var id = $(this).data("id");

        if (confirm("Are You sure want to delete !")){
            $.ajax({
                type: "DELETE",
                url: "{{ route('users.store') }}"+'/'+id,
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

        }
    });


    $(document).on('click', '#saveBtn', function (e) {
        $("#entryForm").validate({
            debug: true,

            // rules: {
            //   password: "required",
            //   password_confirmation: {
            //     equalTo: "#password"
            //   }
            // },

            submitHandler: function(form) {
                $(this).html('Sending..');

                $.ajax({
                    data: $('#entryForm').serialize(),
                    url: "{{ route('users.store') }}",
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

    $(document).on('change', '#user_type', function (e) {
        var type = e.target.value;
        if(type==3) {
            $("#coordinator").val('');
            $("#emine").val('');
            $(".coordinator_emine").show();
        }
        else {
            $("#coordinator").val('');
            $("#emine").val('');
            $(".coordinator_emine").hide();
        }
    });

    $(".coordinator_emine").hide();
});

</script>
@endpush
