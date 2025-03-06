<?php

namespace Database\Factories;

use App\Models\Exercise;
use App\Models\User;
use App\Models\Workout;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\WorkoutHistory>
 */
class WorkoutHistoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::inRandomOrder()->first()->id, // Véletlenszerű user kiválasztása
            'exercise_id' => Exercise::inRandomOrder()->first()->id, // Véletlenszerű exercise kiválasztása
            'workout_id' => Workout::inRandomOrder()->first()->id, // Véletlenszerű workout kiválasztása
            'reps' => $this->faker->numberBetween(5, 20), // Véletlenszerű ismétlésszám
            'weight' => $this->faker->optional()->numberBetween(20, 100), // Véletlenszerű súly (vagy null)
        ];
    }
}
