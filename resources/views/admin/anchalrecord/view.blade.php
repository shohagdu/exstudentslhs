<div class="row">
	<div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-bordered table-responsive">
                <tr>
                    <td class="text-bold">Project</td>
                    <td>{{$data->project_id}}</td>
                    <td class="text-bold">Child ID</td>
                    <td>{{$data->child_auto_id}}</td>
                    <td class="text-bold">1.Year</td>
                    <td>{{$data->yearofentry}}</td>
                    <td class="text-bold">2.Entry Month</td>
                    <td>{{$data->monthofentry}}</td>
                </tr>
                <tr>
                    <td class="text-bold">3.Admit This Month?</td>
                    <td>{{$data->ifadmittedthismonth}}</td>
                    <td class="text-bold">4.Present</td>
                    <td>{{$data->days_present}}</td>
                    <td class="text-bold">5.Absent</td>
                    <td>{{$data->days_absent}}</td>
                    <td class="text-bold">6.Days Open</td>
                    <td>{{$data->days_anchal_open}}</td>

                </tr>
                <tr>
                    <td class="text-bold">7.Absent Reason</td>
                    <td>{{$data->absent_reason}}</td>
                    <td class="text-bold">Absent Other Reason</td>
                    <td>{{$data->absent_other_reason}}</td>
                    <td class="text-bold">8.Graduate Date</td>
                    <td>{{$data->ifdropout_graduate_date}}</td>
                    <td class="text-bold">9.Current Status</td>
                    <td>{{$data->current_status}}</td>

                </tr>
                <tr>
                    <td class="text-bold">Other Current Status</td>
                    <td>{{$data->other_current_status}}</td>
                    <td class="text-bold">10.Present Person (meeting)</td>
                    <td>{{$data->who_present_parents_meeting}}</td>
                    <td class="text-bold">11.Any Injury?</td>
                    <td>{{$data->anyinjuryoccurred}}</td>
                    <td class="text-bold">12.Injury Time</td>
                    <td>{{$data->timeofinjury}}</td>

                </tr>
                <tr>
                    <td class="text-bold">13.Injury Date</td>
                    <td>{{$data->dateofinjury}}</td>
                    <td class="text-bold">14.Injury Place</td>
                    <td>{{$data->placeofinjury}}</td>
                    <td class="text-bold">Other Injury Place</td>
                    <td>{{$data->other_placeofinjury}}</td>
                    <td class="text-bold">15.Injury Type</td>
                    <td>{{$data->typeofinjury}}</td>

                </tr>
                <tr>
                    <td class="text-bold">Other Injury Type</td>
                    <td>{{$data->other_typeofinjury}}</td>
                    <td class="text-bold">16.Need Medical Care</td>
                    <td>{{$data->need_medical_care}}</td>
                    <td class="text-bold">17.Send To Hospital</td>
                    <td>{{$data->send_to_hospital}}</td>
                    <td class="text-bold">18.Treatment</td>
                    <td>{{$data->treatment_outcome}}</td>
                </tr>
            </table>
        </div>
	</div>
</div>
