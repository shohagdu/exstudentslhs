<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title"><i class="fa fa-download"></i> Download Monthly Record Information</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-md-12 col-lg-12 col-sm-12">
            <div id="message_section"></div>
            <div id="update-status">
                <span class="label label-primary">Total : <span id="total">{{$data['total']}}</span></span>
                <span class="label label-success">Processed : <span id="total_process">{{$data['total_processed']}}</span></span>
                <span class="label label-warning">Remaining : <span id="remain_no">{{$data['total']-$data['total_processed']}}</span></span>
            </div>
            <div class="clearfix"></div>
            <div id="loading" class="text-center">
                <img src="{{URL::asset('excel-export/assets/images/loading-2.gif')}}" alt="Loading....." title="Loading....."/>
                <br>
                <p>System is processing the download, please wait.....</p>
            </div>
            <div class="progress" style="height: 40px">
                <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: {{ $data['process_percent'] ? $data['process_percent'] : 0 }}%;">
                    <span><span id="process_percent">{{ $data['process_percent']}}</span>%</span>
                </div>
            </div>
            <div id="downloadLink" class="text-center mt-2"></div>
        </div>
    </div>
</div>

<?php
$request = request()->all();
$queryString = $_SERVER['QUERY_STRING'];
//echo $queryString;
if($user->is_admin!=1) {
    $request['project_id']=$user->project_id;
}
?>
<script>

    var module = "excel-export/commission";
    var total = {{$data['total']}};
    var limit = {{$data['limit']}};
    var total_process = {{$data['total_processed']}};
    var remain_no = {{$data['total']-$data['total_processed']}};
    var process_percent = parseInt((total_process / total) * 100);
    var file_name = '';
    var last_index_count = 0;
    var url_strings = '<?php echo $queryString; ?>';
    var base_url="{{URL::asset('')}}";

    $(document).ready(function () {
        ajax_download_file();
    });

    function ajax_download_file() {
        $.ajax({
            url: base_url+module+"/ajax_download_record?" + url_strings,
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
                        $("#loading").html('<img  style="width: 100px;" src="' + base_url + 'excel-export/assets/images/done.png" alt="Done" title="Done" /><br/><p style="text-align: center">Your Download is ready</p>');
                        $("#downloadLink").html('<a href="' + base_url + 'excel-export/assets/media/xlsx_download/' + file_name + '" class="btn btn-success">Download Now</a>');
                    }
                } else {
                    $('#message_section').html(response.msg);
                }
            }
        });
    }

</script>
