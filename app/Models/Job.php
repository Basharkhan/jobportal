<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model {
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'location_id',
        'user_id',
        'type',
        'salary_range',
        'expires_at'
    ];
}
