<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Yajra\Datatables\Datatables;

class ProjectController extends Controller {

    //Project page
    public function index() {

        if ( !auth()->user()->can( 'project_view' ) ) {
            abort( 404 );
        }

        return view( 'project.index' );
    }

    //Project DataTable
    public function ssd( Request $request ) {

        if ( !auth()->user()->can( 'project_view' ) ) {
            abort( 404 );
        }

        $projects = Project::with( 'leaders', 'members' );

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

                $edit_icon = '';
                $delete_icon = '';
                $info_icon = '';

                if ( auth()->user()->can( 'project_edit' ) ) {
                    $edit_icon = '<a href = "' . route( 'project.edit', $each->id ) . '"><i class="far fa-edit text-warning"></i></a>';
                }

                if ( auth()->user()->can( 'project_delete' ) ) {
                    $delete_icon = '<a href ="#" class="delete-btn" data-id="' . $each->id . '"><i class="fas fa-trash-alt text-danger"></i></a>';
                }

                if ( auth()->user()->can( 'project_view' ) ) {
                    $info_icon = '<a href = "' . route( 'project.show', $each->id ) . '"><i class="fas fa-info-circle text-primary"></i></a>';
                }

                return '<div class="action-icon">' . $edit_icon . $delete_icon . $info_icon . '</div>';
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

    //Project Create Page
    public function create() {

        if ( !auth()->user()->can( 'project_create' ) ) {
            abort( 404 );
        }

        $employees = User::get();

        return view( 'project.create', compact( 'employees' ) );
    }

    //Project Store
    public function store( Request $request ) {

        if ( !auth()->user()->can( 'project_create' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'title'       => 'required',
            'description' => 'required',
            'startDate'   => 'required',
            'deadline'    => 'required',
            'priority'    => 'required',
            'status'      => 'required',
        ), );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $projects = $this->projectData( $request );

        $image_name = null;

        if ( $request->hasFile( 'images' ) ) {
            $image_name = array();
            $files = $request->file( 'images' );

            foreach ( $files as $file ) {
                $fileNames = uniqid() . '_' . time() . $file->getClientOriginalName();
                $image_name[] = $fileNames;
                $file->move( storage_path() . '/app/public/project/image/', $fileNames );
            }

        }

        $file_name = null;

        if ( $request->hasFile( 'files' ) ) {
            $file_name = array();
            $files = $request->file( 'files' );

            foreach ( $files as $file ) {
                $fileNames = uniqid() . '_' . time() . $file->getClientOriginalName();
                $file_name[] = $fileNames;
                $file->move( storage_path() . '/app/public/project/file/', $fileNames );
            }

        }

        $projects['images'] = $image_name;
        $projects['files'] = $file_name;
        $project = Project::create( $projects );

        $project->leaders()->sync( $request->leaders );
        $project->members()->sync( $request->members );

        return redirect()->route( 'project.index' )->with( array( 'create' => 'Project crate success...' ) );

    }

    //Project Edit Page
    public function edit( $id ) {

        if ( !auth()->user()->can( 'project_edit' ) ) {
            abort( 404 );
        }

        $projects = Project::findOrFail( $id );
        $employees = User::get();

        return view( 'project.edit' )->with( array( 'project' => $projects, 'employees' => $employees ) );
    }

    //Project Update Page
    public function update( $id, Request $request ) {

        if ( !auth()->user()->can( 'project_edit' ) ) {
            abort( 404 );
        }

        $validator = Validator::make( $request->all(), array(
            'title'       => 'required',
            'description' => 'required',
            'startDate'   => 'required',
            'deadline'    => 'required',
            'priority'    => 'required',
            'status'      => 'required',
        ) );

        if ( $validator->fails() ) {
            return back()
                ->withErrors( $validator )
                ->withInput();
        }

        $updateData = $this->projectData( $request );
        $project = Project::findOrFail( $id );

        $image_name = $project->images;

        if ( $request->hasFile( 'images' ) ) {

            $delete_images = Project::select( 'images' )
                ->where( 'id', $id )
                ->first();

            foreach ( $delete_images->images as $delete_image ) {

                if ( File::exists( storage_path() . '/app/public/project/image/' . $delete_image ) ) {
                    File::delete( storage_path() . '/app/public/project/image/' . $delete_image );
                }

            }

            $image_name = array();
            $files = $request->file( 'images' );

            foreach ( $files as $file ) {
                $fileNames = uniqid() . '_' . time() . $file->getClientOriginalName();
                $image_name[] = $fileNames;
                $file->move( storage_path() . '/app/public/project/image/', $fileNames );
            }

        }

        $file_name = $project->files;

        if ( $request->hasFile( 'files' ) ) {

            $delete_files = Project::select( 'files' )
                ->where( 'id', $id )
                ->first();

            foreach ( $delete_files->files as $delete_file ) {

                if ( File::exists( storage_path() . '/app/public/project/file/' . $delete_file ) ) {
                    File::delete( storage_path() . '/app/public/project/file/' . $delete_file );
                }

            }

            $file_name = array();
            $files = $request->file( 'files' );

            foreach ( $files as $file ) {
                $fileNames = uniqid() . '_' . time() . $file->getClientOriginalName();
                $file_name[] = $fileNames;
                $file->move( storage_path() . '/app/public/project/file/', $fileNames );
            }

        }

        $updateData['images'] = $image_name;
        $updateData['files'] = $file_name;

        $project->update( $updateData );

        $project->leaders()->sync( $request->leaders );
        $project->members()->sync( $request->members );

        return redirect()->route( 'project.index' )->with( array( 'update' => 'Project data update success..' ) );

    }

    //Project Delete
    public function destroy( $id ) {

        if ( !auth()->user()->can( 'project_delete' ) ) {
            abort( 404 );
        }

        $delete_images = Project::select( 'images' )
            ->where( 'id', $id )
            ->first();

        $delete_files = Project::select( 'files' )
            ->where( 'id', $id )
            ->first();

        $project = Project::findOrFail( $id );

        $project->leaders()->detach();
        $project->members()->detach();

        foreach ( $delete_images->images as $delete_image ) {

            if ( File::exists( storage_path() . '/app/public/project/image/' . $delete_image ) ) {
                File::delete( storage_path() . '/app/public/project/image/' . $delete_image );
            }

        }

        foreach ( $delete_files->files as $delete_file ) {

            if ( File::exists( storage_path() . '/app/public/project/file/' . $delete_file ) ) {
                File::delete( storage_path() . '/app/public/project/file/' . $delete_file );
            }

        }

        $project->delete();

        return 'success';
    }

    //Project Info
    public function show( $id ) {

        if ( !auth()->user()->can( 'project_view' ) ) {
            abort( 404 );
        }

        $data = Project::findOrfail( $id );

        return view( 'project.show' )->with( array( 'project' => $data ) );
    }

    //Request Project Data
    private function projectData( Request $request ) {
        return array(
            'title'       => $request->title,
            'description' => $request->description,
            'start_date'  => $request->startDate,
            'deadline'    => $request->deadline,
            'priority'    => $request->priority,
            'status'      => $request->status,
        );
    }

}
