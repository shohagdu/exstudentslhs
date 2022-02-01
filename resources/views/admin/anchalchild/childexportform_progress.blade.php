<form class="form-horizontal" role="form" method="get" style="background-color:#fff;"
      action="{{ route('anchalchild.exportChild') }}" enctype="multipart/form-data"  data-submit-download="ajax">
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
                        <option selected value="">Select Project</option>
                        @foreach($project as $item)
                            <option  value="{{$item->project_code}}">{{$item->name}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        @endif
        <div class="form-group">
            <label for="" class="col-md-2 control-label">District: <small style="color:red"></small></label>
            <div class="col-md-8">
                <select class="form-control" id="district" name="district">
                    <option value="">Select District</option>
                    @foreach($district as $item)
                        <option filter_value="{{$item->id}}" value="{{$item->district_code}}">{{$item->district_name_en}}</option>
                    @endforeach;
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Upazila: <small style="color:red"></small></label>
            <div class="col-md-8">
                <select class="form-control" id="subdistrict" name="subdistrict">
                    <option selected value="">Select Sub District</option>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label for="" class="col-md-2 control-label">Union: <small style="color:red"></small></label>
            <div class="col-md-8">
                <select class="form-control" id="anchal_union" name="anchal_union">
                    <option selected value="">Select Union</option>
                </select>
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

    var module = 'excel-export';
    var total = {{$data['total']}};
    var limit = {{$data['limit']}};
    var total_process = {{$data['total_processed']}};
    var remain_no = {{$data['total']-$data['total_processed']}};
    var process_percent = parseInt((total_process / total) * 100);
    var file_name = '';
    var last_index_count = 0;
    {{--var url_strings = '<?php echo ($this->input->get()) ? '?' . http_build_query($this->input->get()) : ''; ?>';--}}
    var url_strings = '';
    var base_url="{{URL::asset('')}}";

    $(document).ready(function () {
        ajax_download_file();
    });

    function ajax_download_file() {
        $.ajax({
            url: base_url + module + "/ajax_download" + url_strings,
            type: 'POST',
            data: {file_name: file_name, limit: limit, start_no: total_process, last_index_count: last_index_count},
            dataType: "json",
            success: function (response) {
                if (response.status) {
                    total_process = total_process + parseInt(response.total_process);
                    remain_no = remain_no - parseInt(response.total_process);
                    process_percent = parseInt((total_process / total) * 100);
                    last_index_count = parseInt(response.last_index_count);
                    file_name = response.file_name;

                    $('#total_process').text(total_process);
                    $('#remain_no').text(remain_no);
                    $('#process_percent').text(process_percent);
                    $('.progress-bar').css("width", process_percent + "%");
                    if (total_process < total) {
                        settimeout_id = setTimeout(ajax_download_file, 1000);
                    } else {
                        $("#loading").html('<img  style="width: 100px;" src="' + base_url + 'assets/images/done.png" alt="Done" title="Done" /><br/><p style="text-align: center">Your Download is ready</p>');
                        $("#downloadLink").html('<a href="' + base_url + 'assets/media/xlsx_download/' + file_name + '" class="btn btn-success">Download Now</a>');
                    }
                } else {
                    $('#message_section').html(response.msg);
                }
            }
        });
    }

</script>
