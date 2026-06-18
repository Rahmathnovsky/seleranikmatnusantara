<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('career_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('job_category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('description');
            $table->longText('requirements')->nullable();
            $table->longText('benefits')->nullable();
            $table->string('location');
            $table->string('salary_range')->nullable()->comment('e.g. "Rp 4.000.000 - Rp 6.000.000"');
            $table->enum('type', ['fulltime', 'parttime', 'internship', 'contract'])->default('fulltime');
            $table->enum('status', ['open', 'closed', 'draft'])->default('open');
            $table->date('deadline')->nullable();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->timestamps();
        });

        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('career_job_id')->constrained('career_jobs')->cascadeOnDelete();
            $table->string('full_name');
            $table->string('email');
            $table->string('phone');
            $table->text('address')->nullable();
            $table->string('cv_file');
            $table->text('cover_letter')->nullable();
            $table->string('portfolio_url')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('last_education')->nullable();
            $table->string('major')->nullable();
            $table->string('university')->nullable();
            $table->decimal('gpa', 3, 2)->nullable();
            $table->integer('work_experience_years')->nullable();
            $table->enum('status', ['new', 'reviewed', 'shortlisted', 'interview', 'offered', 'rejected'])->default('new');
            $table->text('hr_notes')->nullable();
            $table->timestamp('applied_at')->useCurrent();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
        Schema::dropIfExists('career_jobs');
        Schema::dropIfExists('job_categories');
    }
};
