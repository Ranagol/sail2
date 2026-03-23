<?php

namespace Database\Factories;

use App\Models\Post;
use App\Models\User;
use Faker\Factory as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = FakerFactory::create();

        return [
            'user_id' => User::factory(),
            'title' => $faker->sentence(6),
            'content' => $faker->paragraph(4),
        ];
    }
}
