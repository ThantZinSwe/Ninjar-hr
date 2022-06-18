<?php

namespace App\Http\Controllers;

use App\Models\Checkinout;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CheckInCheckOutController extends Controller {
    public function checkInCheckOut() {

        $hashValue = Hash::make( date( 'Y-m-d' ) );

        return view( 'checkInCheckOut' )->with( array( 'hashValue' => $hashValue ) );
    }

    public function checkInOut( Request $request ) {

        if ( now()->format( 'D' ) == 'Sat' || now()->format( 'D' ) == 'Sun' ) {
            return array(
                'status'  => 'fail',
                'message' => 'Today is off day.',
            );
        }

        $user = User::where( 'pin_code', $request->pin_code )->first();

        if ( !$user ) {
            return array(
                'status'  => 'fail',
                'message' => 'Pin Code is wrong.Try Again!',
            );
        }

        $checkin_checkout = Checkinout::firstOrCreate( array(
            'user_id' => $user->id,
            'date'    => now()->format( 'Y-m-d' ),
        ) );

        if ( !is_null( $checkin_checkout->checkin_time ) && !is_null( $checkin_checkout->checkout_time ) ) {
            return array(
                'status'  => 'fail',
                'message' => 'Already Check-in and Check-out for today...',
            );
        }

        if ( is_null( $checkin_checkout->checkin_time ) ) {
            $checkin_checkout->checkin_time = now();
            $message = 'Check-in Successfully at ' . now();
        } else {

            if ( is_null( $checkin_checkout->checkout_time ) ) {
                $checkin_checkout->checkout_time = now();
                $message = 'Check-out Successfully at ' . now();
            }

        }

        $checkin_checkout->update();

        return array(
            'status'  => 'success',
            'message' => $message,
        );
    }

}
