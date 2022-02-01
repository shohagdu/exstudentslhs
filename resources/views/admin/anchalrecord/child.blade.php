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
        <h1><small>Project:</small>{{$childInfo->projectInfo->name}}<small>Child ID:</small> {{ $childInfo->child_auto_id }} <small>Child Name:</small>  {{ $childInfo->child_name }}</h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Anchal App</a></li>
            <li class="active">List of Single Child Data(Month wise)</li>
        </ol>
    </section>


    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12 col-md-12 col-lg-12">
                <div class="box box-default">
                    <div class="box-header text-right">
                        <div class="col-md-4 text-left">
                            <h4>Child Monthly Information</h4>
                        </div>
                        <div class="col-md-3 text-left">

                        </div>
                        <div class="col-md-5">
                            @if($user->hasPermissionTo('child.list'))
                                <a href="{{route('anchalchild.index')}}" class="btn btn-sm btn-primary"> <i class="fa fa-list"></i> Child List</a>
                            @endif
                            @if($user->hasPermissionTo('childrecord.create'))
                                <a href="javascript:void(0);" data-id="{{  $childInfo->child_auto_id }}" class="btn btn-sm btn-info monthlyform"> <i class="fa fa-plus"></i> New Month Data</a>
                            @endif
                            @if($user->hasPermissionTo('childrecord.export'))
                                <a href="{{route('anchalrecord.exportDemo5')}}?child_auto_id=<?php echo $childInfo->child_auto_id; ?>" class="btn btn-sm btn-success"><i class="fa fa-download"></i> Export</a>
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
                                        <th class="text-left">Year</th>
                                        <th class="text-left">Month</th>
                                        <th class="text-left">Admit This Month</th>
                                        <th class="text-left">Present</th>
                                        <th class="text-left">Absent</th>
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
        <div style="" class="modal-dialog modal-lg"role="document">
            <div class="modal-content" id="monthly_entry_form">

            </div>
        </div>
    </div>

    <div class="modal fade"  id="export_modal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-md"role="document">
            <div class="modal-content" id="export_modal_content">
                <form class="form-horizontal" role="form" method="get" style="background-color:#fff;"
                      action="{{ route('anchalrecord.exportChildRecord') }}" enctype="multipart/form-data"  data-submit-download="ajax">
                    @csrf
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        <h4 class="modal-title">Modal Header</h4>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Years: <small style="color:red"></small></label>
                            <div class="col-md-8">
                                <select  class="form-control" id="yearofentry" name="yearofentry">
                                    <option selected value="">Select Year</option>
                                    @if(!empty($years))
                                        @foreach($years as $item)
                                            <option {{ !empty($request['yearofentry']) && $request['yearofentry']==$item->yearofentry ? 'selected' : ''}} value="{{$item->yearofentry}}">{{$item->yearofentry}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Months: <small style="color:red"></small></label>
                            <div class="col-md-8">
                                <select  class="form-control" id="monthofentry" name="monthofentry">
                                    <option selected value="">Select Month</option>
                                    @if(!empty($months))
                                        @foreach($months as $key => $item)
                                            <option {{ !empty($request['monthofentry']) && $request['monthofentry']==$key ? 'selected' : ''}} value="{{$key}}">{{$item}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">Export: <small style="color:red"></small></label>
                            <div class="col-md-8">
                                <select class="form-control" id="export_type" name="export_type">
                                    <option value=".xlsx">Excel</option>
                                    <option value=".csv">CSV</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="" class="col-md-2 control-label">View Type: <small style="color:red"></small></label>
                            <div class="col-md-8">
                                <select class="form-control" id="view_type" name="view_type">
                                    <option value="multiview">Multi View</option>
                                    <option value="regular">Regular View</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="res_handover_to" class="col-md-2 control-label"> Start: <small style="color:red"></small></label>
                            <div class="col-md-3">
                                <input type="number" min="1" class="form-control" placeholder="Start Number" name="start"/>
                            </div>
                            <label for="res_handover_to" class="col-md-2 control-label"> End: <small style="color:red"></small></label>
                            <div class="col-md-3">
                                <input type="number" min="1"  class="form-control" placeholder="End Number" name="end"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="hidden" name="child_auto_id" value="{{ $childInfo->child_auto_id }}"/>
                        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                        <button type="submit" name="submit" id="saveBtn" class="btn btn-success pull-right">Start Export</button>
                    </div>
                </form>
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
        let DT_URL = "{{ route('anchalrecord.getChildData').'?childid='.$childInfo->child_auto_id }}";
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
                $("#monthly_entry_form").empty();
                $("#monthly_entry_form").load("{{ route('anchalrecord.loadMonthlyDataForm') }}?id="+id,function(data){
                    let modal = $('#monthly_entry');
                    modal.find('#child_hidden_id').val(id);
                    modal.find('.modal-title').html('Monthly Entry Form')
                    modal.modal('show');
                });
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

            $(document).on('click', '.export-btn', function(e) {
                let modal = $('#export_modal');
                modal.find('.modal-title').html('Monthly Record Export')
                modal.modal('show');
            });



        });
        $(document).ready(function(){
            var table = $('.data-table').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: DT_URL,
                columns: [
                    {data: 'sl', name: 'sl',class: 'text-left'},
                    {data: 'yearofentry', name: 'yearofentry',class: 'text-left'},
                    {data: 'monthofentry', name: 'monthofentry',class: 'text-left'},
                    {data: 'ifadmittedthismonth', name: 'ifadmittedthismonth',class: 'text-center'},
                    {data: 'days_present', name: 'days_present',class: 'text-center'},
                    {data: 'days_absent', name: 'days_absent',class: 'text-center'},
                    {data: 'modify_info', name: 'modify_info',class: 'text-left'},
                    {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
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
