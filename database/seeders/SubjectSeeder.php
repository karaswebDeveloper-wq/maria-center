<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $subjects = [
            ['code' => 'AR', 'name' => 'لغة عربية'],
            ['code' => 'MATH', 'name' => 'رياضيات'],
            ['code' => 'SCI', 'name' => 'علوم'],
            ['code' => 'SOC', 'name' => 'دراسات اجتماعية'],
            ['code' => 'EN', 'name' => 'لغة إنجليزية'],
            ['code' => 'FR', 'name' => 'لغة فرنسية'],
            ['code' => 'COMP', 'name' => 'حاسب آلي'],
            ['code' => 'REL', 'name' => 'تربية دينية'],
        ];

        foreach ($subjects as $subject) {
            Subject::query()->updateOrCreate(
                ['code' => $subject['code']],
                ['name' => $subject['name'], 'is_active' => true],
            );
        }
    }
}