<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\Job;
use App\Models\Post;
use App\Models\Tag;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        User::factory(100)->create();

        Employee::factory(30)->create();
        Job::factory(400)->create();

        Post::factory(50)->create();
        Tag::factory(20)->create();
    }
}
