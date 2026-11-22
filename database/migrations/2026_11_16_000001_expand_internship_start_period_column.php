<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('career_applications', function (Blueprint $table) {
            $table->string('internship_start_period', 10)->nullable()->change();
        });
    }

    public function down(): void
    {
        DB::table('career_applications')
            ->whereNotNull('internship_start_period')
            ->update(['internship_start_period' => DB::raw('substr(internship_start_period, 1, 7)')]);

        Schema::table('career_applications', function (Blueprint $table) {
            $table->string('internship_start_period', 7)->nullable()->change();
        });
    }
};
