<?php

namespace App\Http\Controllers;

class PageController extends Controller {
    public function home() {

        $employee = auth()->user();

        return view( 'home' )->with( array('employee' => $employee) );
    }
}
