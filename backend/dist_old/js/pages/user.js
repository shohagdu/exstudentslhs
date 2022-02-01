initDatePicker();
    function ichecker()
    {
        $('input[type="checkbox"].flat-red, input[type="radio"].flat-red').iCheck({
        checkboxClass : 'icheckbox_flat-green',
        radioClass : 'iradio_flat-green'
        });
    }



    function active_action(id) {
        $.ajax({
            url: "{{route('users.toggle')}}",
            type:"POST",
            data: { 'id' : id },
            dataType: 'JSON',
            success:function(response){
                toastr.success(response.message);
            },error:function(e){
                console.log(e);
            }
        }); //end of ajax
    }

    function initDatePicker(){

        $('.datepicker').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            todayBtn: "linked",
           // startDate: new Date(),

        });
    }

   

    

    $(".view").click(function(){
        var id = $(this).attr("data-id");
        $.ajax({
            url: "users/view_data",
            type:"POST",
            data: { 'id' : id },
            dataType: 'JSON',
            success:function(data){

                $('.modal-title').text(data.title);
                $('.modal-category').html('Category: <b>'+data.category+'</b>');
                $('.modal-details').html(data.content);
                $('.modal-image').children('img').attr('src', data.image);


                $('#user_view').modal('show');
            },error:function(){
                alert("error!!!!");
            }
        }); //end of ajax
    });


    $('#createNewData').click(function () {
        $('#saveBtn').val("Create Employee");
        $('#id').val('');
        $('.password_el').show();    
        $('#entryForm').trigger("reset");
        $('#modelHeading').html("Create New Employee");
        $('#form_modal').modal('show');
    });

    


    $('body').on('click', '.editData', function () {
        var id = $(this).data('id');
        var route = $(this).data('route');
        $.get(route, function (data) {
            $('#modelHeading').html("Edit Employee");
            $('#saveBtn').val("Edit Employee");
            $('#id').val(data.id);
            $('#user_name').val(data.user_name);
            $('#email').val(data.email);

            $('#password').val('');
            $('.password_el').hide();
            $('#confirm-password').val('');
            $('#password').removeAttr('required');
            $('#confirm-password').removeAttr('required');

            $('#full_name').val(data.full_name);
            $('#father_name').val(data.father_name);
            $('#mother_name').val(data.mother_name);

            $('#designation option[value='+data.designation_id+']').prop('selected','selected');
            $('#department option[value='+data.department_id+']').prop('selected','selected');
            $('#joining_date').val(data.joining_date);
            $('#extension_date').val(data.extension_date);
            $('#salary_grade option[value='+data.salary_grade+']').prop('selected','selected');
            $('#phone').val(data.phone);
            $('#year_experience').val(data.year_experience);
            $('#prevoius_organization').val(data.prevoius_organization);
            $('#previous_designation').val(data.previous_designation);
            $('#academic_institute').val(data.academic_institute);
            $('#reference').val(data.reference);
            $('#present_address').val(data.present_address);
            $('#permanent_address').val(data.permanent_address);

            
            initDatePicker();
            
            $('#form_modal').modal('show');
        });
    });

    $(document).on("ifChecked", '.active_status', function (event) {
        var id = $(this).attr("data-id");
        // alert(id);
        active_action(id);
        table.draw();
    });
    $(document).on("ifUnchecked", '.active_status', function (event) {
        var id = $(this).attr("data-id");
        // alert(id);
        active_action(id);
        table.draw();
    });



    $(document).on('click', '.deleteData', function () {
        var id = $(this).data("id");

        if (confirm("Are You sure want to delete !")){
            $.ajax({
                type: "DELETE",
                url: "{{ route('users.store') }}"+'/'+id,
                success: function (data) {
                    if(data.success){
                        toastr.success(data.success);
                        table.draw();
                    }
                    else{
                        toastr.error(data.error);
                    }
                },
                error: function (data) {
                    //console.log('Error:', data);
                }
            });
            
        }
    });


    $(document).on('click', '#saveBtn', function (e) {
        $("#entryForm").validate({
            debug: true,

            // rules: {
            //   password: "required",
            //   password_confirmation: {
            //     equalTo: "#password"
            //   }
            // },
        
            submitHandler: function(form) {
                $(this).html('Sending..');

                $.ajax({
                    data: $('#entryForm').serialize(),
                    url: "{{ route('users.store') }}",
                    type: "POST",
                    dataType: 'json',
                    success: function (data) {

                        if(data.success){
                            $('#entryForm').trigger("reset");
                            $('#form_modal').modal('hide');
                            table.draw();
                            toastr.success(data.success);
                        }
                        else{
                            toastr.error(data.error);
                        }

                    },
                    error: function (data) {
                        $.each(data.responseJSON.errors, function(error){
                            toastr.error(data.responseJSON.errors[error]);
                        });
                        // console.log('Error:', data);
                        $('#saveBtn').html('Save Changes');
                    }
                });
            }
        });
    });


