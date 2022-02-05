@include('home.header')
@include('home.navbar')
<div class="donation-box">
    <div class="col-sm-12 text-center">

{{--        <img src="{{ url('backend/images/logo/exStdLogo_.png') }}" style="height: 140px">--}}
    </div>
    <div >
        <div class="card-body">
            <form method="POST" action="{{ route('donationFormAction') }}">
                @csrf
                <div class="form-group row">
                    <div class="offset-md-4 col-md-8">
                        <p class="h4">Donation & Sponsor Form</p>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>
                    <div class="col-md-6">
                        <input id="name" type="text" placeholder="Enter Name" class="form-control @error('name')
                            is-invalid @enderror"
                               name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
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
                        <input id="mobileNumber" type="text" placeholder="Enter Your Mobile Number"
                               class="form-control  @error ('mobileNumber') is-invalid @enderror"
                               name="mobileNumber" value="{{ old('mobileNumber') }}"  autocomplete="email">
                        @error('mobileNumber')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="sscBatch" class="col-md-4 col-form-label text-md-right">SSC Batch</label>
                    <div class="col-md-6">
                        <select class="form-control @error ('sscBatch') is-invalid @enderror" id="sscBatch" name="sscBatch">
                            <option value="">Select Batch</option>
                            @for($i=2021;$i>=1962;$i--)
                                <option value="{{ $i }}" {{ ((!empty(old('sscBatch')) && (old('sscBatch')==$i))?"selected":'') }}>{{ $i
                                }}</option>
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
                    <label for="donationAmount" class="col-md-4 col-form-label text-md-right"></label>
                    <div class="col-md-6 text-center ">
                        <label><u>Donation & Sponsor Information</u></label>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="donationAmount" class="col-md-4 col-form-label text-md-right">Donation Amount</label>
                    <div class="col-md-6">
                        <input id="donationAmount" placeholder="Enter Donation Amount" value="{{ old('donationAmount') }}" type="text"
                               class="form-control @error('donationAmount') is-invalid @enderror" name="donationAmount">
                        @error('donationAmount')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sendNumber" class="col-md-4 col-form-label text-md-right">Donation Send Number</label>
                    <div class="col-md-6">
                        <select class="form-control  @error ('sendNumber') is-invalid @enderror" id="sendNumber" name="sendNumber">
                            <option value="">Select Send Number</option>
                            @if(!empty($fundCoordinator))
                                @foreach($fundCoordinator as $coOrdinatorKey=>$fundOrdinatorName)
                            <option value="{{ $coOrdinatorKey }}" {{ ((!empty(old('sendNumber')) && (old('sendNumber')==$coOrdinatorKey))
                            ?"selected":'') }}>{{ $fundOrdinatorName }}</option>
                                @endforeach
                            @endif
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
                        <select class="form-control @error ('donationBy') is-invalid @enderror" id="donationBy" name="donationBy">
                            <option value="2" {{ ((!empty(old('donationBy')) && (old('donationBy')==2))
                            ?"selected":'') }}>bKash</option>
                        </select>
                        @error('donationBy')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <label for="TransactionID" class="col-md-4 col-form-label text-md-right">Transaction ID</label>
                    <div class="col-md-6">
                        <input id="TransactionID" placeholder="Enter Transaction ID" type="text" value="{{ old('TransactionID') }}"
                               class="form-control @error('TransactionID') is-invalid @enderror" name="TransactionID"
                                >
                        @error('TransactionID')
                             <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row mb-0">
                    <div class="col-md-6 offset-md-4">
                        @if(Session::has('message'))
                            <div class="alert alert-success alert-dismissible" id="alert_hide_after" role="alert" style="margin-bottom:10px; ">
                                <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                                {{ Session::get('message') }}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="form-group row mb-0">
                    <div class="col-md-2 offset-md-4">
                        <button type="submit" class="btn btn-primary btn-lg btn-block">
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
@include('home.footer')
