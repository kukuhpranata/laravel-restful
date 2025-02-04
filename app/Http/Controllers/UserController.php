<?php

namespace App\Http\Controllers;

use App\Helpers\JwtHelper;
use App\Helpers\CryptHelper;
use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;

class UserController extends Controller
{
    private UserRepositoryInterface $userRepositoryInterface;

    public function __construct(UserRepositoryInterface $userRepositoryInterface)
    {
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function login(Request $request)
    {
        $dataRequest = [
            "email" => $request->email,
            "password" => $request->password
        ];

        $user = $this->userRepositoryInterface->getByemail($dataRequest["email"]);
        if (empty($user)) {
            $message = "User Not Registered!";
            return ApiResponseClass::sendResponse(false, null, $message, 404);
        }

        if (!($user->password == $dataRequest["password"])) {
            $message = "Wrong Password!";
            return ApiResponseClass::sendResponse(false, null, $message, 400);
        }

        $token = JWTAuth::fromUser($user);

        $message = "Successfully Getting User Data!";

        $responseData = [
            "user" => [
                "email" => $user->email,
                "name" => $user->name
            ],
            "token" => $token
        ];

        return ApiResponseClass::sendResponse(true, $responseData, $message, 200);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data = $this->userRepositoryInterface->index();
        $message = "Successfully Getting User Data!";

        return ApiResponseClass::sendResponse(true, UserResource::collection($data), $message, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $details = [
            "email" => $request->email,
            "password" => $request->password,
            "name" => $request->name
        ];

        DB::beginTransaction();
        try {
            $existedUser = $this->userRepositoryInterface->getByEmail($details['email']);
            if (!empty($existedUser)) {
                $message = "Email Already Exist!";
                return ApiResponseClass::sendResponse(false, null, $message, 400);
            }
            $user = $this->userRepositoryInterface->store($details);
            $message = "User Create Successful";

            return ApiResponseClass::sendResponse(true, new UserResource($user), $message, 201);
            DB::commit();
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $cryptor = new CryptHelper();
        $userId = $cryptor->decrypt($id);
        $user = $this->userRepositoryInterface->getById($userId);
        if (empty($user)) {
            $message = "User Not Found!";
            return ApiResponseClass::sendResponse(false, null, $message, 404);
        }
        $message = "Successfuly Getting User Data!";

        return ApiResponseClass::sendResponse(true, new UserResource($user), $message, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $cryptor = new CryptHelper();
        $userId = $cryptor->decrypt($id);

        $updateDetails = [
            "email" => $request->email,
            "name" => $request->name,
            "password" => $request->password
        ];

        DB::beginTransaction();
        try {
            $existedUser = $this->userRepositoryInterface->getById($userId);
            if (empty($existedUser)) {
                $message = "User Not Found!";
                return ApiResponseClass::sendResponse(false, null, $message, 404);
            }

            $user = $this->userRepositoryInterface->update($updateDetails, $userId);
            $message = "User Update Successfu";

            DB::commit();
            return ApiResponseClass::sendResponse(true, null, $message, 201);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    public function updateOwnData(Request $request)
    {
        $jwtHelper = new JwtHelper();
        $cryptor = new CryptHelper();
        $jwtPayload = $jwtHelper->getPayload();
        $userId = $cryptor->decrypt($jwtPayload["user_id"]);

        $updateDetails = [
            "email" => $request->email,
            "name" => $request->name,
            "password" => $request->password
        ];

        DB::beginTransaction();
        try {
            $existedUser = $this->userRepositoryInterface->getById($userId);
            if (empty($existedUser)) {
                $message = "User Not Found!";
                return ApiResponseClass::sendResponse(false, null, $message, 404);
            }

            $user = $this->userRepositoryInterface->update($updateDetails, $userId);
            $message = "User Update Successfu";

            DB::commit();
            return ApiResponseClass::sendResponse(true, null, $message, 201);
        } catch (\Exception $e) {
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $cryptor = new CryptHelper();
        $userId = $cryptor->decrypt($id);

        $this->userRepositoryInterface->delete($userId);
        $message = "Product Delete Successful";

        return ApiResponseClass::sendResponse(true, null, $message, 204);
    }
}
