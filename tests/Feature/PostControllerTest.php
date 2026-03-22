<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    #[\Override]
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /**
     * @return array<string, mixed>
     */
    private function validPayload(): array
    {
        return [
            'title' => 'My first post',
            'content' => 'This is the body of my first post.',
        ];
    }

    public function test_index_shows_only_authenticated_users_posts(): void
    {
        $ownPost = Post::factory()->create(['user_id' => $this->user->id]);
        $otherPost = Post::factory()->create();

        $response = $this->actingAs($this->user)->get(route('posts.index'));

        $response->assertOk();
        $response->assertViewHas('posts');
        $this->assertTrue($response->viewData('posts')->contains($ownPost));
        $this->assertFalse($response->viewData('posts')->contains($otherPost));
    }

    public function test_create_page_loads_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->get(route('posts.create'));

        $response->assertOk();
        $response->assertViewIs('posts.create');
    }

    public function test_store_creates_post_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)
            ->post(route('posts.store'), $this->validPayload());

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success', 'Post created successfully.');

        $this->assertDatabaseHas('posts', [
            'user_id' => $this->user->id,
            'title' => 'My first post',
            'content' => 'This is the body of my first post.',
        ]);
    }

    public function test_create_demo_posts_creates_ten_posts_for_authenticated_user(): void
    {
        $response = $this->actingAs($this->user)->post(route('posts.demo.create'));

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success', '10 demo posts created successfully.');
        $this->assertDatabaseCount('posts', 10);
        $this->assertEquals(10, Post::query()->where('user_id', $this->user->id)->count());
    }

    public function test_store_validates_required_fields(): void
    {
        $response = $this->actingAs($this->user)
            ->from(route('posts.create'))
            ->post(route('posts.store'), [
                'title' => '',
                'content' => '',
            ]);

        $response->assertRedirect(route('posts.create'));
        $response->assertInvalid(['title', 'content']);
    }

    public function test_show_displays_owned_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('posts.show', $post));

        $response->assertOk();
        $response->assertViewIs('posts.show');
        $response->assertViewHas('post', $post);
    }

    public function test_show_forbids_access_to_other_users_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->get(route('posts.show', $post));

        $response->assertForbidden();
    }

    public function test_edit_displays_form_for_owned_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->get(route('posts.edit', $post));

        $response->assertOk();
        $response->assertViewIs('posts.edit');
        $response->assertViewHas('post', $post);
    }

    public function test_update_modifies_owned_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->put(route('posts.update', $post), [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]);

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success', 'Post updated successfully.');

        $this->assertDatabaseHas('posts', [
            'id' => $post->id,
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]);
    }

    public function test_update_forbids_modifying_other_users_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->put(route('posts.update', $post), [
            'title' => 'Updated title',
            'content' => 'Updated content',
        ]);

        $response->assertForbidden();
    }

    public function test_destroy_deletes_owned_post(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->delete(route('posts.destroy', $post));

        $response->assertRedirect(route('posts.index'));
        $response->assertSessionHas('success', 'Post deleted successfully.');
        $this->assertDatabaseMissing('posts', ['id' => $post->id]);
    }

    public function test_destroy_forbids_deleting_other_users_post(): void
    {
        $post = Post::factory()->create();

        $response = $this->actingAs($this->user)->delete(route('posts.destroy', $post));

        $response->assertForbidden();
        $this->assertDatabaseHas('posts', ['id' => $post->id]);
    }

    public function test_guest_cannot_access_posts_routes(): void
    {
        $post = Post::factory()->create();

        $this->get(route('posts.index'))->assertRedirect(route('login'));
        $this->get(route('posts.create'))->assertRedirect(route('login'));
        $this->post(route('posts.store'), $this->validPayload())->assertRedirect(route('login'));
        $this->post(route('posts.demo.create'))->assertRedirect(route('login'));
        $this->get(route('posts.show', $post))->assertRedirect(route('login'));
        $this->get(route('posts.edit', $post))->assertRedirect(route('login'));
        $this->put(route('posts.update', $post), $this->validPayload())->assertRedirect(route('login'));
        $this->delete(route('posts.destroy', $post))->assertRedirect(route('login'));
    }

    public function test_update_validates_required_fields(): void
    {
        $post = Post::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->from(route('posts.edit', $post))
            ->put(route('posts.update', $post), [
                'title' => '',
                'content' => '',
            ]);

        $response->assertRedirect(route('posts.edit', $post));
        $response->assertInvalid(['title', 'content']);
    }
}
