<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SeekerProfile extends Model {
    protected $fillable = [
        'user_id',
        'resume_path',
        'skills',
        'experience',
        'education',
    ];
}
