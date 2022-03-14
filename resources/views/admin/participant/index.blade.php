@extends('admin.layouts.app')
@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"/>
@endpush
@php
    $userType         = (!empty(Auth::user()->user_type)?Auth::user()->user_type:'');
    $profession       = (!empty($data['profession'])?$data['profession']:'');
    $userType         = (!empty($data['userType'])?$data['userType']:'');
    $userSscBatch     = (!empty($data['userSscBatch'])?$data['userSscBatch']:'');

@endphp
@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-th-list"></i> {{ (!empty($data['page_title'])
                            ?$data['page_title']:'')
                            }}</h3>
                            <div class="card-tools">
                                <button class="btn btn-primary btn-sm" onclick="addParticipant()"  data-toggle="modal"
                                        data-target="#participantModal">
                                    <i class="fa fa-plus-circle"></i> Add
                                    New</button>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <div class="form-group">
                                <div class="row">
                                    <div class="col-sm-3">
                                        <select name="sscBatchSearch" id="sscBatchSearch" class="form-control">
                                            <option value="">Select One</option>
                                            @if(!empty($userType) && $userType==7 )
                                                <option value="{{ $userSscBatch }}" >{{ $userSscBatch
                                                }}</option>
                                            @else
                                                @for($i=2022;$i>=1962;$i--)
                                                    <option value="{{ $i }}" {{ ((!empty(old('sscBatch')) && (old('sscBatch')==$i))?"selected":'') }}>{{ $i
                                                    }}</option>
                                                @endfor
                                            @endif

                                        </select>
                                    </div>
                                </div>
                            </div>
                            <table  class="data-table table table-bordered table-hover">
                                <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Batch</th>
                                    <th>Gender</th>
                                    <th>Mobile  </th>
                                    <th>Profession</th>
{{--                                    <th>Present Address </th>--}}
                                    <th style="width: 20%">Action</th>
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
    <div class="modal fade" id="participantModal" tabindex="-1" role="dialog" aria-labelledby="participantModal"
         aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="col-md-10">
                        <h6 class="modal-title" id="exampleModalLabel">Participant Information</h6>
                    </div>
                    <div class="col-sm-2">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">
                    <form  action="" id="participantForm" class="form-horizontal" method="post">
                        <div class="form-group row">
                            <label class="control-label col-sm-3 " for="bankID">Batch</label>
                            <div class="col-sm-8">
                                <select name="sscBatch" id="sscBatch" class="form-control select2">
                                    <option value="">Select One</option>
                                        @if(!empty($userType) && $userType==7 )
                                            <option value="{{ $userSscBatch }}" >{{ $userSscBatch
                                            }}</option>
                                        @else
                                            @for($i=2022;$i>=1962;$i--)
                                                <option value="{{ $i }}" {{ ((!empty(old('sscBatch')) && (old('sscBatch')==$i))?"selected":'') }}>{{ $i
                                                }}</option>
                                            @endfor
                                        @endif

                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3 " for="bankID">Name</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Name" name="name" id="name" class="form-control">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="control-label col-sm-3 " for="bankID">Mobile</label>
                            <div class="col-sm-8">
                                <input type="text" placeholder="Enter Mobile" name="mobile" id="mobile"
                                       class="form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3 " for="bankID">Gender</label>
                            <div class="col-sm-8">
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">Select One</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>
                        </div>
                        <!--
                        <div class="form-group row">
                            <label class="control-label col-sm-3 " for="Remarks">Present Address</label>
                            <div class="col-sm-8">
                                <textarea type="text" name="present_address" placeholder="Enter Present Address" id="present_address"
                                          class="form-control"></textarea>
                            </div>
                        </div>
                        -->

                        <div class="form-group row">
                            <label class="control-label col-sm-3 " for="bankID">Current Profession </label>
                            <div class="col-sm-8">
                                <select name="currentProfession" id="currentProfession" class="form-control">
                                    <option value="">Select One</option>
                                    @if(!empty($profession))
                                        @foreach($profession as $profesKey=> $profs)
                                            <option value="{{ $profesKey }}">{{ $profs  }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="control-label col-sm-3 " for="Remarks">Current Profession
                                Details</label>
                            <div class="col-sm-8">
                                <textarea type="text" name="currentProfessionDetails" placeholder="Enter Current  Profession Details" id="currentProfessionDetails"
                                          class="form-control"></textarea>
                            </div>
                        </div>
                        <!--
                         <div class="form-group row">
                            <label class="control-label col-sm-3 " for="Remarks">Facebook Link</label>
                            <div class="col-sm-8">
                                <input type="text" name="FacebookLink" placeholder="Enter Facebook Link" id="FacebookLink"  class="form-control">
                            </div>
                        </div>
                        -->






                        <div class="form-group row">
                            <div class="col-sm-3">
                                <div id="formOutput"></div>
                            </div>
                            <div class="col-sm-8">
                                <input type="hidden" name="update_id" id="update_id">
                                <button type="submit" class="btn btn-primary submit_btn" > <i
                                        class="fa fa-address-book" aria-hidden="true"></i> <span id="submitBtnLabel"></span></button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fa
                                fa-times" aria-hidden="true"></i> Close</button>
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
                ajax: {
                    url: base_url + "/participantsRecord",
                    method: "get",
                    data: function (d) {
                        (d.sscBatchSearch    = $("#sscBatchSearch").val())
                    }
                },
                columns: [
                    {data: 'sl', name: 'sl',class: 'text-left'},
                    {data: 'name', name: 'name',class: 'text-left'},
                    {data: 'batch', name: 'batch',class: 'text-left'},
                    {data: 'genderTitle', name: 'genderTitle',class: 'text-left'},
                    {data: 'mobile', name: 'mobile',class: 'text-left'},
                    {data: 'professionTitle', name: 'professionTitle',class: 'text-left'},
                    // {data: 'present_address', name: 'present_address',class: 'text-left'},
                    {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
                ],
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });
        });
        $("#sscBatchSearch").change(function () {
            table.draw();
        });
        $(document).on('click', '.deleteData', function () {
            var id = $(this).data("id");
            if (confirm("Are You sure want to delete !")){
                $.ajax({
                    type: "DELETE",
                    url: base_url + "/participantsDelete"+'/'+id,
                    success: function (data) {
                        if(data.success){
                            toastr.success(data.success);
                            table.draw();
                        } else{
                            toastr.error(data.error);
                        }
                    },
                    error: function (data) {

                    }
                });

            }
        });
        function updateParticipantInfo(id){
            $("#update_id").val('');
            $("#submitBtnLabel").html('Update');
            $('#participantForm')[0].reset();
            $(".submit_btn").attr("disabled", true);
            $.ajax({
                type: "POST",
                url: base_url + "/singleParticipantsStore",
                data: {id:id},
                'dataType': 'json',
                success: function (response) {
                    if (response.status=='success') {
                        var data=response.data;
                        $(".submit_btn").attr("disabled", false);
                        $("#update_id").val(data.id);
                        $("#sscBatch").val(data.batch);
                        $("#name").val(data.name);
                        $("#mobile").val(data.mobile);
                        $("#gender").val(data.gender);
                        // $("#present_address").val(data.present_address);
                        $("#currentProfession").val(data.profession);
                        $("#currentProfessionDetails").val(data.profession_details);
                        // $("#FacebookLink").val(data.facebookLink);
                    } else {

                    }
                }
            });
        }
        function addParticipant(){
            $('#participantForm')[0].reset();
            $("#submitBtnLabel").html('Save');
            $("#formOutput").html('');
        }

        $("#participantForm").on('submit', (function (e) {
            $(".submit_btn").attr("disabled", true);
            var formData = new FormData(this)

            e.preventDefault();
            $.ajax({
                url: base_url + "/participantsStore",
                type: "POST",
                data: formData,
                contentType: false,
                cache: false,
                processData: false,
                'dataType': 'json',
                success: function (data) {
                    $(".submit_btn").attr("disabled", false);
                    if(data.success){
                        $('#participantModal').modal('toggle');
                        toastr.success(data.success);
                        table.draw();
                    } else{
                        if (data.error.length > 0) {
                            var error_html = '';
                            for (var count = 0; count < data.error.length; count++) {
                                error_html += '<div class="alert alert-danger">' + data.error[count] + '</div> <br/>';
                            }
                            toastr.error(error_html);
                        }else {
                            toastr.error(data.error);
                        }
                    }


                }
            });
        }));

    </script>
@endpush
<style>
    .table td{
        font-size: 13px !important;
    }
    .table th{
        font-size: 11px !important;
    }
</style>

