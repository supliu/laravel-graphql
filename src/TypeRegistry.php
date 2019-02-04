<?php

namespace Supliu\LaravelGraphQL;

use GraphQL\Type\Definition\InputObjectType;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\Type;

abstract class TypeRegistry
{
    /**
     * @var array
     */
    private static $types = [];

    /**
     * @return Type
     * @throws \Exception
     */
    public static function type($type): Type
    {
        if(!is_object($type))
            $type = new $type;

        if(!$type instanceof ObjectType && !$type instanceof InputObjectType)
            throw new \Exception('$type must be ObjectType or InputObjectType');

        if(!in_array($type->name, self::$types))
            self::$types[$type->name] = $type;

        return self::$types[$type->name];
    }
}