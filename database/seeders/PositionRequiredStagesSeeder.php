<?php

namespace Database\Seeders;

use App\Models\PositionRequiredStage;
use Illuminate\Database\Seeder;

class PositionRequiredStagesSeeder extends Seeder
{
    public function run(): void
    {
        $base = [
            'entrevista_rh',
            'test_lucher',
            'persona_bajo_lluvia',
            'psicotest',
            'entrevista_encargado_sucursal',
            'entrega_documentos',
        ];

        $mapping = [
            'Asesor de Ventas' => [],
            'Auxiliar de Almacén' => [
                'entrevista_encargado_almacen',
                'visita_domiciliaria',
            ],
            'Auxiliar de Inventarios' => [
                'entrevista_encargado_almacen',
                'visita_domiciliaria',
            ],
            'Auxiliar de Limpieza' => [
                'entrevista_capacitacion',
                'entrevista_encargado_almacen',
                'entrevista_direccion_rh',
                'entrevista_gerente_regional',
                'visita_domiciliaria',
            ],
            'Caja Administrativa' => [
                'entrevista_capacitacion',
                'entrevista_encargado_almacen',
                'entrevista_direccion_rh',
                'entrevista_gerente_regional',
                'estudio_socioeconomico',
                'visita_domiciliaria',
            ],
            'Chofer' => [
                'entrevista_capacitacion',
                'entrevista_encargado_almacen',
                'entrevista_direccion_rh',
                'entrevista_gerente_regional',
                'prueba_manejo',
                'estudio_socioeconomico',
                'visita_domiciliaria',
            ],
            'Encargado de Almacén' => [
                'entrevista_capacitacion',
                'entrevista_encargado_almacen',
                'visita_domiciliaria',
            ],
            'Encargado de Sucursal' => [
                'entrevista_capacitacion',
                'entrevista_encargado_almacen',
                'entrevista_direccion_rh',
                'entrevista_gerente_regional',
                'visita_domiciliaria',
            ],
            'Gerente Regional' => [
                'entrevista_capacitacion',
                'entrevista_encargado_almacen',
                'entrevista_direccion_rh',
                'entrevista_gerente_regional',
                'visita_domiciliaria',
            ],
            'Montacarguista' => [
                'entrevista_capacitacion',
                'entrevista_encargado_almacen',
                'entrevista_direccion_rh',
                'entrevista_gerente_regional',
                'visita_domiciliaria',
            ],
        ];

        foreach ($mapping as $title => $extra) {
            foreach (array_unique(array_merge($base, $extra)) as $stage) {
                PositionRequiredStage::firstOrCreate([
                    'position_title' => $title,
                    'stage' => $stage,
                ]);
            }
        }
    }
}
