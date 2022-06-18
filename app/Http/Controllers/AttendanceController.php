<?php

namespace App\Http\Controllers;

use App\Models\Checkinout;
use App\Models\CompanySetting;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class AttendanceController extends Controller {

    //Attendance Page
    public function index() {

        if ( !auth()->user()->can( 'attendance_view' ) ) {
            abort( 404 );
        }

        return view( 'attendance.index' );
    }

    //Attendance DataTable
    public function ssd( Request $request ) {

        if ( !auth()->user()->can( 'attendance_view' ) ) {
            abort( 404 );
        }

        $attendances = Checkinout::with( 'employee' );

        return Datatables::of( $attendances )
            ->addColumn( 'employee', function ( $each ) {
                return $each->employee ? $each->employee->name : '-';
            } )
            ->filterColumn( 'employee', function ( $query, $keyword ) {
                $query->whereHas( 'employee', function ( $q1 ) use ( $keyword ) {
                    $q1->where( 'name', 'like', '%' . $keyword . '%' );
                } );
            } )
            ->addColumn( 'action', function ( $each ) {

                $edit_icon = '';
                $delete_icon = '';

                if ( auth()->user()->can( 'attendance_edit' ) ) {
                    $edit_icon = '<a href = "' . route( 'attendance.edit', $each->id ) . '"><i class="far fa-edit text-warning"></i></a>';
                }

                if ( auth()->user()->can( 'attendance_delete' ) ) {
                    $delete_icon = '<a href ="#" class="delete-btn" data-id="' . $each->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                }

                return '<div class="action-icon">' . $edit_icon . $delete_icon . '</div>';
            } )
            ->addColumn( 'plus', function ( $each ) {
                return null;
            } )
            ->editColumn( 'updated_at', function ( $each ) {
                return Carbon::parse( $each->updated_at )->format( 'Y-m-d H:i:s' );
            } )
            ->rawColumns( array( 'action' ) )
            ->make( true );
    }

    //Attendance Create Page
    public function create() {

        if ( !auth()->user()->can( 'attendance_create' ) ) {
            abort( 404 );
        }

        $employee = User::get();

        return view( 'attendance.create' )->with( array( 'employee' => $employee ) );
    }

    //Attendance Store
    public function store( Request $request ) {

        if ( !auth()->user()->can( 'attendance_create' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'employee' => 'required',
            'date'     => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        if ( Checkinout::where( 'user_id', $request->employee )
            ->where( 'date', $request->date )
            ->exists() ) {
            return back()->withErrors( array( 'fail' => 'Already exisits this employee for today.' ) )->withInput();
        }

        $attendances = $this->attendanceData( $request );

        if ( isset( $request->checkin ) ) {
            $attendances['checkin_time'] = $request->date . " " . $request->checkin;
        }

        if ( isset( $request->checkout ) ) {
            $attendances['checkout_time'] = $request->date . " " . $request->checkout;
        }

        Checkinout::create( $attendances );
        return redirect()->route( 'attendance.index' )->with( array( 'create' => 'Attendance crate success...' ) );

    }

    //Attendance Edit Page
    public function edit( $id ) {

        if ( !auth()->user()->can( 'attendance_edit' ) ) {
            abort( 404 );
        }

        $attendances = Checkinout::findOrFail( $id );
        $employee = User::get();

        return view( 'attendance.edit' )->with( array( 'attendance' => $attendances, 'employee' => $employee ) );
    }

    //Attendance Update Page
    public function update( $id, Request $request ) {

        if ( !auth()->user()->can( 'attendance_edit' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'employee' => 'required',
            'date'     => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $attendance = Checkinout::findOrFail( $id );

        if ( Checkinout::where( 'user_id', $request->employee )
            ->where( 'date', $request->date )
            ->where( 'id', '!=', $attendance->id )
            ->exists() ) {
            return back()->withErrors( array( 'fail' => 'Already exisits this employee for a day.' ) )->withInput();
        }

        $updateData = $this->attendanceData( $request );

        if ( isset( $request->checkin ) ) {
            $updateData['checkin_time'] = $request->date . " " . $request->checkin;
        }

        if ( isset( $request->checkout ) ) {
            $updateData['checkout_time'] = $request->date . " " . $request->checkout;
        }

        $attendance->update( $updateData );

        return redirect()->route( 'attendance.index' )->with( array( 'update' => 'Attendances data update success..' ) );

    }

    //Attendance Delete
    public function destroy( $id ) {

        if ( !auth()->user()->can( 'attendance_delete' ) ) {
            abort( 404 );
        }

        Checkinout::findOrFail( $id )->delete();

        return 'success';
    }

    public function attendanceOverview() {

        if ( !auth()->user()->can( 'attendance_view' ) ) {
            abort( 404 );
        }

        return view( 'attendance.overview' );
    }

    public function attendanceOverviewTable( Request $request ) {

        if ( !auth()->user()->can( 'attendance_view' ) ) {
            abort( 404 );
        }

        $month = $request->month;
        $year = $request->year;
        $employee = $request->employee_name;
        $start_of_month = $year . '-' . $month . '-' . '01';
        $end_of_month = Carbon::parse( $start_of_month )->endOfMonth()->format( 'Y-m-d' );

        $periods = new CarbonPeriod( $start_of_month, $end_of_month );
        $employees = User::orderBy( 'employee_id' )->where( 'name', 'like', '%' . $employee . '%' )->get();
        $attendances = Checkinout::whereMonth( 'date', $month )->whereYear( 'date', $year )->get();
        $companySetting = CompanySetting::findOrFail( '1' );

        return view( 'components.attendance_overview_table', compact( 'periods', 'employees', 'attendances', 'companySetting' ) )->render();
    }

    //Request Attendance Data
    private function attendanceData( Request $request ) {
        return array(
            'user_id' => $request->employee,
            'date'    => $request->date,
        );
    }

}
