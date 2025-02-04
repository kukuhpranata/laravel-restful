<?php

namespace App\Classes;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiResponseClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function rollback($e, $message = "Something went wrong! Process not completed")
    {
        DB::rollBack();
        self::throw($e, $message);
    }

    public static function throw($e, $message = "Something went wrong! Process not completed")
    {
        Log::info($e);
        throw new HttpResponseException(response()->json(["message" => $message], 500));
    }

    public static function sendResponse(bool $status, $result, string $message, int $code = 200)
    {
        $response = [
            'success' => $status
        ];

        if (!empty($message)) {
            $response['message'] = $message;
        }
        if (!empty($result)) {
            $response['data'] = $result;
        }
        return response()->json($response, $code);
    }
}
