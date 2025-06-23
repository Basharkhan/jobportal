<?php
namespace App\Repositories;

use App\Models\CompanyProfile;
use App\Models\SeekerProfile;
use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserRepository implements UserRepositoryInterface {
    public function getAllAdmins(int $perPage = 10): LengthAwarePaginator {
        return User::role('admin')->with('roles')->latest()->paginate($perPage);
    }

    public function getAllEmployers(int $perPage = 10): LengthAwarePaginator {
        return User::role('employer')->with('roles', 'companyProfile')->latest()->paginate($perPage);
    }

    public function getAllJobSeekers(int $perPage = 10): LengthAwarePaginator {            
        return User::role('job_seeker')->with('roles')->latest()->paginate($perPage);
    }    

    public function getAllUsers(int $perPage = 10): LengthAwarePaginator {
        return User::with('roles')->latest()->paginate($perPage);        
    }

    public function getUserById(int $id): ?User {
        return User::with('roles')->find($id);
    }    

    public function getEmployerById(int $id): ?User{
        return User::role('employer')->with('roles', 'companyProfile')->find($id);
    }
    
    public function getJobSeekerById(int $id): ?User {
        return User::role('job_seeker')->with('roles', 'seekerProfile')->find($id);
    }

    public function getUserByEmail(string $email): ?User {
        return User::where('email', $email)->first();
    }

    public function deleteUser(int $id): bool {
        $user = User::find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }

    public function changeEmployerStatus(int $id, int $status): ?User{
        $user = User::has('companyProfile')->findOrFail($id);
        $user->companyProfile()->update(['status' => $status]);
        return $user->load('companyProfile');
    }

    public function changeJobSeekerStatus(int $id, int $status): ?User {
        $user = User::has('seekerProfile')->findOrFail($id);
        $user->seekerProfile()->update(['status' => $status]);        
        return $user->load('seekerProfile');
    }

   public function updateEmployerProfile(User $user, array $data): ?User {
        $user->companyProfile()->update($data);
        return $user->load('companyProfile');
    }

    public function updateJobSeekerProfile(User $user, array $data): ?User {        
        DB::transaction(function () use ($user, $data) {
            // update user basic info
            if(isset($data['name'])) {
                $user->update(['name' => $data['name']]);
            }

            // handle profile data without relations
            $profileData = Arr::except($data, ['name', 'experiences', 'educations']);
            $user->seekerProfile()->updateOrCreate([], $profileData);

            // handle relations if present
            if(isset($data['experiences']) || isset($data['educations'])) {
                $this->updateSeekerProfileRelations($user->seekerProfile, $data);
            }
        });
        
        return $user->load('seekerProfile.experiences', 'seekerProfile.educations');
    }

    public function updateSeekerProfileRelations(SeekerProfile $profile, array $data) {
        if (isset($data['experiences']) && is_array($data['experiences'])) {
            $this->syncExperiences($profile, $data['experiences']);
        }

        if (isset($data['educations']) && is_array($data['educations'])) {
            $this->syncEducations($profile, $data['educations']);
        }
    }

    protected function syncExperiences(SeekerProfile $profile, array $experiences) {
        $existingIds = [];

        foreach($experiences as $exp) {
            $experience = $profile->experiences()->updateOrCreate(
                ['id' => $exp['id'] ?? null],
                Arr::only($exp, [                    
                    'job_title',
                    'company_name',
                    'location',
                    'start_date',
                    'end_date',
                    'is_current',
                    'description'
                ])
            );
            $existingIds[] = $experience->id;
        }

        $profile->experiences()->whereNotIn('id', $existingIds)->delete();
    }

     protected function syncEducations(SeekerProfile $profile, array $educations) {
        $existingIds = [];
        
        foreach ($educations as $edu) {
            $education = $profile->educations()->updateOrCreate(
                ['id' => $edu['id'] ?? null],
                Arr::only($edu, [
                    'degree',
                    'institution_name',
                    'field_of_study',
                    'start_date',
                    'end_date',
                    'grade',
                    'description'
                ])
            );
            $existingIds[] = $education->id;
        }
        
        $profile->educations()->whereNotIn('id', $existingIds)->delete();
    }
}

