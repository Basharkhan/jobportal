<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeekerProfile extends Model {
    protected $fillable = [
        'user_id',
        'resume_path',
        'resume',
        'skills',        
        'bio',
        'phone',
        'location',
        'desired_job_title',
        'expected_salary',
        'employment_type',
        'available_from',
        'portfolio_link',
        'linkedin',
        'github',
        'certifications',
        'languages',
        'status'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }

    public function experiences() {
        return $this->hasMany( Experience::class );
    }

    public function educations() {
        return $this->hasMany(Education::class);
    }
}
