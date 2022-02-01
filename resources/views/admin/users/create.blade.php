@extends('admin.layouts.app')


@push('css_lib')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{URL::asset('backend/bower_components/chosen/chosen.css') }}">

    <link href="{{ asset('backend/bower_components/summernote/summernote.css') }}" rel="stylesheet">




@endpush

@push('css_custom')
<style>


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

</style>
@endpush

@section('content')
    <!-- Content Header (user header) -->
    <section class="content-header">
        <h1>Add New User</h1>
        <ol class="breadcrumb">
            <li><a href="{{ URL('/') }}"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="{{ route('users.index') }}"> Users </a></li>
            <li class="active">Add New User</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="col-md-12">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">User Info</h3>
                    <a href="{{ route('users.index') }}" class="btn btn-danger btn-xs pull-right">Back to List</a>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                <!-- form start -->
                    <form method="POST" action="{{ route('users.store') }}">
                        <div class="col-md-12">
                            <div class="row">
                                    
                                @csrf

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label for="name" class="text-md-right">{{ __('Name') }}: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" tabindex="1" autofocus>

                                            @error('name')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-4">

                                            <label for="email" class="text-md-right">{{ __('E-Mail Address') }}: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" tabindex="2">

                                            @error('email')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-4">

                                            <label for="password" class="text-md-right">{{ __('Password') }}: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" tabindex="3">

                                            @error('password')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <br>
                                    <br>
                                    <div class="form-group">
                                        <div class="col-sm-4">

                                            <label for="password-confirm" class="text-md-right">{{ __('Confirm Password') }}: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">                                   
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" tabindex="4">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="col-sm-4">
                                            <label>Role: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">                 
                                            {{-- <select name="role" required class="form-control">
                                                <option value="">--Select Role--</option>
                                                @foreach($user_role as $role)
                                                    <option value="{{$role->id}}">{{$role->name}} @if($role->short_name) ({{$role->short_name}}) @endif</option>
                                                @endforeach
                                            </select> --}}

                                            <select class="form-control select2" id="role_select" multiple="" data-placeholder="Select Role" required style="width: 100%;" name="role[]" aria-hidden="true" tabindex="2">
                                                @foreach($roles as $role)
                                                  <option value="{{$role->name}}" @if (old("role")){{ (in_array($role->name, old("role")) ? "selected":"") }}@endif
                                                    >{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12" style="margin-top: 20px;">
                            <div class="col-md-4">
                                <div class="form-group" style="margin: 20px 0px 0px 10px;">
                                    <a href="{{ route('users.index') }}" class="btn btn-block bg-red" tabindex="7">Cancel</a>
                                </div>
                            </div>

                            <div class="col-md-4 pull-right">
                                <div class="form-group" style="margin: 20px 0px 0px 10px;">
                                    <input type="submit" class="btn btn-block btn-success pull-right" value="Add User" tabindex="6">
                                </div>
                            </div>
                            
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js_lib')
    <!-- Select2 -->
    <script src="{{ URL::asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>

    <script src="{{ asset('backend/bower_components/summernote/summernote.js')}}"></script>

    <script src="{{ URL::asset('backend/bower_components/chosen/chosen.jquery.js') }}"></script>

    <script src="{{ URL::asset('backend/bower_components/chosen/chosen.proto.js') }}"></script>

@endpush

@push('js_custom')
<script>
    $(function() {


        $('#summernote').summernote({
            tabsize: 2,
            height: 80
        });


        // Initial Focused field
        $('#name').focus();




        //Initialize Select2 Elements
        $('.select2').select2();

        //Date picker
        $('.date').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',


        });

        $(".chosen-select").chosen({
            create_option: true,
            persistent_create_option: true,
            create_option_text: 'add',
        });
    });
</script>
@endpush
