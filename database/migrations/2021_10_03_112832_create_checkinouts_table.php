<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCheckinoutsTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create( 'checkinouts', function ( Blueprint $table ) {
            $table->id();
            $table->biginteger( 'user_id' );
            $table->timestamp( 'checkin_time' )->nullable();
            $table->timestamp( 'checkout_time' )->nullable();
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists( 'checkinouts' );
    }
}
