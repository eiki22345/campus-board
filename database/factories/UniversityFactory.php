<?php

namespace Database\Factories;

use App\Models\Region;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\University>
 */
class UniversityFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'region_id' => Region::factory(),
            'email_domain' => fake()->unique()->domainName(),
        ];
    }
}
