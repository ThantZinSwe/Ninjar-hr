<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller {

    public function taskData( Request $request ) {
        $project = Project::where( 'id', $request->project_id )->firstOrFail();

        return view( 'components.task', compact( 'project' ) )->render();
    }

    public function store( Request $request ) {

        $task = new Task();
        $task->project_id = $request->project_id;
        $task->title = $request->title;
        $task->description = $request->description;
        $task->start_date = $request->startDate;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->status = $request->status;
        $task->save();

        $task->members()->sync( $request->members );

        return 'success';
    }

    public function update( $id, Request $request ) {

        $task = Task::findOrFail( $id );
        $task->title = $request->title;
        $task->description = $request->description;
        $task->start_date = $request->startDate;
        $task->deadline = $request->deadline;
        $task->priority = $request->priority;
        $task->update();

        $task->members()->sync( $request->members );

        return 'success';
    }

    public function destroy( $id ) {

        $task = Task::findOrfail( $id );

        $task->members()->detach();
        $task->delete();

        return 'success';
    }

    public function taskDraggable( Request $request ) {

        $project = Project::with( 'tasks' )->where( 'id', $request->project_id )->firstOrFail();

        if ( $request->pendingTaskBoard ) {
            $pendingTaskBoard = explode( ',', $request->pendingTaskBoard );

            foreach ( $pendingTaskBoard as $key => $task_id ) {
                $task = collect( $project->tasks )->where( 'id', $task_id )->first();

                if ( $task ) {
                    $task->serial_number = $key;
                    $task->status = 'pending';
                    $task->update();
                }

            }

        }

        if ( $request->progressTaskBoard ) {
            $progressTaskBoard = explode( ',', $request->progressTaskBoard );

            foreach ( $progressTaskBoard as $key => $task_id ) {
                $task = collect( $project->tasks )->where( 'id', $task_id )->first();

                if ( $task ) {
                    $task->serial_number = $key;
                    $task->status = 'in_progress';
                    $task->update();
                }

            }

        }

        if ( $request->completeTaskBoard ) {
            $completeTaskBoard = explode( ',', $request->completeTaskBoard );

            foreach ( $completeTaskBoard as $key => $task_id ) {
                $task = collect( $project->tasks )->where( 'id', $task_id )->first();

                if ( $task ) {
                    $task->serial_number = $key;
                    $task->status = 'complete';
                    $task->update();
                }

            }

        }

        return 'success';

    }

}
