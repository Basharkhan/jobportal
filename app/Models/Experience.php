<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model {
    protected $fillable = [
        'seeker_profile_id',
        'job_title',
        'company_name',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
    ];

    public function seekerProfile() {
        return $this->belongsTo( SeekerProfile::class );
    }
}
