<?php

declare(strict_types=1);

namespace App\Domain\Faculty\DataTransferObjects;

class FacultyData
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $password,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }
}
