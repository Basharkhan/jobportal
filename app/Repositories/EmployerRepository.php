<?php
namespace App\Repositories;

use App\Models\JobPosting;
use App\Models\User;
use App\Repositories\Interfaces\EmployerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployerRepository implements EmployerRepositoryInterface {
    public function createJob(array $data): ?JobPosting {
        return DB::transaction(function () use ($data) {
            $job = JobPosting::create([
                'user_id' => $data['user_id'],
                'title' => $data['title'],
                'description' => $data['description'],
                'category_id' => $data['category_id'] ?? null,
                'location_id' => $data['location_id'] ?? null,
                'job_type' => $data['job_type'],
                'salary_min' => $data['salary_min'] ?? null,
                'salary_max' => $data['salary_max'] ?? null,
                'salary_currency' => $data['salary_currency'] ?? 'USD',
                'experience_level' => $data['experience_level'],
                'education_level' => $data['education_level'],
                'application_deadline' => $data['application_deadline'],
                'remote' => $data['remote'] ?? false,
                'benefits' => $data['benefits'] ?? null,
                'requirements' => $data['requirements'] ?? null,
                'responsibilities' => $data['responsibilities'] ?? null,
            ]);

            return $job;
        });
        
    }
}
