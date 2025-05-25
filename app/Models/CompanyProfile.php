<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompanyProfile extends Model {
    protected $fillable = [
        'user_id',
        'company_name',
        'logo',
        'website',
        'email',
        'phone',
        'address',
        'city',
        'state',
        'country',
        'zip_code',
        'founded_year',
        'company_size',
        'linkedin',
        'business_description',
        'industry_type',
        'trade_license_number',
        'is_verified',
        'status'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo( User::class );
    }
}
