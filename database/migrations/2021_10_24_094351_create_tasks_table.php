<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTasksTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'tasks', function ( Blueprint $table ) {
            $table->id();
            $table->bigInteger( 'project_id' );
            $table->string( 'title' )->nullable();
            $table->string( 'description' )->nullable();
            $table->date( 'start_date' )->nullable();
            $table->date( 'deadline' )->nullable();
            $table->enum( 'priority', array( 'high', 'middle', 'low' ) );
            $table->enum( 'status', array( 'pending', 'in_progress', 'complete' ) );
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'tasks' );
    }
}
