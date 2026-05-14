<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuestionRiasec>
 */
class QuestionRiasecFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'dimension' => $this->faker->randomElement(['R', 'I', 'A', 'S', 'E', 'C']),
            'texte_fr' => $this->faker->sentence(),
            'type_reponse' => 'likert',
            'poids' => 1,
            'ordre' => $this->faker->numberBetween(1, 100),
            'actif' => true,
            'discrimination' => $this->faker->randomFloat(1, 5.0, 10.0),
            'difficulty' => $this->faker->randomFloat(1, -2.0, 2.0),
            'is_reverse' => false,
            'version' => '2.0',
            'is_seed' => false,
        ];
    }
}
