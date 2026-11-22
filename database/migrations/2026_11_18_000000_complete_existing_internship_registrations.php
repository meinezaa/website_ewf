<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('internship_registrations')) {
            return;
        }

        if (in_array(DB::connection()->getDriverName(), ['mysql', 'mariadb'], true)) {
            DB::statement(
                "ALTER TABLE internship_registrations MODIFY status "
                ."ENUM('pending', 'in_review', 'accepted', 'rejected', 'completed') NULL DEFAULT 'pending'"
            );
        }

        DB::table('internship_registrations')
            ->where('status', 'pending')
            ->update([
                'status' => 'completed',
                'updated_at' => now(),
            ]);
    }

    public function down(): void
    {
        throw new \RuntimeException(
            'This migration changes existing internship statuses. Restore their previous values before rolling it back.'
        );
    }
};
