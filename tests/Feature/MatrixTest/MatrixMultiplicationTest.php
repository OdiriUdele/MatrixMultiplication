<?php

namespace Tests\Feature\MatrixTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
USE App\Models\User;
use JWTAuth;

class MatrixMultiplicationTest extends TestCase
{
	
	use RefreshDatabase;

    /**
	 * Created data for different scenarios.
	 * 
	 * @return array
	 */
	public function matrixDataProvider(): array
	{

		return [
			'unequal matrixA' => [
				[
					[2,4],
					[3,5,6]
				],
				[
					[5,6,7],
					[4,3,6]
				],
				422,
				[
					'message' => 'The given data was invalid.',
					'errors' => [
						"firstMatrix" => ["The first matrix must not contain null or empty values."]]
				]
			],
			'unequal matrixB' => [
				[
					[3,6,5],
					[6,8,7]
				],
				[
					[1],
					[4,6,7],
					[5,7,8]
				],
				422,
				[
					'message' => 'The given data was invalid.',
					'errors' => ["secondMatrix" => ["The second matrix must not contain null or empty values."]]
				]
			],
			'unequal row to col' => [
				[
					[2,4,5,6]
				],
				[
					[2],
					[5],
					[7]
				],
				422,
				[
					'result' => 'fail',
					'errors' => ['secondMatrix' => ["The second matrix row count must match the first matrix column count."]],
                    'status' =>  false
				]
			],
			'nonnumeric values MatrixA' => [
				[
					[2,5,6,'K']
				],
				[
					[5],
					[6],
					[8],
					[9]
				],
				422,
				[
					'message' => 'The given data was invalid.',
					'errors' => ['firstMatrix' => ["The first matrix must only contain integers(whole numbers)."]],
				]
			],
			'small complete matrices' => [
				[
					[8,12]
				],
				[
					[25, 18],
					[8,4]
				],
				200,
				[
					'result' => 'success',
					'data' => [["KJ", "GJ"]],
                    'status' =>  true
				]
			],
			'medium complete matrices' => [
				[
					[11,20,10,15],
					[5,6,7,15]
				],
				[
					[5,4],
					[6,8],
					[10,12],
					[16,18]
				],
				200,
				[
					'result' => 'success',
					'data' => [ ['SU', 'VV'], ['NG','PF']],
                    'status' =>  true
				]
			]
		];
	}


	/**
	 * Tests the post call to retrieve 
	 * a matrix multiplicatio result from input.
	 *
	 * @dataProvider matrixDataProvider
	 * 
	 * @param array $matrixA  The first input matrix
	 * @param array $matrixB  The second input matrix
	 * @param int   $status   The HTTP status expected from call
	 * @param array $expected The expected array denoting 
	 * 						  response from call
	 * @return void
	 */
    public function testTwoByTwoMatrixProduct(
						array $matrixA, 
						array $matrixB, 
						int $status,
						array $expected): void 
	{
		$user = User::factory()->create();
		$token = JWTAuth::fromUser($user);

		$response = $this->withHeaders([
            'Authorization' => 'Bearer '.$token
        ])->post('/api/2by2matrix', [
				'firstMatrix' => $matrixA, 
				'secondMatrix' => $matrixB 
        ]);

		// \Log::info(json_encode($response));

		   $this->assertEquals($status, $response->status());
		   $response->assertJson($expected);
	}
}
