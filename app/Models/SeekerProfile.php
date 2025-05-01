<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SeekerProfile extends Model {
    protected $fillable = [
        'user_id',
        'resume_path',
        'skills',
        'experience',
        'education',
    ];

    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }
}
