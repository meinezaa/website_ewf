<?php

namespace App\Console\Commands;

use App\Auth\FileUserStore;
use App\Models\InternshipRegistration;
use App\Models\Role;
use App\Models\StaffRegistration;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use UnexpectedValueException;

class ImportFileUsers extends Command
{
    protected $signature = 'auth:import-file-users {--dry-run : Preview the import without creating accounts}';

    protected $description = 'Import existing local login accounts into the MySQL users table';

    public function handle(FileUserStore $legacyUsers): int
    {
        $toImport = 0;
        $alreadyPresent = 0;

        foreach ($legacyUsers->all() as $record) {
            $email = isset($record['email']) && is_string($record['email'])
                ? mb_strtolower(trim($record['email']))
                : '';

            if (
                $email === ''
                || ! filter_var($email, FILTER_VALIDATE_EMAIL)
                || ! is_string($record['name'] ?? null)
                || ! is_string($record['password'] ?? null)
                || $record['password'] === ''
            ) {
                throw new UnexpectedValueException('A legacy account is missing valid required fields.');
            }

            $user = User::withTrashed()->where('email', $email)->first();
            if ($user !== null) {
                $alreadyPresent++;
            }

            if ($user?->trashed()) {
                continue;
            }

            if ($this->option('dry-run')) {
                if ($user === null) {
                    $toImport++;
                }

                continue;
            }

            DB::transaction(function () use ($record, $email, $user, &$toImport): void {
                $account = $user ?? User::create([
                    'role_id' => $record['role_id'] ?? Role::query()->firstOrCreate(['role_name' => 'Applicant'])->getKey(),
                    'name' => $record['name'],
                    'email' => $email,
                    'phone' => $record['phone'] ?? null,
                    'gender' => $record['gender'] ?? null,
                    'location' => $record['location'] ?? null,
                    'headline' => $record['headline'] ?? null,
                    'status' => $record['status'] ?? 'active',
                    'password' => $record['password'],
                ]);
                $toImport += $user === null ? 1 : 0;

                foreach ([StaffRegistration::class, InternshipRegistration::class] as $registration) {
                    $registration::query()
                        ->whereNull('applicant_id')
                        ->whereRaw('LOWER(email) = ?', [$email])
                        ->update(['applicant_id' => $account->getKey()]);
                }
            });
        }

        $action = $this->option('dry-run') ? 'would be imported' : 'imported';
        $this->info("Accounts {$action}: {$toImport}. Already present: {$alreadyPresent}.");

        return self::SUCCESS;
    }
}
