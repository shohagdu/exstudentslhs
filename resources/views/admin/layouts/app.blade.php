<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{ env('APP_NAME') }} @yield('title') </title>
    <meta name="csrf-token" content="{{ csrf_token() }}"/>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <script>
        var base_url = '<?php echo url('admin/');?>';
    </script>
@yield('meta')

<!----------------- new ------------------>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/fontawesome-free/css/all.min.css') }}"/>
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('backend/dist/css/adminlte.min.css') }}"/>

    <!-- Toastr -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/toastr/toastr.min.css') }}"/>


    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"/>

    <!--------------- end new ------------>

    <!-- Google Font -->
{{--    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">--}}

    <!-- Bootstrap 3.3.7 -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/bootstrap/dist/css/bootstrap.min.css') }}">--}}
<!-- Font Awesome -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/font-awesome/css/font-awesome.min.css') }}">--}}
<!-- Ionicons -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/Ionicons/css/ionicons.min.css') }}">--}}
<!-- jvectormap -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/jvectormap/jquery-jvectormap.css') }}">--}}
    <!-- Date Picker -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">--}}
    <!-- Daterange picker -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css') }}">--}}
    <!-- bootstrap wysihtml5 - text editor -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css') }}">--}}

    <!--Time Picker -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/bootstrap-timepicker/css/bootstrap-timepicker.min.css') }}">--}}

    <!-- iCheck -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/iCheck/all.css') }}">--}}

    <!-- toastr -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/toastr/toastr.min.css') }}">--}}

@stack('css_lib')

<!-- Theme style -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/dist/css/AdminLTE.min.css') }}">--}}
<!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
{{--    <link rel="stylesheet" href="{{ asset('backend/dist/css/skins/all-skins.min.css') }}">--}}
    <!-- Morris chart -->
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/morris.js/morris.css') }}">--}}


{{--    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/select2/dist/css/select2.min.css') }}">--}}
{{--    <link rel="stylesheet" href="{{ URL::asset('backend/dist/css/custom.css') }}">--}}
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    {{-- jquery validator --}}
{{--    <link rel="stylesheet" href="{{ asset('backend/plugins/validate/validate.css') }}">--}}

    <!-- DataTables -->
{{--    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">--}}

    <style type="text/css">
        /*th {*/
        /*    text-align: center;*/
        /*}*/

        /*.table thead tr th {*/
        /*    vertical-align: middle !important;*/
        /*}*/

        /*@media print {*/
        /*    .noprint {*/
        /*        display: none;*/
        /*    }*/
        /*}*/

        /*.select2-container--default .select2-selection--multiple .select2-selection__choice {*/
        /*    padding: 0px 5px;*/
        /*}*/
    </style>
    @stack('css_custom')
</head>

<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed">
<div class="wrapper">

@include('admin.layouts.partial.header')

    <!-- Left side column. contains the logo and sidebar -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- sidebar: style can be found in sidebar.less -->
        @include('admin.layouts.partial.sidenav')
    <!-- /.sidebar -->
    </aside>


    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        @yield('content')
    </div>


    <!-- Modal -->
    <div class="modal fade" id="commonModal" aria-hidden="true" data-keyboard="false" data-backdrop="static">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header bg-camelia">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h3 class="modal-title">Modal Heading</h3>
                </div>
                <div class="modal-body">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <!-- /.content-wrapper -->
    <footer class="main-footer">
        @include('admin.layouts.partial.footer')
    </footer>

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
        <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->
</div>

<!---------- new ---------->
<!-- jQuery -->
<script src="{{ URL::asset('backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ URL::asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('backend/dist/js/adminlte.min.js')}}"></script>
<!-- AdminLTE for demo purposes -->
<script src="{{URL::asset('backend/dist/js/demo.js')}}"></script>

<!----------------- new ------------->

<!-- jQuery 3 -->
{{--<script src="{{ URL::asset('backend/bower_components/jquery/dist/jquery.min.js') }}"></script>--}}
<!-- jQuery UI 1.11.4 -->
{{--<script src="{{ URL::asset('backend/bower_components/jquery-ui/jquery-ui.min.js') }}"></script>--}}
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
    $.widget.bridge('uibutton', $.ui.button);
</script>
<!-- Bootstrap 3.3.7 -->
{{--<script src="{{ URL::asset('backend/bower_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>--}}

<!-- Select2 -->
{{--<script src="{{ URL::asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>--}}

<!-- Sparkline -->
{{--<script src="{{ URL::asset('backend/bower_components/jquery-sparkline/dist/jquery.sparkline.min.js') }}"></script>--}}
<!-- jvectormap -->
{{--<script src="{{ URL::asset('backend/plugins/jvectormap/jquery-jvectormap-1.2.2.min.js') }}"></script>--}}
{{--<script src="{{ asset('backend/plugins/jvectormap/jquery-jvectormap-world-mill-en.js') }}"></script>--}}
<!-- jQuery Knob Chart -->
{{--<script src="{{ URL::asset('backend/bower_components/jquery-knob/dist/jquery.knob.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.js') }}"></script>--}}
<!-- datepicker -->
{{--<script src="{{ URL::asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>--}}
<!-- Bootstrap WYSIHTML5 -->
{{--<script src="{{ URL::asset('backend/plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.all.min.js') }}"></script>--}}
<!-- Slimscroll -->
{{--<script src="{{ URL::asset('backend/bower_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>--}}
<!-- FastClick -->
{{--<script src="{{ URL::asset('backend/bower_components/fastclick/lib/fastclick.js') }}"></script>--}}

<!-- AdminLTE App -->
{{--    <script src="{{ URL::asset('backend/dist/js/adminlte.min.js') }}"></script>--}}
<!-- AdminLTE for demo purposes -->
{{--    <script src="{{ URL::asset('backend/dist/js/demo.js') }}"></script>--}}
<!-- daterangepicker -->
{{--<script src="{{ URL::asset('backend/bower_components/moment/min/moment.min.js') }}"></script>--}}

<!-- toastr -->
{{--<script src="{{ URL::asset('backend/bower_components/toastr/toastr.min.js') }}"></script>--}}

<!-- iCheck -->
{{--<script src="{{ URL::asset('backend/plugins/iCheck/icheck.min.js') }}"></script>--}}
<!-- Morris.js charts -->
{{--<script src="{{ URL::asset('backend/bower_components/raphael/raphael.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('backend/bower_components/morris.js/morris.min.js') }}"></script>--}}


{{-- jquery validator --}}
{{--<script src="{{ URL::asset('backend/plugins/validate/jquery.validate.min.js') }}"></script>--}}
{{----}}
{{--<script src="{{ URL::asset('backend/plugins/validate/messages.validate.js') }}"></script>--}}

<!-- Toastr -->
<script src="{{ URL::asset('backend/plugins/toastr/toastr.min.js') }}"></script>

<!-- jquery-validation -->
<script src="{{ URL::asset('backend/plugins/jquery-validation/jquery.validate.min.js') }}"></script>
<script src="{{ URL::asset('backend/plugins/jquery-validation/additional-methods.min.js') }}"></script>

<!-- bootstrap datepicker -->
{{--<script src="{{ asset('backend/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>--}}

<!-- bootstrap timepicker -->
{{--<script src="{{ asset('backend/bower_components/bootstrap-timepicker/js/bootstrap-timepicker.min.js') }}"></script>--}}


<!-- DataTables  & Plugins -->
{{--<script src="{{ URL::asset('backend/plugins/datatables/jquery.dataTables.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('backend/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('backend/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('backend/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('backend/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>--}}
{{--<script src="{{ URL::asset('backend/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>--}}

<!-- DataTables -->
{{--<script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>--}}
{{--<script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>--}}

<script type="text/javascript">

    function ajaxSubmit(that){

        callback_function = $(that).attr('data-callback');

        $.ajax({
            url: $(that).attr('action'),
            data: new FormData(that),
            type: "POST",
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                $("#saveBtn").attr("disabled",true);
                $(".saveBtn").attr("disabled",true);
            },
            success:  function (data) {
                if(typeof callback_function == "string" && typeof window[callback_function] == "function"){
                    window[callback_function](data);
                    $("#saveBtn").attr("disabled",false);
                    $(".saveBtn").attr("disabled",false);
                }
                else{
                    if(data.success){
                        toastr.success(data.success).then(window.location = data.redirectTo);
                    }
                    else{
                        $("#saveBtn").attr("disabled",false);
                        $(".saveBtn").attr("disabled",false);
                        toastr.error(data.error);
                    }
                }


            },
            error: function (errors) {
                $("#saveBtn").attr("disabled",false);
                $(".saveBtn").attr("disabled",false);
                $.each(errors.responseJSON.errors, function(error){
                    toastr.error(errors.responseJSON.errors[error][0]);
                });
                if(errors.responseJSON.message){
                    toastr.error(errors.responseJSON.message);
                }
            }
        });
    }

    $(function(){

        $( document ).ajaxError(function(rs, textStatus) {
            if(textStatus.status == 401)
                window.location.reload();
        });

        $.ajaxSetup({
            headers: {
                // 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            }
        });

        $(document).on('submit', "form[data-submit='ajax']",  function(e){
            e.preventDefault();
            // await callback_function();
            // window[callback_function].call();
            ajaxSubmit(this);

        });

        $(document).on('click', "[data-view='detailView']", function(e){
            e.preventDefault();
            let url = $(this).attr('href');
            let modal_heading = $(this).attr('data-modalHeading');
            let modal = $('#commonModal');
            modal.find('.modal-title').text(modal_heading);
            $.get(url, function(response) {
                let modal_body = modal.find('.modal-body');
                modal_body.html(response);
                modal.modal('show');
            });
        });

    })
</script>

@stack('js_lib')
{!! Toastr::message() !!}
@stack('js_custom')


@include('admin.layouts.partial.errors')


@if($errors->any())
    @foreach ($errors->all() as $error)
        <script>
            toastr.error('{{ $error }}');
        </script>
    @endforeach
@endif

</body>

</html>
