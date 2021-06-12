<?php

namespace Tests\Feature\Auth;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
USE App\Models\User;
use JWTAuth;

class LoginTest extends TestCase
{
  use RefreshDatabase;
    
   /**
	 * Created data for different scenarios.
	 * 
	 * @return array
	 */
	public function userloginDataProvider(): array
	{

		return [
			'invalid email' => [
				[
                    "email"=>"odiri@mail.com",
                    "password"=>"password",
                ],
				401,
				[
					'error' => 'invalid Login Details. ',
					'status' => false
				]
			],
			'invalid password' => [
				[
                    "email"=>"odiriudele@gmail.com",
                    "password"=>"password",
                ],
				
				401,
				[
					'error' => 'invalid Login Details. ',
					'status' => false
				]
			],
		];
	}


	/**
	 * Tests the post call to login 
	 * a user  from input.
	 *
	 * @dataProvider userloginDataProvider
	 * 
	 * @param array $login_details  The user login input 
	 * @param int   $status   The HTTP status expected from call
	 * @param array $expected The expected array denoting 
	 * 						  response from call
	 * @return void
	 */
    public function testLogin(
						array $login_details, 
						int $status,
						array $expected): void 
	{
        $user = User::where('email',"odiriudele@gmail.com")->first();

        if(!$user){
            $user = User::create([
                "name"=>"Odiri Udele",
                "email"=>"odiriudele@gmail.com",
                "password"=>"password",
                "password_confirmation"=>"password",
            ]);
        }

		$response = $this->call('POST','/api/auth/login', $login_details);
        $this->assertEquals($status, $response->status());
        $response->assertJson($expected);
	}

}
