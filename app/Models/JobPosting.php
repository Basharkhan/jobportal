<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model {
    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category_id',
        'location_id',
        'job_type',
        'salary_min',
        'salary_max',
        'salary_currency',
        'experience_level',
        'education_level',
        'application_deadline',
        'remote',
        'benefits',
        'requirements',
        'responsibilities',
        'status',
        'published_at',
        'expires_at',
    ];

    // Relationships

    public function employer() {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function category() {
        return $this->belongsTo( Category::class );
    }

    public function location() {
        return $this->belongsTo( Location::class );
    }

    public function applications() {
        return $this->hasMany( Application::class );
    }
}
