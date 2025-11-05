<?php

namespace Modules\User\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\User\Entities\User;

class UserDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'email' => 'teste@gmail.com',
            'password' => Hash::make('12345678'),
            'name' => 'Teste'
        ]);
        $this->call([UserSeeder::class]);
    }
}
