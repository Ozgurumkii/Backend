<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->name,
            'surname' => $this->faker->name,
            'email' => 'admin@admin.com', //$this->faker->unique()->safeEmail
            'email_verified_at' => now(),
            'password' => bcrypt("123456"), // password
            'usercode' => '1',
            'isregister' => '1',
            'isadmin' => 1,
            'remember_token' => Str::random(10),
        ];
    }
}
