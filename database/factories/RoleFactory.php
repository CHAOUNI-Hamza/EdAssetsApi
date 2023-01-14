<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RoleFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            
                'role' => '0' // Admin
                /*'role' => '1', // Generale Manager 
                'role' => '2', // Manager
                'role' => '3', // Accountant
                'role' => '4', // Auditor
                'role' => '5', // Agent Inve
                'role' => '6'*/  // Agent inventaire
            
        ];
    }
}
