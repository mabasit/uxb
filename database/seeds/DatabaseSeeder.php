<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);

        factory(Uxbert\Models\User::class, 50)->create()->each(function ($u) {
		    $u->posts()->save(factory(Uxbert\Models\Post::class)->make());
		});

    }
}
