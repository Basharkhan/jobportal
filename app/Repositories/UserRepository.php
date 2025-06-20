<?php
namespace App\Repositories;

use App\Models\CompanyProfile;
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
}

