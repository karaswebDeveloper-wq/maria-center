<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\Grade;
use App\Models\Stage;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    /** @var array<string, list<string>> grade names per stage code, in order */
    private const GRADES = [
        'nursery' => ['KG1', 'KG2'],
        'primary' => ['الأول', 'الثاني', 'الثالث', 'الرابع', 'الخامس', 'السادس'],
        'preparatory' => ['الأول', 'الثاني', 'الثالث'],
        'secondary' => ['الأول', 'الثاني', 'الثالث'],
    ];

    public function run(): void
    {
        foreach (self::GRADES as $stageCode => $gradeNames) {
            $stage = Stage::query()->where('code', $stageCode)->first();

            if (! $stage) {
                $this->command->warn("Stage [{$stageCode}] not found — run StageSeeder first. Skipping its grades.");

                continue;
            }

            foreach ($gradeNames as $index => $name) {
                Grade::query()->updateOrCreate(
                    ['stage_id' => $stage->id, 'code' => (string) ($index + 1)],
                    [
                        'name' => $name,
                        'sort_order' => $index + 1,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}