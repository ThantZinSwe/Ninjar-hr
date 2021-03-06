<div class="table-responsive">
    <table class="table table-bordered table-striped">
        <thead>
            <th class="text-center">Employee</th>
            <th class="text-center">Role</th>
            <th class="text-center">Day Of Month</th>
            <th class="text-center">Working Days</th>
            <th class="text-center">Off Day</th>
            <th class="text-center">Attendance Day</th>
            <th class="text-center">Leave</th>
            <th class="text-center">Per Day (MMK)</th>
            <th class="text-center">Total (MMK)</th>
        </thead>

        <tbody class="text-center">
            @foreach ($employees as $employee)
            @php
                $attendanceDays = 0;
                $salary = collect($employee->salaries)->where('month',$month)->where('year',$year)->first();
                $perDay = $salary ? ($salary->amount/$working_days) : 0;
            @endphp
            <tr>
                <td>{{ $employee->name }}</td>
                <td class="text-center">{{ implode('',$employee->roles->pluck('name')->toArray()) }}</td>
                <td class="text-center">{{ $days_in_month }}</td>
                <td class="text-center">{{ $working_days }}</td>
                <td class="text-center">{{ $off_days }}</td>
                @foreach ($periods as $period)
                    @php

                    $office_start_time = $period->format('Y-m-d').' '.$companySetting->office_start_time;
                    $office_end_time = $period->format('Y-m-d').' '.$companySetting->office_end_time;
                    $break_start_time = $period->format('Y-m-d').' '.$companySetting->break_start_time;
                    $break_end_time = $period->format('Y-m-d').' '.$companySetting->break_end_time;

                    $attendance = collect($attendances)->where('user_id',$employee->id)->where('date',$period->format('Y-m-d'))->first();

                    if($attendance){
                        if(!is_null($attendance->checkin_time)){
                            if($attendance->checkin_time <= $office_start_time){
                                $attendanceDays += 0.5;
                            }else if($attendance->checkin_time > $office_start_time && $attendance->checkin_time < $break_start_time){
                                $attendanceDays += 0.5;
                            }else{
                                $attendanceDays += 0;
                            }
                        }else{
                            $attendanceDays += 0;
                        }

                        if(!is_null($attendance->checkout_time)){
                            if($attendance->checkout_time <= $break_end_time){
                                $attendanceDays += 0;
                            }else if($attendance->checkout_time > $break_end_time && $attendance->checkout_time < $office_end_time){
                                $attendanceDays += 0.5;
                            }else{
                                $attendanceDays += 0.5;
                            }
                        }else{
                            $attendanceDays += 0.5;
                        }
                    }
                    @endphp
                @endforeach
                <td class="text-center">{{ $attendanceDays }}</td>
                @php
                    $leaveDays = $working_days - $attendanceDays;
                @endphp
                <td class="text-center">{{ $leaveDays }}</td>
                <td class="text-center">{{ number_format($perDay) }}</td>
                @php
                    $total = $perDay * $attendanceDays;
                @endphp
                <td class="text-center">{{ number_format($total) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
