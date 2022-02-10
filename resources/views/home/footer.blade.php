
<footer>
    &copy; <a target="_blank" href="{{ url('/') }} " >Ex. Students Forum</a> (Lemua High School). Software Developed
    By <a target="_blank" href="{{ 'https://du.ac.bd/faculty/faculty_details/ictcell/45320' }}">Md. Omar Faruk</a>
    <b><span style="color:blue">. Any Query: </span></b> +88 01819 841209 (Kapil Uddin), +88 01839 707645 (Shohag)
</footer>
<script src="{{ URL::asset('backend/plugins/jquery/jquery.min.js') }}"></script>
<!-- Bootstrap 4 -->
<script src="{{ URL::asset('backend/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{URL::asset('backend/dist/js/adminlte.min.js')}}"></script>

<script>
    function ajaxSubmit(that){

        callback_function = $(that).attr('data-callback');
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="_token"]').attr('content')
            },
            url: $(that).attr('action'),
            data: new FormData(that),
            type: $(that).attr('method'),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData:false,
            beforeSend: function() {
                $("#saveBtn").attr("disabled",true);
                $(".saveBtn").attr("disabled",true);
            },
            success:  function (data) {
                if(typeof callback_function == "string" && typeof window[callback_function] == "function"){
                    window[callback_function](data);
                    $("#saveBtn").attr("disabled",false);
                    $(".saveBtn").attr("disabled",false);
                }
                else{
                    if(data.success){
                     //   toastr.success(data.success).then(window.location = data.redirectTo);
                    }
                    else{
                        $("#saveBtn").attr("disabled",false);
                        $(".saveBtn").attr("disabled",false);
                     //   toastr.error(data.error);
                    }
                }


            },
            error: function (errors) {
                $("#saveBtn").attr("disabled",false);
                $(".saveBtn").attr("disabled",false);
                $.each(errors.responseJSON.errors, function(error){
                  //  toastr.error(errors.responseJSON.errors[error][0]);
                });
                if(errors.responseJSON.message){
                  //  toastr.error(errors.responseJSON.message);
                }
            }
        });
    }

    $(function(){

        $( document ).ajaxError(function(rs, textStatus) {
            if(textStatus.status == 401)
                window.location.reload();
        });


        $(document).on('submit', "form[data-submit='ajax']",  function(e){
            e.preventDefault();
            // await callback_function();
            // window[callback_function].call();
            ajaxSubmit(this);

        });

        $(document).on('click', "[data-view='detailView']", function(e){
            e.preventDefault();
            let url = $(this).attr('href');
            let modal_heading = $(this).attr('data-modalHeading');
            let modal = $('#commonModal');
            modal.find('.modal-title').text(modal_heading);
            $.get(url, function(response) {
                let modal_body = modal.find('.modal-body');
                modal_body.html(response);
                modal.modal('show');
            });
        });


    })
</script>

</body>
</html>

