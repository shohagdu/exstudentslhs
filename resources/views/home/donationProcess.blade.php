@include('home.header')
@include('home.navbar')
<div class="container">
    <div class="col-sm-12" style="min-height: 400px;margin-top: 20px;" >
        <h5 >Donation Process:</h5>
        <ul>
            <li>প্রথমে যে কোন বিকাশ নাম্বার থেকে নিম্ন প্রদানকৃত নাম্বারে সেন্ড মানি করুন।   </li>
            <li>তারপর <a href="{{ url('/') }}"> Donation & Sponsor Form</a> এর তথ্য সমূহ পূরণ করুন।  </li>
        </ul>

    </div>
</div>
@include('home.footer')
