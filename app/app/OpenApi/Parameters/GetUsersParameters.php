<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class GetUsersParameters extends ParametersFactory
{
    protected PaginationParameters $paginationParams;

    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        $paginationParams = new PaginationParameters();
        return [

            Parameter::query()
                ->name('role')
                ->description('User role used for filtering')
                ->required(false)
                ->schema(Schema::string()),

            Parameter::query()
                ->name('user_id')
                ->description('User ID for fetching a single user')
                ->required(false)
                ->schema(Schema::integer()),

            $paginationParams->build()
        ];
    }
}
