<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Detection>
 */
class DetectionFactory extends Factory
{
    /** Fecha random */
    private function randDate($init = '2023-04-01', $end = '2023-05-31') {
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
        return [
            'id_tracking' => $this->faker->sha1,
            'clase' => $opciones[array_rand($opciones)],
            'fecha' => $this->randDate(),
        ];
    }
}
