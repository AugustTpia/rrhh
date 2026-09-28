<?php

namespace Database\Factories;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Factory para QA del dashboard con datos realistas locales.
 * Uso: Vacancy::factory()->count(40)->create()
 */
class VacancyFactory extends Factory
{
    protected $model = Vacancy::class;

    public function definition(): array
    {
        $titles = ['Asesor de Ventas', 'Encargado de Almacén', 'Chofer', 'Auxiliar de Inventarios', 'Gerente Regional', 'Auditor Senior', 'Encargado de Sucursal'];
        $branches = ['ORIZABA 1', 'POZA RICA', 'CÓRDOBA 1', 'Xalapa Centro', 'Veracruz 3', 'Coyutla', 'Tuxpan 1'];
        $regions = ['Golfo 1', 'Golfo 2', 'Golfo 3', 'Norte 1', 'Corporativo'];
        $status = $this->faker->randomElement(['activa', 'activa', 'activa', 'cerrada', 'pausada']);
        $created = $this->faker->dateTimeBetween('-400 days', 'now');
        $closed = $status === 'cerrada' ? $this->faker->dateTimeBetween($created, 'now') : null;

        return [
            'folio' => 'VAC-'.date('Y').'-'.strtoupper(Str::random(5)),
            'title' => $this->faker->randomElement($titles),
            'branch' => $this->faker->randomElement($branches),
            'region' => $this->faker->randomElement($regions),
            'reason' => $this->faker->randomElement(['nueva_creacion', 'reemplazo']),
            'type' => $this->faker->randomElement(['publica', 'confidencial']),
            'description' => $this->faker->sentence(),
            'status' => $status,
            'created_at' => $created,
            'updated_at' => $created,
            'closed_at' => $closed,
        ];
    }
}
