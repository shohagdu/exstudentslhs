@extends('admin.layouts.app')

@push('css_lib')
    <!-- sweetalert2.css -->
    <link rel="stylesheet" href="{{ asset('backend/plugins/sweetalert2/dist/sweetalert2.min.css') }}">
@endpush
<style type="text/css">
    th{
        text-align: center;
    }
    table tr td {
        font-size:15px;
    }
    table b{
        color:sienna;
    }
</style>
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Anchal Child Information</h1>

        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Anchal Info</a></li>
            <li class="active">Anchal Register</li>
        </ol>
    </section>

    <?php
    $request = request()->all();
    //Get the full string
    $queryString = $_SERVER['QUERY_STRING'];
    ?>
    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12">
                <div class="box box-primary">
                    <div class="box-header text-right">
                        <div class="col-md-4 text-left">
                            @if($user->hasPermissionTo('child.import'))
                                <form id="upload_attendace" action="{{route('anchalchild.childImport')}}" method="post"  class="form-inline" enctype="multipart/form-data"  data-submit="ajax">
                                    @csrf
                                    <input type="file" name="child_file" accept=".csv,.xlsx" class="form-control input-sm"/>
                                    <button type="submit" id="saveBtn" class="btn btn-sm btn-success" style="border-radius: 0px;margin-bottom: 6px;"><span class="fa fa-upload"></span> Upload</button>
                                </form>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($user->is_admin==1  || $user->access_all_project==1)
                                <form  action="{{route('anchalchild.index')}}" method="get"  class="form-inline" enctype="multipart/form-data">
                                    <select style="width:50%;" class="form-control input-sm" id="project_id" name="project_id">
                                        <option selected value="">Select Project</option>
                                        @foreach($project as $item)
                                            <option {{ !empty($request['project_id']) && $request['project_id']==$item->project_code ? 'selected' : ''}} value="{{$item->project_code}}">{{$item->name}}</option>
                                        @endforeach
                                    </select>
                                    <button type="submit"  class="btn btn-sm btn-primary" style="border-radius: 0px;"><span class="fa fa-search"></span> Search</button>
                                    <a href="{{route('anchalchild.index')}}"  class="btn btn-sm btn-danger" style="border-radius: 0px;"><span class="fa fa-refresh"></span> Refresh</a>
                                </form>
                            @endif
                        </div>
                        <div class="col-md-4">
                            @if($user->hasPermissionTo('child.create'))
                                <a href="{{route('anchalchild.create')}}" class="btn btn-sm btn-primary"> <i class="fa fa-plus"></i> New Child</a>
                            @endif
                            @if($user->hasPermissionTo('child.export'))
                                <a href="{{route('anchalchild.exportDemo4')}}?<?php echo $queryString;  ?>" class="btn btn-sm btn-info"> <i class="fa fa-download"></i> Export</a>
                            @endif

                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover data-table table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width:10px">SL</th>
{{--                                        <th class="text-center">Child ID</th>--}}
{{--                                        <th class="text-center">Child Name</th>--}}
{{--                                        <th class="text-center">Anchal ID</th>--}}
                                        <th class="text-left">Child Info</th>
                                        <th class="text-left">District</th>
                                        <th class="text-left">SubDistrict</th>
                                        <th class="text-left">Union</th>
                                        <th class="text-left">Modified</th>
{{--                                        <th class="text-left">Ward</th>--}}
{{--                                        <th class="text-left">Village</th>--}}
                                        <th class="text-center" style="width:150px;">Action</th>
                                    </tr>
                                </thead>

                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- /.content -->


<div class="modal fade"  id="export_modal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md"role="document">
        <div class="modal-content" id="export_modal_content">

        </div>
    </div>
</div>

@endsection

@push('js_lib')
    <!-- sweetalert2.js -->
    <script src="{{ asset('backend/plugins/sweetalert2/dist/sweetalert2.all.min.js') }}"></script>
@endpush

@push('js_custom')

<script type="text/javascript">

    function ichecker()
    {
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass : 'icheckbox_flat-green',
        radioClass : 'iradio_flat-green'
        });
    }

    function workorder_print(data){
        window.location = data.redirectTo;
    }

    $(function () {
        // $('body').addClass('sidebar-collapse');
        ichecker();
        $(document).on('click', '.viewDetails', function(e){
            let id = $(this).attr('data-id');

            let commonModal = $('#commonModal');
            commonModal.find('.modal-title').html('Child Details')
            let modal_body = commonModal.find('.modal-body');
            modal_body.empty();
            $.ajax({
                type: "get",
                url: "{{ route('anchalchild.index') }}"+'/'+id,
                success: function (data) {
                    modal_body.empty();
                    modal_body.html(data);
                    commonModal.modal('show');

                },
                error: function (data) {
                    //console.log('Error:', data);
                }
            });

        });

        $(document).on('click', '.export-btn', function(e) {
            $("#export_modal_content").empty();
            $("#export_modal_content").load("{{ route('anchalchild.childExportForm').'?'.http_build_query($request) }}",function(data){
                let modal = $('#export_modal');
                modal.find('.modal-title').html('Child Information Export')
                modal.modal('show');
            });
        });


    });

    $(document).ready(function(){
        {{--let DT_URL = "{{ URL::full() }}";--}}
        let DT_URL = "{{  route('anchalchild.index').'?' }}<?php echo $queryString; ?>";

        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            ajax: DT_URL,
            columns: [
                {data: 'sl', name: 'sl',class: 'text-left'},
                {data: 'child_info', name: 'child_info',class: 'text-left'},
                {data: 'district_name', name: 'district_name',class: 'text-left'},
                {data: 'subdistrict_name', name: 'subdistrict_name',class: 'text-left'},
                {data: 'union_name', name: 'union_name',class: 'text-left'},
                {data: 'modify_info', name: 'modify_info',class: 'text-left'},
                {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-left'},
            ],
            drawCallback: function (settings) {
            }
        });

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
                        url: "{{ route('anchalchild.store') }}"+'/'+id,
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

