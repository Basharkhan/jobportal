<?php
namespace App\Services;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

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

    public function updateEmployerProfile(int $id, array $data): ?User {
        $user = $this->getUserById($id);

        if (!$user->isEmployer()) {
            throw new NotFoundHttpException("User with ID {$id} is not an employer.");
        }

        if (isset($data['name'])) {
            $user->name = $data['name'];
            $user->save();
        }

        $companyData = Arr::except($data, ['name']);

        if (isset($data['logo']) && $data['logo'] instanceof UploadedFile) {
            $companyProfile = $user->companyProfile;

            if ($companyProfile->logo && Storage::disk('public')->exists('logos/' . $companyProfile->logo)) {
                Storage::disk('public')->delete('logos/' . $companyProfile->logo);
            }

            $extension = $data['logo']->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $data['logo']->storeAs('logos', $filename, 'public');
            $companyData['logo'] = $filename;
        }

        return $this->userRepository->updateEmployerProfile($user, $companyData);
    }


    public function updateJobSeekerProfile(int $id, array $data): ?User {
        $user = $this->getUserById($id);

        if (!$user->isJobSeeker()) {
            throw new NotFoundHttpException("User with ID {$id} is not a job seeker.");
        }

        if (isset($data['name'])) {
            $user->name = $data['name'];
            $user->save();
        }

        $seekerData = Arr::except($data, ['name']);

        if (isset($data['resume']) && $data['resume'] instanceof UploadedFile) {
            $seekerProfile = $user->seekerProfile;

            if ($seekerProfile->resume && Storage::disk('public')->exists('resumes/' . $seekerProfile->resume)) {
                Storage::disk('public')->delete('resumes/' . $seekerProfile->resume);
            }

            $extension = $data['resume']->getClientOriginalExtension();
            $filename = Str::uuid() . '.' . $extension;
            $data['resume']->storeAs('resumes', $filename, 'public');
            $seekerData['resume'] = $filename;
        }

        return $this->userRepository->updateJobSeekerProfile($user, $seekerData);
    }
}