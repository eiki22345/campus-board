<?php

namespace Database\Factories;

use App\Models\MajorCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Board>
 */
class BoardFactory extends Factory
{
  public function definition(): array
  {
    return [
      'name' => fake()->words(3, true),
      'major_category_id' => MajorCategory::factory(),
      'university_id' => null,
    ];
  }

  /**
   * 特定の大学に紐付いたボード
   */
  public function forUniversity(\App\Models\University $university): static
  {
    return $this->state(['university_id' => $university->id]);
  }
}
