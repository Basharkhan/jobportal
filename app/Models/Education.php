<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model {
    protected $fillable = [
        'seeker_profile_id',
        'degree',
        'institution_name',
        'field_of_study',
        'start_date',
        'end_date',
        'grade',
        'description',
    ];

    public function seekerProfile() {
        return $this->belongsTo(SeekerProfile::class);
    }
}
