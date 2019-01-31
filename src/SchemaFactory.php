<?php

namespace Supliu\LaravelGraphQL;

use Exception;
use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Schema;

class SchemaFactory
{
    /**
     * @return Schema
     * @throws Exception
     */
    public function create($schema)
    {
        $config = config('graphql');

        if(!isset($config['schemas']))
            throw new Exception('Schemas not found.');

        if(!isset($config['schemas'][$schema]))
            throw new Exception('Access "'.$schema.'" not found.');

        $ref = $config['schemas'][$schema];

        return new Schema([
            'query' => $this->createQuery($ref['queries']),
            'mutation' => $this->createMutation($ref['mutations'])
        ]);
    }

    /**
     * @param array $queries
     * @return ObjectType
     */
    private function createQuery(array $queries)
    {
        $fields = [];

        foreach ($queries as $key => $value)
            $fields[$key] = app()->make($value)->getConfig();

        return new ObjectType([
            'name' => 'QueryRoot',
            'fields' => $fields
        ]);;
    }

    /**
     * @param array $mutations
     * @return ObjectType
     */
    private function createMutation(array $mutations)
    {
        $fields = [];

        foreach ($mutations as $key => $value)
            $fields[$key] = app()->make($value)->getConfig();

        return new ObjectType([
            'name' => 'MutationRoot',
            'fields' => $fields
        ]);
    }
}