<?php

namespace App\OpenApi\Parameters;

use GoldSpecDigital\ObjectOrientedOAS\Objects\Parameter;
use GoldSpecDigital\ObjectOrientedOAS\Objects\Schema;
use Vyuldashev\LaravelOpenApi\Contracts\Reusable;
use Vyuldashev\LaravelOpenApi\Factories\ParametersFactory;

class PaginationParameters extends ParametersFactory implements Reusable
{
    /**
     * @return Parameter[]
     */
    public function build(): array
    {
        return [

            Parameter::query()
                ->name('page')
                ->description('Page number')
                ->required(false)
                ->schema(Schema::integer()),

            Parameter::query()
                ->name('itemsPerPage')
                ->description('Number of items per page')
                ->required(false)
                ->schema(Schema::integer()),

            Parameter::query()
                ->name('sortBy')
                ->description('Items to sort by')
                ->required(false)
                ->schema(Schema::array()),

            Parameter::query()
                ->name('sortDesc')
                ->description('Array of booleans showing if sort should be desc')
                ->required(false)
                ->schema(Schema::array()),

            Parameter::query()
                ->name('mustSort')
                ->required(false)
                ->schema(Schema::boolean()),

            Parameter::query()
                ->name('multiSort')
                ->required(false)
                ->schema(Schema::boolean()),
        ];
    }
}
