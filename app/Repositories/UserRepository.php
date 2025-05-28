<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface {
    public function getAllAdmins(int $perPage = 10): LengthAwarePaginator {
        return User::role('admin')->with('roles')->latest()->paginate($perPage);
    }

    public function getEmployers(int $perPage = 10): LengthAwarePaginator {
        return User::role('employer')->with('roles')->latest()->paginate($perPage);
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

    public function changeJobSeekerStatus(int $id, string $status): ?User {
        $user = User::with('seekerProfile')->find($id);
        if ($user && $user->isJobSeeker()) {
            $user->seekerProfile()->update(['status' => $status]);
            return $user;
        }
        return null;
    }
}

