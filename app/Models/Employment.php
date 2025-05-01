<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Employment extends Model {
    protected $fillable = [
        'title',
        'description',
        'category_id',
        'location_id',
        'user_id',
        'type',
        'salary_range',
        'expires_at',
    ];

    public function employer(): BelongsTo {
        return $this->belongsTo( User::class, 'user_id' );
    }

    public function category(): BelongsTo {
        return $this->belongsTo( Category::class );
    }

    public function location(): BelongsTo {
        return $this->belongsTo( Location::class );
    }

    public function applications(): HasMany {
        return $this->hasMany( Application::class );
    }
}
