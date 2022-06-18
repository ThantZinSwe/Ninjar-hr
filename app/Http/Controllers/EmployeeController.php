<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use Yajra\Datatables\Datatables;

class EmployeeController extends Controller {

    //Employee List Page
    public function index() {

        if ( !auth()->user()->can( 'employee_view' ) ) {
            abort( 404 );
        }

        return view( 'employee.index' );
    }

    //Employee DataTable
    public function ssd( Request $request ) {

        if ( !auth()->user()->can( 'employee_view' ) ) {
            abort( 404 );
        }

        $employee = User::with( 'department' );
        return Datatables::of( $employee )
            ->addColumn( 'department_name', function ( $each ) {
                return $each->department ? $each->department->title : '-';
            } )
            ->filterColumn( 'department_name', function ( $query, $keyword ) {
                $query->whereHas( 'department', function ( $q1 ) use ( $keyword ) {
                    $q1->where( 'title', 'like', '%' . $keyword . '%' );
                } );
            } )
            ->addColumn( 'role', function ( $each ) {
                $output = '';

                foreach ( $each->roles as $role ) {
                    $output .= '<span class="badge badge-pill badge-success m-1" >' . $role->name . '</span>';
                }

                return $output;

            } )
            ->editColumn( 'is_present', function ( $each ) {

                if ( $each->is_present == 1 ) {
                    return '<span class="badge badge-pill border badge-success">Present</span>';
                } else {
                    return '<span class="badge badge-pill border badge-danger">Leave</span>';
                }

            } )
            ->editColumn( 'updated_at', function ( $each ) {
                return Carbon::parse( $each->updated_at )->format( 'Y-m-d H:i:s' );
            } )
            ->editColumn( 'profile_img', function ( $each ) {
                return '<img src="' . $each->profile_img_path() . '" class="img-thumbnail" /><p class="mb-0 mt-2 p-text">' . $each->name . '</p>';
            } )
            ->addColumn( 'plus', function ( $each ) {
                return null;
            } )
            ->addColumn( 'action', function ( $each ) {

                $edit_icon = '';
                $info_icon = '';
                $delete_icon = '';

                if ( auth()->user()->can( 'employee_edit' ) ) {
                    $edit_icon = '<a href="' . route( 'employee.edit', $each->id ) . '"><i class="far fa-edit text-warning"></i></a>';
                }

                if ( auth()->user()->can( 'employee_view' ) ) {
                    $info_icon = '<a href="' . route( 'employee.show', $each->id ) . '"><i class="fas fa-info-circle text-primary"></i></a>';
                }

                if ( auth()->user()->can( 'employee_delete' ) ) {
                    $delete_icon = '<a href="#" class="delete-btn" data-id="' . $each->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                }

                return '<div class="action-icon">' . $edit_icon . $info_icon . $delete_icon . '</div>';
            } )
            ->rawColumns( array( 'role', 'is_present', 'profile_img', 'action' ) )
            ->make( true );
    }

    //Employee Create Page
    public function create() {

        if ( !auth()->user()->can( 'employee_create' ) ) {
            abort( 404 );
        }

        $departments = Department::orderBy( 'title' )->get();

        $role = Role::get();

        return view( 'employee.create' )->with( array( 'department' => $departments, 'role' => $role ) );
    }

