<?php

namespace Supliu\LaravelGraphQL;

use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Type\Definition\Type;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\UnauthorizedException;

abstract class Query
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
     * Query constructor.
     */
    public function __construct()
    {
        $this->config = [
            'type' => $this->typeResult(),
            'args' => $this->resolveArgs(),
            'resolve' => function ($root, $args, $context, $info) {

                if(!$this->authorize())
                    throw new UnauthorizedException("Unauthorized.");

                if(!empty($this->rules()))
                    Validator::make($args, $this->rules())->validate();

                return $this->resolve($root, $args, $context, $info);
            }
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