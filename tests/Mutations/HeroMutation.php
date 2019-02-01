<?php

namespace Supliu\LaravelGraphQL\Tests\Mutations;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;
use Supliu\LaravelGraphQL\Mutation;

class HeroMutation extends Mutation
{
    protected function typeResult(): Type
    {
        return new ObjectType([
            'name' => 'UpdateHeroResult',
            'fields' => [
                'hero_id' => Type::int(),
                'error' => Type::boolean(),
                'message' => Type::string()
            ]
        ]);
    }

    /**
     * @return array
     */
    protected function args(): array
    {
        return [
            'id' => Type::nonNull(Type::int())
        ];
    }

    /**
     * @return mixed
     */
    protected function resolve($root, $args, $context, $info)
    {
        return [
            'hero_id' => $args['id'],
            'error' => false,
            'message' => 'Updated!'
        ];
    }
}