<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('roles')) {
            return;
        }

        Schema::create('roles', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('role_name', 50)->unique();
        });
    }

    public function down(): void
    {
        // Existing role records may already be referenced by user accounts.
    }
};
