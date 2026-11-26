<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('internship_registrations') || Schema::hasColumn('internship_registrations', 'loa_details')) {
            return;
        }

        Schema::table('internship_registrations', function (Blueprint $table): void {
            $table->json('loa_details')->nullable()->after('loa_path');
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('internship_registrations') || ! Schema::hasColumn('internship_registrations', 'loa_details')) {
            return;
        }

        Schema::table('internship_registrations', function (Blueprint $table): void {
            $table->dropColumn('loa_details');
        });
    }
};
