<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponseHelper;
use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


/**
 * @OA\Info(
 *      title="API Swagger",
 *      version="1.0",
 *      description="API Documentation for the project",
 * )
 * @OA\Server(url="http://localhost:8000")
 */
class AuthController extends Controller
{
    //
    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    /**
     * @OA\Post(
     *      path="/api/login",
     *      tags={"Auth"},
     *      summary="Login user",
     *      description="Login user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"email","password"},
     *              @OA\Property(property="email", type="string", format="email", example="german@gmail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password")
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="User logged in successfully",
     *          @OA\JsonContent( ref="#/components/schemas/UserResource")
     *      )
     * ) 
     */
    public function login(LoginUserRequest $request)
    {
        $data = [
            'email' => $request->email,
            'password' => $request->password
        ];

        try {
            $user = $this->userRepositoryInterface->login($data);
            // echo $user;

            DB::commit();
            return ApiResponseHelper::sendResponseWithToken(
                $user,
                'User logged in successfully',
                200
            );
        } catch (\Exception $ex) {
            DB::rollBack();
            return  ApiResponseHelper::rollback($ex);
        }
    }

    /**
     * @OA\Post(
     *      path="/api/register",
     *      tags={"Auth"},
     *      summary="Register user",
     *      description="Register user",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *              required={"name","email","password"},
     *              @OA\Property(property="name", type="string", format="name", example="John Doe"),
     *              @OA\Property(property="email", type="string", format="email", example="doe@gmail.com"),
     *              @OA\Property(property="password", type="string", format="password", example="password"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="User created successfully",
     *          @OA\JsonContent( ref="#/components/schemas/UserResource")
     *      )
     * ) 
     *  
     */
    public function register(RegisterUserRequest $request)
    {
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password
        ];

        DB::beginTransaction();

        try {
            $user = $this->userRepositoryInterface->register($data);
            DB::commit();
            return ApiResponseHelper::sendResponseWithToken(
                new UserResource($user),
                'User created successfully',
                201,
            );
        } catch (\Exception $ex) {
            DB::rollBack();
            return  ApiResponseHelper::rollback($ex);
        }
    }
}
