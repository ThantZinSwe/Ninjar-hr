<?php

namespace App\Http\Controllers;

use App\Models\Checkinout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AttedanceScanController extends Controller {
    public function scan() {
        return view( 'attendanceScan' );
    }

    public function scanStore( Request $request ) {

        if ( now()->format( 'D' ) == 'Sat' || now()->format( 'D' ) == 'Sun' ) {
            return array(
                'status'  => 'fail',
                'message' => 'Today is off day.',
            );
        }

        if ( !Hash::check( date( 'Y-m-d' ), $request->hash_value ) ) {
            return array(
                'status'  => 'fail',
                'message' => 'QR Code is invaild',
            );
        }

        $user = auth()->user();

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
