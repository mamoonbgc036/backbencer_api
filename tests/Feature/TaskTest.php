<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskTest extends TestCase
{
    use RefreshDatabase;

    protected function authenticate()
    {
        $user = User::factory()->create();
        $this->actingAs($user, 'sanctum');
        return $user;
    }

    public function test_user_can_create_task()
    {
        $this->authenticate();

        $response = $this->postJson('/api/v1/tasks', [
            'title' => 'Test Task',
            'body' => 'This is a test task.',
        ]);

        $response->assertStatus(201)
            ->assertJsonFragment(['title' => 'Test Task']);
    }

    public function test_user_can_view_tasks()
    {
        $user = $this->authenticate();

        Task::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson('/api/v1/tasks');

        $response->assertStatus(200)
            ->assertJsonCount(3);
    }

    public function test_user_can_update_task()
    {
        $user = $this->authenticate();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->putJson("/api/v1/tasks/{$task->id}", [
            'title' => 'Updated Task',
            'body' => 'Updated body text.',
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['title' => 'Updated Task']);
    }

    public function test_user_can_delete_task()
    {
        $user = $this->authenticate();

        $task = Task::factory()->create(['user_id' => $user->id]);

        $response = $this->deleteJson("/api/v1/tasks/{$task->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_can_mark_task_as_completed()
    {
        $user = $this->authenticate();

        $task = Task::factory()->create([
            'user_id' => $user->id,
            'is_completed' => false
        ]);

        $response = $this->putJson("/api/v1/tasks/{$task->id}", [
            'title' => $task->title,
            'body' => $task->body,
            'is_completed' => true,
        ]);

        $response->assertStatus(200)
            ->assertJsonFragment(['is_completed' => true]);
    }
}
