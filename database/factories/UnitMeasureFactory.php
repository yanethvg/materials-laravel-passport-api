<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class UnitMeasureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "name" => $this->faker->lexify('Measure ???'),
            "description" => $this->faker->text($maxNbChars = 50),
            "abbreviate" => $this->faker->lexify('???'),	
        ];
    }
}
