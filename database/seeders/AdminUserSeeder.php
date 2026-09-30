<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => config('maria.admin.email')],
            [
                'name' => config('maria.admin.name'),
                'password' => config('maria.admin.password'), // hashed by the User model's "hashed" cast
                'email_verified_at' => now(),
            ],
        );
    }
}