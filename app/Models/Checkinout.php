<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Checkinout extends Model {
    use HasFactory;

    protected $fillable = array(
        'user_id',
        'checkin_time',
        'checkout_time',
        'date',
    );

    public function employee() {
        return $this->belongsTo( User::class, 'user_id', 'id' );
    }
}
