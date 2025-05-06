<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model {
    protected $fillable = [
        'city',
        'state',
        'country',
    ];

    public function jobPostings(): HasMany {
        return $this->hasMany( JobPosting::class );
    }
}
