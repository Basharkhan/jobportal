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
        Schema::create('job_postings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description');
            $table->foreignId('category_id')->nullable();
            $table->foreignId('location_id')->nullable();
            $table->string('job_type'); 
            $table->decimal('salary_min', 10, 2)->nullable();
            $table->decimal('salary_max', 10, 2)->nullable();   
            $table->string('salary_currency', 3)->default('USD'); 
            $table->string('experience_level'); 
            $table->string('education_level'); 
            $table->date('application_deadline');
            $table->boolean('remote')->default(false); 
            $table->text('benefits')->nullable(); 
            $table->text('requirements')->nullable(); 
            $table->text('responsibilities')->nullable(); 
            $table->enum('approval_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('job_status', ['draft', 'published', 'closed'])->default('draft');
            $table->timestamp('published_at')->nullable(); 
            $table->timestamp('expires_at')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('job_postings');
    }
};