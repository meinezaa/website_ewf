<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            $table->date('date_of_birth')->nullable();
            $table->string('gender', 32)->nullable();
            $table->string('registration_type', 80)->nullable();
            $table->string('target_position', 180)->nullable();
            $table->string('internship_start_period', 7)->nullable();
            $table->string('internship_duration', 40)->nullable();
            $table->string('school_internship_period', 100)->nullable();
            $table->boolean('cover_letter_required')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            $table->dropColumn([
                'date_of_birth',
                'gender',
                'registration_type',
                'target_position',
                'internship_start_period',
                'internship_duration',
                'school_internship_period',
                'cover_letter_required',
            ]);
        });
    }
};
