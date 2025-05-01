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

    public function employments(): HasMany {
        return $this->hasMany( Employment::class );
    }
}
