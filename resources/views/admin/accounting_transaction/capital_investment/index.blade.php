@extends('admin.layouts.app')

@push('css_lib')
    <!-- Bootstrap time Picker -->
    {{--  <link rel="stylesheet" href="{{URL::asset('backend/plugins/timepicker/bootstrap-timepicker.min.css')}}">--}}
    {{--  <link rel="stylesheet" href="{{URL::asset('backend/plugins/fullcalendar/fullcalendar.css')}}">--}}
    {{--  <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/bootstrap-colorpicker/dist/css/bootstrap-colorpicker.min.css')}}" />--}}
    {{--  <!-- daterange picker -->--}}
    {{--  <link rel="stylesheet" href="{{URL::asset('backend/bower_components/bootstrap-daterangepicker/daterangepicker.css')}}">--}}
    {{--  <link rel="stylesheet" href="{{URL::asset('backend/dist/css/custom.css')}}" media="all">--}}

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"/>

@endpush


@push('css_custom')

@endpush

@section('content')

    @include('admin.layouts.partial.content_header',
            ['breadcrumb'=>$data['breadcrumb'],'page_title'=>$data['page_title']])
    <!-- Main content -->
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-th-list"></i> Investment Transaction</h3>
                            <div class="card-tools">
                                <a class="btn btn-primary btn-sm" href="{{ route('accounting_transaction.capital_investment.create') }}"><i class="fa fa-plus-circle"></i> Add Investment</a>
                            </div>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table  class="data-table table table-bordered table-hover">
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>Transaction ID</th>
                                        <th>Amount</th>
                                        <th>Comment</th>
                                        <th>Created At</th>
                                        <th></th>
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
    <!-- /.content -->

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
                ajax: "{{ route('accounting_transaction.capital_investment') }}",
                columns: [
                    {data: 'sl', name: 'sl',class: 'text-center'},
                    {data: 'transaction_id', name: 'transaction_id',class: 'text-center'},
                    {data: 'amount', name: 'amount',class: 'text-center'},
                    {data: 'comment', name: 'comment',class: 'text-center'},
                    {data: 'created_at', name: 'created_at',class: 'text-center'},
                    {data: 'action', name: 'action', orderable: false, searchable: false,class: 'text-center'},
                ],
                "info": true,
                "autoWidth": false,
                "responsive": true,
            });

        });

        $(document).on('click', '.deleteData', function () {
            var id = $(this).data("id");

            if (confirm("Are You sure want to delete !")){
                $.ajax({
                    type: "DELETE",
                    url: "{{ route('accounting_transaction.capital_investment') }}"+'/'+id,
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
                    }
                });

            }
        });

    </script>

@endpush

