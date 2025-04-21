<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Exercise;
use App\Models\Workout;
use App\Models\WorkoutExerciseHistory;
use App\Models\WorkoutHistory;

class ExampleDataTest extends TestCase
{
    use RefreshDatabase;

    public function test_factories_and_migrations_work_in_test_db()
    {
        // This will run all migrations in the test DB
        $user = User::factory()->create();
        $workout = Workout::factory()->create();
        $history = WorkoutHistory::factory()->create(['user_id' => $user->id, 'workout_id' => $workout->id]);

        $this->assertDatabaseCount('users', 1);
        $this->assertDatabaseCount('workouts', 1);
        $this->assertDatabaseCount('workout_histories', 1);
    }

}
