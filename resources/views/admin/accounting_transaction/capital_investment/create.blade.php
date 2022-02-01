@extends('admin.layouts.app')

@push('css_lib')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">
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
                <!-- left column -->
                <div class="col-md-6">
                    <!-- general form elements -->
                    <div class="card card-default">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fa fa-indent"></i> Add Investment</h3>
                        </div>
                        <!-- /.card-header -->
                        <!-- form start -->
                        <form method="post" id="saveForm" action="{{ route('accounting_transaction.capital_investment.store') }}" data-submit="ajax">
                            @csrf
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="invest_amount">Invest Amount</label>
                                    <input type="number" required class="form-control" name="invest_amount" id="invest_amount" placeholder="Invest Amount">
                                </div>
                                <div class="form-group">
                                    <label for="receiving_account">Receiving Account</label>
                                    <select class="form-control select2bs4" required id="receiving_account" name="receiving_account">
                                        <option value="">Select</option>
                                        @if($data['receiveing_accounts'])
                                            @foreach($data['receiveing_accounts'] as $item)
                                                <option value="{{$item->id}}">{{ $item->name.'('.$item->economic_code.')' }}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="comment">Comment</label>
                                    <textarea class="form-control" placeholder="Your Comment Here" id="comment" required name="comment"></textarea>
                                </div>
                            </div>
                            <!-- /.card-body -->
                            <div class="card-footer">
                                <button type="submit" id="saveBtn" class="btn btn-primary saveBtn">Submit</button>
                            </div>
                        </form>
                    </div>
                    <!-- /.card -->
                </div>
                <!--/.col (left) -->
                <!-- right column -->
                <div class="col-md-6">

                </div>
                <!--/.col (right) -->
            </div>
            <!-- /.row -->
        </div><!-- /.container-fluid -->
    </section>

@endsection

@push('js_lib')
    <!-- Select2 -->
    <script src="{{ URL::asset('backend/plugins/select2/js/select2.full.min.js') }}"></script>
@endpush

@push('js_custom')
    <script>
        $(document).ready(function() {
            //Initialize Select2 Elements
            // $('.select2').select2()
            //Initialize Select2 Elements
            $('.select2bs4').select2({
                theme: 'bootstrap4'
            })
        });

        $(function () {
            $.validator.setDefaults({
                submitHandler: function () {
                    return true;
                }
            });
            $('#saveForm').validate({
                rules: {
                    receiving_account: {
                        required: true
                    },
                    invest_amount: {
                        required: true
                    },
                    comment: {
                        required: true
                    },
                },
                messages: {
                },
                errorElement: 'span',
                errorPlacement: function (error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function (element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                }
            });
        });
    </script>
@endpush
