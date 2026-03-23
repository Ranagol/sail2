<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
            'original_name' => 'file-'.Str::lower(Str::random(8)).'.txt',
            'path' => 'uploads/'.random_int(1, 100).'/'.md5(random_bytes(16)).'.txt',
            'size' => random_int(1024, 5120 * 1024),
        ];
    }
}
