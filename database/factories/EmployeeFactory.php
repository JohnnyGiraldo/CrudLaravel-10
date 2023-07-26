<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //crear registros de prueba con info falsa
            'name' => $this->faker->name,
            'email' => $this->faker->email,
            // formato e16
            'phone' => $this->faker->e164PhoneNumber,
            // para el id se utiliza un numero random entre el 1 y 6
            // es decir, se crean de inicio 6 deptos y al azar se asigna el id
            // hacia el empleado
            'department_id' => $this->faker->numberBetween(1,6)
        ];
    }
}
