<?php
namespace App\Services;

use App\Repositories\Interfaces\EmployerRepository;

class EmployerService {
    public function __construct( protected EmployerRepository $employerRepository ) {

    }

    public function registerEmployer( array $data ) {
        return $this->employerRepository->register( $data );
    }
}