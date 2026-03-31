<?php

namespace Database\Factories;

use App\Models\WeeklyPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<WeeklyPlan>
 */
class WeeklyPlanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'title' => fake()->sentence(),
            'expected_output' => fake()->paragraph(),
            'category' => fake()->randomElement(['improvement', 'problem', 'maintenance']),
            'impact_level' => fake()->randomElement(['low', 'medium', 'high']),
            'week_start_date' => now()->startOfWeek(),
            'week_end_date' => now()->endOfWeek(),
            'status' => 'planned',
            'created_by' => User::factory(),
            'updated_by' => User::factory(),
        ];
    }
}
