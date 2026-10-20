<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CandidateStage;
use App\Models\Hire;
use App\Models\HiringCost;
use App\Models\JobOffer;
use App\Models\Vacancy;
use Illuminate\Database\Seeder;

/**
 * Datos demo para los indicadores de efectividad (Fase 4).
 * Idempotente: se apoya en los folios DEMO01/02/03.
 */
class RecruitmentDemoSeeder extends Seeder
{
    public function run(): void
    {
        $sales = Vacancy::where('folio', 'VAC-2026-DEMO01')->first();
        $driver = Vacancy::where('folio', 'VAC-2026-DEMO02')->first();
        $warehouse = Vacancy::where('folio', 'VAC-2026-DEMO03')->first();

        if (! $sales || ! $driver || ! $warehouse) {
            return;
        }

        $base = [
            'entrevista_rh',
            'test_lucher',
            'persona_bajo_lluvia',
            'psicotest',
            'entrevista_encargado_sucursal',
            'entrega_documentos',
        ];

        // A) Finalista de Asesor de Ventas (no contratado).
        $finalist = $this->candidate($sales, 'Laura Méndez Ríos', 'OCC', 'finalista', now()->subDays(25));
        $this->stages($finalist, $sales, $base, 24, 2);

        // B) Contratado de Encargado de Almacén: finalista + oferta aceptada + 120 días de antigüedad.
        $hired = $this->candidate($warehouse, 'Jorge Castillo Nava', 'Indeed', 'contratado', now()->subDays(130));
        $this->stages($hired, $warehouse, array_merge($base, [
            'entrevista_capacitacion',
            'entrevista_encargado_almacen',
            'visita_domiciliaria',
        ]), 128, 2);

        JobOffer::firstOrCreate(
            ['candidate_id' => $hired->id],
            [
                'vacancy_id' => $warehouse->id,
                'status' => 'aceptada',
                'offered_at' => now()->subDays(126),
                'responded_at' => now()->subDays(124),
            ],
        );

        Hire::firstOrCreate(
            ['candidate_id' => $hired->id],
            [
                'vacancy_id' => $warehouse->id,
                'hire_date' => now()->subDays(120)->toDateString(),
                'termination_date' => null,
            ],
        );

        // C) Candidato de Chofer en proceso: oferta emitida y rechazada.
        $rejected = $this->candidate($driver, 'Diego Ramírez Solís', 'Indeed', 'entrevistas', now()->subDays(10));
        $this->stages($rejected, $driver, array_slice($base, 0, 3), 9, 2);

        JobOffer::firstOrCreate(
            ['candidate_id' => $rejected->id],
            [
                'vacancy_id' => $driver->id,
                'status' => 'rechazada',
                'offered_at' => now()->subDays(4),
                'responded_at' => now()->subDays(3),
            ],
        );

        HiringCost::firstOrCreate(
            ['period' => now()->format('Y-m')],
            ['total_investment' => 15000, 'hires_count' => 1],
        );
    }

    private function candidate(Vacancy $vacancy, string $name, string $source, string $stage, \DateTimeInterface $createdAt): Candidate
    {
        $candidate = Candidate::firstOrCreate(
            ['vacancy_id' => $vacancy->id, 'full_name' => $name],
            [
                'phone' => '555-000-0000',
                'email' => null,
                'source' => $source,
                'salary_expectation' => null,
                'stage' => $stage,
                'notes' => 'Registro demo de indicadores.',
            ],
        );

        $candidate->forceFill(['created_at' => $createdAt, 'updated_at' => $createdAt])->save();

        return $candidate;
    }

    /**
     * @param  array<int, string>  $stages
     */
    private function stages(Candidate $candidate, Vacancy $vacancy, array $stages, int $daysAgoStart, int $spacingDays): void
    {
        foreach ($stages as $index => $stage) {
            CandidateStage::firstOrCreate(
                ['candidate_id' => $candidate->id, 'stage' => $stage],
                [
                    'vacancy_id' => $vacancy->id,
                    'completed_at' => now()->subDays($daysAgoStart - ($index * $spacingDays)),
                ],
            );
        }
    }
}
