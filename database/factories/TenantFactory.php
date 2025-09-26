<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Tenant>
 */
class TenantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'date_of_birth' => $this->faker->date(),
            'id_proof_type' => $this->faker->randomElement(['Aadhar', 'PAN', 'Passport', 'Driver License']),
            'id_proof_number' => strtoupper($this->faker->bothify('??######')),
            'flat_id' => null, 
            'assigned_by' => null, 
            'move_in_date' => $this->faker->date(),
            'move_out_date' => null,
            'security_deposit' => $this->faker->randomFloat(2, 5000, 20000),
            'notes' => $this->faker->optional()->paragraph(),
            'is_active' => $this->faker->boolean(80), 
        ];
    }
}
