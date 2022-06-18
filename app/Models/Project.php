<?php

namespace App\Models;

use App\Models\Task;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model {
    use HasFactory;

    protected $fillable = array(
        'title',
        'description',
        'images',
        'files',
        'start_date',
        'deadline',
        'priority',
        'status',
    );

    protected $casts = array(
        'images' => 'array',
        'files'  => 'array',
    );

    public function leaders() {
        return $this->belongsToMany( User::class, 'project_leaders', 'project_id', 'user_id' );
    }

    public function members() {
        return $this->belongsToMany( User::class, 'project_members', 'project_id', 'user_id' );
    }

    public function tasks() {
        return $this->hasMany( Task::class, 'project_id', 'id' );
    }
}
