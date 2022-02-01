
<form class="form-horizontal" role="form" method="get" style="background-color:#fff;"
      action="{{ route('anchalrecord.exportChildRecord') }}" enctype="multipart/form-data"  data-submit-download="ajax">
    @csrf
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title">Modal Header</h4>
    </div>
    <div class="modal-body">
        @if($user->is_admin==1)
            <div class="form-group">
                <label for="" class="col-md-2 control-label">Project: <small style="color:red"></small></label>
                <div class="col-md-8">
                    <select  class="form-control" id="project_id" required name="project_id">
                        @foreach($project as $item)
                            <option  value="{{$item->project_code}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Years: <small style="color:red"></small></label>
            <div class="col-md-8">
                <select  class="form-control" id="yearofentry" name="yearofentry">
                    <option selected value="">Select Year</option>
                    @if(!empty($years))
                        @foreach($years as $item)
                            <option {{ !empty($request['yearofentry']) && $request['yearofentry']==$item->yearofentry ? 'selected' : ''}} value="{{$item->yearofentry}}">{{$item->yearofentry}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Months: <small style="color:red"></small></label>
            <div class="col-md-8">
                <select  class="form-control" id="monthofentry" name="monthofentry">
                    <option selected value="">Select Month</option>
                    @if(!empty($months))
                        @foreach($months as $key => $item)
                            <option {{ !empty($request['monthofentry']) && $request['monthofentry']==$key ? 'selected' : ''}} value="{{$key}}">{{$item}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Anchal ID</label>
            <div class="col-md-8">
                <input type="text" name="anchal_auto_id" class="form-control" placeholder="Anchal Auto ID" value=""/>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Child ID</label>
            <div class="col-md-8">
                <input type="text" name="child_auto_id" class="form-control" placeholder="Child Auto ID" value=""/>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Export: <small style="color:red"></small></label>
            <div class="col-md-8">
                <select class="form-control" id="export_type" name="export_type">
                    <option value=".xlsx">Excel</option>
                    <option value=".csv">CSV</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">View Type: <small style="color:red"></small></label>
            <div class="col-md-8">
                <select class="form-control" id="view_type" name="view_type">
                    <option value="multiview">Multi View</option>
                    <option value="regular">Regular View</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="res_handover_to" class="col-md-2 control-label"> Start: <small style="color:red"></small></label>
            <div class="col-md-3">
                <input type="number" min="1" class="form-control" placeholder="Start Number" name="start"/>
            </div>
            <label for="res_handover_to" class="col-md-2 control-label"> End: <small style="color:red"></small></label>
            <div class="col-md-3">
                <input type="number" min="1"  class="form-control" placeholder="End Number" name="end"/>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
        <button type="submit" name="submit" id="saveBtn" class="btn btn-success pull-right">Start Export</button>
    </div>
</form>


<script>
    $(document).ready(function(){
        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
        });
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
            },
            error: function (data) {
                //console.log('Error:', data);
            }
        });

    });

    function ajaxDownload(that){

        callback_function = $(that).attr('data-callback');

        $.ajax({
            url: $(that).attr('action'),
            data: new FormData(that),
            type: "POST",
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                $("#saveBtn").attr("disabled",true);
            },
            success:  function (data) {
                $("#saveBtn").attr("disabled",false);

            },
            error: function (errors) {
                $("#saveBtn").attr("disabled",false);
                $.each(errors.responseJSON.errors, function(error){
                    toastr.error(errors.responseJSON.errors[error][0]);
                });
                if(errors.responseJSON.message){
                    toastr.error(errors.responseJSON.message);
                }
            }
        });
    }

    $(document).on('submit', "form[data-submit-download='ajax']",  function(e){
        // e.preventDefault();
    });
</script>
