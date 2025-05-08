<?php
namespace App\Repositories;

use App\Models\User;
use App\Repositories\Interfaces\EmployerRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployerRepository implements EmployerRepositoryInterface {
    public function register(array $data) {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $user->assignRole('employer');

            $user->companyProfile()->create([
                'company_name' => $data['company_name'],
                'logo' => $data['logo'] ?? null,
                'website' => $data['website'] ?? null,
                'email' => $data['email'],
                'phone' => $data['phone'],
                'address' => $data['address'] ?? null,
                'city' => $data['city'] ?? null,
                'state' => $data['state'] ?? null,
                'country' => $data['country'],
                'zip_code' => $data['zip_code'] ?? null,
                'founded_year' => $data['founded_year'],
                'company_size' => $data['company_size'] ?? null,
                'linkedin' => $data['linkedin'] ?? null,
                'business_description' => $data['business_description'] ?? null,
                'industry_type' => $data['industry_type'],
                'trade_license_number' => $data['trade_license_number'],
            ]);

            return $user;
        });
    }

    public function validateCredentials(User $user, string $password): bool {
        return Hash::check($password, $user->password);
    }

    public function findByEmail(string $email): ?User{
        return User::where('email', $email)->first();
    }
}
