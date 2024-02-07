<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NationalTeam>
 */
class NationalTeamFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "lang" => Str::random(3),
            'federation' => fake()->randomElement(['AFRICA','ASIA','CONCACAF','CONMEBOL','UEFA','ANFITRION']),
        ];
    }
}
