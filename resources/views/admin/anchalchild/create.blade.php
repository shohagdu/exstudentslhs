@extends('admin.layouts.app')

@push('css_lib')
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ URL::asset('backend/bower_components/select2/dist/css/select2.min.css') }}">

@endpush
@push('css_custom')
    <style>
        .input-group-addon{
            border: 0px!important;
            padding: 0px!important;
        }
    </style>
@endpush
@section('content')
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <h1>Anchal Children Registration Form</h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li><a href="#">Registration Form</a></li>
            <li class="active">Anchal Children Registration Form</li>
        </ol>
    </section>

    <!-- Main content -->
    <section class="content">
        <form role="form" method="post" style="background-color:#fff;" action="{{ route('anchalchild.store') }}" enctype="multipart/form-data"  data-submit="ajax">
            @csrf
            <div class="box box-primary">
                <div class="box-header with-border">

                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="row">

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="district">1. Project <span style="color: red">*</span> :</label>
                                <select class="form-control" id="project" name="project" required>
                                    @foreach($project as $item)
                                        <option filter_value="{{$item->project_code}}" value="{{$item->project_code}}">{{$item->name}}</option>
                                    @endforeach;
                                </select>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="district">1. District Name <span style="color: red">*</span> :</label>
                                <select class="form-control" id="district" name="district" required>
                                    <option value="">Select District</option>
                                    @foreach($district as $item)
                                        <option filter_value="{{$item->id}}" value="{{$item->district_code}}">{{$item->district_name_en}}</option>
                                    @endforeach;
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="subdistrict"> 2. Sub District Name <span style="color: red">*</span> :</label>
                                <select class="form-control" id="subdistrict" name="subdistrict" required>
                                    <option selected value="">Select Sub District</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="anchal_union">3. Anchal Union <span style="color: red">*</span> :</label>

                                <select class="form-control" id="anchal_union" name="anchal_union" required>
                                    <option selected value="">Select Union</option>

                                </select>


                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>4. Ward <span style="color: red">*</span> :</label>

                                <select class="form-control" id="ward" name="ward" required>
                                    <option selected value="">Select One</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>5. Village <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control" id="village" name="village" required>
                            </div>
                        </div>
                        <div class="col-md-4">


                            <div class="form-group">
                                <label>6. Anchal Number <span style="color: red">*</span> :</label>
                                <select class="form-control" id="anchal_number" name="anchal_number" required>
                                    <option selected value="">Select One</option>
                                    <option  value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>7. Anchal Unique Code <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control" minlength="6" maxlength="6" id="anchal_auto_id" readonly name="anchal_auto_id" onkeyup="generate_code();" required>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label>8. Name of Anchal Ma <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control"  id="" name="anchal_ma" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>9. Child Serial Number <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control"  id="child_number" name="child_number" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="generate_code();" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label> 10. Child Unique Code </label>
                                <input type="text" class="form-control" id="child_auto_id" name="child_auto_id" minlength="8" maxlength="8" readonly required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>11. Child Name <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control"  name="child_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label>12. Sex <span style="color: red">*</span> :</label>


                                <select class="form-control" id="sex" name="sex" onkeyup="generate_code();" required>
                                    <option selected value="">Select One</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">


                            <div class="form-group">
                                <label>13. Date of Birth <span style="color: red">*</span> :</label>
                                <input type="text" readonly="readonly" class="form-control" id="child_birthdate" name="child_birthdate" value="{{date('d-m-Y')}}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>14. Admission Date:</label>
                                <input type="text" readonly="readonly" class="form-control" id="admission_date" name="admission_date" value="{{date('d-m-Y')}}" required />
                            </div>
                        </div>
                        <div class="col-md-4">


                            <div class="form-group">
                                <label>15. Age <small>(Months)</small> <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control"  name="age" id="age" required readonly="readonly" style="display: none;">
                                <button type="button" id="calculate_months" class="form-control btn btn-info">Calculate Months</button>
                            </div>
                        </div>
                        <div class="col-md-4">

                            <div class="form-group">
                                <label>16. Parents Name <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control"  name="parents_name" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>17. Mobile Number <span style="color: red">*</span> :</label>
                                <input type="text" class="form-control"  name="mobile" minlength="11" maxlength="11" required>
                            </div>

                        </div>
                        <div class="col-md-12">


                            <div class="box-footer">
                                <a href="{{ route('anchalchild.index') }}" class="btn btn-default">Cancel</a>
                                <button type="submit" name="submit" id="saveBtn" class="btn btn-success pull-right">Save Registration Info</button>
                            </div>

                        </div>
                        <!-- /.col -->


                    </div>
                </div>
                <!-- /.box-body -->
            </div>

        </form>
    </section>
@endsection

@push('js_lib')
    <!-- Select2 -->
    <script src="{{ URL::asset('backend/bower_components/select2/dist/js/select2.full.min.js') }}"></script>
@endpush

