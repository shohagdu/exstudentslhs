<table border="1" cellpadding="0" cellspacing="0">
    <thead>
        <tr>
            <th><b>Child ID</b></th>
            <th><b>Child Name</b></th>
            <th><b>Anchal ID</b></th>
            <th><b>Year</b></th>
            <th><b>Month</b></th>
            <th><b>Days Present</b></th>
            <th><b>Days Absent</b></th>
            <th><b>Days Open</b></th>
            <th><b>ifAdmittedthismonth</b></th>
            <th><b>Absent Reason</b></th>
            <th><b>Absent Other Reason</b></th>
            <th><b>Dropout Graduate Date</b></th>
            <th><b>Current status</b></th>
            <th><b>Other Current Status</b></th>
            <th><b>Who Present Parents Meeting</b></th>
            <th><b>Anyinjury occurred</b></th>
            <th><b>Injury Time</b></th>
            <th><b>Injury Date</b></th>
            <th><b>Place Of Injury</b></th>
            <th><b>Other Place Of Injury</b></th>
            <th><b>Type Of Injury</b></th>
            <th><b>Other Type of Injury</b></th>
            <th><b>Howsevere the injury</b></th>
            <th><b>Need Medical Care</b></th>
            <th><b>Treatment Outcome</b></th>
            <th><b>Created At</b></th>
        </tr>
    </thead>
    @if(!empty($makeData))
        @foreach($makeData as $childId => $yearInfo)
            @foreach($yearInfo as $year => $months)
                <tr>
                    <td rowspan="{{count($months)+1}}">
                        {{$childId}}
                    </td>
                    <td rowspan="{{count($months)+1}}">
                        {{ $makeData[$childId][$year][array_key_first($months)]->childInfo->child_name }}
                    </td>
                    <td rowspan="{{count($months)+1}}">
                        {{ $makeData[$childId][$year][array_key_first($months)]->childInfo->anchal_auto_id }}
                    </td>
                    <td rowspan="{{count($months)+1}}">
                        {{$year}}
                    </td>
                </tr>
                @foreach($months as $monthName => $monthInfo)
                    <tr>
                        <td>{{$monthName}}</td>
                        <td>{{$monthInfo->days_present}}</td>
                        <td>{{$monthInfo->days_absent}}</td>
                        <td>{{$monthInfo->days_anchal_open}}</td>
                        <td>{{$monthInfo->ifadmittedthismonth}}</td>
                        <td>{{$monthInfo->absent_reason}}</td>
                        <td>{{$monthInfo->absent_other_reason}}</td>
                        <td>{{$monthInfo->ifdropout_graduate_date}}</td>
                        <td>{{$monthInfo->current_status ? $monthInfo->current_status : 'N/A'}}</td>
                        <td>{{ $monthInfo->other_current_status ? $monthInfo->other_current_status :'N/A'}}</td>
                        <td>{{$monthInfo->who_present_parents_meeting}}</td>
                        <td>{{$monthInfo->anyinjuryoccurred ? $monthInfo->anyinjuryoccurred : 'N/A'}}</td>
                        <td>{{$monthInfo->timeofinjury ? $monthInfo->timeofinjury : 'N/A' }}</td>
                        <td>{{$monthInfo->dateofinjury ? $monthInfo->dateofinjury : 'N/A'}}</td>
                        <td>{{$monthInfo->placeofinjury ? $monthInfo->placeofinjury : 'N/A'}}</td>
                        <td>{{$monthInfo->other_placeofinjury ? $monthInfo->other_placeofinjury : 'N/A'}}</td>
                        <td>{{$monthInfo->typeofinjury ? $monthInfo->typeofinjury : 'N/A'}}</td>
                        <td>{{$monthInfo->other_typeofinjury ? $monthInfo->other_typeofinjury : 'N/A'}}</td>
                        <td>{{$monthInfo->howsevere_the_injury ? $monthInfo->howsevere_the_injury : 'N/A'}}</td>
                        <td>{{$monthInfo->need_medical_care ? $monthInfo->need_medical_care : 'N/A'}}</td>
                        <td>{{$monthInfo->treatment_outcome ? $monthInfo->treatment_outcome : 'N/A'}}</td>
                        <td>{{$monthInfo->created_at}}</td>

                    </tr>
                @endforeach
            @endforeach
        @endforeach
    @endif
</table>
