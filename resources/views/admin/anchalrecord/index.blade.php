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
        <h1>Anchal Children Monthly Information</h1>
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Anchal App</a></li>
            <li class="active">Report Of Child Monthly Information</li>
        </ol>
    </section>

    <?php
    $request = request()->all();
    //Get the full string
    $queryString = $_SERVER['QUERY_STRING'];
//    echo $queryString;
    ?>
    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12 col-lg-12 col-md-12">
                <div class="box box-default">
                    <div class="box-header">
                        <div class="row">
                            <div class="col-md-10 col-xs-12 text-left">

                                <form  action="{{route('anchalrecord.index')}}" method="get"  class="form-inline" enctype="multipart/form-data">
                                    @if($user->is_admin==1 || $user->access_all_project==1)
                                        <select style="margin-top: -7px;" class="form-control input-sm" id="project_id" name="project_id">
                                            <option selected value="">Select Project</option>
                                            @foreach($project as $item)
                                                <option {{ !empty($request['project_id']) && $request['project_id']==$item->project_code ? 'selected' : ''}} value="{{$item->project_code}}">{{$item->name}}</option>
                                            @endforeach
                                        </select>
                                    @endif
                                    <select style="margin-top: -7px;" class="form-control input-sm" id="yearofentry" name="yearofentry">
                                        <option selected value="">Select Year</option>
                                        @if(!empty($years))
                                        @foreach($years as $item)
                                            <option {{ !empty($request['yearofentry']) && $request['yearofentry']==$item->yearofentry ? 'selected' : ''}} value="{{$item->yearofentry}}">{{$item->yearofentry}}</option>
                                        @endforeach
                                        @endif
                                    </select>
{{--                                    <select style="margin-top: -7px;" class="form-control input-sm" id="monthofentry_" name="monthofentry">--}}
{{--                                        <option selected value="">Select Month</option>--}}
{{--                                        @if(!empty($months))--}}
{{--                                            @foreach($months as $key => $item)--}}
{{--                                                <option {{ !empty($request['monthofentry']) && $request['monthofentry']==$key ? 'selected' : ''}} value="{{$key}}">{{$item}}</option>--}}
{{--                                            @endforeach--}}
{{--                                        @endif--}}
{{--                                    </select>--}}
                                    <input type="text" name="child_auto_id" class="form-control input-sm" style="margin-top:0px;" placeholder="Child Auto ID" value="{{ !empty($request['child_auto_id']) ? $request['child_auto_id'] : ''}}"/>
                                    <button type="submit" style="margin-top: -7px;"  class="btn btn-sm btn-success" style="border-radius: 0px;"><span class="fa fa-search"></span> Search</button>
                                    <a href="{{route('anchalrecord.index')}}" style="margin-top: -7px;" class="btn btn-md btn-danger" style="border-radius: 0px;"><span class="fa fa-refresh"></span></a>
                                </form>
                            </div>
                            <div class="col-md-2">
                                @if($user->hasPermissionTo('childrecord.export'))
                                    <a href="{{route('anchalrecord.exportDemo5')}}?<?php echo $queryString; ?>" class="btn btn-sm btn-primary"> <i class="fa fa-download"></i> Export</a>
                                @endif
                                @if($user->hasPermissionTo('childrecord.import'))
                                    <a href="javascript:void(0);" class="btn btn-sm btn-info importForm"> <i class="fa fa-upload"></i> Import</a>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="table-responsive">
                            <table id="example1" class="table table-bordered table-striped table-hover data-table table-responsive">
                                <thead>
                                    <tr>
                                        <th class="text-left" style="width:10px">SL</th>
{{--                                        <th class="text-left">Project</th>--}}
                                        <th class="text-left">Child Info</th>
{{--                                        <th class="text-left">Child Auto ID</th>--}}
{{--                                        <th class="text-left">Anchal Auto ID</th>--}}
                                        <th class="text-left">Year</th>
                                        <th class="text-left">Month</th>
                                        <th class="text-left">Present Count</th>
                                        <th class="text-left">Absent Count</th>
                                        <th class="text-left">Modified</th>
                                        <th class="text-left">Action</th>
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


