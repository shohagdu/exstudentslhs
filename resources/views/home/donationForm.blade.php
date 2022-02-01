<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>{{env('APP_NAME')}}</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ URL::asset('backend/plugins/fontawesome-free/css/all.min.css') }}"/>
    <!-- icheck bootstrap -->
    <link rel="stylesheet" href="{{URL::asset('backend/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ URL::asset('backend/dist/css/adminlte.min.css') }}"/>

</head>
<body class="hold-transition donation-page">
<div class="donation-box">
    <div class="col-sm-12 text-center">
        <img src="{{ url('backend/images/logo/logo.jpg') }}" style="height: 130px">
{{--        <img src="{{ url('backend/images/logo/exStdLogo_.png') }}" style="height: 140px">--}}
    </div>
    <div style="margin-top: 30px">
        <div class=" text-center">
            <a href="#" class="h1"><b>{{env('APP_NAME')}}</b></a>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('register') }}">
                @csrf
                <div class="form-group row">
                    <div class="col-md-12 text-center">
                        <p class="h4">Donation & Sponsor Form</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                    <div class="col-md-6">
                        <input id="name" type="text" placeholder="Enter Name" class="form-control @error('name')
                            is-invalid @enderror"
                               name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <label for="mobileNumber" class="col-md-4 col-form-label text-md-right">Mobile Number</label>
                    <div class="col-md-6">
                        <input id="mobileNumber" type="text" placeholder="Enter Your Mobile Number" type="text"
                               class="form-control  @error ('mobileNumber') is-invalid @enderror"
                               name="mobileNumber" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sscBatch" class="col-md-4 col-form-label text-md-right">SSC Batch</label>
                    <div class="col-md-6">
                        <select class="form-control" id="sscBatch" name="sscBatch">
                            <option value="">Select Batch</option>
                            @for($i=2022;$i>=1962;$i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                            @endfor
                        </select>
                        @error('sscBatch')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sendNumber" class="col-md-4 col-form-label text-md-right">Send Number</label>
                    <div class="col-md-6">
                        <select class="form-control" id="sendNumber" name="sendNumber">
                            <option value="">Select Send Number</option>
                            <option value="01839707645">01839707645</option>
                            <option value="01521572228">01521572228</option>
                        </select>
                        @error('sendNumber')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="donationBy" class="col-md-4 col-form-label text-md-right">Select Donation By</label>
                    <div class="col-md-6">
                        <select class="form-control" id="donationBy" name="donationBy">
                            <option value="">Select Donation By</option>
                            <option value="bKash">bKash</option>
                        </select>
                        @error('donationBy')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="transID" class="col-md-4 col-form-label text-md-right">Transaction ID</label>
                    <div class="col-md-6">
                        <input id="transID" placeholder="Enter Transaction ID" type="text"
                               class="form-control @error('transID') is-invalid @enderror" name="transID"
                               required >
                        @error('transID')
                             <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="donationAmount" class="col-md-4 col-form-label text-md-right">Donation Amount</label>
                    <div class="col-md-6">
                        <input id="donationAmount" placeholder="Enter Donation Amount" type="text"
                               class="form-control @error('donationAmount') is-invalid @enderror" name="donationAmount"
                               required >
                        @error('donationAmount')
                             <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        <button type="submit" class="btn btn-primary">
                            Submit
                        </button>
                    </div>
                </div>
            </form>

        </div>
        <!-- /.card-body -->
    </div>
    <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{ URL::asset('backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ URL::asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('backend/dist/js/adminlte.min.js')}}"></script>

</body>
</html>
