<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Stage;
use Illuminate\Database\Seeder;

class StageSeeder extends Seeder
{
    public function run(): void
    {
        $stages = [
            ['code' => 'nursery', 'name' => 'الحضانة'],
            ['code' => 'primary', 'name' => 'الابتدائية'],
            ['code' => 'preparatory', 'name' => 'الإعدادية'],
            ['code' => 'secondary', 'name' => 'الثانوية العامة'],
        ];

        foreach ($stages as $stage) {
            Stage::query()->updateOrCreate(
                ['code' => $stage['code']],
                ['name' => $stage['name'], 'is_active' => true],
            );
        }
    }
}