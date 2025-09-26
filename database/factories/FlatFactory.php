<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Flat>
 */
class FlatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'flat_number' => $this->faker->unique()->bothify('Flat-###'),
            'building_id' => \App\Models\Building::factory(),
            'owner_name' => $this->faker->name(),
            'owner_phone' => $this->faker->phoneNumber(),
            'owner_email' => $this->faker->unique()->safeEmail(),
            'owner_address' => $this->faker->address(),
            'carpet_area' => $this->faker->randomFloat(2, 300, 2000), 
            'bedrooms' => $this->faker->numberBetween(1, 5),
            'bathrooms' => $this->faker->numberBetween(1, 4),
            'notes' => $this->faker->optional()->paragraph(),
            'tenant_id' => 1, 
            'is_occupied' => $this->faker->boolean(50), 
            'is_active' => $this->faker->boolean(90), 
        ];
    }
}
