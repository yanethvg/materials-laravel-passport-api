<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    // refresh database
    use RefreshDatabase;

    public function test_api_register() {
        $this->withoutExceptionHandling();

        \Artisan::call('passport:install');
       
        $body = [
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => 'testtest',
            "password_confirmation" => 'testtest',

        ];
        
        $response =$this->json('POST','/api/register',$body,['Accept' => 'application/json']);
       
        $response->assertStatus(200);

        $response->assertJsonStructure(['user' => [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at'
        ], 'access_token', 'token_type']);
    }
    public function test_wrong_api_register_without_email_password_confirmed() {

        \Artisan::call('passport:install');
       
        $body = [
            'name'=> 'test',
            'password' => 'testtest',
        ];
        
        $response =$this->json('POST','/api/register',$body,['Accept' => 'application/json']);
       
        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['password','email']);
    }
    public function test_wrong_api_register_email_taking() {

        \Artisan::call('passport:install');

        $user = User::create([
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('testtest')
        ]);
       
        $body = [
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => 'testtest',
            "password_confirmation" => 'testtest',

        ];
        
        $response =$this->json('POST','/api/register',$body,['Accept' => 'application/json']);
       
        $response->assertUnprocessable();

        $response->assertJsonValidationErrors(['email']);
    }
    public function test_api_login() {
        // $this->withoutExceptionHandling();
        \Artisan::call('passport:install');

        $user = User::create([
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('testtest')
        ]);
        
        
        $body = [
            'email' => 'test@test.com',
            'password' => 'testtest'
        ];
        $response = $this->json('POST','/api/login',$body,['Accept' => 'application/json']);
       
        $response->assertStatus(200);
        $response->assertJsonStructure(['user' => [
            'id',
            'name',
            'email',
            'created_at',
            'updated_at'
        ], 'access_token', 'token_type']);
    }
    public function test_wrong_api_login() {
        \Artisan::call('passport:install');

        $user = User::create([
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('testtest')
        ]);
        
        
        $body = [
            'email' => 'test@test.com',
            'password' => 'testtest123'
        ];
        $response = $this->json('POST','/api/login',$body,['Accept' => 'application/json']);
       
        $response->assertUnauthorized();
        $response->assertJsonStructure(['message']);
    }
    public function test_api_logout() {
        $this->withoutExceptionHandling();
        \Artisan::call('passport:install');

        $user =  User::create([
            'name'=> 'test',
            'email' => 'test@test.com',
            'password' => Hash::make('testtest'),
        ]);

        //creating access token
        $token = $user->createToken('authToken')->accessToken;
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            ])->json('POST','/api/logout');
       
        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
    }
    public function test_wrong_api_logout_unautorized() {
       
        \Artisan::call('passport:install');
        //creating access token
        $token = "swqehd12ye1273782136";
        $response = $this->withHeaders([
            'Authorization' => 'Bearer '. $token,
            ])->json('POST','/api/logout');
       
        $response->assertUnauthorized();
        $response->assertJsonStructure(['message']);
    }
}
