<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('internship_registrations')) {
            return;
        }

        Schema::table('internship_registrations', function (Blueprint $table): void {
            if (! Schema::hasColumn('internship_registrations', 'certificate_path')) {
                $table->string('certificate_path')->nullable();
            }

            if (! Schema::hasColumn('internship_registrations', 'loa_path')) {
                $table->string('loa_path')->nullable();
            }
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('internship_registrations')) {
            return;
        }

        Schema::table('internship_registrations', function (Blueprint $table): void {
            if (Schema::hasColumn('internship_registrations', 'loa_path')) {
                $table->dropColumn('loa_path');
            }

            if (Schema::hasColumn('internship_registrations', 'certificate_path')) {
                $table->dropColumn('certificate_path');
            }
        });
    }
};
