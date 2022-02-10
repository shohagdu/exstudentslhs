<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="{{ url('/') }}">হোম <span class="sr-only">(current)</span></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/aboutUs') }}">আমাদের সম্পর্কে</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/donationProcess') }}">ডোনেশন </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ url('/events') }}">ইভেন্ট </a>
                </li>

            </ul>
            <form class="form-inline my-2 my-lg-0">
                <a href="{{ url('/admin') }}" target="_blank" class="btn btn-outline-success my-2 my-sm-0" type="submit">Log
                    In</a>
            </form>
        </div>

    </div>
</nav>
<div class="clearfix"></div>

