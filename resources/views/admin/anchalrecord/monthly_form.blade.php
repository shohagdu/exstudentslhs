<style>
    .select2-container--default .select2-selection--multiple .select2-selection__choice {
        background-color: #3c8dbc;
        border-color: #367fa9;
    }
    .select2-selection__choice {
        color: white;
    }
</style>
<form role="form"  method="post" style="background-color:#fff;"
      action="{{ route('anchalrecord.store') }}" enctype="multipart/form-data"  data-submit="ajax">
    @csrf
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Modal Header</h4>
</div>
<div class="modal-body">
    <div class="row">
    <div class="col-md-2">
        <div class="form-group">
            <label>Entry Date </label>
            <input type="text" class="form-control text-center" readonly value="<?php echo date('d-m-Y'); ?>">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>**. Child Unique id </label>
            <input type="text" class="form-control text-center" id="child_auto_id" name="child_auto_id" required readonly value="<?php echo $child_auto_id;?>">
        </div>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>1. Year <span style="color: red">*</span> :</label>
            <select class="form-control" id="yearofentry" name="yearofentry" onkeyup="javascript:show();" required>
                <option selected value="">Select Year</option>
                <?php
                $maxYear = date("Y");
                for($y=2017;$maxYear >= $y; $y++){
                ?>
                <option value="<?php echo $y; ?>"><?php echo $y; ?></option>
                <?php } ?>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>2. Month <span style="color: red">*</span> :</label>
            <select class="form-control" id="monthofentry" name="monthofentry" required>
                <option selected value="">Select Month</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>3. If admitted this month:</label>
            <select class="form-control" id="ifadmittedthismonth" name="ifadmittedthismonth" required>
                <option selected value="">Select One</option>
                <option value="1">Yes</option>
                <option value="2">No</option>
                <option value="3">Re-admitted</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>4. Number of days present: </label>
            <input type="number" min="1" class="form-control" id="days_present" name="days_present" onkeyup="show()" required max="27">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>5. Number of Days Absent</label>
            <input type="number" min="0" class="form-control" id="days_absent" name="days_absent" onkeyup="show()"/>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>6. Total number of days Anchal open</label>
            <input type="text" class="form-control" id="days_anchal_open" name="days_anchal_open" readonly="readonly" required>
        </div>
    </div>
    <div class="col-md-4" id="absent_reason_div" style="display: none;">
        <div class="form-group">
            <label>7. Reason for Absence</label>
            <select class="form-control  select2" multiple name="absent_reason[]" id="absent_reason" style="width: 100%;">
                <option value="1">Sickness</option>
                <option value="2">Vacation</option>
                <option value="3">No specific reason</option>
                <option value="4">Festival</option>
                <option value="5">Dropout</option>
                <option value="6">Graduate</option>
                <option value="7">Others</option>
            </select>
        </div>
    </div>
    <div class="col-md-4" id="absent_other_reason_div" style="display: none;">
        <div class="form-group">
            <label>Please specify the Other Reason <span style="color: red">*</span> </label>
            <input type="text" class="form-control" id="absent_other_reason" name="absent_other_reason" value="">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>9. Current status </label>
            <select class="form-control" name="current_status" id="current_status" required>
                <option selected value="">Select One</option>
                <option value="1">Attending Anchal</option>
                <option value="2">Graduate</option>
                <option value="3">Drop out</option>
                <option value="4">Child died (drowning) </option>
                <option value="5">Child died (other cause) </option>
                <option value="6">Others</option>
            </select>
            <input type="text" class="form-control" id="other_current_status" placeholder="Other Current Status" name="other_current_status" value="" style="display: none;">
        </div>
    </div>
    <div class="col-md-4" id="ifdropout_graduate_date_div">
            <div class="form-group">
                <label>8. If dropout/Graduate, Date </label>
                <input type="text" class="form-control" id="ifdropout_graduate_date" name="ifdropout_graduate_date" readonly="readonly" value="">
            </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>10. Who was present in the parents meeting</label>
            <select class="form-control" name="who_present_parents_meeting" id="who_present_parents_meeting" required>
                <option selected value="">Select One</option>
                <option value="1">Mother</option>
                <option value="2">Father</option>
                <option value="3">Other Male gaurdian</option>
                <option value="4">Other Female gaurdian</option>
                <option value="5">No one</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>11. Any Injury occurred</label>
            <select class="form-control" name="anyinjuryoccurred" id="anyinjuryoccurred" onchange="attended_no_hideshow()"required>
                <option selected value="">Select One</option>
                <option value="1">Yes</option>
                <option value="2">NO</option>
            </select>
        </div>
    </div>
    <div class="col-md-4"></div>

    <div class="col-md-12">
        <label class="bg-danger"><strong>Any Injury occurred then, following form will appear</strong></label>
    </div>

    <div class="col-md-4">
        <div class="form-group">
            <label>12. Time of Injury</label>
            <input type="text" class="form-control timeofinjury" id="timeofinjury" name="timeofinjury" placeholder="HH:MM" required readonly>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>13. Date of Injury </label>
            <input type="text" class="form-control" id="dateofinjury" name="dateofinjury" readonly="readonly" required value="<?php echo date("d-m-Y"); ?>">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>14. Place of Injury</label>
            <select class="form-control" name="placeofinjury" id="placeofinjury" onchange="showfield(this.options[this.selectedIndex].value)" required>
                <option selected value="">Select One</option>
                <option value="1">Anchal room</option>
                <option value="2">Bed room</option>
                <option value="3">Drawing room</option>
                <option value="4">Kitchen</option>
                <option value="5">Bathroom</option>
                <option value="6">Courtyard</option>
                <option value="7">Ditches</option>
                <option value="8">Road </option>
                <option value="9">Pond </option>
                <option value="10">River </option>
                <option value="11">water Container </option>
                <option value="12">Other Water Bodies </option>
                <option value="99">Others</option>
            </select>
            <input type="text" class="form-control" id="other_placeofinjury" placeholder="Other Place of Injury" name="other_placeofinjury" value="" style="display: none;">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>15. Type of injury</label>
            <select class="form-control" name="typeofinjury" id="typeofinjury" >
                <option selected value="">Select One</option>
                <option  value="1">Suicide</option>
                <option value="2">Road accident</option>
                <option value="3">Violence</option>
                    <option value="4">Fall</option>
                <option value="5">Cut with sharp object</option>
                <option value="6">Burn</option>
                <option value="7">Drowning</option>
                <option value="8">Poisoning </option>
                <option value="9">Mechanical injury</option>
                <option value="10">Electrocution</option>
                <option value="11">Insect/Animal bite bite</option>
                <option value="12">Sprain/strain</option>
                <option value="13">Hit with blunt object</option>
                <option value="14">Suffocation</option>
                <option value="15">Don’t know</option>
                <option value="16">0thers</option>
            </select>
            <input type="text" class="form-control" id="other_typeofinjury" placeholder="Other Type of injury" name="other_typeofinjury" value="" style="display: none;">
        </div>
    </div>
    <!--  <div class="col-md-4">
         <div class="form-group">
             <label>16. How severe the injury was </label>
             <select class="form-control" name="howsevere_the_injury" id="howsevere_the_injury" >
                 <option selected value="">Select One</option>
                 <option  value="1">Mild</option>
                 <option value="2">Moderate</option>
                 <option value="3">Serious</option>
                 <option value="4">Severe</option>

             </select>
         </div>
     </div> -->
    <div class="col-md-4">
        <div class="form-group">
            <label>16. Does he/she need any medical care</label>
            <select class="form-control" name="need_medical_care" id="need_medical_care" >
                <option selected value="">Select One</option>
                <option  value="1">Yes</option>
                <option value="2">NO</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>17. Does he/she send to hospital due to injury</label>
            <select class="form-control" name="send_to_hospital" id="send_to_hospital" >
                <option selected value="">Select One</option>
                <option  value="1">Yes</option>
                <option value="2">NO</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>18. What was the outcome of the treatment? </label>
            <select class="form-control" name="treatment_outcome" id="treatment_outcome" >
                <option selected value="">Select One</option>
                <option  value="1"> Cured</option>
                <option value="2">Under treatment</option>
            </select>
        </div>
    </div>

