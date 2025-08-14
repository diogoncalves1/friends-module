<?php

namespace Database\Seeders;

use App\Models\Friendship;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FriendshipSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Friendship::factory(5)->create();
    }
}
