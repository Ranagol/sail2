<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<File>
 */
class FileFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'original_name' => $this->faker->word().'.txt',
            'path' => 'uploads/'.rand(1, 100).'/'.md5(random_bytes(16)).'.txt',
            'size' => $this->faker->numberBetween(1024, 5120 * 1024),
        ];
    }
}
