<?php
namespace Feature\Api;

use App\Models\User;
use App\Models\Workout;
use App\Models\Exercise;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class WorkoutApiTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('app:store-exercise-data');

        // Create a test user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs($this->user, 'sanctum');
    }


    public function test_user_can_get_all_workouts()
    {
        // Seed the database with some workouts
        Workout::factory()->count(5)->create();

        // Hit the endpoint
        $response = $this->getJson('/api/workouts');
        Log::info($response->getContent());

        // Assert the response
        $response->assertStatus(200)
            ->assertJsonStructure([
                'workouts' => [
                    '*' => [
                        'id',
                        'name',
                        'type',
                        'description',
                        'exercise_ids',
                        'is_custom',
                        'created_at',
                        'updated_at'
                    ]
                ],
                'total',
                'limit',
                'offset'
            ]);
    }

    public function test_user_can_get_workout_by_id()
    {
        // Seed the database with some workouts
        $workouts  = Workout::factory()->count(5)->create();
        $workout = $workouts->first();

        // Hit the endpoint
        $response = $this->getJson("/api/workouts/id/{$workout->id}");

        // Assert the response contains the correct workout and exercises
        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $workout->id,
                'name' => $workout->name,
                'type' => $workout->type,
                'description' => $workout->description,
                'created_at' => $workout->created_at->toISOString(),
                'updated_at' => $workout->updated_at->toISOString(),
            ]);
    }

    public function test_user_can_delete_workout()
    {
        $workout = Workout::factory()->create([
            'is_custom' => true,  // Ensure it's a custom workout
        ]);

        $response = $this->getJson('/api/user');
        $response->assertStatus(200);

        // Hit the endpoint to delete the workout
        $response = $this->deleteJson("/api/workouts/delete/{$workout->id}");

        // Assert the response status
        $response->assertStatus(200);

        // Assert the workout was deleted
        $this->assertDatabaseMissing('workouts', [
            'id' => $workout->id
        ]);
    }

    public function test_user_can_create_workout()
    {
        $workoutData = [
            'name' => 'Test Workout',
            'type' => 'cardio',
            'description' => 'A test workout description',
            'exercise_ids' => Exercise::inRandomOrder()->limit(3)->pluck('id')->toArray()
        ];

        // Hit the endpoint
        $response = $this->postJson('/api/workouts/create', $workoutData);

        // Assert the response status
        $response->assertStatus(201)
            ->assertJsonFragment([
                'name' => 'Test Workout',
                'type' => 'cardio',
                'description' => 'A test workout description'
            ]);

        // Assert the workout was created in the database
        $this->assertDatabaseHas('workouts', [
            'name' => 'Test Workout'
        ]);
    }

    public function test_user_can_get_workout_types()
    {
        // Hit the endpoint
        $response = $this->getJson('/api/workouts/types');

        // Assert the response status and data
        $response->assertStatus(200)
            ->assertJsonStructure([
                'types' => []
            ]);
    }

    public function test_user_can_update_workout()
    {

        $workout = Workout::factory()->create();


        $updatedData = [
            'name' => 'Updated Workout',
            'type' => 'strength',
            'description' => 'Updated workout description',
            'exercise_ids' => Exercise::inRandomOrder()->limit(3)->pluck('id')->toArray()
        ];


        $response = $this->putJson("/api/workouts/update/{$workout->id}", $updatedData);


        $response->assertStatus(200)
            ->assertJsonFragment([
                'name' => 'Updated Workout',
                'type' => 'strength',
                'description' => 'Updated workout description'
            ]);

        $this->assertDatabaseHas('workouts', [
            'id' => $workout->id,
            'name' => 'Updated Workout'
        ]);
    }
}
