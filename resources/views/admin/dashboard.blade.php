@extends('admin.layouts.app')

@push('css_lib')
<!-- Bootstrap time Picker -->
{{--  <link rel="stylesheet" href="{{URL::asset('backend/plugins/timepicker/bootstrap-timepicker.min.css')}}">--}}
{{--  <link rel="stylesheet" href="{{URL::asset('backend/plugins/fullcalendar/fullcalendar.css')}}">--}}
{{--  <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" />--}}
{{--  <!-- daterange picker -->--}}
{{--  <link rel="stylesheet" href="{{URL::asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">--}}
{{--  <link rel="stylesheet" href="{{URL::asset('backend/dist/css/custom.css')}}" media="all">--}}
@endpush


@push('css_custom')

@endpush

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1>Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="#">{{env('APP_NAME')}}</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div><!-- /.container-fluid -->
</section>
<!-- Main content -->
<section class="content">

    <!-- Default box -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Title</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            Start creating your amazing application!
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
            Footer
        </div>
        <!-- /.card-footer-->
    </div>
    <!-- /.card -->

</section>
<!-- /.content -->

@endsection

@push('js_lib')
<!-- bootstrap time picker -->
<script src="{{URL::asset('backend/plugins/timepicker/bootstrap-timepicker.min.js')}}"></script>
<script src="{{URL::asset('backend/bower_components/bootstrap-colorpicker/dist/js/bootstrap-colorpicker.min.js')}}"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="{{ URL::asset('backend/dist/js/pages/dashboard.js') }}"></script>
<script src="{{ URL::asset('backend/plugins/printThis/printThis.js') }}"></script>
<script src="{{ URL::asset('backend/bower_components/moment/min/moment.min.js') }}"></script>
<script src="{{ URL::asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>

<script src="{{ URL::asset('backend/plugins/fullcalendar/fullcalendar.js') }}"></script>
@endpush

@push('js_custom')


<script>


    $(function(){

        $('.select2').select2();



        // Date picker
        $('.input-daterange').datepicker({
            format: "dd-mm-yyyy",
            todayBtn: "linked",
            autoclose: true,
            todayHighlight: true
        });

        $('.timepicker').timepicker({
          showInputs: false,
          interval: 5,
        });

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var SITEURL = "{{url('/')}}";

        $("document").ready(function(){
            {{--$("#bdiv").load("{{route('admin.loadDashboard')}}",function(data){--}}
            {{--    // use the data param--}}
            {{--    // e.g. $(data).find('#icc10n')--}}
            {{--});--}}
        });

});
</script>

@endpush

