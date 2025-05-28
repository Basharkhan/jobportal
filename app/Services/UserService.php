<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService {
    public function __construct(protected UserRepository $userRepository) {        
    }

    public function getAllAdmins(int $perPage = 10): LengthAwarePaginator {
        return $this->userRepository->getAllAdmins($perPage);
    }
    
    public function getAllEmployers(int $perPage = 10): LengthAwarePaginator {
        return $this->userRepository->getAllEmployers($perPage);
    }
    
    public function getAllJobSeekers(int $perPage = 10): LengthAwarePaginator {
        return $this->userRepository->getAllJobSeekers($perPage);
    }   

    public function getAllUsers(int $perPage = 10): LengthAwarePaginator {
        $users = $this->userRepository->getAllUsers($perPage);        
        return $users;
    }

    public function getUserById(int $id) {
        $user = $this->userRepository->getUserById($id);
        
        if (!$user) {
            throw new NotFoundHttpException("User with ID {$id} not found.");
        }        

        return $user;
    }

    public function getEmployerById(int $id) {
        $user = $this->userRepository->getEmployerById($id);
        
        if (!$user) {
            throw new NotFoundHttpException("Employer with ID {$id} not found.");
        }

        return $user;
    }

    public function getJobSeekerById(int $id) {
        $user = $this->userRepository->getJobSeekerById($id);
        
        if (!$user) {
            throw new NotFoundHttpException("Job seeker with ID {$id} not found.");
        }

        return $user;
    }

    public function getUserByEmail(string $email) {
        return $this->userRepository->getUserByEmail($email);
    }
    
    public function deleteUser(int $id) {
        $user = $this->getUserById($id);        
        return $user->delete();
    }

    public function changeEmployerStatus(int $id, string $status) {
        $user = $this->getUserById($id);
        
        if (!$user->isEmployer()) {
            throw new NotFoundHttpException("User with ID {$id} is not an employer.");
        }

        return $this->userRepository->changeEmployerStatus($id, $status);
    }

    public function changeJobSeekerStatus(int $id, string $status) {
        $user = $this->getUserById($id);
        
        if (!$user->isJobSeeker()) {
            throw new NotFoundHttpException("User with ID {$id} is not a job seeker.");
        }

        return $this->userRepository->changeJobSeekerStatus($id, $status);
    }
}