<div class="modal fade" id="details" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-md"role="document">
        <div class="modal-content">

        </div>
    </div>
</div>

<div class="modal fade"  id="monthly_entry" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog modal-lg"role="document">
        <div class="modal-content" id="monthly_entry_form">

        </div>
    </div>
</div>


<div class="modal fade"  id="import_modal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-md"role="document">
            <div class="modal-content">
                <form role="form" method="post" style="background-color:#fff;"
                      action="{{ route('anchalrecord.childRecordImport') }}" enctype="multipart/form-data"  data-submit="ajax">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="child_file" accept=".csv,.xlsx" class="form-control input-sm"/>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-danger pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" id="saveBtn" class="btn btn-sm btn-success pull-left" style="border-radius: 0px;margin-bottom: 6px;"><span class="fa fa-upload"></span> Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


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

    $(function () {

        // $('body').addClass('sidebar-collapse');

        $(document).on('click', '.viewDetails', function(e){
            let id = $(this).attr('data-id');

            let commonModal = $('#commonModal');
            commonModal.find('.modal-title').html('Monthly Details')
            let modal_body = commonModal.find('.modal-body');
            modal_body.empty();
            $.ajax({
                type: "get",
                url: "{{ route('anchalrecord.monthlyDetails') }}"+'?id='+id,
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

        $(document).on('click', '.monthlyform', function () {
            var id = $(this).data("id");
            $("#monthly_entry_form").load("{{ route('anchalrecord.loadMonthlyDataForm') }}?id="+id,function(data){
                let modal = $('#monthly_entry');
                modal.find('#child_hidden_id').val(id);
                modal.find('.modal-title').html('Monthly Entry Form')
                modal.modal('show');
            });
        });



        $(document).on('click', '.importForm', function () {
            let modal = $('#import_modal');
            modal.find('.modal-title').html('Monthly Data Import')
            modal.modal('show');
        });

        $(document).on('click', '.export-btn', function(e) {
            $("#export_modal_content").empty();
            $("#export_modal_content").load("{{ route('anchalrecord.childRecordExportForm').'?'}}<?php echo $queryString; ?>",function(data){
                let modal = $('#export_modal');
                modal.find('.modal-title').html('Monthly Record Export')
                modal.modal('show');
            });
        });



    });

    $(document).ready(function(){
        let DT_URL = "{{  route('anchalrecord.index').'?' }}<?php echo $queryString; ?>";
        var table = $('.data-table').DataTable({
            processing: true,
            serverSide: true,
            responsive:true,
            ajax: DT_URL,
            columns: [
                {data: 'sl', name: 'sl',class: 'text-left'},
                {data: 'child_info', name: 'child_info',class: 'text-left'},
                {data: 'yearofentry', name: 'yearofentry',class: 'text-left'},
                {data: 'monthofentry', name: 'monthofentry',class: 'text-left'},
                {data: 'days_present', name: 'days_present',class: 'text-center'},
                {data: 'days_absent', name: 'days_absent',class: 'text-center'},
                {data: 'modify_info', name: 'modify_info',class: 'text-left'},
                {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
            ],
            drawCallback: function (settings) {
            }
        });

        $(document).on('click', '.monthlyformEdit', function () {
            var id = $(this).data("id");
            $("#monthly_entry_form").empty();
            $("#monthly_entry_form").load("{{ route('anchalrecord.loadMonthlyDataFormEdit') }}?id="+id,function(data){
                let modal = $('#monthly_entry');
                // modal.find('#child_hidden_id').val(id);
                modal.find('.modal-title').html('Edit Monthly Data')
                modal.modal('show');
            });
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
                        type: "GET",
                        url: "{{ route('anchalrecord.deleteRecord') }}"+'?id='+id,
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
@endpush

