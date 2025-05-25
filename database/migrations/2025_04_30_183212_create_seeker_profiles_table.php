<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('seeker_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->constrained()->onDelete('cascade');
            $table->string('resume_path')->nullable();
            $table->text('skills')->nullable();
            $table->text('bio')->nullable();
            $table->string('phone')->nullable();
            $table->string('location')->nullable();
            $table->string('desired_job_title')->nullable();
            $table->decimal('expected_salary', 10, 2)->nullable();
            $table->string('employment_type')->nullable();
            $table->date('available_from')->nullable();
            $table->string('portfolio_link')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('github')->nullable();
            $table->text('certifications')->nullable();
            $table->text('languages')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seeker_profiles');
    }
};