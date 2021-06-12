<?php

namespace App\Http\Controllers\Api\Matrix;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\TwoByTwoMultiplicationRequest;
use Illuminate\Support\Facades\Validator;
use App\Services\MatrixMultiplicationService;
use Illuminate\Http\JsonResponse;
use Exception;
use Error;

class MatrixController extends Controller
{
    protected $matrixMultiplicationService;

    /**
     * Create a new instance.
     *
     * @param  MatrixService  $matrixMultiplicationService
     * @return void
     */
    public function __construct(MatrixMultiplicationService $matrixMultiplicationService)
    {
        $this->matrixMultiplicationService = $matrixMultiplicationService;
    }


    /**
     * Creates a new matrix product from user input.
     * 
     * @param  TwoByTwoMultiplicationRequest $request
     * 
     * @return JsonResponse
     */
    public function TwoByTwoMatrixProduct( TwoByTwoMultiplicationRequest $request ): JsonResponse
    {
        try {

            //validate the input
            $messages = array(
                'secondMatrix.size' => 'The second matrix row count must match the first matrix column count.'
            );
            $validator = Validator::make($request->all(), [
                            'secondMatrix' =>  "size:{$this->getFirstMatrixCount($request, 'firstMatrix')}"
                            ],
                            $messages
                        );

            if ($validator->fails()) {

                return response()->json([
                    'result' => 'fail',
                    'errors' => $validator->errors(),
                    'status' =>  false
                ], 422);

            }
                
            //multiply the matrix
            $product = $this->matrixMultiplicationService->MultiplyMatrix(
                $request->firstMatrix, 
                $request->secondMatrix);
            
            return response()->json([
                'result' => 'success',
                'data' => $product,
                "status"=>true
            ],200);

        } catch (Exception $e) {

            \Log::info($e->getMessage());

           return response()->json([
               "message" => "Something went wrong.",
                "status" => false 
            ], 500);
           
        } catch (Error $e) {

            \Log::info($e->getMessage());

            return response()->json([
                "message" => "Something went wrong.",
                 "status" => false
            ], 500);
        
        }
    }

    /**
     * Get the count(number of columns) for the requested Matrix.
     * 
     * @param Request $request
     * @param string  $name     The field to use.
     * 
     * @return int
     */
    protected function getFirstMatrixCount(Request $request, string $name): int
    {

        return count($request->$name[0]);

    }
}
