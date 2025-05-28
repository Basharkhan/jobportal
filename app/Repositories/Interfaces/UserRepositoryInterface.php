<?php
namespace App\Repositories\Interfaces;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface {
    public function getAllAdmins(int $perPage = 10): LengthAwarePaginator;

    public function getAllEmployers(int $perPage = 10): LengthAwarePaginator;

    public function getAllJobSeekers(int $perPage = 10): LengthAwarePaginator;   
    
    public function getAllUsers(int $perPage = 10): LengthAwarePaginator;

    public function getUserById(int $id): ?User;

    public function getEmployerById(int $id): ?User;

    public function getJobSeekerById(int $id): ?User;

    public function getUserByEmail(string $email): ?User;

    public function deleteUser(int $id): bool;

    public function changeEmployerStatus(int $id, string $status): ?User;

    public function changeJobSeekerStatus(int $id, string $status): ?User;
}