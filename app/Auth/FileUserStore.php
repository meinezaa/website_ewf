<?php

namespace App\Auth;

use RuntimeException;
use UnexpectedValueException;

class FileUserStore
{
    public function __construct(private readonly string $path) {}

    public function all(): array
    {
        return $this->readUsers();
    }

    private function readUsers(): array
    {
        return $this->withLock(LOCK_SH, function ($file): array {
            $contents = stream_get_contents($file);
            if ($contents === false) {
                throw new RuntimeException('Unable to read local user records.');
            }

            return $this->decode($contents);
        });
    }

    private function withLock(int $operation, callable $callback): mixed
    {
        $directory = dirname($this->path);
        if (! is_dir($directory) && ! mkdir($directory, 0700, true) && ! is_dir($directory)) {
            throw new RuntimeException('Unable to create local user storage.');
        }

        $file = fopen($this->path, 'c+');
        if ($file === false) {
            throw new RuntimeException('Unable to open local user storage.');
        }

        try {
            if (! flock($file, $operation)) {
                throw new RuntimeException('Unable to lock local user storage.');
            }

            return $callback($file);
        } finally {
            flock($file, LOCK_UN);
            fclose($file);
        }
    }

    private function decode(string $contents): array
    {
        if ($contents === '') {
            return [];
        }

        $users = json_decode($contents, true, 512, JSON_THROW_ON_ERROR);
        if (! is_array($users)) {
            throw new UnexpectedValueException('Local user records must be a JSON object.');
        }

        return $users;
    }
}
