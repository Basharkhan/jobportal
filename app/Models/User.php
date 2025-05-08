<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens, HasRoles;

    /**
    * The attributes that are mass assignable.
    *
    * @var list<string>
    */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    public function isEmployer(): bool {
        return $this->hasRole( 'employer' );
    }

    public function isJobSeeker(): bool {
        return $this->hasRole( 'job_seeker' );
    }

    public function isSuperAdmin(): bool {
        return $this->hasRole( 'super_admin' );
    }

    public function companyProfile(): HasOne {
        return $this->hasOne( CompanyProfile::class );
    }

    public function seekerProfile(): HasOne {
        return $this->hasOne( SeekerProfile::class );
    }

    public function jobPostings(): HasMany {
        return $this->hasMany( JobPosting::class );
    }

    public function applications(): HasMany {
        return $this->hasMany( Application::class );
    }

    public function setPasswordAttribute( $value ) {
        $this->attributes[ 'password' ] = bcrypt( $value );
    }

    /**
    * The attributes that should be hidden for serialization.
    *
    * @var list<string>
    */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
    * Get the attributes that should be cast.
    *
    * @return array<string, string>
    */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
