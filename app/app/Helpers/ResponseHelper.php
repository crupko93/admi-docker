<?php

namespace App\Helpers;

use DB;
use Exception;

class ResponseHelper
{
    /**
     * @var string The response array to be returned in JSON format
     */
    protected static $response;

    /**
     * Returns a successful response with optional data
     *
     * @param array $data The array data to be merged
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @throws Exception when $data is not an array
     */
    public static function success($data = [])
    {
        if (!is_array($data)) {
            throw new Exception('ResponseHelper::success() expects first parameter to be array');
        }

        self::$response = ['success' => true] + $data;

        return response()->json(self::$response);
    }

    /**
     * Returns an error response with optional message
     * Note that this function is aware of DB transactions and will roll back in case one is detected
     *
     * @param string $message The error message
     *
     * @return \Symfony\Component\HttpFoundation\Response|\Illuminate\Contracts\Routing\ResponseFactory
     *
     * @throws Exception when $message is not a string
     */
    public static function error($message = '')
    {
        // If a database transaction is active when the error is triggered, make sure to roll back changes first
        if (DB::getPdo()->inTransaction()) {
            DB::rollBack();
        }

        if (!is_string($message)) {
            throw new Exception('ResponseHelper::error() expects first parameter to be string');
        }

        self::$response = [
            'success' => false,
            'message' => $message
        ];

        return response()->json(self::$response, 500);
    }
}
