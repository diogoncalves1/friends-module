<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\User\Entities\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Friendship>
 */
class FriendshipFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'sender_id' => User::pluck('id')->random(),
            'receiver_id' => User::pluck('id')->random(),
            'status' => $this->faker->randomElement(['pending', 'blocked', 'accepted', 'declined'])
        ];
    }
}
