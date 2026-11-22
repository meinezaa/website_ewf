<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('users')) {
            return;
        }

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('role_id')->nullable()->index();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone', 40)->nullable();
            $table->string('location')->nullable();
            $table->string('headline', 180)->nullable();
            $table->string('status')->default('active');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
        });

        if (! Schema::hasTable('ewf_schema_migration_markers')) {
            Schema::create('ewf_schema_migration_markers', function (Blueprint $table) {
                $table->string('migration')->primary();
            });
        }
        DB::table('ewf_schema_migration_markers')->insert([
            'migration' => '2026_11_11_000001_create_users_table',
        ]);
    }

    public function down(): void
    {
        if (
            Schema::hasTable('users')
            && Schema::hasTable('ewf_schema_migration_markers')
            && DB::table('ewf_schema_migration_markers')
                ->where('migration', '2026_11_11_000001_create_users_table')
                ->exists()
        ) {
            Schema::drop('users');
            DB::table('ewf_schema_migration_markers')
                ->where('migration', '2026_11_11_000001_create_users_table')
                ->delete();

            if (DB::table('ewf_schema_migration_markers')->doesntExist()) {
                Schema::drop('ewf_schema_migration_markers');
            }
        }
    }
};
