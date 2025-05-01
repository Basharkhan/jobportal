<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder {
    /**
    * Run the database seeds.
    */

    public function run(): void {
        // Reset cached roles and permissions
        app()[ PermissionRegistrar::class ]->forgetCachedPermissions();

        // Create permissions
        $this->createPermissions();

        // Create roles and assign permissions
        $this->createRoles();
    }

    protected function createPermissions() {

        // Job-related permissions
        Permission::firstOrCreate( [ 'name' => 'view_jobs' ] );
        Permission::firstOrCreate( [ 'name' => 'create_jobs' ] );
        Permission::firstOrCreate( [ 'name' => 'edit_jobs' ] );
        Permission::firstOrCreate( [ 'name' => 'delete_jobs' ] );
        Permission::firstOrCreate( [ 'name' => 'publish_jobs' ] );
        Permission::firstOrCreate( [ 'name' => 'apply_jobs' ] );

        // Application-related permissions
        Permission::firstOrCreate( [ 'name' => 'view_applications' ] );
        Permission::firstOrCreate( [ 'name' => 'create_applications' ] );
        Permission::firstOrCreate( [ 'name' => 'edit_applications' ] );
        Permission::firstOrCreate( [ 'name' => 'delete_applications' ] );
        Permission::firstOrCreate( [ 'name' => 'manage_applications' ] );

        // Company/profile permissions
        Permission::firstOrCreate( [ 'name' => 'manage_company_profile' ] );
        Permission::firstOrCreate( [ 'name' => 'manage_job_seeker_profile' ] );

        // Admin permissions
        Permission::firstOrCreate( [ 'name' => 'manage_users' ] );
        Permission::firstOrCreate( [ 'name' => 'manage_categories' ] );
        Permission::firstOrCreate( [ 'name' => 'manage_all_content' ] );
    }

    protected function createRoles() {
        // Super Admin - has all permissions
        $admin = Role::firstOrCreate( [ 'name' => 'super_admin' ] );
        $admin->givePermissionTo( Permission::all() );

        // Employer Role
        $employer = Role::firstOrCreate( [ 'name' => 'employer' ] );
        $employer->givePermissionTo( [
            'create_jobs',
            'edit_jobs',
            'delete_jobs',
            'publish_jobs',
            'view_applications',
            'manage_applications',
            'manage_company_profile'
        ] );

        // Job Seeker Role
        $jobSeeker = Role::firstOrCreate( [ 'name' => 'job_seeker' ] );
        $jobSeeker->givePermissionTo( [
            'view_jobs',
            'apply_jobs',
            'create_applications',
            'manage_job_seeker_profile'
        ] );

        // Moderator Role ( optional )
        $moderator = Role::firstOrCreate( [ 'name' => 'moderator' ] );
        $moderator->givePermissionTo( [
            'view_jobs',
            'edit_jobs',
            'publish_jobs',
            'view_applications',
            'manage_applications'
        ] );

    }
}
