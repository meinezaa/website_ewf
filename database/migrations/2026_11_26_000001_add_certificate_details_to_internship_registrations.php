<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (
            Schema::hasTable('internship_registrations')
            && ! Schema::hasColumn('internship_registrations', 'certificate_details')
        ) {
            Schema::table('internship_registrations', function (Blueprint $table): void {
                $table->json('certificate_details')->nullable();
            });
        }
    }

    public function down(): void
    {
        if (
            Schema::hasTable('internship_registrations')
            && Schema::hasColumn('internship_registrations', 'certificate_details')
        ) {
            Schema::table('internship_registrations', function (Blueprint $table): void {
                $table->dropColumn('certificate_details');
            });
        }
    }
};
