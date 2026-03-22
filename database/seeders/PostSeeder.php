<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (User::query()->count() === 0) {
            User::factory(10)->create();
        }

        $userIds = User::query()->pluck('id');

        foreach ($userIds as $userId) {
            Post::factory(3)->create([
                'user_id' => $userId,
            ]);
        }
    }
}
