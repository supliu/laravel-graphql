<?php

namespace Supliu\LaravelGraphQL;

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException;

abstract class QueryPaginated
{
    /**
     * @var array
     */
    private $config;

    /**
     * @var ResolveInfo
     */
    protected $resolveInfo;

    /**
     * QueryPaginated constructor.
     */
    public function __construct()
    {
        $className = get_class($this);

        if ($pos = strrpos($className, '\\'))
            $className = substr($className, $pos + 1);

        $this->config = [
            'type' => new ObjectType([
                'name' => $className . 'List',
                'fields' => [
                    'total' => Type::int(),
                    'current_page' => Type::int(),
                    'per_page' => Type::int(),
                    'last_page' => Type::int(),
                    'data' => Type::listOf($this->typeResult())
                ]
            ]),
            'args' => $this->resolveArgs(),
            'resolve' => $this->resolveFunction()
        ];
    }

    /**
     * @return mixed
     */
    protected abstract function resolve($root, $args, $context, $info);

    /**
     * @return boolean
     */
    protected function authorize(): bool
    {
        return true;
    }

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

            if(!$this->authorize())
                throw new UnauthorizedException("Unauthorized.");

            if(!empty($this->rules()))
                Validator::make($args, $this->rules())->validate();

            $this->resolveInfo = $info;

            Paginator::currentPageResolver(function ($pageName = 'page') use ($args) {

                return isset($args[$pageName]) ? $args[$pageName] : 1;

            });

            return $this->resolve($root, $args, $context, $info);
        };
    }

    /**
     * @return array
     */
    private function resolveArgs(): array
    {
        $args = array_merge($this->args(), [
            'page' => Type::int(),
            'limit' => Type::int()
        ]);

        $transformed = collect($args)->transform(function ($item, $key){

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