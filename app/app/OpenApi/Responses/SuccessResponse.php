<?php

namespace App\OpenApi\Responses;

use GoldSpecDigital\ObjectOrientedOAS\Objects\MediaType;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Response;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ResponseFactory;

class SuccessResponse extends ResponseFactory
{
    public function build(): Response
    {
        $response = Schema::object()->properties(
            Schema::boolean('success')->default(true)
        );

        return Response::create('SuccessResponse')
            ->description('General success response')
            ->statusCode(200)
            ->content(
                MediaType::json()->schema($response)
            );
    }
}
