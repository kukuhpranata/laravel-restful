<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes\ApiResponseClass;
use Illuminate\Support\Facades\DB;
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
        $user = $this->userRepositoryInterface->getById($id);
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
        $updateDetails = [
            "email" => $request->email,
            "name" => $request->name
        ];

        DB::beginTransaction();
        try {
            $existedUser = $this->userRepositoryInterface->getById($id);
            if (empty($existedUser)) {
                $message = "User Not Found!";
                return ApiResponseClass::sendResponse(false, null, $message, 404);
            }

            $user = $this->userRepositoryInterface->update($updateDetails, $id);
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
        $this->userRepositoryInterface->delete($id);
        $message = "Product Delete Successful";

        return ApiResponseClass::sendResponse(true, null, $message, 204);
    }
}
