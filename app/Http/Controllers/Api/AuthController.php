<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Exception;
use Error;
use JWTAuth;

class AuthController extends Controller
{


     /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }


    public function register  (RegisterRequest $request ){

        $credentials = request(['email', 'password']);

        try{
            
            $request["password"] = bcrypt($request->password);

            $user = User::create( $request->all() );

            $token = JWTAuth::attempt($credentials);

            return response(["message"=>"Account created succesfully.","status" => true, "data" => $user, "token" => $token], 201);
            
        }catch(Error $e){

             \Log::info($e->getMessage());

            return response(["message" => "Something went wrong.", "status" => false], 500);

        } catch(Exception $e){

             \Log::info($e->getMessage());

            return response(["message" => "Something went wrong.", "status" => false], 500);
            
        } catch (JWTException $e) {

            return response(["message" => "Could not create token."], 500);

        }        
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login()
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'invalid Login Details'], 401);
        }

        return $this->respondWithToken($token);
    }

    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function user()
    {
        return response()->json(auth()->user());
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'user'=>auth()->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }

}
