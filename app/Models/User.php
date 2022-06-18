<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Salary;
use DarkGhostHunter\Larapass\Contracts\WebAuthnAuthenticatable;
use DarkGhostHunter\Larapass\WebAuthnAuthentication;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements WebAuthnAuthenticatable {
    use HasApiTokens, HasFactory, Notifiable, HasRoles, WebAuthnAuthentication;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = array(
        'name',
        'email',
        'password',
        'employee_id',
        'phone',
        'nrc_number',
        'birthday',
        'gender',
        'address',
        'department_id',
        'date_of_join',
        'is_present',
        'profile_img',
        'pin_code',
    );

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = array(
        'password',
        'remember_token',
    );

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = array(
        'email_verified_at' => 'datetime',
    );

    public function department() {
        return $this->belongsTo( Department::class, 'department_id', 'id' );
    }

    public function profile_img_path() {

        if ( $this->profile_img ) {
            return asset( 'storage/employee/' . $this->profile_img );
        } else {
            return null;
        }

    }

    public function salaries() {
        return $this->hasMany( Salary::class, 'user_id', 'id' );
    }

}
