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
                            <h3 class="card-title"><i class="fas fa-th-list"></i> Update New User</h3>
                            <div class="card-tools">
                                <a class="btn btn-primary btn-sm" href="{{ route('user.userRecord') }}"><i class="fa
                                fa-plus-circle"></i> User Record</a>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="POST" class="form-horizontal" action="{{ route('user.update') }}">
                                <div class="col-md-12">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
                                            <label for="name" class="text-md-right">{{ __('Name') }}: <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="name" type="text" class="form-control @error('name')
                                                is-invalid @enderror" name="name" value="{{ old('name', $user->name) }}"
                                                   required autocomplete="name" tabindex="1" autofocus>
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
                                            <input id="email" type="text" class="form-control @error('email')
                                                is-invalid @enderror" name="email"  value="{{ old('email', $user->email) }}" required autocomplete="email" tabindex="2">

                                            @error('email')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
                                            <label for="mobileNumber" class="text-md-right">{{ __('Mobile Number') }}:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="mobileNumber" type="text" placeholder="Enter Mobile Number"
                                                   class="form-control @error('mobileNumber')    is-invalid
@enderror" name="mobileNumber" value="{{ old('mobileNumber', $user->mobile) }}"
                                                   tabindex="3" autofocus>

                                            @error('mobileNumber')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
                                            <label for="bkashNumber" class="text-md-right">{{ __('bKash Number') }}:</label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="bkashNumber" type="text" placeholder="Enter bKash Number"
                                                   class="form-control
@error('bkashNumber')
                                                       is-invalid @enderror" name="bkashNumber" value="{{ old('bkashNumber', $user->mobileBankBkash)
                                                 }}"   tabindex="4" autofocus>

                                            @error('bkashNumber')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-2 text-center " for="sscBatch">Batch</label>
                                        <div class="col-sm-8">
                                            <select name="sscBatch" id="sscBatch" class="form-control select2">
                                                <option value="">Select One</option>
                                                @for($i=2022;$i>=1962;$i--)
                                                    <option value="{{ $i }}" {{ ((!empty($user->user_type) && ($user->userSscBatch==$i))?"selected":'') }}>{{ $i
                                        }}</option>
                                                @endfor
                                            </select>
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
                                                    <option value="{{ $rKey}}"
                                                        {{ ((!empty($user->user_type) && ($user->user_type==$rKey))?"selected":'') }}   >{{$role}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
                                            <label for="password" class="text-md-right">{{ __('Password') }} :
                                                <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <input id="password" placeholder="Enter Password" type="password"
                                                   class="form-control"
                                                   name="password"  autocomplete="new-password" tabindex="3">
                                            <span style="color:red"> (If Need
                                            Change Password, Then Press New Password)</span>

                                            @error('password')
                                                <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-2 text-center">
                                            <label for="password" class="text-md-right">{{ __('Status') }} :
                                                <small style="color:red">*</small></label>
                                        </div>
                                        <div class="col-sm-8">
                                            <select id="isActiveStatus"
                                                   class="form-control"
                                                   name="isActiveStatus"  >
                                                <option value="">Select One</option>
                                                <option value="1" {{ ((!empty($user->status) &&
                                                ($user->status==1))?"selected":'') }}>Active</option>
                                                <option value="2" {{ ((!empty($user->status) &&
                                                ($user->status==2))?"selected":'') }}>InActive</option>
                                            </select>

                                            @error('password')
                                            <span class="text-danger">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" >
                                    <div class="col-sm-2 text-right">
                                    </div>
                                    <div class="col-sm-3">
                                        <input id="id" type="hidden" class="form-control @error('id')
                                            is-invalid @enderror" name="id"  value="{{ old('id', $user->id) }}" required >

                                        <button class="btn btn-block btn-lg btn-success pull-right"
                                        ><i class="fa fa-check-circle" aria-hidden="true"></i> Update</button>
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