    //Employee Data Store
    public function store( Request $request ) {

        if ( !auth()->user()->can( 'employee_create' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'employeeId' => 'required|unique:users,employee_id',
            'name'       => 'required',
            'phone'      => 'required|min:9|max:11|unique:users,phone',
            'email'      => 'required|email|unique:users,email',
            'password'   => 'required',
            'pinCode'    => 'required|min:6|max:6|unique:users,pin_code',
            'nrcNumber'  => 'required',
            'gender'     => 'required',
            'birthday'   => 'required',
            'address'    => 'required',
            'department' => 'required',
            'dateOfJoin' => 'required',
            'isPresent'  => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $employee = $this->employeeData( $request );

        if ( isset( $request->profileImage ) ) {
            $file = $request->file( 'profileImage' );
            $fileName = uniqid() . '_' . time() . $file->getClientOriginalName();
            $file->move( storage_path() . '/app/public/employee/', $fileName );
            $employee['profile_img'] = $fileName;
        }

        $employees = User::create( $employee );

        $employees->syncRoles( $request->roles );

        return redirect()->route( 'employee.index' )->with( array( 'create' => 'Employee created success.' ) );

    }

    //Employee Edit Page
    public function edit( $id ) {

        if ( !auth()->user()->can( 'employee_edit' ) ) {
            abort( 404 );
        }

        $employees = User::findOrFail( $id );
        $oldRole = $employees->roles->pluck( 'id' )->toArray();

        $departments = Department::orderBy( 'title' )->get();

        $role = Role::get();

        return view( 'employee.edit' )->with( array( 'department' => $departments, 'employee' => $employees, 'role' => $role, 'oldRole' => $oldRole ) );
    }

    //Employee Update Page
    public function update( $id, Request $request, ) {

        if ( !auth()->user()->can( 'employee_edit' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'employeeId' => 'required|unique:users,employee_id,' . $id,
            'name'       => 'required',
            'phone'      => 'required|min:9|max:11|unique:users,phone,' . $id,
            'email'      => 'required|email|unique:users,email,' . $id,
            'nrcNumber'  => 'required',
            'gender'     => 'required',
            'birthday'   => 'required',
            'address'    => 'required',
            'department' => 'required',
            'dateOfJoin' => 'required',
            'isPresent'  => 'required',
            'pinCode'    => 'required|max:6|min:6|unique:users,pin_code,' . $id,
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $updateData = $this->updateEmployeeData( $request );

        if ( isset( $updateData['password'] ) ) {
            $updateData['password'] = Hash::make( $request->password );
        }

        if ( isset( $request->profileImage ) ) {
            $deleteFile = User::select( 'profile_img' )
                ->where( 'id', $id )
                ->first();

            if ( File::exists( storage_path() . '/app/public/employee/' . $deleteFile->profile_img ) ) {
                File::delete( storage_path() . '/app/public/employee/' . $deleteFile->profile_img );
            }

            $file = $request->file( 'profileImage' );
            $fileName = uniqid() . '_' . time() . $file->getClientOriginalName();
            $file->move( storage_path() . '/app/public/employee/', $fileName );

            $updateData['profile_img'] = $fileName;

        }

        $employee = User::findOrFail( $id );
        $employee->update( $updateData );

        $employee->syncRoles( $request->roles );

        return redirect()->route( 'employee.index' )->with( array( 'update' => 'Employee Data Update Success...' ) );

    }

    //Employee Info
    public function show( $id ) {

        if ( !auth()->user()->can( 'employee_view' ) ) {
            abort( 404 );
        }

        $data = User::findOrfail( $id );

        return view( 'employee.show' )->with( array( 'employee' => $data ) );
    }

    public function destroy( $id ) {

        if ( !auth()->user()->can( 'employee_delete' ) ) {
            abort( 404 );
        }

        $deleteProfile = User::select( 'profile_img' )
            ->where( 'id', $id )
            ->first();

        User::findOrFail( $id )->delete();

        if ( File::exists( storage_path() . '/app/public/employee/' . $deleteProfile->profile_img ) ) {
            File::delete( storage_path() . '/app/public/employee/' . $deleteProfile->profile_img );
        }

        return 'success';
    }

    //Request Employee Data
    private function employeeData( $request ) {
        $data = array(
            'employee_id'   => $request->employeeId,
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'pin_code'      => $request->pinCode,
            'password'      => Hash::make( $request->password ),
            'nrc_number'    => $request->nrcNumber,
            'gender'        => $request->gender,
            'birthday'      => $request->birthday,
            'address'       => $request->address,
            'department_id' => $request->department,
            'date_of_join'  => $request->dateOfJoin,
            'is_present'    => $request->isPresent,
        );

        return $data;

    }

    //Request Employee Update Data
    private function updateEmployeeData( $request ) {
        $data = array(
            'employee_id'   => $request->employeeId,
            'name'          => $request->name,
            'phone'         => $request->phone,
            'email'         => $request->email,
            'nrc_number'    => $request->nrcNumber,
            'gender'        => $request->gender,
            'birthday'      => $request->birthday,
            'address'       => $request->address,
            'department_id' => $request->department,
            'date_of_join'  => $request->dateOfJoin,
            'is_present'    => $request->isPresent,
            'pin_code'      => $request->pinCode,
        );

        if ( isset( $request->password ) ) {
            $data['password'] = $request->password;
        }

        return $data;

    }

}
