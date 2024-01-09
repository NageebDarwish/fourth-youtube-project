<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => "admin",
            'email' => 'admin@gmail.com',
            'email_verified_at' => now(),
            'role' => '1995',
            'password' => Hash::make('admin123$%'), // password
            'remember_token' => Str::random(10),
        ];
    }

    public function withToken()
    {
        return $this->afterCreating(function ($user) {
            $user->createToken('token')->accessToken;
        });
    }
    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
