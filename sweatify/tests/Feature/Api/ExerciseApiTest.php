<?php

namespace Feature\Api;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class ExerciseApiTest extends TestCase
{
    use RefreshDatabase;
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('app:store-exercise-data');
        Log::info('Exercise count: ' . Exercise::count());

        // Create test user and authenticate
        $this->user = User::factory()->create();
        $this->actingAs( $this->user, 'sanctum');
    }

    public function test_user_can_get_exercise_list()
    {

        // Hit the endpoint
        $response = $this->getJson('/api/exercises');

        // Assert status and data format
        $response->assertStatus(200);

        $response->assertJsonStructure([
            'exercises' => [
                '*' => [
                    'id',
                    'name',
                    'gifUrl',
                    'instructions',
                    'target',
                    'bodyPart',
                    'equipment',
                    'secondaryMuscles',
                    'created_at',
                    'updated_at'
                ]
            ],
            'total',
            'limit',
            'offset'
        ]);
    }

    public function test_user_can_get_exercise_by_id()
    {

        // Fetch an exercise from the database
        $exercise = Exercise::first();

        // Test the API response
        $response = $this->getJson("/api/exercises/id/{$exercise->id}");

        // Assert the response contains the correct data
        $response->assertStatus(200)
            ->assertJson([
                'id' => $exercise->id,
                'name' => $exercise->name,
                'target' => $exercise->target,
                'bodyPart' => $exercise->bodyPart,
            ]);
    }

    public function test_user_can_get_exercise_by_name()
    {
        $exercise = Exercise::where('name', 'not like', '%/%')->first();
        $response = $this->getJson("/api/exercises/name/{$exercise->name}");
        $response->assertStatus(200)
        ->assertJsonFragment(['name' => $exercise->name]);
    }

    public function test_user_can_get_body_part_list()
    {
        // Make a request to the endpoint
        $response = $this->getJson('/api/exercises/bodyPartList');

        // Assert the response status
        $response->assertStatus(200);

        // Make sure it's an array
        $response->assertJsonIsArray();
        $this->assertNotEmpty($response);
    }

    public function test_user_can_filter_exercises_by_body_part()
    {
        // Pick a valid body part from the DB
        $bodyPart = Exercise::first()->bodyPart;

        // Make the request
        $response = $this->getJson("/api/exercises/body-part/{$bodyPart}");

        // Assert response is OK and structure is correct
        $response->assertStatus(200)
            ->assertJsonStructure([
                'exercises' => [
                    '*' => [
                        'id',
                        'name',
                        'gifUrl',
                        'instructions',
                        'target',
                        'bodyPart',
                        'equipment',
                        'secondaryMuscles',
                        'created_at',
                        'updated_at',
                    ]
                ],
                'total',
                'limit',
                'offset',
            ]);

        // Assert all returned exercises match the body part
        foreach ($response->json('exercises') as $exercise) {
            $this->assertEquals($bodyPart, $exercise['bodyPart']);
        }
    }

    public function test_user_can_get_equipment_list()
    {
        $response = $this->getJson('/api/exercises/equipmentList');

        $response->assertStatus(200);

        // Response should be a list (array) of strings
        $equipmentList = $response->json();

        $this->assertIsArray($equipmentList);
        $this->assertNotEmpty($equipmentList);
    }

    public function test_user_can_filter_exercises_by_equipment()
    {
        // Pick a known equipment value from your data
        $equipment = 'body weight';

        $response = $this->getJson("/api/exercises/equipment/{$equipment}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'exercises' => [
                '*' => [
                    'id',
                    'name',
                    'gifUrl',
                    'instructions',
                    'target',
                    'bodyPart',
                    'equipment',
                    'secondaryMuscles',
                    'created_at',
                    'updated_at'
                ]
            ],
            'total',
            'limit',
            'offset'
        ]);

        $exercises = $response->json('exercises');

        foreach ($exercises as $exercise) {
            $this->assertEquals($equipment, $exercise['equipment']);
        }
    }

    public function test_user_can_get_target_list()
    {
        $response = $this->getJson('/api/exercises/targetList');

        $response->assertStatus(200);

        $targets = $response->json();

        $this->assertIsArray($targets);
        $this->assertNotEmpty($targets);
    }

    public function test_user_can_filter_exercises_by_target()
    {
        $target = Exercise::whereNotNull('target')->value('target');

        $response = $this->getJson("/api/exercises/target/{$target}");

        $response->assertStatus(200);

        $response->assertJsonStructure([
            'exercises' => [
                '*' => [
                    'id',
                    'name',
                    'gifUrl',
                    'instructions',
                    'target',
                    'bodyPart',
                    'equipment',
                    'secondaryMuscles',
                    'created_at',
                    'updated_at'
                ]
            ],
            'total',
            'limit',
            'offset'
        ]);

        foreach ($response->json('exercises') as $exercise) {
            $this->assertEquals($target, $exercise['target']);
        }
    }

}
