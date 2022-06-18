<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Yajra\Datatables\Datatables;

class PermissionController extends Controller {

    //Permission page
    public function index() {

        if ( !auth()->user()->can( 'permission_view' ) ) {
            abort( 404 );
        }

        return view( 'permission.index' );
    }

    //Permission DataTable
    public function ssd( Request $request ) {

        if ( !auth()->user()->can( 'permission_view' ) ) {
            abort( 404 );
        }

        $permissions = Permission::query();

        return Datatables::of( $permissions )
            ->addColumn( 'action', function ( $each ) {

                $edit_icon = '';
                $delete_icon = '';

                if ( auth()->user()->can( 'permission_edit' ) ) {
                    $edit_icon = '<a href = "' . route( 'permission.edit', $each->id ) . '"><i class="far fa-edit text-warning"></i></a>';
                }

                if ( auth()->user()->can( 'permission_delete' ) ) {
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

    //Permission Create Page
    public function create() {

        if ( !auth()->user()->can( 'permission_create' ) ) {
            abort( 404 );
        }

        return view( 'permission.create' );
    }

    //Permission Store
    public function store( Request $request ) {

        if ( !auth()->user()->can( 'permission_create' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'name' => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $permissions = $this->permissionData( $request );

        Permission::create( $permissions );
        return redirect()->route( 'permission.index' )->with( array( 'create' => 'Permission crate success...' ) );

    }

    //Permission Edit Page
    public function edit( $id ) {

        if ( !auth()->user()->can( 'permission_edit' ) ) {
            abort( 404 );
        }

        $permissions = Permission::findOrFail( $id );

        return view( 'permission.edit' )->with( array( 'permission' => $permissions ) );
    }

    //Permission Update Page
    public function update( $id, Request $request ) {

        if ( !auth()->user()->can( 'permission_edit' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'name' => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $updateData = $this->permissionData( $request );

        Permission::findOrFail( $id )->update( $updateData );
        return redirect()->route( 'permission.index' )->with( array( 'update' => 'Permission data update success..' ) );

    }

    //Permission Delete
    public function destroy( $id ) {

        if ( !auth()->user()->can( 'permission_delete' ) ) {
            abort( 404 );
        }

        Permission::findOrFail( $id )->delete();

        return 'success';
    }

    //Request Permission Data
    private function permissionData( Request $request ) {
        return array(
            'name' => $request->name,
        );
    }

}
