<?php

namespace App\Http\Controllers;

use App\Models\Checkinout;
use App\Models\CompanySetting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;

class MyAttendanceController extends Controller {

    public function ssd( Request $request ) {

        $attendances = Checkinout::with( 'employee' )->where( 'user_id', auth()->user()->id );

        if ( $request->month ) {
            $attendances = $attendances->whereMonth( 'date', $request->month );
        }

        if ( $request->year ) {
            $attendances = $attendances->whereYear( 'date', $request->year );
        }

        return Datatables::of( $attendances )
            ->addColumn( 'employee', function ( $each ) {
                return $each->employee ? $each->employee->name : '-';
            } )
            ->filterColumn( 'employee', function ( $query, $keyword ) {
                $query->whereHas( 'employee', function ( $q1 ) use ( $keyword ) {
                    $q1->where( 'name', 'like', '%' . $keyword . '%' );
                } );
            } )
            ->addColumn( 'plus', function ( $each ) {
                return null;
            } )
            ->make( true );
    }

    public function attendanceOverviewTable( Request $request ) {

        $month = $request->month;
        $year = $request->year;
        $start_of_month = $year . '-' . $month . '-' . '01';
        $end_of_month = Carbon::parse( $start_of_month )->endOfMonth()->format( 'Y-m-d' );

        $periods = new CarbonPeriod( $start_of_month, $end_of_month );
        $employees = User::orderBy( 'employee_id' )->where( 'id', auth()->user()->id )->get();
        $attendances = Checkinout::whereMonth( 'date', $month )->whereYear( 'date', $year )->get();
        $companySetting = CompanySetting::findOrFail( '1' );

        return view( 'components.attendance_overview_table', compact( 'periods', 'employees', 'attendances', 'companySetting' ) )->render();
    }

}
