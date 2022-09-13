<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use App\Models\User;
use App\Models\UnitMeasure;

class UnitMeasureTest extends TestCase
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
 
     public function test_list_measures()
     {
         $this->withoutExceptionHandling();
 
         $unitMeasuresTest =  UnitMeasure::factory()->count(10)->create(); // test
 
         $response = $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('get', '/api/unitmeasures', ['Accept' => 'application/json']);
 
         $response->assertStatus(200);
 
         $this->assertCount(10,$unitMeasuresTest);
 
     }
     public function test_wrong_list_measures_unautorized()
     {
 
         $response = $this->withHeaders([
             'Authorization' => 'Bearer '. 'swdqwdqwedwehj',
             ])->json('get', '/api/unitmeasures', ['Accept' => 'application/json']);
 
        $response->assertUnauthorized();
        $response->assertJsonStructure(['message']);
 
     }
     public function test_store_unit_measure()
     {
         $this->withoutExceptionHandling();
         
         $unitMeasureTest = [
             "name" => $this->faker->lexify('UnitMeasure ???'),
             "description" => $this->faker->text($maxNbChars = 50),
             "abbreviate" => $this->faker->lexify('???'),
         ];
 
         $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('post', '/api/unitmeasures', $unitMeasureTest);
 
 
         $response->assertStatus(201);
         
 
         $unitMeasure = UnitMeasure::find($response->original->id);
       
         $this->assertEquals($unitMeasure->name, $unitMeasureTest["name"]);
         $this->assertEquals($unitMeasure->description, $unitMeasureTest["description"]);
         $this->assertEquals($unitMeasure->abbreviate, $unitMeasureTest["abbreviate"]);
 
     }
     public function test_wrong_store_unit_measure()
     {
         $unitMeasureTest = [
             "name" => $this->faker->lexify('UnitMeasure ???'),
             "description" => $this->faker->text($maxNbChars = 50),
         ];
 
         $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('post', '/api/unitmeasures', $unitMeasureTest);

         $response->assertUnprocessable();

         $response->assertJsonValidationErrors(["abbreviate"]);
 
     }
     public function test_update_unit_measure()
     {
        $this->withoutExceptionHandling();
 
        $unitMeasureTest =  UnitMeasure::factory()->count(1)->create();
 
        $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('put', '/api/unitmeasures/'.$unitMeasureTest[0]->id,  [
             "description" =>"Testing Update"
         ]);
 
        $response->assertStatus(200);
 
        $unitMeasure = UnitMeasure::find($response->original->id);
        
        $this->assertEquals($unitMeasure->description,"Testing Update");
 
     }
     public function test_wrong_id_update_unit_measure()
     {
        $measure_id = 9999;
        $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('put', '/api/unitmeasures/'.$measure_id,  [
             "description" =>"Testing Update"
         ]);
 
         $response->assertNotFound();
     }
    public function test_show_measure()
    {
        $this->withoutExceptionHandling();
        
        $unitMeasureTest = UnitMeasure::factory()->count(1)->create();

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('get', '/api/unitmeasures/'.$unitMeasureTest[0]->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'id',
            'name',
            'description',
            'abbreviate',
            'created_at'
        ]]);
    }
    public function test_wrong_show_unit_measure()
    {
        $measure_id = 9999;
        $response =  $this->withHeaders([
             'Authorization' => 'Bearer '. $this->token,
             ])->json('put', '/api/unitmeasures/'.$measure_id,  [
             "description" =>"Testing Update"
         ]);
        $response->assertNotFound();
    }
    public function test_delete_category()
    {
        $this->withoutExceptionHandling();

        $unitMeasureTest = UnitMeasure::factory()->count(1)->create();

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('delete', '/api/unitmeasures/'.$unitMeasureTest[0]->id);

        $response->assertStatus(200);
        $response->assertJsonStructure(['data' => [
            'id',
            'name',
            'description',
            'abbreviate',
            'created_at'
        ]]);

    }
    public function test_wrong_delete_measure()
    {
        $measure_id = 9999;

        $response =  $this->withHeaders([
            'Authorization' => 'Bearer '. $this->token,
            ])->json('delete', '/api/unitmeasures/'.$measure_id);

        $response->assertNotFound();

    }
}
