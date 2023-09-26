<?php

declare(strict_types=1);

namespace App\Domain\Faculty\DataTransferObjects;

use Illuminate\Http\UploadedFile;

class FacultyData
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $address,
        public readonly string $gender,
        public readonly string|null $mobile = null,
        public readonly string|null $designation = null,
        public readonly UploadedFile|string|null $image = null,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(...$data);
    }
}
