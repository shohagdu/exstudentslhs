@php
    $userType           = Auth::user()->user_type;
@endphp
<!-- Brand Logo -->
<a href="{{ URL('/admin')}}" class="brand-link">
    <img src="{{ asset('backend/dist/img/AdminLTELogo.png') }}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
    <span class="brand-text font-weight-light">{{env('APP_NAME')}}</span>
</a>

<!-- Sidebar -->
<div class="sidebar">
    <!-- Sidebar Menu -->
    <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu">
            <!-- Add icons to the links using the .nav-icon class
                 with font-awesome or any other icon font library -->
            <li class="nav-item">
                <a href="{{ url('/admin') }}" class="nav-link">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Dashboard
                    </p>
                </a>
            </li>
            @if(!empty($userType) && ($userType==1 || $userType==2|| $userType==3))
                <li class="nav-item">
                    <a href="{{ route('donation.donationRecord') }}" class="nav-link">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            Donation Received
                        </p>
                    </a>
                </li>
            @endif
            @if(!empty($userType) && ($userType==1 || $userType==2))
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p> Account Management <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('bankTransaction.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Transaction</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('bank.index') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Bank Account Record</p>
                            </a>
                        </li>

                    </ul>
                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p> Expense <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('expenseRecord') }}" class="nav-link ">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Expense Record</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('expenseCtg') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> Expense Category</p>
                            </a>
                        </li>

                    </ul>
                </li>
                <li class="nav-item">
                    <a href="{{ route('participantsRecord') }}" class="nav-link">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            Participants Record
                        </p>
                    </a>
                </li>


                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p> User Management <i class="right fas fa-angle-left"></i></p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('user.userRecord') }}" class="nav-link">
                                <i class="far fa-circle nav-icon"></i>
                                <p> User Record</p>
                            </a>
                        </li>
                    </ul>
                </li>
            @endif
            @if(!empty($userType) && ($userType==5 || $userType==6 || $userType==7 ))
                <li class="nav-item">
                    <a href="{{ route('participantsRecord') }}" class="nav-link">
                        <i class="nav-icon fas fa-calculator"></i>
                        <p>
                            Participants Record
                        </p>
                    </a>
                </li>
            @endif


            <li class="nav-item">
                <a href="{{ route('userPass.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-calculator"></i>
                    <p>
                        Change Password
                    </p>
                </a>
            </li>

            <li class="nav-item">
                <a href="{{ route('logout') }}" class="nav-link" onclick="event.preventDefault();document.getElementById
                ('logout-form').submit();" ><i class="nav-icon fas fa-sign-out-alt"></i>Sign out</a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none">
                            @csrf
                        </form>
            </li>


        </ul>
    </nav>
    <!-- /.sidebar-menu -->

</div>
<!-- /.sidebar -->

@push('js_custom')

    <script type="text/javascript">
        $(function(){
            let treeview_menu = $('.treeview-menu');

            $.each(treeview_menu, function(index, menu) {
                let lis = $(menu).children('li');

                if(lis.length == 0){
                    $(menu).closest('.treeview').addClass('hidden');
                }
            });

            $('.sidebar-menu').removeClass('hidden');
        })
    </script>

@endpush
