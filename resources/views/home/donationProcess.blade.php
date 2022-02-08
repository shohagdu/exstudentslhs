@include('home.header')
@include('home.navbar')
<div class="container">
    <div class="col-sm-12 text-justify" style="min-height: 400px;margin-top: 20px;line-height: 30px" >
        <h5 ><u>ডোনেশন পাঠানোর প্রক্রিয়া:</u> </h5>
       <div class="donationHead">
           পুরো ডোনেশন প্রক্রিয়াটির সচ্ছতা ও জবাবদিহিতা নিশ্চিত করতে <span class="color-blue"> অনলাইন মোবাইল ব্যাংকিং
               সিস্টেম (বিকাশ)</span> এর
           মাধ্যমে সম্পন্ন করব।
       </div>
        <div class="donationHead">
            ডোনেশন প্রক্রিয়াটি  ২ ধাপে সম্পন্ন হবে:
        </div>
        <div > <span class="color-green"> প্রথম ধাপ:</span> নির্ধারিত বিকাশ নাম্বারে <span class="color-blue">Send
                Money</span>  করতে
            হবে।</div>
        <div class="donationStep"><span class="color-green"> দ্বিতীয় ধাপ:</span> আপনার ডোনেশন নিশ্চিত করতে <span class="color-blue">Ex Students Forum</span> এর
            Website <span class="color-blue">
                <a href="http://exstudentslhs.com/admin/" target="_blank"> (http://exstudentslhs.com)</a> </span> এ
            গিয়ে  ফরমের নিম্নোক্ত তথ্য গুলো পূরণ করতে হবে  অন্যথায় উক্ত ডোনেশন পাঠানোর প্রক্রিয়াটি <span
                class="color-red">  সঠিকভাবে
            সম্পন্ন
            হবে না।</span></div>
        <div class="donationHead">যে তথ্যগুলো দিতে হবে:</div>
        <ul>
            <li>  ডোনেশনকারীর নাম; </li>
            <li>  ডোনেশনকারীর মোবাইল নং; </li>
            <li> এস.এস.সি'র ব্যাচ; </li>
            <li>  ডোনেশনের পরিমান; </li>
            <li>  যে বিকাশ নাম্বারে টাকা পাঠিয়েছিলেন তা সিলেক্ট করবেন; </li>
            <li>  যে বিকাশ নাম্বার থেকে টাকা পাঠানো হয়েছে; </li>
            <li> বিকাশ ট্রানজেশন আইডি; </li>
        </ul>
        <div class="donationHead">
           উপরের তথ্য গুলো দেওয়া পর <span class="color-blue"> Submit বাটনে </span> ক্লিক করুন।  তারপর আপনি যে  বিকাশ নাম্বারে টাকা  পাঠিয়েছেন, আমাদের
            <span class="color-blue"> Fund
                Collection Coordinator </span> আপনার
            পাঠানো তথ্যের সাথে বিকাশে পাঠানো টাকা যাচাই করে <span class="color-blue"> Approved </span> করার মাধ্যমে
            ডোনার'রা একটি <span class="color-blue"> Confirmation SMS </span>
            পাবেন। <span class="color-red"> ফরম পূরণ করার পর কেউ যদি Confirmation SMS না পেয়ে থাকেন অবশ্যই আমাদের <a
                    href="https://www
            .facebook.com/groups/231794134771154" target="_blank"> Facebook
            Group</a> এর Admin & moderator কে
            বিষয়টি অবহিত করবেন
                দিবেন।</span>
        </div>
        <div class="donationHead">
            আমাদের বিকাশ (Personal) নাম্বার গুলো:
        </div>
        <table class="table-style" style="width: 100%">
            <tr>
                <th class="text-center" style="width: 5%;">S/N</th>
                <th>Name</th>
                <th>বিকাশ(bKash) নাম্বার</th>
            </tr>
            @php($i=1)
            @if(!empty($fundCoordinator))
                @foreach($fundCoordinator as $coordinator)
                    <tr>
                        <td class="text-center">{{ $i++ }}</td>
                        <td>{{ (!empty($coordinator->name)?$coordinator->name:'') }}</td>
                        <td><span class="bkashNumber"> {{ (!empty($coordinator->mobileBankBkash)
                        ?$coordinator->mobileBankBkash:'') }}</span></td>
                    </tr>
                @endforeach
            @endif

        </table>
        <div class="donationFooter">
            <div style="text-decoration: underline 3px dotted green;"> সম্ভাব্য তারিখ</div>
           ডোনেশন পাঠানোর সম্ভাব্য শেষ তারিখঃ <span class="color-green"> ১০ মার্চ ২০২২ ইং</span>
            <div class="clearfix"></div>
            অনুষ্ঠান করার সম্ভাব্য তারিখঃ <span class="color-green"> ১৮ মার্চ ২০২২ ইং</span>
        </div>


    </div>
</div>
@include('home.footer')
