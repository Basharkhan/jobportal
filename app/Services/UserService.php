<?php
namespace App\Services;

use App\Repositories\UserRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService {
    public function __construct(protected UserRepository $userRepository) {        
    }

    public function getAllAdmins(int $perPage = 10) {
        return $this->userRepository->getAllAdmins($perPage);
    }
    
    public function getEmployers(int $perPage = 10) {
        return $this->userRepository->getEmployers($perPage);
    }
    
    public function getAllJobSeekers(int $perPage = 10) {
        return $this->userRepository->getAllJobSeekers($perPage);
    }   

    public function getUserById(int $id) {
        $user = $this->userRepository->getUserById($id);
        if (!$user) {
            throw new NotFoundHttpException("User with ID {$id} not found.");
        }
        $user['role'] = $user->getRoleNames();

        return $user;
    }

    public function getUserByEmail(string $email) {
        return $this->userRepository->getUserByEmail($email);
    }
    
    public function deleteUser(int $id) {
        $user = $this->getUserById($id);        
        return $user->delete();
    }
}