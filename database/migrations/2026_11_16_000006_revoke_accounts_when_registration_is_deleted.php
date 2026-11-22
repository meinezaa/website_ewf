<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const TABLES = [
        'staff_registrations' => 'ewf_staff_registration_revoke_login',
        'internship_registrations' => 'ewf_internship_registration_revoke_login',
    ];

    public function up(): void
    {
        if (! Schema::hasTable('users')) {
            throw new RuntimeException('The users table must exist before account revocation triggers are installed.');
        }

        $driver = DB::connection()->getDriverName();
        if (! in_array($driver, ['mysql', 'sqlite'], true)) {
            throw new RuntimeException('Account revocation triggers are supported only on MySQL and SQLite.');
        }

        foreach (self::TABLES as $table => $trigger) {
            if (! Schema::hasTable($table)) {
                throw new RuntimeException('The '.$table.' table must exist before account revocation triggers are installed.');
            }

            if ($driver === 'mysql') {
                DB::unprepared(
                    "CREATE TRIGGER {$trigger} AFTER DELETE ON {$table} FOR EACH ROW ".
                    "UPDATE users SET status = 'inactive', deleted_at = CURRENT_TIMESTAMP ".
                    'WHERE id = OLD.applicant_id OR LOWER(email) = LOWER(OLD.email)'
                );
            } else {
                DB::unprepared(
                    "CREATE TRIGGER {$trigger} AFTER DELETE ON {$table} BEGIN ".
                    "UPDATE users SET status = 'inactive', deleted_at = CURRENT_TIMESTAMP ".
                    'WHERE id = OLD.applicant_id OR LOWER(email) = LOWER(OLD.email); END'
                );
            }
        }
    }

    public function down(): void
    {
        foreach (self::TABLES as $trigger) {
            DB::unprepared('DROP TRIGGER IF EXISTS '.$trigger);
        }
    }
};
