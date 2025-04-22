<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthProtectionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('app:store-exercise-data');
    }

    public function test_guest_cannot_access_dashboard()
    {
        $response = $this->get('/dashboard');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_profile_edit()
    {
        $response = $this->get('/profile');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_profile_edit()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/profile');
        $response->assertStatus(200);
    }

    public function test_unverified_user_is_redirected_from_verified_routes()
    {
        $user = User::factory()->create(['email_verified_at' => null]);
        $this->actingAs($user);

        $response = $this->get('/dashboard');
        $response->assertStatus(409);
        $response->assertJson(['message' => 'Your email address is not verified.']);
    }

    public function test_guest_cannot_access_exercises()
    {
        $response = $this->get('/exercises');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_exercises()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/exercises');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_create_workout()
    {
        $response = $this->get('/workouts/create');
        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_create_workout()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->get('/workouts/create');
        $response->assertStatus(200);
    }

    public function test_guest_cannot_view_workout()
    {
        $workout = Workout::factory()->create();

        $response = $this->get("/workouts/id/{$workout->id}");

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_workout()
    {
        $user = User::factory()->create();
        $workout = Workout::factory()->create();
        $this->actingAs($user);

        $response = $this->get("/workouts/id/{$workout->id}");
        $response->assertStatus(200);
    }

    public function test_user_cannot_delete_another_users_profile()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->actingAs($userA);
        $response = $this->delete('/profile', ['user_id' => $userB->id]);

        $response->assertRedirect('/');
    }

    public function test_profile_update_requires_name()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->patch('/profile', []);
        $response->assertSessionHasErrors(['name']); // check if 'name' is required
    }
}
