<div class="row">
	<div class="col-md-12">
        <table class="table table-bordered">
            <tr>
                <td class="text-bold">1.Project</td>
                <td>{{$data->projectInfo->name}}</td>
                <td class="text-bold">1.District</td>
                <td>{{ $data->districtInfo->district_name_en }}</td>
                <td class="text-bold">2.SubDistrict</td>
                <td>{{$subDistrictInfo->upazila_name_en ? $subDistrictInfo->upazila_name_en : ''}}</td>
                <td class="text-bold">3.Union</td>
                <td>{{ !empty($unionInfo->union_name_en) ? $unionInfo->union_name_en : '' }}</td>
            </tr>
            <tr>
                <td class="text-bold">4.Word</td>
                <td>{{$data->ward}}</td>
                <td class="text-bold">5.Village</td>
                <td>{{$data->village}}</td>
                <td class="text-bold">6.Anchal Number</td>
                <td>{{$data->anchal_number }}</td>
                <td class="text-bold">7.Anchal ID</td>
                <td>{{$data->anchal_auto_id}}</td>
            </tr>
            <tr>
                <td class="text-bold">8.Anchal Maa</td>
                <td>{{$data->anchal_ma}}</td>
                <td class="text-bold">9.Child Number</td>
                <td>{{$data->child_number}}</td>
                <td class="text-bold">10.Child ID</td>
                <td>{{$data->child_auto_id}}</td>
                <td class="text-bold">11.Child Name</td>
                <td>{{$data->child_name}}</td>
            </tr>
            <tr>
                <td class="text-bold">12.Gender</td>
                <td>{{$data->sex==1?'male':'Female'}}</td>
                <td class="text-bold">13.Date Of Birth</td>
                <td>{{ !empty($data->child_birthdate) ? date('d/m/Y',strtotime($data->child_birthdate)) :'' }}</td>
                <td class="text-bold">14.Admission Date</td>
                <td>{{ !empty($data->admission_date) ? date('d/m/Y',strtotime($data->admission_date)) :'' }}</td>
                <td class="text-bold">15.Age</td>
                <td>{{$data->age.' Months'}}</td>
            </tr>
            <tr>
                <td class="text-bold">16.Parents Name</td>
                <td>{{$data->parents_name}}</td>
                <td class="text-bold">17.Mobile No</td>
                <td>{{$data->mobile}}</td>
                <td class="text-bold"></td>
                <td></td>
                <td class="text-bold"></td>
                <td></td>
            </tr>
        </table>
	</div>
</div>
