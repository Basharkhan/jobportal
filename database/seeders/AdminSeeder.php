<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class AdminSeeder extends Seeder {
    /**
    * Run the database seeds.
    */

    public function run(): void {

        User::createOrFirst( [
            'name' => 'Thor',
            'email' => 'admin@jobportal.com',
            'password' => bcrypt( '12345678' ),
            'email_verified_at' => now(),
        ] )->assignRole( 'admin' );
    }
}
