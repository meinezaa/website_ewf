<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('career_applications', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 24)->unique();
            $table->string('job_id', 64)->index();
            $table->string('job_title');
            $table->string('company');
            $table->string('full_name');
            $table->string('email')->index();
            $table->string('phone', 40);
            $table->string('location');
            $table->string('headline');
            $table->string('expected_salary', 40)->nullable();
            $table->string('availability', 100)->nullable();
            $table->string('portfolio_url')->nullable();
            $table->text('summary');
            $table->timestamp('privacy_consent_at');
            $table->json('experiences')->nullable();
            $table->json('education')->nullable();
            $table->json('skills')->nullable();
            $table->string('cv_path');
            $table->json('documents')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('career_applications');
    }
};
