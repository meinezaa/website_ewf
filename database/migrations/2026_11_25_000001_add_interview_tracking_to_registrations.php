<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private const REGISTRATION_TABLES = [
        'staff_registrations' => 'staff',
        'internship_registrations' => 'internship',
    ];

    public function up(): void
    {
        foreach (self::REGISTRATION_TABLES as $tableName => $type) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                if (! Schema::hasColumn($tableName, 'interview_at')) {
                    $table->dateTime('interview_at')->nullable();
                }
                if (! Schema::hasColumn($tableName, 'interview_information')) {
                    $table->text('interview_information')->nullable();
                }
                if (! Schema::hasColumn($tableName, 'interview_attendance')) {
                    $table->string('interview_attendance', 20)->nullable();
                }
                if (! Schema::hasColumn($tableName, 'interview_confirmed_at')) {
                    $table->dateTime('interview_confirmed_at')->nullable();
                }
            });
        }

        if (! Schema::hasTable('career_application_tracking_events')) {
            Schema::create('career_application_tracking_events', function (Blueprint $table): void {
                $table->id();
                $table->string('application_type', 20);
                $table->unsignedBigInteger('application_id');
                $table->string('event_type', 32);
                $table->string('from_status', 32)->nullable();
                $table->string('to_status', 32)->nullable();
                $table->dateTime('interview_at')->nullable();
                $table->text('interview_information')->nullable();
                $table->string('attendance', 20)->nullable();
                $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamps();
                $table->index(
                    ['application_type', 'application_id', 'created_at'],
                    'career_tracking_events_application_time_index'
                );
            });
        }

        foreach (self::REGISTRATION_TABLES as $tableName => $type) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            foreach (DB::table($tableName)->get(['id', 'status', 'created_at', 'updated_at']) as $application) {
                $createdAt = $application->created_at ?: now()->toDateTimeString();
                DB::table('career_application_tracking_events')->insert([
                    'application_type' => $type,
                    'application_id' => $application->id,
                    'event_type' => 'application_submitted',
                    'to_status' => 'pending',
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt,
                ]);

                if (
                    filled($application->status)
                    && $application->status !== 'pending'
                    && $application->updated_at
                    && $application->updated_at !== $createdAt
                ) {
                    DB::table('career_application_tracking_events')->insert([
                        'application_type' => $type,
                        'application_id' => $application->id,
                        'event_type' => 'status_changed',
                        'from_status' => 'pending',
                        'to_status' => $application->status,
                        'created_at' => $application->updated_at,
                        'updated_at' => $application->updated_at,
                    ]);
                }
            }
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('career_application_tracking_events');

        foreach (array_keys(self::REGISTRATION_TABLES) as $tableName) {
            if (! Schema::hasTable($tableName)) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) use ($tableName): void {
                foreach (['interview_confirmed_at', 'interview_attendance', 'interview_information', 'interview_at'] as $column) {
                    if (Schema::hasColumn($tableName, $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
