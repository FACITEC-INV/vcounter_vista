<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detection>
 */
class DetectionFactory extends Factory
{
    /** Fecha random */
    private function randDate($init = '2020-01-01', $end = '2023-12-31') {
        $minTime = strtotime($init);
        $maxTime = strtotime($end);
        $randTime = rand($minTime, $maxTime);
        $randDate = date('Y-m-d H:i:s', $randTime);
        return $randDate;
    }
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $opciones = ['car', 'truck'];
        $zonas = ['mapy', 'centro'];
        return [
            'id_zona' => $zonas[array_rand($zonas)],
            'clase' => $opciones[array_rand($opciones)],
            'fecha' => $this->randDate(),
        ];
    }
}
