<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->lexify('Material ???'),
            "description" => $this->faker->text($maxNbChars = 50),	
            "stock_minim" => $this->faker->numberBetween($min = 1, $max = 100),	
            "is_active" => $this->faker->boolean($chanceOfGettingTrue = 50),
            "unit_measure_id" => $this->faker->numberBetween($min = 1, $max = 10),
            "category_id" => $this->faker->numberBetween($min = 1, $max = 10),
        ];
    }
}
