<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\doctor>
 */
class DoctorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name'=>$this->faker->name,
            'email'=>$this->faker->unique()->email,
            'phone_number'=>$this->faker->numberBetween(100000,1000000),
            'clinic_address'=>$this->faker->address,
            'schedule'=>$this->faker->sentence()
        ];
    }
}
