<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\Category;

class CategoryTest extends TestCase
{
    // refresh database
    use RefreshDatabase;
    // using faker
    use WithFaker;

    protected $token;

    public function setUp(): void
    {
        parent::setUp();
        $this->token = $this->authenticate();
    }

    protected function authenticate(){
        \Artisan::call('passport:install');

        $user =  User::create([
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('testtest'),
        ]);

        //creating access token
        $token = $user->createToken('authToken')->accessToken;
        return $token;
    }

    public function test_list_categories()
    {
        $this->withoutExceptionHandling();

        $categoriesTest =  Category::factory()->count(10)->create(); // test

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('get', '/api/categories', ['Accept' => 'application/json']);

        $response->assertStatus(200);

        $this->assertCount(10,$categoriesTest);

    }


    public function test_store_category()
    {
        $this->withoutExceptionHandling();
        
        $categoryTest = [
            "name" => $this->faker->lexify('Category ???'),
            "description" => $this->faker->text($maxNbChars = 50)
        ];

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('post', '/api/categories', $categoryTest);


        $response->assertStatus(201);

        $category = Category::find($response->original->id);
      
        $this->assertEquals($category->name, $categoryTest["name"]);
        $this->assertEquals($category->description, $categoryTest["description"]);

    }

    public function test_wrong_store_category()
    {
        
        $categoryTest = [];

        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('post', '/api/categories', $categoryTest);

        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['name','description']);

    }
    public function test_update_category()
    {
        $this->withoutExceptionHandling();
        

        $categoryTest =  Category::factory()->count(1)->create();

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('put', '/api/categories/'.$categoryTest[0]->id,  [
            "description" =>"Testing Update"
        ]);

        $response->assertStatus(200);

        $category = Category::find($response->original->id);
       
        $this->assertEquals($category->description,"Testing Update");

    }
     public function test_wrong_update_category()
    {
        $category_id = 9999;

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('put', '/api/categories/'.$category_id);

        $response->assertNotFound();

    }

    public function test_show_category()
    {
        $this->withoutExceptionHandling();
        
        $categoryTest =  Category::factory()->count(1)->create();

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('get', '/api/categories/'.$categoryTest[0]->id);

        $response->assertStatus(200);

    }
    public function test_wrong_show_category()
    {
        $category_id = 9999;

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('get', '/api/categories/'.$category_id);

        $response->assertNotFound();

    }

    public function test_delete_category()
    {
        $this->withoutExceptionHandling();
        

        $categoryTest =  Category::factory()->count(1)->create();

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('delete', '/api/categories/'.$categoryTest[0]->id);

        $response->assertStatus(200);

    }
    public function test_wrong_delete_category()
    {
        $category_id = 9999;

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('delete', '/api/categories/'.$category_id);

        $response->assertNotFound();

    }
}
