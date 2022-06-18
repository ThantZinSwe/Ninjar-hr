<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\Datatables\Datatables;

class RoleController extends Controller {
    //Role page
    public function index() {

        if ( !auth()->user()->can( 'role_view' ) ) {
            abort( 404 );
        }

        return view( 'role.index' );
    }

    //Role DataTable
    public function ssd( Request $request ) {

        if ( !auth()->user()->can( 'role_view' ) ) {
            abort( 404 );
        }

        $roles = Role::query();

        return Datatables::of( $roles )
            ->addColumn( 'permissions', function ( $each ) {
                $output = '';

                foreach ( $each->permissions as $permissions ) {
                    $output .= '<span class="badge badge-pill badge-success m-1">' . $permissions->name . '</span>';
                }

                return $output;
            } )
            ->addColumn( 'action', function ( $each ) {

                $edit_icon = '';
                $delete_icon = '';

                if ( auth()->user()->can( 'role_edit' ) ) {
                    $edit_icon = '<a href = "' . route( 'role.edit', $each->id ) . '"><i class="far fa-edit text-warning"></i></a>';
                }

                if ( auth()->user()->can( 'role_delete' ) ) {
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
            ->rawColumns( array( 'permissions', 'action' ) )
            ->make( true );
    }

    //Role Create Page
    public function create() {

        if ( !auth()->user()->can( 'role_create' ) ) {
            abort( 404 );
        }

        $permissions = Permission::get();

        return view( 'role.create' )->with( array( 'permission' => $permissions ) );
    }

    //Role Store
    public function store( Request $request ) {

        if ( !auth()->user()->can( 'role_create' ) ) {
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

        $role = Role::create( array(

            'name' => $request->name,
        ) );

        $role->givePermissionTo( $request->permissions );

        return redirect()->route( 'role.index' )->with( array( 'create' => 'Role crate success...' ) );

    }

    //Role Edit Page
    public function edit( $id ) {

        if ( !auth()->user()->can( 'role_edit' ) ) {
            abort( 404 );
        }

        $roles = Role::findOrFail( $id );
        $oldPermission = $roles->permissions->pluck( 'id' )->toArray();

        $permissions = Permission::get();

        return view( 'role.edit' )->with( array( 'role' => $roles, 'permission' => $permissions, 'oldPermission' => $oldPermission ) );
    }

    //Role Update Page
    public function update( $id, Request $request ) {

        if ( !auth()->user()->can( 'role_edit' ) ) {
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

        $role = Role::findOrFail( $id );

        $role->update( array(
            'name' => $request->name,
        ) );

        $oldPermission = $role->permissions->pluck( 'name' )->toArray();
        $role->revokePermissionTo( $oldPermission );

        $role->givePermissionTo( $request->permissions );

        return redirect()->route( 'role.index' )->with( array( 'update' => 'Role data update success..' ) );

    }

    //Role Delete
    public function destroy( $id ) {

        if ( !auth()->user()->can( 'role_delete' ) ) {
            abort( 404 );
        }

        Role::findOrFail( $id )->delete();

        return 'success';
    }

}