@push('js_custom')
    <script>
        $(function() {

            // $('body').addClass('sidebar-collapse');

            //Date picker
            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
            });

            $('.select2').select2();

            $(document).on('change', '.product', function(e){
                let sku = $(this).children('option:selected').attr('data-unit');

                let row = $(this).closest('.row');
                row.find('.unit').val(sku);
            })
        });

        $(document).ready(function(){

            $('.datepicker').datepicker({
                autoclose: true,
                format: 'dd-mm-yyyy',
            });

            $('#child_birthdate').datepicker({
                format: "dd-mm-yyyy",
                startView: 2,
                autoclose: true
            });

            $('#admission_date').datepicker({
                format: "dd-mm-yyyy",
                autoclose: true
            });

            $(document).on("change", "#project", function (e) {
                e.preventDefault();
                generate_code();
                var thisValue = $(this).val();
                var thisFilterValue = $(this).find('option:selected').attr('filter_value');
            });

            $(document).on("change", "#district", function (e) {
                e.preventDefault();


                var thisValue = $(this).val();
                var thisFilterValue = $(this).find('option:selected').attr('filter_value');
                // alert(thisFilterValue);
                var subdistrict = '';
                var anchal_union = '';
                $("#subdistrict").empty();
                subdistrict += '<option selected value="">Select Sub District</option>';

                $.ajax({
                    type: "get",
                    url: "{{ route('common.getSubDistrict') }}"+'?district_id='+thisFilterValue,
                    success: function (response) {
                        if(response.status=='success') {
                            var subdistrictData = response.data;
                            for(var i=0;i<subdistrictData.length;i++) {
                                subdistrict += `<option filter_value="${subdistrictData[i].id}" value="${subdistrictData[i].upazila_code}">${subdistrictData[i].upazila_name_en}</option>`;
                            }
                        }

                        $("#subdistrict").append(subdistrict);

                        // clear anchal_union
                        $("#anchal_union").empty();
                        anchal_union += '<option selected value="">Select Union</option>';
                        $("#anchal_union").append(anchal_union);

                        generate_code();

                    },
                    error: function (data) {
                        //console.log('Error:', data);
                    }
                });

            });

            $(document).on("change", "#subdistrict", function (e) {
                e.preventDefault();
                var thisValue = $(this).val();
                var thisFilterValue = $(this).find('option:selected').attr('filter_value');
                var anchal_union = '';
                $("#anchal_union").empty();
                anchal_union += '<option selected value="">Select Union</option>';
                $.ajax({
                    type: "get",
                    url: "{{ route('common.getUnion') }}"+'?upazila_id='+thisFilterValue,
                    success: function (response) {
                        if(response.status=='success') {
                            var unionData = response.data;
                            for(var i=0;i<unionData.length;i++) {
                                anchal_union += `<option filter_value="${unionData[i].id}" value="${unionData[i].union_code}">${unionData[i].union_name_en}</option>`;
                            }
                        }
                        $("#anchal_union").append(anchal_union);
                        generate_code();
                    },
                    error: function (data) {
                        //console.log('Error:', data);
                    }
                });

            });

            $(document).on("change", "#anchal_union, #ward, #anchal_number", function (e) {
                e.preventDefault();
                generate_code();

            });

            $(document).on("click", "#calculate_months", function (e) {
                e.preventDefault();

                var child_birthdate = $('#child_birthdate').val();
                var admission_date = $('#admission_date').val();

                $('#calculate_months').hide();
                $('#age').attr("value", "");

                $.ajax({
                    type: "GET",
                    url: "{{ route('common.ageCalculator') }}"+"?date1="+child_birthdate+"&date2="+admission_date,
                    success: function(data){
                        respons = (data.counter/30.4167).toFixed(2);
                        if ((respons < 12) || (respons > 60)) {
                            alert('Calculated age (months): '+respons+'\n\nNOTE: Age cannot be less than 12 months or more than 60 months!\n\n');
                            $('#age').hide();
                            $('#age').attr("value", "");
                            $('#calculate_months').show();
                        } else {
                            $('#age').show();
                            $('#age').attr("value", respons);
                        }
                    },
                    error: function(){
                        alert("Fail to calculate age months!");
                        $('#age').hide();
                        $('#age').attr("value", "");
                        $('#calculate_months').show();
                    }
                });

            });

            $(document).on("change", "#admission_date, #child_birthdate", function (e) {
                e.preventDefault();
                $('#age').hide();
                $('#calculate_months').show();
            });

        });

        function generate_code() {

            // anchal_auto_id
            var anchal_auto_id = document.getElementById('anchal_auto_id');

            var project = document.getElementById('project');
            var district = document.getElementById('district');
            var subdistrict = document.getElementById('subdistrict');
            var anchal_union = document.getElementById('anchal_union');
            var ward = document.getElementById('ward');
            var anchal_number = document.getElementById('anchal_number');

            var total_ied = project.value + district.value + subdistrict.value + anchal_union.value + ward.value + anchal_number.value;
            anchal_auto_id.value = total_ied;

            // child_auto_id
            var child_auto_id = document.getElementById('child_auto_id');
            var child_number = document.getElementById('child_number');
            child_number = (Number(child_number.value) > 0) ? leftPad(child_number.value, 2) : "";

            var total_ited = anchal_auto_id.value + child_number;
            child_auto_id.value = total_ited;
        }
        //
        function leftPad(value, length) {
            return ('0'.repeat(length) + value).slice(-length);
        }
    </script>
@endpush
