<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\Datatables\Datatables;

class DepartmentController extends Controller {

    //Department page
    public function index() {

        if ( !auth()->user()->can( 'department_view' ) ) {
            abort( 404 );
        }

        return view( 'department.index' );
    }

    //Department DataTable
    public function ssd( Request $request ) {

        if ( !auth()->user()->can( 'department_view' ) ) {
            abort( 404 );
        }

        $departments = Department::query();

        return Datatables::of( $departments )
            ->addColumn( 'action', function ( $each ) {

                $edit_icon = '';
                $delete_icon = '';

                if ( auth()->user()->can( 'department_edit' ) ) {
                    $edit_icon = '<a href = "' . route( 'department.edit', $each->id ) . '"><i class="far fa-edit text-warning"></i></a>';
                }

                if ( auth()->user()->can( 'department_delete' ) ) {
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

    //Department Create Page
    public function create() {

        if ( !auth()->user()->can( 'department_create' ) ) {
            abort( 404 );
        }

        return view( 'department.create' );
    }

    //Department Store
    public function store( Request $request ) {

        if ( !auth()->user()->can( 'department_create' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'title' => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $departments = $this->depatmentData( $request );

        Department::create( $departments );
        return redirect()->route( 'department.index' )->with( array( 'create' => 'Department crate success...' ) );

    }

    //Department Edit Page
    public function edit( $id ) {

        if ( !auth()->user()->can( 'department_edit' ) ) {
            abort( 404 );
        }

        $departments = Department::findOrFail( $id );

        return view( 'department.edit' )->with( array( 'department' => $departments ) );
    }

    //Department Update Page
    public function update( $id, Request $request ) {

        if ( !auth()->user()->can( 'department_edit' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'title' => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $updateData = $this->depatmentData( $request );

        Department::findOrFail( $id )->update( $updateData );
        return redirect()->route( 'department.index' )->with( array( 'update' => 'Departments data update success..' ) );

    }

    //Department Delete
    public function destroy( $id ) {

        if ( !auth()->user()->can( 'department_delete' ) ) {
            abort( 404 );
        }

        Department::findOrFail( $id )->delete();

        return 'success';
    }

    //Request Department Data
    private function depatmentData( Request $request ) {
        return array(
            'title' => $request->title,
        );
    }

}
