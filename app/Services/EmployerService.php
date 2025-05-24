<?php
namespace App\Services;

use App\Models\JobPosting;
use App\Repositories\EmployerRepository;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class EmployerService {
    public function __construct( protected EmployerRepository $employerRepository ) {

    }

    public function getAllEmployers(int $perPage = 10): LengthAwarePaginator {
        $user = auth()->user();

        if(!$user->isAdmin()) {
            throw new UnauthorizedHttpException('Unauthorized! You do not have permission to view this resource.');
        }

        $employers = $this->employerRepository->getAll($perPage);
        return $employers;
    }
}