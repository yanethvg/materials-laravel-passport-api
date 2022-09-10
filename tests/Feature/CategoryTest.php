<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Category;

class CategoryTest extends TestCase
{
    // refresh database
    use RefreshDatabase;
    // using faker
    use WithFaker;
    /**
     * A basic feature test example.
     *
     * @return void
     */


    public function test_store_product()
    {
        $this->withoutExceptionHandling();
        

        $categoryTest = [
            "name" => $this->faker->lexify('Category ???'),
            "description" => $this->faker->text($maxNbChars = 50)
        ];

        $response =  $this->json('post', '/api/categories', $categoryTest);


        $response->assertStatus(201);

        $category = Category::find($response->original->id);
      
        $this->assertEquals($category->name, $categoryTest["name"]);
        $this->assertEquals($category->description, $categoryTest["description"]);

    }
}
