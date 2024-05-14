<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Post;
use App\Http\Controllers\PostController;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    // Post Index
    public function test_list_of_post(): void
    {
        // Arrange
        Post::factory()->count(15)->create();

        // Act
        $posts = (new PostController)->index();

        // Assert
        $this->assertEquals(15, $posts->count());
    }

    // Post Single Show
    public function test_a_single_post(): void
    {
        // Arrange
        $post = Post::factory()->create([
            'title' => "This is title",
            'slug' => "This is slug",
            'body' => "This is body",
        ]);

        // Act
        $getPost = (new PostController)->show($post->id);

        // Assert
        $this->assertEquals($post->id, $getPost->id);
        $this->assertEquals("This is title", $getPost->title);
    }

    public function test_if_invalid_id_passed(): void
    {
        // Arrange
        $post = Post::factory()->create();

        // Assert
        $this->expectException(ModelNotFoundException::class);

        // Act
        (new PostController)->show(99);
    }

    public function test_it_create_a_new_post(): void
    {
        // Arrange
        $this->assertDatabaseCount('posts', 0);
        $post = [
            'title' => "This is title",
            'slug' => "This is slug",
            'body' => "This is body",
        ];

        // Act
        (new PostController)->store($post);

        // Assert
        $this->assertDatabaseCount('posts', 1);
    }

    public function test_it_deletes_a_specific_post(): void
    {
        // Arrange
        $unTouchPost = Post::factory()->create();
        $deletePost = Post::factory()->create();
        $this->assertDatabaseCount('posts', 2);

        // Act
        (new PostController)->destroy($deletePost->id);

        // Assert
        $this->assertDatabaseCount('posts', 1);
        $this->assertDatabaseCount('posts', 1);
    }
}