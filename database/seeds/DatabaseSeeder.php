<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        factory( \App\User::class , 100 )->create();
        factory( \App\Category::class , 100 )->create();
        factory( \App\Post::class , 10)->create();
        factory( \App\Comment::class , 20 )->create();
    }
}
