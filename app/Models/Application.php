<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model {
    protected $fillable = [
        'job_posting_id',
        'user_id',
        'cover_letter',
        'resume',
        'status'
    ];

    public function jobPosting(): BelongsTo {
        return $this->belongsTo( JobPosting::class );
    }

    public function seeker(): BelongsTo {
        return $this->belongsTo( User::class, 'user_id' );
    }
}
