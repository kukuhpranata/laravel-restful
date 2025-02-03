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

        return ApiResponseClass::sendResponse(UserResource::collection($data), $message, 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $details =[
            'email' => $request->email,
            'password' => $request->password,
            'name' => $request->name
        ];
        
        DB::beginTransaction();
        try{
             $existedUser = $this->userRepositoryInterface->getByEmail($details['email']);
             if (!empty($existedUser)) {
                $message = "Email Already Exist!";
                 return ApiResponseClass::sendResponse('', $message, 400);
             }
             $user = $this->userRepositoryInterface->store($details);
             $message = "User Create Successful";

             return ApiResponseClass::sendResponse(new UserResource($user), $message, 201);
             DB::commit();

        }catch(\Exception $e){
            return ApiResponseClass::rollback($e);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userRepositoryInterface->getById($id);
        $message = "Successfuly Getting User Data!";

        return ApiResponseClass::sendResponse(new UserResource($user), $message, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $updateDetails =[
            'email' => $request->email,
            'name' => $request->name
        ];

        DB::beginTransaction();
        try{
            $existedUser = $this->userRepositoryInterface->getById($id);

             $user = $this->userRepositoryInterface->update($updateDetails, $id);
             $message = "User Update Successfu";

             DB::commit();
             return ApiResponseClass::sendResponse($message, '', 201);

        }catch(\Exception $ex){
            return ApiResponseClass::rollback($ex);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $this->userRepositoryInterface->delete($id);
        $message = "Product Delete Successful";

        return ResponseClass::sendResponse($message,'',204);
    }
}
