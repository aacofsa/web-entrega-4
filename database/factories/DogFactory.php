<?php

namespace Database\Factories;

use App\Models\Dog;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

class DogFactory extends Factory
{
    protected $model = Dog::class;
    
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->unique()->userName(),
            'description' => fake()->text(128),
            'breed' => fake()->word(),
            'photo' => "https://static.vecteezy.com/system/resources/previews/006/043/051/original/black-dog-silhouette-free-vector.jpg",
            'gender' => fake()->randomElement(['Male', 'Female', "Not Defined"]),
            'password' => Hash::make("123456"), // password
        ];
    }
}
