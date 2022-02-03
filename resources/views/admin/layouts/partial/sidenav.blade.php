
<!-- Brand Logo -->
<a href="{{ URL('/')}}" class="brand-link">
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
            <li class="nav-item">
                <a href="{{ route('donation.donationRecord') }}" class="nav-link">
                    <i class="nav-icon fas fa-calculator"></i>
                    <p>
                        Donation Received
                    </p>
                </a>
            </li>
            <!--
            <li class="nav-item {{ (Request::is('admin/accounting_transaction*')) ? 'menu-open': '' }}">
                <a href="#" class="nav-link {{ (Request::is('admin/accounting_transaction*')) ? 'active': '' }}">
                    <i class="nav-icon fas fa-calculator"></i>
                    <p> Accounting Transaction <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview ">

                    <li class="nav-item">
                        <a href="{{ route('accounting_transaction.capital_investment') }}" class="nav-link {{ (\Request::route()->getName() == 'accounting_transaction.capital_investment') ? 'active' : '' }}">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Capital Investment</p>
                        </a>
                    </li>
                </ul>
            </li>

            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-exchange-alt"></i>
                    <p> Expense Management <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Operating Expense Item</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Capital Expense Item</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Unexpected Expense Item</p>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-clipboard-list"></i>
                    <p> Report Management <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Receipt</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Payment</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Transaction List</p>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Transaction Journal</p>
                        </a>
                    </li>
                </ul>
            </li>



            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-cogs"></i>
                    <p> Configuration <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Donation Category</p>
                        </a>
                    </li>
                </ul>
            </li>
            -->
            <!--
            <li class="nav-item">
                <a href="#" class="nav-link">
                    <i class="nav-icon fas fa-user-cog"></i>
                    <p> User Management <i class="right fas fa-angle-left"></i></p>
                </a>
                <ul class="nav nav-treeview">
                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p> User Record</p>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Add Permission</p>
                        </a>
                    </li>

                </ul>
            </li>
            -->
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
