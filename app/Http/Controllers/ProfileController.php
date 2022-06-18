<?php

namespace App\Http\Controllers;

class ProfileController extends Controller {
    public function profile() {

        $employee = auth()->user();

        return view( 'profile.profile' )->with( array( 'employee' => $employee ) );
    }
}
