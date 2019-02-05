<?php

namespace Supliu\LaravelGraphQL;

use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Validator;

abstract class Mutation
{
    /**
     * @var array
     */
    private $config;

    /**
     * Mutation constructor.
     */
    public function __construct()
    {
        $this->config = [
            'type' => $this->typeResult(),
            'args' => $this->resolveArgs(),
            'resolve' => $this->resolveFunction()
        ];
    }

    /**
     * @return mixed
     */
    protected abstract function resolve($root, $args, $context, $info);

    /**
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * @return array
     */
    protected function args(): array
    {
        return [];
    }

    /**
     * @return Type
     */
    protected function typeResult(): Type
    {
        return Type::boolean();
    }

    /**
     * @return \Closure
     */
    private function resolveFunction()
    {
        return function ($root, $args, $context, $info) {

            if(!empty($this->rules()))
                Validator::make($args, $this->rules())->validate();

            return $this->resolve($root, $args, $context, $info);
        };
    }

    /**
     * @return array
     */
    private function resolveArgs(): array
    {
        $transformed = collect($this->args())->transform(function ($item, $key){

            if(is_array($item))
                return $item;

            return [
                'type' => $item,
                'description' => $key
            ];
        });

        return $transformed->toArray();
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return $this->config;
    }
}