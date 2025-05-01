<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Application extends Model {
    protected $fillable = [
        'employment_id',
        'user_id',
        'cover_letter',
        'resume',
        'status'
    ];

    public function employment(): BelongsTo {
        return $this->belongsTo( Employment::class );
    }

    public function seeker(): BelongsTo {
        return $this->belongsTo( User::class, 'user_id' );
    }
}
