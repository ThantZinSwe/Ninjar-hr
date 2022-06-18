<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salary extends Model {
    use HasFactory;

    protected $fillable = array(
        'user_id',
        'month',
        'year',
        'amount',
    );

    public function employee() {
        return $this->belongsTo( User::class, 'user_id', 'id' );
    }
}
