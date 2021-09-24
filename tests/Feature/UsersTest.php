<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersTest extends TestCase
{
    use RefreshDatabase;
    
    public function test_it_shows_user_edit_form()
    {
        $user = User::factory()->create();

        $response = $this->get(route('users.edit', $user));
        $response->assertSessionHasNoErrors();
        $response->assertStatus(200);
        $response->assertViewHas('user', $user);
        $response->assertViewHas('roles', Role::all());
        $response->assertViewIs('users.edit');
    }

    public function test_it_returns_validation_errors()
    {
        $user = User::factory()->create();

        $this->patch(route('users.update', $user), [
                'email' => 'S',
                'name' => '',
                'role' => 'ghost'
            ]
        )->assertSessionHasErrors(['email', 'name', 'role']);

        $this->assertDatabaseHas('users', $user->fresh()->toArray());
    }

    public function test_it_edits_user()
    {
        $roleViewer = Role::factory()->viewer()->create();
        $roleAdmin = Role::factory()->admin()->create();
        $user = User::factory()->create([
            'role_id' => $roleViewer->id
        ]);

        $this->patch(route('users.update', $user), [
                'email' => 'editedmail@mail.com',
                'name' => 'John Test',
                'role' => $roleAdmin->name
            ]
        )->assertRedirect(route('users.index'))
            ->assertSessionHasNoErrors()
            ->assertSessionHas('message', "User id {$user->id} was edited.");

        $this->assertDatabaseHas('users', $user->fresh()->toArray());
    }

    public function test_it_fails_to_edit_user_with_existing_email()
    {
        $userA = User::factory()->create();
        $userB = User::factory()->create();

        $this->patch(route('users.update', $userA), [
                'name' => $userA->name,
                'email' => $userB->email,
                'role' => $userA->role->name
            ]
        )->assertSessionHasErrors(['email']);

        $this->assertDatabaseHas('users', $userA->fresh()->toArray());
        $this->assertDatabaseHas('users', $userB->fresh()->toArray());
    }
}
