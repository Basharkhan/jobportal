<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class UserRepository implements UserRepositoryInterface {
    public function getAllAdmins(int $perPage = 10): LengthAwarePaginator {
        return User::role('admin')->latest()->paginate($perPage);
    }

    public function getEmployers(int $perPage = 10): LengthAwarePaginator {
        return User::role('employer')->latest()->paginate($perPage);
    }

    public function getAllJobSeekers(int $perPage = 10): LengthAwarePaginator {            
        return User::role('job_seeker')->latest()->paginate($perPage);
    }    

    public function getUserById(int $id): ?User {
        return User::find($id);
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
}

