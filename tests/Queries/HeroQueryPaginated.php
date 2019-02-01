<?php

namespace Supliu\LaravelGraphQL\Tests\Queries;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Supliu\LaravelGraphQL\QueryPaginated;

class HeroQueryPaginated extends QueryPaginated
{
    /**
     * @return Type
     */
    protected function typeResult(): Type
    {
        return new ObjectType([
            'name' => 'HeroQueryPaginatedResult',
            'fields' => [
                'name' => Type::string()
            ]
        ]);
    }

    /**
     * @return mixed
     */
    protected function resolve($root, $args, $context, $info)
    {
        return [
            'total' => 1,
            'current_page' => 1,
            'per_page' => 15,
            'last_page' => 1,
            'data' => [
                [
                    'name' => 'R2-D2'
                ]
            ]
        ];
    }
}