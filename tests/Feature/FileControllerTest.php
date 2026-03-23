<?php

namespace Tests\Feature;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_index_shows_files_for_authenticated_user(): void
    {
        $file = File::factory()->create(['user_id' => $this->user->id]);
        $otherUserFile = File::factory()->create();

        $response = $this->actingAs($this->user)->get(route('files.index'));

        $response->assertOk();
        $response->assertViewHas('files');
        $this->assertTrue($response->viewData('files')->contains($file));
        $this->assertFalse($response->viewData('files')->contains($otherUserFile));
    }

    public function test_index_displays_empty_list_for_user_with_no_files(): void
    {
        $response = $this->actingAs($this->user)->get(route('files.index'));

        $response->assertOk();
        $response->assertViewHas('files');
        $this->assertCount(0, $response->viewData('files'));
    }

    public function test_create_shows_merged_files_page(): void
    {
        $response = $this->actingAs($this->user)->get(route('files.create'));

        $response->assertOk();
        $response->assertViewIs('files.index');
        $response->assertViewHas('files');
    }

    public function test_download_forbids_unauthorized_user(): void
    {
        $file = File::factory()->create(['user_id' => $this->user->id]);
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->get(route('files.download', $file->id));

        $response->assertForbidden();
    }

    public function test_download_returns_not_found_for_nonexistent_file(): void
    {
        $response = $this->actingAs($this->user)->get(route('files.download', 9999));

        $response->assertNotFound();
    }

    public function test_destroy_deletes_file_for_owner(): void
    {
        $file = File::factory()->create([
            'user_id' => $this->user->id,
            'path' => 'uploads/'.$this->user->id.'/test.txt',
        ]);

        Storage::shouldReceive('delete')
            ->once()
            ->with($file->path)
            ->andReturnTrue();

        // Delete the file
        $response = $this->actingAs($this->user)->delete(route('files.destroy', $file->id));

        $response->assertRedirect(route('files.index'));
        $response->assertSessionHas('success', 'File deleted successfully.');
        $this->assertDatabaseMissing('files', ['id' => $file->id]);
    }

    public function test_destroy_forbids_unauthorized_user(): void
    {
        $file = File::factory()->create(['user_id' => $this->user->id]);
        $otherUser = User::factory()->create();

        $response = $this->actingAs($otherUser)->delete(route('files.destroy', $file->id));

        $response->assertForbidden();
        $this->assertDatabaseHas('files', ['id' => $file->id]);
    }

    public function test_unauthenticated_user_cannot_access_files(): void
    {
        $this->get(route('files.index'))->assertRedirect(route('login'));
        $this->get(route('files.create'))->assertRedirect(route('login'));
        $this->post(route('files.store'), [])->assertRedirect(route('login'));
    }
}
