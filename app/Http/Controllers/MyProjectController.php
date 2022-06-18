<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class MyProjectController extends Controller {

    //Project page
    public function index() {
        return view( 'my_project' );
    }

    //Project DataTable
    public function ssd( Request $request ) {
        $projects = Project::with( 'leaders', 'members' )
            ->whereHas( 'leaders', function ( $query ) {
                $query->where( 'user_id', auth()->user()->id );
            } )
            ->orWhereHas( 'members', function ( $query ) {
                $query->where( 'user_id', auth()->user()->id );
            } );

        return Datatables::of( $projects )
            ->editColumn( 'description', function ( $each ) {
                return Str::limit( $each->description, 200 );
            } )
            ->addColumn( 'leaders', function ( $each ) {
                $output = '<div style ="width:150px">';

                foreach ( $each->leaders as $leader ) {
                    $output .= '<img src="' . $leader->profile_img_path() . '" alt="" class = "img-thumbnail2 mx-1">';
                }

                return $output . '</div>';
            } )
            ->addColumn( 'members', function ( $each ) {
                $output = '<div style ="width:150px">';

                foreach ( $each->members as $member ) {
                    $output .= '<img src="' . $member->profile_img_path() . '" alt="" class = "img-thumbnail2 mx-1">';
                }

                return $output . '</div>';
            } )
            ->editColumn( 'priority', function ( $each ) {
                $output = '';

                if ( $each->priority == 'low' ) {
                    return '<span class="badge badge-pill badge-danger">Low</span>';
                } elseif ( $each->priority == 'middle' ) {
                    return '<span class="badge badge-pill badge-warning">Middle</span>';
                } else {
                    return '<span class="badge badge-pill badge-success">High</span>';
                }

            } )
            ->editColumn( 'status', function ( $each ) {
                $output = '';

                if ( $each->status == 'pending' ) {
                    return '<span class="badge badge-pill badge-primary">Pending</span>';
                } elseif ( $each->status == 'in_progress' ) {
                    return '<span class="badge badge-pill badge-warning">In Progress</span>';
                } else {
                    return '<span class="badge badge-pill badge-success">Complete</span>';
                }

            } )
            ->addColumn( 'action', function ( $each ) {

                $info_icon = '';

                $info_icon = '<a href = "' . route( 'my-project.show', $each->id ) . '"><i class="fas fa-info-circle text-primary"></i></a>';

                return '<div class="action-icon">' . $info_icon . '</div>';
            } )
            ->addColumn( 'plus', function ( $each ) {
                return null;
            } )
            ->editColumn( 'updated_at', function ( $each ) {
                return Carbon::parse( $each->updated_at )->format( 'Y-m-d H:i:s' );
            } )
            ->editColumn( 'start_date', function ( $each ) {
                return '<div style="width:100px;">' . $each->start_date . '</div>';
            } )
            ->editColumn( 'deadline', function ( $each ) {
                return '<div style="width:100px;">' . $each->deadline . '</div>';
            } )
            ->rawColumns( array( 'leaders', 'members', 'priority', 'status', 'action', 'start_date', 'deadline' ) )
            ->make( true );
    }

    //Project Info
    public function show( $id ) {

        $data = Project::with( 'leaders', 'members', 'tasks' )
            ->where( 'id', $id )
            ->where( function ( $query ) {
                $query->whereHas( 'leaders', function ( $q1 ) {
                    $q1->where( 'user_id', auth()->user()->id );
                } );
                $query->orWhereHas( 'members', function ( $q1 ) {
                    $q1->where( 'user_id', auth()->user()->id );
                } );
            } )
            ->findOrfail( $id );

        return view( 'my_project_show' )->with( array( 'project' => $data ) );
    }

}
