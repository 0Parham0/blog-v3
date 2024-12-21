<?php

namespace Database\Factories;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\blog>
 */
class blogFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $user = DB::table('users')->inRandomOrder()->first();

        return [
            'title' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'user_id' => $user->id
        ];
    }
}
