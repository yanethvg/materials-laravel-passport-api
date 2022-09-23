<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
//models
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Material;
use App\Models\UnitMeasure;
use App\Models\Category;

class MaterialTest extends TestCase
{
    // refresh database
     use RefreshDatabase;
    // using faker
     use WithFaker;

     protected $token;
     protected $categoriesTest;
     protected $unitMeasuresTest;

     public function setUp(): void
     {
        parent::setUp();
        $this->token = $this->authenticate();
        $this->categoriesTest =  Category::factory()->count(10)->create();
        $this->unitMeasuresTest =  UnitMeasure::factory()->count(10)->create();
     }
 
     protected function authenticate(){
        \Artisan::call('passport:install');
 
        //creating permission
        Permission::create(['name' => 'categories']);
        //creating role
        $role = Role::create(['name' => 'boss']);
        $role->givePermissionTo('categories');
        $user =  User::create([
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('testtest'),
        ]);

        $user->assignRole($role);
 
        //creating access token
        $token = $user->createToken('authToken')->accessToken;
        return $token;
     }
    public function test_list_materials()
    {
        $this->withoutExceptionHandling();

        $materialsTest =  Material::factory()->count(10)->create(); // test
 
        $response = $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('get', '/api/materials', ['Accept' => 'application/json']);
 
        $response->assertStatus(200);
        $response->assertJsonStructure(['data', 'links', 'meta']);
    }
    public function test_wrong_list_materials_unautorized()
    {
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. 'swdqwdqwedwehj',
            ])->json('get', '/api/materials', ['Accept' => 'application/json']);

       $response->assertUnauthorized();
       $response->assertJsonStructure(['error']);

    }
    public function test_store_material()
    {
        $this->withoutExceptionHandling();
        
        $materialTest = [
            "name" => $this->faker->lexify('Material ???'),
            "description" => $this->faker->text($maxNbChars = 50),
            "stock_minim" => $this->faker->numberBetween($min = 1, $max = 10),
            "is_active" => $this->faker->boolean,
            "unit_measure_id" => $this->unitMeasuresTest->random()->id,
            "category_id" => $this->categoriesTest->random()->id,
        ];

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('post', '/api/materials', $materialTest);


        $response->assertStatus(201);

        $material = Material::with('measure', 'category')->find($response->original->id);
        
        $this->assertEquals($material->name, $materialTest["name"]);
        $this->assertEquals($material->description, $materialTest["description"]);
        $this->assertEquals($material->stock_minim, $materialTest["stock_minim"]);
        $this->assertEquals($material->is_active, $materialTest["is_active"]);
        $this->assertEquals($material->measure->id, $materialTest["unit_measure_id"]);
        $this->assertEquals($material->category->id, $materialTest["category_id"]);
    }
    public function test_wrong_store_material()
     {
         $unitMeasureTest = [
            "stock_minim" => $this->faker->numberBetween($min = 1, $max = 10),
            "is_active" => $this->faker->boolean,
            "unit_measure_id" => $this->unitMeasuresTest->random()->id,
            "category_id" => $this->categoriesTest->random()->id,
         ];
 
         $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('post', '/api/unitmeasures', $unitMeasureTest);

         $response->assertUnprocessable();

         $response->assertJsonValidationErrors(['name', 'description']);
 
     }
     public function test_update_material()
     {
        $this->withoutExceptionHandling();
 
        $materialTest = Material::factory()->count(1)->create();
 
        $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('put', '/api/materials/'.$materialTest[0]->id,  [
             "description" =>"Testing Update"
         ]);
 
        $response->assertStatus(200);
 
        $material = Material::find($response->original->id);
        
        $this->assertEquals($material->description,"Testing Update");
 
     }
     public function test_wrong_id_update_material()
     {
        $material_id = 9999;
        $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('put', '/api/materials/'.$material_id,  [
             "description" =>"Testing Update"
        ]);
 
        $response->assertNotFound();
    }
    public function test_show_material()
    {
        $this->withoutExceptionHandling();
        
        $materialTest = Material::factory()->count(1)->create();

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('get', '/api/materials/'.$materialTest[0]->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'id',
            'name',
            'description',
            'stock_minim',
            'is_active',
            'measure',
            'category',
        ]]);
    }
    public function test_wrong_show_material()
    {
        $id =99999;

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('get', '/api/materials/'.$id);

        $response->assertNotFound();
    }
    public function test_delete_material()
    {
        $this->withoutExceptionHandling();

        $materialTest = Material::factory()->count(1)->create();

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('delete', '/api/materials/'.$materialTest[0]->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'id',
            'name',
            'description',
            'stock_minim',
            'is_active',
            'measure',
            'category'
        ]]);
    }
    public function test_wrong_delete_material()
    {
        $id = 9999;

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('delete', '/api/materials/'.$id);

        $response->assertNotFound();

    }

    
}
