<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
USE App\Models\User;
use JWTAuth;

class RegistrationTest extends TestCase
{
    
	use RefreshDatabase;
	
   /**
	 * Created data for different scenarios.
	 * 
	 * @return array
	 */
	public function userDataProvider(): array
	{
		return [
			'missing email' => [
				[
                    "password"=>"password",
                    "name"=>"Odiri Udele",
                    "password_confirmation"=>"password"
                ],
				422,
				[
					'message' => 'The given data was invalid.',
					'errors' => [
						"email" => ["The email field is required."]]
				]
			],
			'missing name' => [
				[
                    "email"=>"odiriudele@gmail.com",
                    "password"=>"password",
                    "password_confirmation"=>"password"
                ],
				
				422,
				[
					'message' => 'The given data was invalid.',
					'errors' => ["name" => ["The name field is required."]]
				]
			],
			'password not verified' => [
				[
                    "email"=>"odiriudele@gmail.com",
                    "password"=>"password",
                    "name"=>"Odiri Udele",
                ],
				422,
				[
					'message' => 'The given data was invalid.',
					'errors' => ["password" => ["The password confirmation does not match."]],
				]
			],
			'short password length' => [
				[
                    "email"=>"odiriudele@gmail.com",
                    "password"=>"pass",
                    "name"=>"Odiri Udele",
                    "password_confirmation"=>"pass"
                ],
				422,
				[
					'message' => 'The given data was invalid.',
					'errors' => ["password" => ["The password must be at least 6 characters."]],
				]
			],
			'registeration success' => [
				[
                    "email"=>"odiriudele@gmail.com",
                    "password"=>"password",
                    "name"=>"Odiri Udele",
                    "password_confirmation"=>"password"
                ],
				201,
				[
                    "message"=> "Account created succesfully.",
                    'status' =>  true,
                    "data"=>[
                        "email"=>"odiriudele@gmail.com",
                        "name"=> "Odiri Udele",
                    ],
				]
			]
		];
	}


	/**
	 * Tests the post call to register 
	 * a new user  from input.
	 *
	 * @dataProvider userDataProvider
	 * 
	 * @param array $reg_details  The user registeration input 
	 * @param int   $status   The HTTP status expected from call
	 * @param array $expected The expected array denoting 
	 * 						  response from call
	 * @return void
	 */
    public function testRegister(
						array $reg_details, 
						int $status,
						array $expected): void 
	{

		$response = $this->call('POST','/api/auth/register', $reg_details);

		// \Log::info(json_encode($response));

		   $this->assertEquals($status, $response->status());
		   $response->assertJson($expected);
	}
}
