@extends('admin.layouts.app')

@push('css_lib')
    {{-- <link rel="stylesheet" href="{{ asset('backend/dist/css/intlInputPhone.min.css') }}"> --}}
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('backend/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

@endpush

@push('css_custom')
<style>
    
label.error{

    margin-top: 5px!important;

}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li{
    list-style: none;
    background-color: #027fbb;
}

.select2-container--default .select2-selection--multiple .select2-selection__rendered li span{
    list-style: none;
    color: #fff;
}



.select2-container--default .select2-search--inline .select2-search__field {
     background: #fff; 
    border: none;
    outline: 0;
    box-shadow: none;
    -webkit-appearance: textfield;
    padding: 5px 0px 0px 0px; 
    margin: 0;
}
</style>
@endpush

@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>User Menu Access</h1>

        <ol class="breadcrumb">
            <li><a href="index.php"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Setting</a></li>
            <li class="active">User Access</li>
        </ol>
    </section>

    <!-- Main content -->
    <section id="product_category" class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box box-primary">

                    <div class="box-header">

                        <h3 class="box-title">User Access List</h3>

                        <a href="{{route('users.index')}}" class="btn btn-info btn-xs pull-right">Go Back</a>

                    </div>
                    <!-- Modal -->

                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="{{route('user-access.store')}}" method="post">
                            @csrf
                            <table id="example1" class="table table-bordered table-striped table-hover data-table">
                                    <thead>
                                        <tr>
                                            <th style="width:150px!important;" class="text-center">Name</th>
                                            <th style="min-width:100px" class="text-center">Assigned Menus/Forms</th>
                                            {{-- <th style="width:30px" colspan="20" class="text-center">Roles</th> --}}
                                        </tr>
                                    </thead>

                                    <tbody>
                                        <tr>
                                            <td class="text-center">{{$user->full_name}}</td>
                                            <td class="text-center">
                                                <input type="hidden" name="user_id" value="{{$user->id}}">

                                                @php
                                                    $user_permissions = $user->getAllPermissions()->pluck('id')->toArray();
                                                @endphp
                                                <input type="checkbox" id="checkbox" > <label for="checkbox"> Select All</label>
                                                <select class="form-control select2" id="permission_select" multiple="" data-placeholder="Select Menus/Forms" style="width: 80%;" name="permission[]" aria-hidden="true">
                                                    @foreach($permissions as $permission)
                                                      <option value="{{$permission->id}}" @if(in_array($permission->id,  (array) $user_permissions)) selected @endif >{{$permission->name}}</option>
                                                    @endforeach
                                                </select>
                                            </td>
                                        </tr>
                                    </tbody>
                                    <tfoot>
                                    </tfoot>
                                </form>
                            </table>
                        <input class="btn btn-info pull-right" type="submit" value="Save">
                    </div>
                    <!-- /.box-body -->
                </div>
                <!-- /.box -->
            </div>
        </div>
    </section>
    <!-- /.content -->
@endsection

@push('js_lib')
    <!-- DataTables -->
    <script src="{{ asset('backend/bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('backend/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
@endpush

@push('js_custom')
<script>
    $(function() {

        $('.select2').select2();

        $("#checkbox").click(function(){
            if($("#checkbox").is(':checked') )
            {
                $("#permission_select > option").prop("selected","selected");
            }
            else
            {
                $("#permission_select option:selected").prop("selected", false);
            }
            $('.select2').select2();
        });

    });

</script>

@endpush
