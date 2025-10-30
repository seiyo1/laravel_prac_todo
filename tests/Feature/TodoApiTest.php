<?php

namespace Tests\Feature;

use App\Models\Todo;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class TodoApiTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_guest_cannot_access_todos(): void
    {
        $response = $this->getJson('/api/v1/todos');
        $response->assertUnauthorized();
    }

    public function test_index_returns_authenticated_user_todos(): void
    {
        Passport::actingAs($this->user);

        $ownedTodos = Todo::factory()->count(2)->create([
            'user_id' => $this->user->id,
        ]);
        Todo::factory()->create(['user_id' => User::factory()->create()->id]);

        $response = $this->getJson('/api/v1/todos');

        $response->assertOk()
            ->assertJsonCount(2)
            ->assertJsonFragment(['id' => $ownedTodos->first()->id])
            ->assertJsonFragment(['id' => $ownedTodos->last()->id]);
    }

    public function test_store_creates_todo_for_authenticated_user(): void
    {
        Passport::actingAs($this->user);

        $payload = [
            'title' => '新しいタスク',
            'description' => '詳細',
        ];

        $response = $this->postJson('/api/v1/todos', $payload);

        $response->assertCreated()
            ->assertJsonFragment([
                'title' => '新しいタスク',
                'description' => '詳細',
                'completed' => false,
            ]);

        $this->assertDatabaseHas('todos', [
            'title' => '新しいタスク',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_show_returns_single_todo(): void
    {
        Passport::actingAs($this->user);

        $todo = Todo::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->getJson("/api/v1/todos/{$todo->id}");

        $response->assertOk()
            ->assertJsonFragment([
                'id' => $todo->id,
                'title' => $todo->title,
            ]);
    }

    public function test_update_modifies_todo(): void
    {
        Passport::actingAs($this->user);

        $todo = Todo::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $payload = [
            'title' => '更新後タイトル',
            'completed' => true,
        ];

        $response = $this->putJson("/api/v1/todos/{$todo->id}", $payload);

        $response->assertOk()
            ->assertJsonFragment([
                'title' => '更新後タイトル',
                'completed' => true,
            ]);

        $this->assertDatabaseHas('todos', [
            'id' => $todo->id,
            'title' => '更新後タイトル',
            'completed' => true,
        ]);
    }

    public function test_destroy_deletes_todo(): void
    {
        Passport::actingAs($this->user);

        $todo = Todo::factory()->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->deleteJson("/api/v1/todos/{$todo->id}");

        $response->assertNoContent();

        $this->assertDatabaseMissing('todos', [
            'id' => $todo->id,
        ]);
    }
}
