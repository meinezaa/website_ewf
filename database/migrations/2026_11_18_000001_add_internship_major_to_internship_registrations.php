<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('internship_registrations') && ! Schema::hasColumn('internship_registrations', 'internship_major')) {
            Schema::table('internship_registrations', function (Blueprint $table): void {
                $table->string('internship_major', 150)->nullable()->after('registration_type');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('internship_registrations') && Schema::hasColumn('internship_registrations', 'internship_major')) {
            Schema::table('internship_registrations', function (Blueprint $table): void {
                $table->dropColumn('internship_major');
            });
        }
    }
};