</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-danger pull-left" data-dismiss="modal">Close</button>
{{--    <input type="hidden" id="child_hidden_id"/>--}}
    <button type="submit" name="submit" id="saveBtn" class="btn btn-success pull-right">Save Monthly Data</button>
</div>

</form>

<script>

    $(document).ready(function() {

        $('#ifdropout_graduate_date, #dateofinjury').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true
        });

        $('.select2').select2();


        // Get the modal
        var modal = document.getElementById("myModal");

        // Get the button that opens the modal
        var btn = document.getElementById("myBtn");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        $(document).on("change", "#yearofentry", function (e) {
            e.preventDefault();
            var thisValue = $(this).val();
            // alert(thisValue);

            $('#monthofentry').empty().append('<option selected value="">Select Month</option>');

            if (thisValue != "") {

                // check already inserted months
                $.ajax({
                    type: "GET",
                    url: "{{route('anchalrecord.monthCheck')}}?child_auto_id=<?php echo $child_auto_id; ?>&year="+thisValue+"&action_type=month_check",
                    success: function(respons){
                        console.log(respons);
                        $('#monthofentry').empty().append('<option selected value="">Select Month</option>');
                        $('#monthofentry').append(respons);
                    },
                    error: function(){
                        alert("Failed to retrive months!");
                    }
                });
            }

        });

        // 7. Other
        $(document).on("change", "#absent_reason", function (e) {
            e.preventDefault();

            var thisValue = $(this).val();
            // console.log(thisValue);
            // alert(thisValue);

            if (($.inArray("5", thisValue) > -1) || ($.inArray("6", thisValue) > -1)) {

                // if ((thisValue === "5") || (thisValue === "6")) {
                $('#ifdropout_graduate_date').attr('value', '<?php echo $today; ?>');
                $('#ifdropout_graduate_date').datepicker('setDate', '<?php echo $today; ?>');
                // $('#ifdropout_graduate_date').attr('required', 'required');
                // $('#ifdropout_graduate_date').removeAttr('disabled');
                // $('#ifdropout_graduate_date_div').show();
            } else {
                // $('#ifdropout_graduate_date').attr('value', '');
                // $.datepicker._clearDate('#ifdropout_graduate_date');
                // $('#ifdropout_graduate_date').datepicker('setDate', null);
                // $('#ifdropout_graduate_date').attr('disabled', 'disabled');
                // $('#ifdropout_graduate_date_div').hide();
            }

            if ($.inArray("7", thisValue) > -1) {
                // if (thisValue === "7") {
                $('#absent_other_reason_div').show();
                $('#absent_other_reason').attr('required', 'required');
                // $('#ifdropout_graduate_date_div').hide();
            } else {
                // $('#ifdropout_graduate_date_div').show();
                $('#absent_other_reason').removeAttr('required');
                $('#absent_other_reason_div').hide();
            }

        });

        // 9. Other
        $(document).on("change", "#current_status", function (e) {
            e.preventDefault();

            var thisValue = $(this).val();
            // alert(thisValue);

            if (thisValue == 6) {
                $('#other_current_status').show();
                $('#other_current_status').attr('required', 'required');
            } else {
                $('#other_current_status').removeAttr('required');
                $('#other_current_status').hide();
            }

        });

        // 14. Other
        $(document).on("change", "#placeofinjury", function (e) {
            e.preventDefault();

            var thisValue = $(this).val();
            // alert(thisValue);

            if (thisValue == 9) {
                $('#other_placeofinjury').show();
                $('#other_placeofinjury').attr('required', 'required');
            } else {
                $('#other_placeofinjury').removeAttr('required');
                $('#other_placeofinjury').hide();
            }

        });

        // 15. Other
        $(document).on("change", "#typeofinjury", function (e) {
            e.preventDefault();

            var thisValue = $(this).val();
            // alert(thisValue);

            if (thisValue == 16) {
                $('#other_typeofinjury').show();
                $('#other_typeofinjury').attr('required', 'required');
            } else {
                $('#other_typeofinjury').removeAttr('required');
                $('#other_typeofinjury').hide();
            }

        });


        //Timepicker
        $('.timeofinjury').timepicker({
            showInputs: false
        })

    });


    function show(){
        // <!-- adding value 26+29-->
        var days_present = document.getElementById('days_present');

        if (days_present.value > 27) {
            alert('Number of days present cannot be more than 27!');
            days_present.value = "";
            days_present.focus();
        }

        var days_absent = document.getElementById('days_absent');
        var days_anchal_open = document.getElementById('days_anchal_open');
        var absent_reason_div = document.getElementById('absent_reason_div');

        var total_days_anchal_open = Number(days_present.value)+Number(days_absent.value);
        days_anchal_open.value = total_days_anchal_open;

        if ((days_absent.value == "") || (days_absent.value == 0)) {
            absent_reason_div.style.display = 'none'; // hide
            absent_reason.value = ""; // hide
        } else {
            absent_reason_div.style.display = 'block'; // show
        }

    }

    // <!--no hide show column 14 no-->
    function attended_no_hideshow() {
        var anyinjuryoccurred = document.getElementById('anyinjuryoccurred').value;
        // alert(ifadmitted_this_month);

        if (2 == anyinjuryoccurred) {
            document.getElementById('timeofinjury').setAttribute('disabled', 'disabled');
            document.getElementById('dateofinjury').setAttribute('disabled', 'disabled');
            document.getElementById('placeofinjury').setAttribute('disabled', 'disabled');
            document.getElementById('typeofinjury').setAttribute('disabled', 'disabled');

            //document.getElementById('howsevere_the_injury').setAttribute('disabled', 'disabled');
            document.getElementById('need_medical_care').setAttribute('disabled', 'disabled');
            document.getElementById('send_to_hospital').setAttribute('disabled', 'disabled');
            document.getElementById('treatment_outcome').setAttribute('disabled', 'disabled');

        } else {
            document.getElementById('timeofinjury').removeAttribute('disabled');
            document.getElementById('dateofinjury').removeAttribute('disabled');
            document.getElementById('placeofinjury').removeAttribute('disabled');
            document.getElementById('typeofinjury').removeAttribute('disabled');

            //document.getElementById('howsevere_the_injury').removeAttribute('disabled');
            document.getElementById('need_medical_care').removeAttribute('disabled');
            document.getElementById('send_to_hospital').removeAttribute('disabled');
            document.getElementById('treatment_outcome').removeAttribute('disabled');
        }
    }

</script>
