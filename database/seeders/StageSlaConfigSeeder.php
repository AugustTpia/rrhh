<?php

namespace Database\Seeders;

use App\Models\PositionRequiredStage;
use App\Models\StageSlaConfig;
use Illuminate\Database\Seeder;

class StageSlaConfigSeeder extends Seeder
{
    public function run(): void
    {
        $defaults = config('recruitment.stage_sla_days', []);

        $stages = collect(array_keys($defaults))
            ->merge(PositionRequiredStage::query()->distinct()->pluck('stage'))
            ->unique()
            ->values();

        foreach ($stages as $stage) {
            StageSlaConfig::firstOrCreate(
                ['stage' => $stage],
                ['sla_days' => (int) ($defaults[$stage] ?? 3)],
            );
        }
    }
}
