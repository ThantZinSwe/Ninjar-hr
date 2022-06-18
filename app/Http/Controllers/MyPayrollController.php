<?php

namespace App\Http\Controllers;

use App\Models\Checkinout;
use App\Models\CompanySetting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;

class MyPayrollController extends Controller {
    public function payroll() {

        return view( 'payroll' );
    }

    public function payrollTable( Request $request ) {

        $month = $request->month;
        $year = $request->year;
        $employee = $request->employee_name;
        $start_of_month = $year . '-' . $month . '-' . '01';
        $end_of_month = Carbon::parse( $start_of_month )->endOfMonth()->format( 'Y-m-d' );
        $days_in_month = Carbon::parse( $start_of_month )->daysInMonth;

        $start = Carbon::now()->setDate( $year, $month, 1 );
        $end = Carbon::now()->setDate( $year, $month, $days_in_month );

        $working_days = $start->diffInDaysFiltered( function ( Carbon $date ) {

            return $date->isWeekday();

        }, $end );

        $off_days = $days_in_month - $working_days;

        $periods = new CarbonPeriod( $start_of_month, $end_of_month );
        $employees = User::orderBy( 'employee_id' )->where( 'id', auth()->user()->id )->get();
        $attendances = Checkinout::whereMonth( 'date', $month )->whereYear( 'date', $year )->get();
        $companySetting = CompanySetting::findOrFail( '1' );

        return view( 'components.payroll_table', compact( 'periods', 'employees', 'attendances', 'companySetting', 'days_in_month', 'working_days', 'off_days', 'month', 'year' ) )->render();
    }

}
