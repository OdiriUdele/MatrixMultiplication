<?php

namespace Tests\Feature\MatrixTest;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class MatrixMultiplicationTest extends TestCase
{
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
					'result' => 'fail',
					'errors' => [
						"firstMatrix" => ["The first matrix must not contain null or empty values."]],
                    'status' =>  false
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
					'result' => 'fail',
					'errors' => ["secondMatrix" => ["The second matrix must not contain null or empty values."]],
                    'status' =>  false
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
					'errors' => ['secondMatrix' => ["The second matrix must contain 4 items."]],
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
					'result' => 'fail',
					'errors' => ['firstMatrix' => ["The first matrix must only contain integers(whole numbers)."]],
                    'status' =>  false
				]
			],
			'out of range numeric values' => [
				[
					[400,30,6]
				],
				[
					[70],
					[7],
					[6]
				],
				422,
				[
					'result' => 'fail',
					'errors' => [
						'firstMatrix' => ["The first matrix must only contain numbers between 1 and 26"],
						'secondMatrix' => ["The second matrix must only contain numbers between 1 and 26"]
					]
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
					'data' => [['KJ', 'GJ']],
                    'status' =>  true
				]
			],
			'medium complete matrices' => [
				[
					[10,20,10,15],
					[5,6,7,15]
				],
				[
					[2,4],
					[6,8],
					[10,12],
					[16,18]
				],
				200,
				[
					'result' => 'success',
					'data' => [ ['RL', 'VR'], ['MR','PF']],
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
		$response = $this->call('POST','/api/2by2matrix', [
				'firstMatrix' => $matrixA, 
				'secondMatrix' => $matrixB 
        ],
        [
            'Authorization' => 'Bearer '.$this->paystackkey
            
        ]);

		   $this->assertEquals($status, $response->status());
		   $this->seeJsonEquals($expected);
	}
}
