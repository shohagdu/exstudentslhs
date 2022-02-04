@extends('admin.layouts.app')
@push('css_lib')
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}"/>
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}"/>
@endpush
@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-th-list"></i> Add New User</h3>
                            <div class="card-tools">
                                <a class="btn btn-primary btn-sm" href="{{ route('user.userRecord') }}"><i class="fa
                                fa-plus-circle"></i> User Record</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="form-horizontal" action="{{ route('user.store') }}">
                                <div class="col-md-12">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
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
                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
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

                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
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
                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
                                            <label for="password-confirm" class="text-md-right">{{ __('Confirm Password') }}: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" tabindex="4">
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
                                            <label>Role: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select class="form-control @error ('user_type') is-invalid @enderror" name="user_type" id="user_type" required  >
                                                <option value="">Select Role</option>
                                                @foreach($roles as $rKey=> $role)
                                                    <option value="{{ $rKey}}" {{ ((!empty(old('user_type')) && (old ('user_type')==$rKey))?"selected":'') }}   >{{$role}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <div class="col-sm-2 text-right">
                                    </div>
                                    <div class="col-sm-3">
                                        <button class="btn btn-block btn-lg btn-success pull-right"
                                                ><i class="fa fa-check-circle" aria-hidden="true"></i> Save</button>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
