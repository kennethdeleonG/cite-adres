<?php

declare(strict_types=1);

namespace App\Domain\Faculty\Models;

use App\Domain\Faculty\Enums\FacultyStatuses;
use App\Domain\Faculty\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;

class Faculty extends Authenticatable implements MustVerifyEmail
{
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'first_name',
        'last_name',
        'address',
        'mobile',
        'gender',
        'designation',
        'email',
        'password',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => FacultyStatuses::class,
        'email_verified_at' => 'datetime',
    ];

    public function sendEmailVerificationNotification(): void
    {
        $this->notify(new VerifyEmail());
    }
}
