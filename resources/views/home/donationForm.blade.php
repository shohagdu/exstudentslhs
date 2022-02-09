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
                        <p class="h4">নির্ধারিত বিকাশ নাম্বারে ডোনেশান পাঠানোর পর নিম্নোক্ত তথ্য গুলো পূরন করুন: </p>
                        <span style="color:red;font-weight: bold;text-align: justify;"> (বিকাশের মাধ্যমে টাকা পাঠানো
                            পর পেমেন্ট নিশ্চিত
                             করার জন্য  নিম্নোক্ত ফরমটি পূরন করা আবশ্যক অন্যথায় উক্ত ডোনেশান পাঠানোর প্রক্রিয়াটি
                            সঠিকভাবে সম্পন্ন হবে না)
                        </span>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="name" class="col-md-4 col-form-label text-md-right">আপনার নাম</label>
                    <div class="col-md-6">
                        <input id="name" type="text" placeholder="আপনার নাম প্রদান করুন" class="form-control @error ('name') is-invalid @enderror"
                               name="name" value="{{ old('name') }}"  autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>


                <div class="form-group row">
                    <label for="mobileNumber" class="col-md-4 col-form-label text-md-right">আপনার মোবাইল নাম্বার</label>
                    <div class="col-md-6">
                        <input id="mobileNumber" maxlength="15" minlength="11" type="text" placeholder="আপনার মোবাইল  নাম্বার প্রদান  করুন"
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
                    <label for="sscBatch" class="col-md-4 col-form-label text-md-right">আপনার এস.এস.সি'র ব্যাচ</label>
                    <div class="col-md-6">
                        <select class="form-control @error ('sscBatch') is-invalid @enderror" id="sscBatch" name="sscBatch">
                            <option value="">এস.এস.সি'র ব্যাচ সিলেক্ট  করুন</option>
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
                    <label for="donationAmount" class="col-md-4 col-form-label text-md-right">ডোনেশানের পরিমান</label>
                    <div class="col-md-6">
                        <input id="donationAmount" placeholder="ডোনেশানের পরিমান" value="{{ old('donationAmount') }}" type="text"
                               class="form-control onlyNumber @error('donationAmount') is-invalid @enderror"
                               name="donationAmount">
                        @error('donationAmount')
                        <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="sendNumber" class="col-md-4 col-form-label text-md-right">যে বিকাশ নাম্বারে টাকা পাঠিয়েছিলেন তা সিলেক্ট করবেন
                    </label>
                    <div class="col-md-6">
                        <select class="form-control  @error ('sendNumber') is-invalid @enderror" id="sendNumber" name="sendNumber">
                            <option value="">বিকাশ নাম্বার সিলেক্ট করুন</option>
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
                <!--
                <div class="form-group row">
                    <label for="donationBy" class="col-md-4 col-form-label text-md-right">যে ভাবে টাকা পাঠাবেন</label>
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
                -->

                <div class="form-group row">
                    <label for="TransactionID" class="col-md-4 col-form-label text-md-right">  যে বিকাশ নাম্বার থেকে
                        টাকা
                        পাঠানো হয়েছে
                    </label>
                    <div class="col-md-6">
                        <input id="TransactionMobileNumber" maxlength="15" minlength="11" placeholder="যে নাম্বার থেকে  টাকা   পাঠানো  হয়েছে ঐ নাম্বার প্রদান করুন"
                               type="text"
                               value="{{ old('TransactionMobileNumber') }}"
                               class="form-control @error('TransactionMobileNumber') is-invalid @enderror" name="TransactionMobileNumber"
                                >
                        @error('TransactionMobileNumber')
                             <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                </div>
                <div class="form-group row">
                    <label for="TransactionID" class="col-md-4 col-form-label text-md-right">বিকাশ ট্রানজেশন আইডি</label>
                    <div class="col-md-6">
                        <input id="TransactionID" placeholder="বিকাশ ট্রানজেশন আইডি প্রদান করুন" type="text"
                               value="{{ old('TransactionID') }}"
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
<script>
    setTimeout(function() {
        $('#alert_hide_after').fadeOut('fast');
    }, 3000);
</script>
@include('home.footer')
