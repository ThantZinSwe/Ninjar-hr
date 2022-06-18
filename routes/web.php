<?php

use App\Http\Controllers\Auth\WebAuthnLoginController;
use App\Http\Controllers\Auth\WebAuthnRegisterController;
use Illuminate\Support\Facades\Route;

Auth::routes( array( 'register' => false ) );

Route::post( 'webauthn/register/options', array( WebAuthnRegisterController::class, 'options' ) )
    ->name( 'webauthn.register.options' );
Route::post( 'webauthn/register', array( WebAuthnRegisterController::class, 'register' ) )
    ->name( 'webauthn.register' );

Route::post( 'webauthn/login/options', array( WebAuthnLoginController::class, 'options' ) )
    ->name( 'webauthn.login.options' );
Route::post( 'webauthn/login', array( WebAuthnLoginController::class, 'login' ) )
    ->name( 'webauthn.login' );

Route::get( 'check-in-checkout', 'CheckInCheckOutController@checkInCheckOut' );
Route::post( 'checkinout', 'CheckInCheckOutController@checkInOut' );

Route::middleware( 'auth' )->group( function () {
    Route::get( '/', 'PageController@home' )->name( 'home' );

    Route::resource( 'employee', 'EmployeeController' );
    Route::get( 'employee/datatable/ssd', 'EmployeeController@ssd' );

    Route::get( 'profile', 'ProfileController@profile' )->name( 'profile#profile' );

    Route::resource( 'department', 'DepartmentController' );
    Route::get( 'department/datatable/ssd', 'DepartmentController@ssd' );

    Route::resource( 'role', 'RoleController' );
    Route::get( 'role/datatable/ssd', 'RoleController@ssd' );

    Route::resource( 'permission', 'PermissionController' );
    Route::get( 'permission/datatable/ssd', 'PermissionController@ssd' );

    Route::resource( 'companySetting', 'CompanySettingController' )->only( array( 'edit', 'update', 'show' ) );

    Route::resource( 'attendance', 'AttendanceController' );
    Route::get( 'attendance/datatable/ssd', 'AttendanceController@ssd' );
    Route::get( 'attendance-overview', 'AttendanceController@attendanceOverview' )->name( 'attendance_overview' );
    Route::get( 'attendance-overview-table', 'AttendanceController@attendanceOverviewTable' );

    Route::get( 'attendance-scan', 'AttedanceScanController@scan' )->name( 'attendance_scan' );
    Route::post( 'attendance-scan/store', 'AttedanceScanController@scanStore' )->name( 'attendance_scan_store' );
    Route::get( 'my-attendance/datatable/ssd', 'MyAttendanceController@ssd' );
    Route::get( 'my-attendance-overview-table', 'MyAttendanceController@attendanceOverviewTable' );

    Route::resource( 'salary', 'SalaryController' );
    Route::get( 'salary/datatable/ssd', 'SalaryController@ssd' );

    Route::get( 'payroll', 'PayrollController@payroll' )->name( 'payroll' );
    Route::get( 'payroll-table', 'PayrollController@payrollTable' );

    Route::get( 'my-payroll', 'MyPayrollController@payroll' )->name( 'payroll' );
    Route::get( 'my-payroll-table', 'MyPayrollController@payrollTable' );

    Route::resource( 'project', 'ProjectController' );
    Route::get( 'project/datatable/ssd', 'ProjectController@ssd' );
    Route::resource( 'my-project', 'MyProjectController' )->only( array( 'index', 'show' ) );
    Route::get( 'my-project/datatable/ssd', 'MyProjectController@ssd' );

    Route::resource( 'task', 'TaskController' );
    Route::get( 'task-data', 'TaskController@taskData' );
    Route::get( 'task-draggable', 'TaskController@taskDraggable' );
} );
